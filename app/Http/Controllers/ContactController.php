<?php

namespace App\Http\Controllers;

use App\Support\Settings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;

class ContactController extends Controller
{
    public function submit(Request $request)
    {
        $data = $request->validate([
            'type' => 'nullable|in:subscribe,partner,general',
            'email' => 'required|email|max:120',
            'name' => 'nullable|string|max:80',
            'organisation' => 'nullable|string|max:120',
            'message' => 'nullable|string|max:2000',
            'website' => 'nullable|max:0', // honeypot
        ]);

        $key = 'contact:' . $request->ip();
        if (RateLimiter::tooManyAttempts($key, 5)) {
            return back()->with('contact_error', 'Umetuma mara nyingi. Jaribu tena baadaye.');
        }
        RateLimiter::hit($key, 3600);

        $type = $data['type'] ?? 'general';
        $to = Settings::get('contact_email') ?: config('mail.from.address');
        $app = Settings::get('app_name');

        $subject = match ($type) {
            'subscribe' => "[{$app}] New subscriber",
            'partner' => "[{$app}] Partnership enquiry",
            default => "[{$app}] New enquiry",
        };

        $body = implode("\n", array_filter([
            "Type: {$type}",
            'Email: ' . $data['email'],
            'Name: ' . ($data['name'] ?? '-'),
            'Organisation: ' . ($data['organisation'] ?? '-'),
            '',
            $data['message'] ?? '',
            '',
            'Received: ' . now()->toDateTimeString() . ' (' . config('app.timezone') . ')',
        ]));

        try {
            if ($to) {
                Mail::raw($body, function ($mail) use ($to, $subject, $data) {
                    $mail->to($to)->replyTo($data['email'], $data['name'] ?? 'Visitor')->subject($subject);
                });
            }
            Log::info('Contact form', ['type' => $type, 'email' => $data['email']]);

            return back()->with('contact_success', match ($type) {
                'partner' => 'Asante! Tutawasiliana nawe ndani ya siku 2 za kazi.',
                'subscribe' => 'Umejiunga! Tutakutumia taarifa za maendeleo.',
                default => 'Ujumbe wako umepokelewa. Asante.',
            });
        } catch (\Throwable $e) {
            Log::error('Contact mail failed: ' . $e->getMessage());

            return back()->with('contact_error', 'Imeshindikana kutuma. Tafadhali tutumie barua pepe moja kwa moja: ' . $to);
        }
    }
}
