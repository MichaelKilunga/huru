<?php

namespace Tests\Unit;

use App\Services\LanguageDetector;
use App\Services\TanzaniaContext;
use App\Support\Phone;
use PHPUnit\Framework\TestCase;

class LanguageAndLocationTest extends TestCase
{
    public function test_language_detection(): void
    {
        $d = new LanguageDetector();
        $this->assertSame('sw', $d->detect('Naomba kujua haki zangu nikikamatwa'));
        $this->assertSame('en', $d->detect('What are my rights when arrested?'));
        $this->assertSame('sw', $d->detect('nimedhulumiwa shamba langu kijijini'));
        $this->assertNull($d->detect(''));
    }

    public function test_location_detection(): void
    {
        $tz = new TanzaniaContext();

        $loc = $tz->detectLocation('naomba msaada wa kisheria nipo Sinza Dar es Salaam');
        $this->assertSame('dar es salaam', $loc['region']);
        $this->assertSame('ubungo', $loc['district']);
        $this->assertSame('sinza', $loc['area']);

        $loc = $tz->detectLocation('Nipo Kahama Shinyanga');
        $this->assertSame('shinyanga', $loc['region']);
        $this->assertSame('kahama', $loc['district']);

        $loc = $tz->detectLocation('I live in Mwanza');
        $this->assertSame('mwanza', $loc['region']);

        $this->assertNull($tz->detectLocation('Hakuna mahali hapa'));
    }

    public function test_phone_normalisation(): void
    {
        $this->assertSame('+255712345678', Phone::normalize('0712 345 678'));
        $this->assertSame('+255712345678', Phone::normalize('255712345678'));
        $this->assertSame('+255712345678', Phone::normalize('+255712345678'));
        $this->assertSame('+255712345678', Phone::normalize('712345678'));
        $this->assertSame('+254700000000', Phone::normalize('+254700000000'));
        $this->assertNull(Phone::normalize('abc'));
        $this->assertTrue(Phone::isTanzanian('+255712345678'));
        $this->assertFalse(Phone::isTanzanian('+254700000000'));
    }

    public function test_locale_block_mentions_tanzania_and_currency(): void
    {
        $tz = new TanzaniaContext();
        $this->assertStringContainsString('Tanzania', $tz->localeBlock('en'));
        $this->assertStringContainsString('TZS', $tz->localeBlock('sw'));
        $this->assertStringContainsString('112', $tz->localeBlock('sw'));
    }
}
