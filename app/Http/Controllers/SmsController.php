<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessIncomingSms;
use App\Support\Phone;
use App\Support\Settings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;

class SmsController extends Controller
{
    /**
     * Africa's Talking inbound callback (form-encoded: from, to, text, date, id, linkId).
     */
    public function inbound(Request $request)
    {
        $from = Phone::normalize($request->input('from'));
        $text = trim((string) $request->input('text', ''));

        if (! $from || $text === '') {
            return response()->json(['status' => 'error', 'message' => 'Invalid payload'], 400);
        }

        // A flood from one number must not become a flood of model calls.
        $key = 'sms-inbound:' . $from;
        if (RateLimiter::tooManyAttempts($key, 20)) {
            Log::warning('Inbound SMS rate limited', ['from' => Phone::mask($from)]);

            return response()->json(['status' => 'rate_limited']);
        }
        RateLimiter::hit($key, 60);

        $keyword = (string) Settings::get('sms_keyword');
        $clean = $text;

        // On a shared short code the operator prepends the keyword; strip it when present.
        if ($keyword !== '' && preg_match('/^' . preg_quote($keyword, '/') . '\b\s*/iu', $text)) {
            $clean = trim(preg_replace('/^' . preg_quote($keyword, '/') . '\b\s*/iu', '', $text));
        } elseif ($keyword !== '' && config('services.at.require_keyword')) {
            Log::info('SMS ignored: keyword missing', ['from' => Phone::mask($from)]);

            return response()->json(['status' => 'ignored']);
        }

        // A bare keyword is a request for help.
        if ($clean === '') {
            $clean = 'HELP';
        }

        Log::info('Inbound SMS accepted', ['from' => Phone::mask($from), 'chars' => strlen($clean)]);

        ProcessIncomingSms::dispatch($from, $clean, $request->input('id'));

        return response()->json(['status' => 'success']);
    }

    /**
     * Optional delivery report callback from Africa's Talking.
     */
    public function deliveryReport(Request $request)
    {
        Log::info('SMS delivery report', $request->only('id', 'status', 'phoneNumber', 'failureReason'));

        return response()->json(['status' => 'ok']);
    }
}
