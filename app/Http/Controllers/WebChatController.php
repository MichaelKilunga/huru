<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Message;
use App\Models\AiLog;
use App\Models\SystemSetting;
use App\Services\AiService;
use App\Services\LanguageDetector;
use App\Services\ModerationService;
use Illuminate\Support\Facades\Log;

class WebChatController extends Controller
{
    public function index()
    {
        return view('chat');
    }

    public function login(Request $request)
    {
        $request->validate([
            'phone_number' => 'required|string|min:10',
        ]);

        $phoneNumber = $request->phone_number;
        
        // Ensure it starts with + if not already (basic normalization)
        if (!str_starts_with($phoneNumber, '+')) {
            // Check if it starts with 0 (e.g. 07...) and replace with +255
            if (str_starts_with($phoneNumber, '0')) {
                $phoneNumber = '+255' . substr($phoneNumber, 1);
            } else {
                $phoneNumber = '+' . $phoneNumber;
            }
        }

        $user = User::firstOrCreate(['phone_number' => $phoneNumber]);

        session(['chat_user_id' => $user->id]);

        return response()->json(['status' => 'success', 'user' => $user]);
    }

    public function getMessages(Request $request)
    {
        $userId = session('chat_user_id');
        if (!$userId) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        $query = Message::where('user_id', $userId);

        // Apply keyword filter
        if ($request->filled('keyword')) {
            $query->where('content', 'like', '%' . $request->keyword . '%');
        }

        // Apply date filter
        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        // If no filters are applied, use the system limit
        if (!$request->filled('keyword') && !$request->filled('date')) {
            $limit = (int) (SystemSetting::where('key', 'web_chat_limit')->value('value') ?? 4);
            $messages = $query->latest()->take($limit)->get()->reverse()->values();
        } else {
            // When filtering, return all matches (or a larger sensible limit)
            $messages = $query->orderBy('created_at', 'asc')->get();
        }

        return response()->json(['status' => 'success', 'messages' => $messages]);
    }

    public function sendMessage(Request $request, AiService $aiService, ModerationService $moderationService)
    {
        $userId = session('chat_user_id');
        if (!$userId) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        $user = User::find($userId);
        if ($user->is_banned) {
            return response()->json(['status' => 'error', 'message' => 'User is banned'], 403);
        }

        $text = $request->input('message');
        if (empty($text)) {
            return response()->json(['status' => 'error', 'message' => 'Empty message'], 400);
        }

        // Store user message
        $inboundMessage = Message::create([
            'user_id' => $user->id,
            'direction' => 'inbound',
            'content' => $text,
        ]);

        // Resolve Language
        $language = SystemSetting::where('key', 'primary_language')->value('value') ?? 'sw';
        if ($language === 'auto') {
            $detector = new LanguageDetector();
            $language = $detector->detect($text) ?? 'sw';
        }

        // Moderation Check
        if ($moderationService->isAbusive($text)) {
            $user->increment('abuse_count');
            $warningMsg = $user->abuse_count >= 3 
                ? $moderationService->getBanMessage($language)
                : $moderationService->getWarningMessage($language, $user->abuse_count);
            
            if ($user->abuse_count >= 3) {
                $user->update(['is_banned' => true]);
            }

            $outboundMessage = Message::create([
                'user_id' => $user->id,
                'direction' => 'outbound',
                'content' => $warningMsg,
            ]);

            return response()->json([
                'status' => 'success', 
                'user_message' => $inboundMessage,
                'ai_message' => $outboundMessage
            ]);
        }

        // Check if AI is enabled
        $aiEnabled = SystemSetting::where('key', 'ai_enabled')->value('value') ?? '1';
        if ($aiEnabled == '0') {
            $maintenanceMsg = SystemSetting::where('key', 'ai_maintenance_message')->value('value') ?? 'System is currently undergoing maintenance.';
            $outboundMessage = Message::create([
                'user_id' => $user->id,
                'direction' => 'outbound',
                'content' => $maintenanceMsg,
            ]);
            return response()->json([
                'status' => 'success', 
                'user_message' => $inboundMessage,
                'ai_message' => $outboundMessage
            ]);
        }

        // Generate AI Response
        $aiResult = $aiService->generateContextualResponse($text, $language);
        $aiResponseText = $aiResult['text'];

        // AI-triggered moderation
        if ($aiResponseText === 'BANNED_CONTENT_DETECTED') {
            $user->increment('abuse_count');
            $warningMsg = $user->abuse_count >= 3 
                ? $moderationService->getBanMessage($language)
                : $moderationService->getWarningMessage($language, $user->abuse_count);
            
            if ($user->abuse_count >= 3) {
                $user->update(['is_banned' => true]);
            }

            $outboundMessage = Message::create([
                'user_id' => $user->id,
                'direction' => 'outbound',
                'content' => $warningMsg,
            ]);

            return response()->json([
                'status' => 'success', 
                'user_message' => $inboundMessage,
                'ai_message' => $outboundMessage
            ]);
        }

        // Store system message
        $outboundMessage = Message::create([
            'user_id' => $user->id,
            'direction' => 'outbound',
            'content' => $aiResponseText,
        ]);

        // Log AI interaction
        AiLog::create([
            'message_id' => $outboundMessage->id,
            'model' => $aiResult['model'] ?? 'gemini-flash-lite-latest',
            'prompt' => $text,
            'response' => $aiResponseText,
            'prompt_tokens' => $aiResult['tokens']['promptTokenCount'] ?? 0,
            'completion_tokens' => $aiResult['tokens']['candidatesTokenCount'] ?? 0,
            'total_tokens' => $aiResult['tokens']['totalTokenCount'] ?? 0,
        ]);

        return response()->json([
            'status' => 'success',
            'user_message' => $inboundMessage,
            'ai_message' => $outboundMessage
        ]);
    }

    public function logout()
    {
        session()->forget('chat_user_id');
        return response()->json(['status' => 'success']);
    }
}
