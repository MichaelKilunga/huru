# Huru

Huru is an everyday question-answering service for the people of Tanzania. Anyone can ask anything, in Swahili or English, and get a short, practical, locally grounded answer:

- **SMS** on any phone: send `HURU <question>` to `15054` (Africa's Talking).
- **Web chat** at `/chat`: phone number plus one-time code, conversation memory, history search, feedback.

It is the successor of the HuruLearn legal-education bot, rebuilt as a generic assistant with real Tanzanian context: topic detection, a curated knowledge base, a national directory of institutions, place-bound services by region and district, emergency awareness and bilingual handling.

## How an answer is produced

1. `SmsController` / `WebChatController` receive the text and hand it to `ConversationService` with the channel.
2. `ConversationService` runs service commands (`MSAADA`, `LUGHA SW|EN`, `JINA`, `MKOA`, `ACHA`, `ANZA`), moderation with strikes, rate limiting and the maintenance switch.
3. `TopicClassifier` picks a topic (legal, health, agriculture, education, government, finance, business, employment, family, technology, transport, general) and flags emergencies. `TanzaniaContext` detects region, district or neighbourhood in the text.
4. `PromptEngine` assembles a system instruction: persona for the topic and language, country context (date in EAT, TZS, institutions, emergency numbers), topic rules, citizen profile, matching `knowledge_entries` (bilingual keyword retrieval with light stemming), nearby `local_resources`, relevant `reference_contacts`, and channel-specific output rules (SMS length vs web length, plain text, honesty rules).
5. `GeminiClient` calls Google Gemini with the system instruction plus the recent conversation turns, and the reply, tokens, latency and status are logged to `ai_logs`.

Everything an operator needs to tune lives in the admin panel: knowledge base (with CSV/JSON import), national directory, local services, personas, settings, citizens, conversations.

## Requirements

- PHP 8.2+, Composer, Node 20+, MySQL 8 (SQLite works for a quick start).
- A Google Gemini API key.
- An Africa's Talking account with an SMS shortcode or sender ID (optional for local web testing).

## Setup

```bash
cp .env.example .env            # then fill GEMINI_API_KEY, AT_*, DB_*, ADMIN_PASSWORD
composer install
npm install && npm run build
php artisan key:generate
php artisan migrate --seed       # creates the admin user and starter data
php artisan serve                # http://localhost:8000
```

Queue worker for SMS replies (one of):

```bash
php artisan queue:work           # long-running worker
# or add to cron:  * * * * * php /path/artisan schedule:run   (drains the queue every minute)
```

Import the full Mama Samia Legal Aid Campaign provider list:

```bash
php artisan huru:import-legal-aid
```

## Africa's Talking configuration

Inbound callback URL (note the token):

```
https://your-host/api/sms/inbound?token=<AT_WEBHOOK_SECRET>
```

Delivery reports (optional): `https://your-host/api/sms/delivery?token=<AT_WEBHOOK_SECRET>`.

On a shared short code the operator prepends the keyword; set `AT_REQUIRE_KEYWORD=true` to ignore messages without it.

## Environment variables

| Variable | Purpose |
| --- | --- |
| `GEMINI_API_KEY` | Google Gemini key. Model, temperature and token limits are set in the admin panel. |
| `AT_USERNAME`, `AT_API_KEY`, `AT_FROM` | Africa's Talking credentials and sender (shortcode). |
| `AT_WEBHOOK_SECRET` | Random string that must be present as `?token=` on inbound callbacks. Required in production. |
| `AT_REQUIRE_KEYWORD` | Ignore inbound SMS without the keyword. |
| `ADMIN_EMAIL`, `ADMIN_PASSWORD`, `ADMIN_NAME` | Admin account created by the seeder. |
| `APP_TIMEZONE` | Defaults to `Africa/Dar_es_Salaam`. |

## Admin panel

`/admin/login` with the seeded admin account.

- **Dashboard**: volume by day and topic, channel split, tokens, latency, feedback, health checks.
- **Conversations / Citizens**: full transcripts with the exact prompt used, block or unblock, clear strikes, delete a citizen.
- **Knowledge base**: curated fact blocks by topic and language; import CSV or JSON.
- **National directory**: hotlines and institutions, with a verified flag the operator controls.
- **Local services**: legal aid providers, hospitals, police desks and offices by region and district.
- **Personas**: one persona per topic and language.
- **Settings**: everything from language policy and reply length to OTP and strike limits, validated against a schema.

## SMS commands

| Command | Effect |
| --- | --- |
| `MSAADA` / `HELP` | Help text |
| `LUGHA SW` / `LUGHA EN` / `LUGHA AUTO` | Reply language |
| `JINA Asha` | Save a name |
| `MKOA Mwanza` | Save a home region for nearby services |
| `ACHA` / `STOP` | Pause replies |
| `ANZA` / `START` | Resume |

## Testing

```bash
php artisan test
```

Tests run on in-memory SQLite and mock the model client and SMS gateway.

## Security notes

- Inbound SMS webhook requires a shared secret and is rate limited per phone.
- Web chat login uses an SMS one-time code (hashed, expiring, attempt-limited). Outside production, when SMS is not configured, the session opens directly so local testing works.
- Settings only accept keys declared in `App\Support\Settings`.
- TLS verification is on for every outbound call; the Gemini key travels in a header, not the URL.
- Admin area uses session auth with role checks and login throttling.

## Licence

Proprietary. © Huru Digital Co. Ltd.
