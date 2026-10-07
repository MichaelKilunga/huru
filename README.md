# Huru SMS

**Huru SMS** is a digital service that lets any person in Tanzania ask a question and get a clear, useful answer on their phone, in Kiswahili or English. It works on the simplest mobile phone through SMS, and on smartphones and computers through a web chat.

Owner and developer: **Huru Digital Co. Ltd.**, Tanzania.

---

## 1. What the service does

A citizen sends a question. Huru SMS reads it, understands the topic and the language, checks where the person is, looks up verified information about Tanzania, and replies with a short, practical answer. If the person needs an office or a service near them (for example a legal aid provider in Kahama), the reply names it with its contact.

Topics covered include:

- Law and rights (arrest, land, marriage, inheritance, tenants, contracts)
- Health (danger signs, clinics, vaccination, health insurance)
- Farming, livestock and fishing (seasons, seeds, pests, animal vaccines)
- Education (school system, examinations, student loans, school subjects)
- Government services (NIDA identity card, birth certificate, passport, TIN, government payments, electricity)
- Money and business (taxes, loans, mobile money safety, registering a business)
- Jobs and employment (contracts, leave, dismissal, job scams)
- Family and community (violence, child protection, where to get help)
- Technology and transport (SIM cards, phone safety, traffic fines, driving licence)
- Any other everyday question

## 2. How a citizen uses it

**By SMS (no internet needed)**

1. Open the message app on any phone.
2. Write the word **HURU** followed by the question. Example: `HURU nifanyeje kupata kitambulisho cha NIDA?`
3. Send it to **15054**.
4. The answer arrives as an SMS within a short time.

Helpful SMS commands:

| Send | What happens |
| --- | --- |
| `MSAADA` or `HELP` | A short guide to the service |
| `LUGHA SW` / `LUGHA EN` | Choose Kiswahili or English for replies |
| `JINA Asha` | Save your name |
| `MKOA Mwanza` | Save your region, so nearby services are suggested |
| `ACHA` | Stop receiving replies |
| `ANZA` | Start again |

**By web chat (with internet)**

1. Open the website and press **Web Chat**.
2. Enter a phone number. A one-time code is sent by SMS to confirm it is yours.
3. Ask questions in the chat. Previous conversations are saved, can be searched, and each answer can be rated helpful or not helpful.

## 3. What makes it different

- **Built for Tanzania.** The service assumes Tanzanian law, the Tanzanian shilling, East Africa time, Tanzanian institutions (NIDA, RITA, TRA, NHIF, NECTA, HESLB and others) and the real emergency numbers (112, 114, 115, 116).
- **Knows where you are.** It recognises all 31 regions, common districts and well-known neighbourhoods, and uses that to point people to services close to them.
- **Verified knowledge first.** Answers are grounded in a knowledge base and directories maintained by the operator, not only on general internet knowledge. The service says clearly when something should be confirmed with the responsible office, and it never invents phone numbers, fees or names of officials.
- **Two languages, one service.** It detects whether a question is in Kiswahili or English and answers in the same language.
- **Works on every phone.** SMS first, web second. Replies sent by SMS are kept short to fit a few text messages.
- **Safe to use.** Abusive language is warned and then blocked, emergencies are recognised and the right number is given first, and people can stop the service at any time.

## 4. What the operator can manage

A protected administration area lets Huru Digital staff:

- see how many questions arrive, by day, by topic and by channel;
- read conversations and the information used to answer them;
- add or edit knowledge entries (one by one or by importing a file);
- maintain the national directory of institutions and hotlines, and the list of local services by region and district;
- adjust the wording and behaviour of the service per topic and language;
- change settings such as reply length, language policy and safety limits;
- block or unblock users.

## 5. Privacy and safety

- Only the phone number and the conversation are stored. They are needed to continue a conversation and to let the person see their own history.
- The web chat requires a one-time SMS code before a phone number's history can be opened.
- A person's name and phone number are never sent together with the question to the language technology provider.
- Government payments are always described as being made through official control numbers, never cash to an officer.
- Full terms and a privacy policy are published on the website.

## 6. Technology used (for the technical reader)

| Part | Technology |
| --- | --- |
| Application | PHP 8.2 with the Laravel 12 framework |
| Database | MySQL 8 (SQLite for quick local testing) |
| SMS | Africa's Talking SMS gateway (shortcode 15054, keyword HURU) |
| Language technology | Google Gemini, used only to word the final reply after the service has gathered the Tanzanian context |
| Web pages | HTML, CSS and JavaScript; installable as a phone app (PWA) |
| Tests | 28 automated tests covering SMS, web chat, topic detection, location detection and the administration area |

Main parts of the source code:

| Folder or file | Purpose |
| --- | --- |
| `app/Services/ConversationService.php` | Handles every incoming question, commands, safety checks and logging |
| `app/Services/TopicClassifier.php` | Detects the topic of a question and recognises emergencies |
| `app/Services/TanzaniaContext.php` | Regions, districts, country facts and topic rules for Tanzania |
| `app/Services/KnowledgeRetriever.php` | Finds the most relevant verified knowledge entries |
| `app/Services/PromptEngine.php` | Combines persona, context, knowledge and contacts into instructions for the reply |
| `app/Services/Ai/` | Connection to the language technology provider |
| `app/Services/SmsService.php` | Sending SMS through Africa's Talking |
| `app/Http/Controllers/` | Web chat, SMS receiver, community and administration screens |
| `database/seeders/` | Starter knowledge, directories, personas and settings |
| `resources/views/` | All web pages |
| `tests/` | Automated tests |

## 7. Running the application

```bash
cp .env.example .env         # fill in GEMINI_API_KEY, AT_* and database details
composer install
npm install && npm run build
php artisan key:generate
php artisan migrate --seed   # creates tables, the admin account and starter data
php artisan serve            # open http://localhost:8000
php artisan queue:work       # processes SMS replies (or run the scheduler every minute)
php artisan huru:import-legal-aid   # imports the national legal aid provider list
```

The SMS gateway must be configured to deliver incoming messages to `/api/sms/inbound?token=<AT_WEBHOOK_SECRET>`.

Administration is at `/admin/login`.

## 8. Ownership

Huru SMS, its source code, its content and its documentation are the property of Huru Digital Co. Ltd. All rights reserved.
