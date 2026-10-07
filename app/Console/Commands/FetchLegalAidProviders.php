<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\LegalAidProvider;
use Illuminate\Support\Str;

class FetchLegalAidProviders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fetch-legal-aid-providers {--pages=16 : Number of pages to scrape}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Dynamically scrapes and imports active legal aid providers from the official Mama Samia Legal Aid Campaign website';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $totalPages = (int) $this->option('pages');
        $this->info("🚀 Starting dynamic import of Legal Aid Providers from mslac.or.tz (fetching {$totalPages} pages)...");

        $importedCount = 0;

        for ($page = 0; $page < $totalPages; $page++) {
            $url = "https://www.mslac.or.tz/en/legal-aid-providers?page={$page}";
            $this->info("Fetching page " . ($page + 1) . "/{$totalPages}: {$url}");

            try {
                $response = Http::retry(3, 2000)->timeout(45)->withoutVerifying()->withHeaders([
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                ])->get($url);

                if (!$response->successful()) {
                    $this->error("Failed to fetch page " . ($page + 1) . ". Status: " . $response->status());
                    continue;
                }

                $html = $response->body();

                // Split HTML into individual provider card chunks
                $chunks = explode('class="item-columns"', $html);
                
                // The first chunk is everything before the first card, skip it
                array_shift($chunks);

                if (empty($chunks)) {
                    $this->warn("No provider cards found on page " . ($page + 1) . ". Stopping.");
                    break;
                }

                foreach ($chunks as $chunk) {
                    // Extract Name
                    preg_match('/views-field-title.*?<strong class="field-content">(.*?)<\/strong>/s', $chunk, $nameMatch);
                    $name = isset($nameMatch[1]) ? trim(strip_tags($nameMatch[1])) : null;

                    if (empty($name)) {
                        continue;
                    }

                    // Extract Region (Mkoa)
                    preg_match('/views-field-field-region.*?<div class="field-content">(.*?)<\/div>/s', $chunk, $regionMatch);
                    $region = isset($regionMatch[1]) ? trim(strip_tags($regionMatch[1])) : null;

                    // Extract Address / Location (Anwani / Eneo)
                    preg_match('/views-field-field-address.*?<div class="field-content">(.*?)<\/div>/s', $chunk, $addressMatch);
                    $location = isset($addressMatch[1]) ? trim(strip_tags($addressMatch[1])) : null;

                    // Dynamically parse District (Wilaya) from the address string
                    $district = null;
                    if ($location) {
                        // Match patterns like "wilaya ya ubungo" or "wilaya kinondoni" or "wilaya ya  ilala"
                        if (preg_match('/wilaya\s+(?:ya\s+)?([a-z]+)/i', $location, $districtMatch)) {
                            $district = strtoupper(trim($districtMatch[1]));
                        }
                    }

                    // Dynamically extract Email from address if any, with fallback
                    $email = null;
                    if ($location && preg_match('/[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}/i', $location, $emailMatch)) {
                        $email = trim($emailMatch[0]);
                    }
                    preg_match('/views-field-field-email.*?href="mailto:(.*?)"/s', $chunk, $emailMatchSep);
                    if (isset($emailMatchSep[1])) {
                        $email = trim(strip_tags($emailMatchSep[1]));
                    }

                    // Dynamically extract Phone from address if any, with fallback
                    $phone = null;
                    if ($location) {
                        // Strip spaces to match phone patterns easily
                        $cleanLocation = str_replace([' ', '-', '(', ')', '/'], '', $location);
                        if (preg_match('/(?:\+255|0)[67]\d{8}/', $cleanLocation, $phoneMatch)) {
                            $phone = trim($phoneMatch[0]);
                        }
                    }
                    preg_match('/views-field-field-phone.*?<div class="field-content">(.*?)<\/div>/s', $chunk, $phoneMatchSep);
                    if (isset($phoneMatchSep[1])) {
                        $phone = trim(strip_tags($phoneMatchSep[1]));
                    }

                    // Update or create in our database
                    LegalAidProvider::updateOrCreate(
                        ['name' => $name],
                        [
                            'region' => $region,
                            'district' => $district,
                            'location' => $location,
                            'email' => $email,
                            'phone' => $phone,
                            'language' => 'sw', // Default to Swahili since these are local providers
                        ]
                    );

                    $importedCount++;
                }

                $this->info("Imported page " . ($page + 1) . " successfully.");

                // Polite sleep to prevent hammering the server
                usleep(500000);

            } catch (\Throwable $e) {
                $this->error("Error importing page " . ($page + 1) . ": " . $e->getMessage());
            }
        }

        $this->info("✅ Import complete! Total active providers imported/updated: {$importedCount}");
        return Command::SUCCESS;
    }
}
