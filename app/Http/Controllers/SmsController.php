<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Jobs\ProcessIncomingSms;
use Illuminate\Support\Facades\Log;

class SmsController extends Controller
{
    public function inbound(Request $request)
    {
        // Africa's Talking sends 'from' and 'text' in the POST body
        $from = $request->input('from');
        $text = $request->input('text');
        
        // Log the raw payload exactly as received from Africa's Talking
        Log::info('Incoming SMS from: ' . $from . ' with text: ' . $text);

        if (!$from || !$text) {
            return response()->json(['status' => 'error', 'message' => 'Invalid payload'], 400);
        }

        // ---------------------------------------------------------------------------
        // Strip the shared short-code keyword "HURU" from the beginning of the text.
        // Africa's Talking prepends it automatically for shared codes.
        // We match it case-insensitively and trim any surrounding whitespace that
        // remains, so the AI receives a clean, keyword-free question.
        // ---------------------------------------------------------------------------
        $keyword = 'HURU';

        if (!preg_match('/^' . preg_quote($keyword, '/') . '\b/i', $text)) {
            // Message was not addressed to our keyword — ignore it silently.
            Log::info('SMS ignored: does not start with keyword "' . $keyword . '"');
            return response()->json(['status' => 'ignored']);
        }

        $cleanText = trim(preg_replace('/^' . preg_quote($keyword, '/') . '\b\s*/i', '', $text));

        Log::info('Clean text after stripping keyword: ' . $cleanText);

        // Dispatch job immediately to ensure fast response
        ProcessIncomingSms::dispatch($from, $cleanText);
        Log::info('Job dispatched for user: ' . $from);

        return response()->json(['status' => 'success']);
    }
}
