# Project Rules - Huru

Huru is a generic, Tanzania-aware question answering service for every citizen (not only students, not only legal topics). It runs over SMS (shortcode `15054`, keyword `HURU`) and a web chat.

## Product
- **Generic by design**: any topic is valid. Topic detection (`App\Services\TopicClassifier`) only chooses the persona, knowledge and contacts; it never refuses a topic.
- **Tanzanian context first**: assume TZS, East Africa Time, Tanzanian law and institutions, Swahili as the default language. Country facts live in `App\Services\TanzaniaContext`; national contacts and knowledge live in the database and are edited in the admin panel, never hard-coded in prompts.
- **Honesty over confidence**: the engine must say when something should be confirmed with the responsible office and must never invent phone numbers, fees or officials. Keep this in every persona and constraint change.

## User interface and aesthetics
- **No "AI"/"Bot" wording** in user-facing text, page titles or labels. Present Huru as a direct guidance service. The admin panel may say "engine".
- **Government palette**: Forest Green (`#166534` / `#15803d`), Royal Blue (`#1e40af` / `#1d4ed8`), white/off-white (`#ffffff`, `#f8fafc`). No brown, amber, gradients or glow effects on public pages.
- **Light, high-readability themes** for all citizen-facing pages. Plain Inter font.
- **SMS visibility**: the keyword `HURU` and shortcode `15054` must stay clearly visible on the home page and the web chat login screen, and must survive any layout refactor. Values come from settings (`sms_keyword`, `sms_shortcode`).
- Swahili is the primary language of public pages; English appears as a secondary line where it helps.

## Engineering
- All channel logic goes through `App\Services\ConversationService`; do not duplicate moderation or logging in controllers or jobs.
- New settings are declared in `App\Support\Settings::schema()` only; the admin form, validation and defaults derive from it.
- Keep the inbound SMS webhook behind `AT_WEBHOOK_SECRET` and keep TLS verification on for all outbound HTTP.
- Tests use SQLite in memory (`phpunit.xml`); keep migrations driver-agnostic.
