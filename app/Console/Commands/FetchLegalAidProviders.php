<?php

namespace App\Console\Commands;

use App\Models\LocalResource;
use App\Services\TanzaniaContext;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

/**
 * Imports registered legal aid providers from the Mama Samia Legal Aid
 * Campaign site (mslac.or.tz) into local_resources as category legal_aid.
 */
class FetchLegalAidProviders extends Command
{
    protected $signature = 'huru:import-legal-aid {--pages=16 : Number of listing pages to read}';

    protected $description = 'Import legal aid providers from mslac.or.tz into the local resources directory';

    public function handle(TanzaniaContext $tz): int
    {
        $pages = (int) $this->option('pages');
        $imported = 0;

        for ($page = 0; $page < $pages; $page++) {
            $url = "https://www.mslac.or.tz/en/legal-aid-providers?page={$page}";
            $this->line('Fetching ' . ($page + 1) . "/{$pages}: {$url}");

            try {
                $response = Http::retry(3, 2000)->timeout(45)
                    ->withHeaders(['User-Agent' => 'HuruBot/1.0 (+https://hurudigital.co.tz)'])
                    ->get($url);
            } catch (\Throwable $e) {
                $this->error('Request failed: ' . $e->getMessage());

                continue;
            }

            if (! $response->successful()) {
                $this->error('HTTP ' . $response->status());

                continue;
            }

            $chunks = explode('class="item-columns"', $response->body());
            array_shift($chunks);
            if (empty($chunks)) {
                $this->warn('No provider cards found; stopping.');
                break;
            }

            foreach ($chunks as $chunk) {
                preg_match('/views-field-title.*?<strong class="field-content">(.*?)<\/strong>/s', $chunk, $m);
                $name = isset($m[1]) ? trim(strip_tags($m[1])) : null;
                if (! $name) {
                    continue;
                }

                preg_match('/views-field-field-region.*?<div class="field-content">(.*?)<\/div>/s', $chunk, $m);
                $regionRaw = isset($m[1]) ? trim(strip_tags($m[1])) : null;

                preg_match('/views-field-field-address.*?<div class="field-content">(.*?)<\/div>/s', $chunk, $m);
                $location = isset($m[1]) ? trim(preg_replace('/\s+/', ' ', strip_tags($m[1]))) : null;

                $region = null;
                $district = null;
                $detected = $tz->detectLocation(trim(($regionRaw ?? '') . ' ' . ($location ?? '')));
                if ($detected) {
                    $region = $detected['region'];
                    $district = $detected['district'];
                }
                if (! $region && $regionRaw) {
                    $region = Str::lower($regionRaw);
                }
                if (! $district && $location && preg_match('/wilaya\s+(?:ya\s+)?([a-z\']+)/i', $location, $dm)) {
                    $district = Str::lower($dm[1]);
                }

                $email = null;
                if ($location && preg_match('/[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}/i', $location, $em)) {
                    $email = $em[0];
                }
                preg_match('/views-field-field-email.*?href="mailto:(.*?)"/s', $chunk, $em2);
                if (! empty($em2[1])) {
                    $email = trim(strip_tags($em2[1]));
                }

                $phone = null;
                if ($location && preg_match('/(?:\+255|0)[67]\d{8}/', str_replace([' ', '-', '(', ')', '/'], '', $location), $pm)) {
                    $phone = $pm[0];
                }
                preg_match('/views-field-field-phone.*?<div class="field-content">(.*?)<\/div>/s', $chunk, $pm2);
                if (! empty($pm2[1])) {
                    $phone = trim(strip_tags($pm2[1]));
                }

                LocalResource::query()->updateOrCreate(
                    ['name' => $name],
                    [
                        'category' => 'legal_aid',
                        'region' => $region,
                        'district' => $district,
                        'location' => $location,
                        'email' => $email,
                        'phone' => $phone,
                        'source' => 'mslac.or.tz',
                        'is_active' => true,
                    ]
                );
                $imported++;
            }

            usleep(500000);
        }

        $this->info("Done. Providers imported or updated: {$imported}");

        return self::SUCCESS;
    }
}
