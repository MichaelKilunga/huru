<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use App\Models\Message;
use App\Models\User;
use App\Services\ConversationService;
use App\Services\SmsService;
use App\Services\TanzaniaContext;
use App\Services\TopicClassifier;
use App\Support\Phone;
use App\Support\Settings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

class WebChatController extends Controller
{
    private const SESSION_KEY = 'chat_user_id';

    public function index()
    {
        return view('chat', [
            'settings' => $this->publicSettings(),
        ]);
    }

    /** Bootstrap data for the page: who is logged in and the public settings. */
    public function session(Request $request)
    {
        $user = $this->currentUser($request);

        return response()->json([
            'authenticated' => (bool) $user,
            'user' => $user ? $this->userPayload($user) : null,
            'settings' => $this->publicSettings(),
            'csrf' => csrf_token(),
        ]);
    }

    /**
     * Step 1 of login. Creates the citizen record if needed and sends an OTP.
     * When OTP is disabled (or SMS is not configured in a non-production
     * environment) the session is opened directly.
     */
    public function requestOtp(Request $request, SmsService $sms)
    {
        $data = $request->validate([
            'phone_number' => 'required|string|min:9|max:20',
            'language' => 'nullable|in:sw,en',
        ]);

        $phone = Phone::normalize($data['phone_number']);
        if (! $phone) {
            throw ValidationException::withMessages(['phone_number' => 'Namba ya simu si sahihi. / Invalid phone number.']);
        }

        $key = 'otp-request:' . $phone;
        if (RateLimiter::tooManyAttempts($key, 4)) {
            return response()->json(['status' => 'error', 'message' => 'Umeomba mara nyingi. Subiri dakika 10. / Too many requests. Wait 10 minutes.'], 429);
        }
        RateLimiter::hit($key, 600);

        $user = User::query()->firstOrCreate(['phone_number' => $phone]);
        if ($user->is_banned) {
            return response()->json(['status' => 'error', 'message' => 'Huduma imefungwa kwa namba hii. / This number is blocked.'], 403);
        }

        $language = $data['language'] ?? $user->preferred_language ?? 'sw';
        $otpEnabled = Settings::bool('otp_enabled');
        $canSend = $sms->isConfigured();

        if (! $otpEnabled || (! $canSend && ! app()->environment('production'))) {
            $this->openSession($request, $user);

            return response()->json([
                'status' => 'success',
                'otp_required' => false,
                'user' => $this->userPayload($user),
            ]);
        }

        if (! $canSend) {
            return response()->json(['status' => 'error', 'message' => 'Huduma ya uthibitisho haipatikani kwa sasa. / Verification is unavailable right now.'], 503);
        }

        $code = (string) random_int(100000, 999999);
        $ttl = Settings::int('otp_ttl_minutes');
        $user->forceFill([
            'otp_hash' => Hash::make($code),
            'otp_expires_at' => now()->addMinutes($ttl),
            'otp_attempts' => 0,
        ])->save();

        $sent = $sms->sendOtp($phone, $code, $language, (string) Settings::get('app_name'), $ttl);
        if ($sent['status'] === 'error') {
            return response()->json(['status' => 'error', 'message' => 'Imeshindikana kutuma ujumbe wa uthibitisho. / Could not send the verification SMS.'], 502);
        }

        $payload = ['status' => 'success', 'otp_required' => true, 'phone_masked' => Phone::mask($phone), 'ttl_minutes' => $ttl];
        if (config('app.debug') && ! app()->environment('production')) {
            $payload['debug_code'] = $code; // never present in production
        }

        return response()->json($payload);
    }

    /** Step 2 of login. */
    public function verifyOtp(Request $request)
    {
        $data = $request->validate([
            'phone_number' => 'required|string',
            'code' => 'required|digits:6',
        ]);

        $phone = Phone::normalize($data['phone_number']);
        $user = $phone ? User::query()->where('phone_number', $phone)->first() : null;

        if (! $user || ! $user->otp_hash || ! $user->otp_expires_at || $user->otp_expires_at->isPast()) {
            return response()->json(['status' => 'error', 'message' => 'Namba ya uthibitisho imeisha muda. Omba nyingine. / Code expired, request a new one.'], 422);
        }

        if ($user->otp_attempts >= 5) {
            $user->forceFill(['otp_hash' => null, 'otp_expires_at' => null])->save();

            return response()->json(['status' => 'error', 'message' => 'Majaribio mengi. Omba namba mpya. / Too many attempts, request a new code.'], 429);
        }

        if (! Hash::check($data['code'], $user->otp_hash)) {
            $user->increment('otp_attempts');

            return response()->json(['status' => 'error', 'message' => 'Namba ya uthibitisho si sahihi. / Incorrect code.'], 422);
        }

        $user->forceFill(['otp_hash' => null, 'otp_expires_at' => null, 'otp_attempts' => 0])->save();
        $this->openSession($request, $user);

        return response()->json(['status' => 'success', 'user' => $this->userPayload($user)]);
    }

