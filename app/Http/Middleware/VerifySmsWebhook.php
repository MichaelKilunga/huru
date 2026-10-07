<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

/**
 * Protects the inbound SMS callback. Africa's Talking does not sign
 * callbacks, so the callback URL registered in their dashboard must carry
 * a shared secret: https://your-host/api/sms/inbound?token=<AT_WEBHOOK_SECRET>
 * (a X-Webhook-Token header works too).
 */
class VerifySmsWebhook
{
    public function handle(Request $request, Closure $next): Response
    {
        $expected = (string) config('services.at.webhook_secret');

        if ($expected === '') {
            if (app()->environment('production')) {
                Log::critical('AT_WEBHOOK_SECRET is not set; refusing inbound SMS in production.');

                return response()->json(['status' => 'error', 'message' => 'webhook secret not configured'], 503);
            }
            Log::warning('AT_WEBHOOK_SECRET is not set; accepting inbound SMS because APP_ENV is not production.');

            return $next($request);
        }

        $given = (string) ($request->query('token') ?? $request->header('X-Webhook-Token') ?? '');

        if ($given === '' || ! hash_equals($expected, $given)) {
            Log::warning('Rejected inbound SMS callback with bad token', ['ip' => $request->ip()]);

            return response()->json(['status' => 'error', 'message' => 'unauthorized'], 401);
        }

        return $next($request);
    }
}
