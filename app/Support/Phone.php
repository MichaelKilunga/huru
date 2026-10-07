<?php

namespace App\Support;

class Phone
{
    /**
     * Normalise a phone number to E.164, assuming Tanzania (+255) when no
     * country code is given. Returns null when the input cannot be a number.
     */
    public static function normalize(?string $input): ?string
    {
        if ($input === null) {
            return null;
        }
        $raw = preg_replace('/[^\d+]/', '', trim($input));
        if ($raw === '' || $raw === '+') {
            return null;
        }

        if (str_starts_with($raw, '+')) {
            $digits = substr($raw, 1);
        } elseif (str_starts_with($raw, '00')) {
            $digits = substr($raw, 2);
        } elseif (str_starts_with($raw, '255')) {
            $digits = $raw;
        } elseif (str_starts_with($raw, '0')) {
            $digits = '255' . substr($raw, 1);
        } elseif (strlen($raw) === 9 && in_array($raw[0], ['6', '7'], true)) {
            $digits = '255' . $raw;
        } else {
            $digits = $raw;
        }

        if (! preg_match('/^\d{10,15}$/', $digits)) {
            return null;
        }

        return '+' . $digits;
    }

    public static function isTanzanian(string $e164): bool
    {
        return (bool) preg_match('/^\+255[67]\d{8}$/', $e164);
    }

    public static function mask(?string $e164): string
    {
        if (! $e164 || strlen($e164) < 7) {
            return '****';
        }

        return substr($e164, 0, 4) . ' *** ' . substr($e164, -3);
    }
}