    public function messages(Request $request)
    {
        $user = $this->currentUser($request);
        if (! $user) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        $query = Message::query()->where('user_id', $user->id)->with('feedback');

        $filtering = false;
        if ($request->filled('keyword')) {
            $query->where('content', 'like', '%' . str_replace(['%', '_'], ['\%', '\_'], $request->string('keyword')) . '%');
            $filtering = true;
        }
        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->string('date'));
            $filtering = true;
        }
        if ($request->filled('category')) {
            $query->where('category', $request->string('category'));
            $filtering = true;
        }
        if ($request->filled('before')) {
            $query->where('id', '<', (int) $request->input('before'));
        }

        $limit = $filtering ? 200 : Settings::int('web_chat_limit');
        $messages = $query->latest('id')->take($limit)->get()->reverse()->values();

        return response()->json([
            'status' => 'success',
            'messages' => $messages->map(fn (Message $m) => $this->messagePayload($m)),
            'has_more' => $messages->isNotEmpty() && Message::query()->where('user_id', $user->id)->where('id', '<', $messages->first()->id)->exists(),
        ]);
    }

    public function send(Request $request, ConversationService $conversation)
    {
        $user = $this->currentUser($request);
        if (! $user) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        $data = $request->validate(['message' => 'required|string|max:2000']);

        $result = $conversation->handle($user, $data['message'], Message::CHANNEL_WEB);

        if ($result['kind'] === ConversationService::KIND_IGNORED) {
            return response()->json(['status' => 'error', 'message' => 'Empty message'], 400);
        }

        return response()->json([
            'status' => 'success',
            'kind' => $result['kind'],
            'category' => $result['category'],
            'category_label' => $result['category'] ? TopicClassifier::label($result['category'], $result['language']) : null,
            'user_message' => $result['inbound'] ? $this->messagePayload($result['inbound']) : null,
            'reply' => $result['outbound']
                ? $this->messagePayload($result['outbound'])
                : ['id' => null, 'direction' => 'outbound', 'content' => $result['text'], 'created_at' => now()->toIso8601String(), 'category' => $result['category'], 'feedback' => null],
        ]);
    }

    public function feedback(Request $request)
    {
        $user = $this->currentUser($request);
        if (! $user) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        $data = $request->validate([
            'message_id' => 'required|integer',
            'rating' => 'required|in:1,-1',
            'comment' => 'nullable|string|max:500',
        ]);

        $message = Message::query()->where('user_id', $user->id)->where('direction', Message::DIRECTION_OUT)->find($data['message_id']);
        if (! $message) {
            return response()->json(['status' => 'error', 'message' => 'Message not found'], 404);
        }

        Feedback::query()->updateOrCreate(
            ['message_id' => $message->id, 'user_id' => $user->id],
            ['rating' => (int) $data['rating'], 'comment' => $data['comment'] ?? null]
        );

        return response()->json(['status' => 'success']);
    }

    public function preferences(Request $request, TanzaniaContext $tz)
    {
        $user = $this->currentUser($request);
        if (! $user) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        $data = $request->validate([
            'name' => 'nullable|string|max:40',
            'preferred_language' => 'nullable|in:sw,en,auto',
            'region' => 'nullable|string|max:40',
        ]);

        $update = [];
        if (array_key_exists('name', $data)) {
            $update['name'] = $data['name'] ? trim($data['name']) : null;
        }
        if (array_key_exists('preferred_language', $data)) {
            $update['preferred_language'] = $data['preferred_language'] === 'auto' ? null : $data['preferred_language'];
        }
        if (array_key_exists('region', $data)) {
            $loc = $data['region'] ? $tz->detectLocation($data['region']) : null;
            $update['region'] = $loc['region'] ?? null;
        }

        $user->forceFill($update)->save();

        return response()->json(['status' => 'success', 'user' => $this->userPayload($user->fresh())]);
    }

    public function logout(Request $request)
    {
        $request->session()->forget(self::SESSION_KEY);
        $request->session()->regenerate();

        return response()->json(['status' => 'success']);
    }

    // ------------------------------------------------------------------ helpers

    private function openSession(Request $request, User $user): void
    {
        $request->session()->regenerate();
        $request->session()->put(self::SESSION_KEY, $user->id);
        $user->forceFill(['last_seen_at' => now()])->saveQuietly();
    }

    private function currentUser(Request $request): ?User
    {
        $id = $request->session()->get(self::SESSION_KEY);
        if (! $id) {
            return null;
        }
        $user = User::query()->find($id);
        if (! $user) {
            $request->session()->forget(self::SESSION_KEY);
        }

        return $user;
    }

    private function userPayload(User $user): array
    {
        return [
            'id' => $user->id,
            'name' => $user->name,
            'display_name' => $user->displayName(),
            'phone_masked' => Phone::mask($user->phone_number),
            'preferred_language' => $user->preferred_language ?? 'auto',
            'region' => $user->region ? ucwords($user->region) : null,
            'is_banned' => $user->is_banned,
        ];
    }

    private function messagePayload(Message $m): array
    {
        return [
            'id' => $m->id,
            'direction' => $m->direction,
            'content' => $m->content,
            'category' => $m->category,
            'category_label' => $m->category ? TopicClassifier::label($m->category, $m->language ?? 'sw') : null,
            'language' => $m->language,
            'created_at' => $m->created_at?->toIso8601String(),
            'feedback' => $m->relationLoaded('feedback') && $m->feedback ? $m->feedback->rating : null,
        ];
    }

    private function publicSettings(): array
    {
        return [
            'app_name' => Settings::get('app_name'),
            'sms_keyword' => Settings::get('sms_keyword'),
            'sms_shortcode' => Settings::get('sms_shortcode'),
            'otp_enabled' => Settings::bool('otp_enabled'),
            'categories' => collect(TopicClassifier::CATEGORIES)->map(fn ($l, $k) => ['key' => $k, 'sw' => $l['sw'], 'en' => $l['en']])->values(),
            'regions' => array_map('ucwords', TanzaniaContext::REGIONS),
        ];
    }
}
