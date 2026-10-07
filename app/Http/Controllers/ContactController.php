<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class ContactController extends Controller
{
    /**
     * Handle all landing page contact/inquiry submissions.
     * Routes: subscribe, partner, impact-deck-request
     */
    public function submit(Request $request)
    {
        $type    = $request->input('type', 'subscribe');
        $email   = $request->input('email');
        $name    = $request->input('name', '');
        $org     = $request->input('organisation', '');
        $message = $request->input('message', '');
        $tier    = $request->input('tier', '');

        $adminEmail = config('mail.from.address');

        $subjects = [
            'subscribe'    => '📧 New Subscriber — HuruLearn Mailing List',
            'partner'      => '🤝 New Partnership Enquiry — HuruLearn',
            'impact_deck'  => '📄 Impact Deck Request — HuruLearn',
        ];

        $subject = $subjects[$type] ?? '📬 New Enquiry — HuruLearn';

        try {
            Mail::raw(
                $this->buildEmailBody($type, $email, $name, $org, $message, $tier),
                function ($mail) use ($adminEmail, $subject, $email, $name) {
                    $mail->to($adminEmail)
                         ->replyTo($email ?: $adminEmail, $name ?: 'Visitor')
                         ->subject($subject);
                }
            );

            Log::info("HuruLearn contact: type={$type}, email={$email}, org={$org}");

            return redirect()->back()->with('contact_success', $this->successMessage($type));
        } catch (\Exception $e) {
            Log::error("HuruLearn contact mail failed: " . $e->getMessage());
            return redirect()->back()->with('contact_error', 'Failed to send. Please email us directly at ' . $adminEmail);
        }
    }

    private function buildEmailBody(string $type, string $email, string $name, string $org, string $message, string $tier): string
    {
        $lines = [
            "=== HuruLearn Landing Page — New " . strtoupper($type) . " ===",
            "",
            "Type    : " . $type,
            "Email   : " . ($email ?: 'not provided'),
            "Name    : " . ($name  ?: 'not provided'),
        ];

        if ($org)     $lines[] = "Org/Co  : " . $org;
        if ($tier)    $lines[] = "Package : " . $tier;
        if ($message) $lines[] = "\nMessage :\n" . $message;

        $lines[] = "";
        $lines[] = "Received at: " . now()->toDateTimeString() . " (EAT)";
        $lines[] = "Platform   : HuruLearn SMS Learning — https://hurulearn.hurudigital.co.tz";

        return implode("\n", $lines);
    }

    private function successMessage(string $type): string
    {
        return match($type) {
            'partner'     => "Thank you for your partnership interest! We'll be in touch within 2 business days.",
            'impact_deck' => "Thanks! We'll email your Impact Deck shortly.",
            default       => "You're on the list! We'll keep you updated on HuruLearn's progress.",
        };
    }
}
