# Huru SMS – contribution contracts for COSOTA

COSOTA asks for three contracts showing who contributed to Huru SMS and that all rights belong to Huru Digital Co. Ltd. Each contract is an agreement between the company and one contributor, and each assigns the economic rights in that person's contribution to the company.

| File | Who signs it | What it covers |
| --- | --- | --- |
| `Huru_SMS_Authors_Contract.pdf` | The person who had the idea and wrote the content | The concept and purpose, the name "Huru SMS", the SMS service design, all Kiswahili and English texts, the documentation |
| `Huru_SMS_Creators_Contract.pdf` | The person who designed how it looks and behaves | Screen designs, logo and visual identity, SMS conversation design, persona templates, diagrams |
| `Huru_SMS_Developers_Contract.pdf` | The person who wrote the software | Source code, architecture, database, integrations, tests, installation; delivery, open-source components, defect correction, security |

The same person may sign more than one contract if they played more than one role. One contract per role, each signed separately.

## How to complete them

1. Fill every blank: date, company registration number and address, the contributor's full name, nationality, NIN and address, the period of the work, the role (director, employee, shareholder, contractor), and the city for arbitration.
2. In Clause 4 tick the consideration that applies (a payment, or the remuneration already received as director, employee or shareholder, or other) and complete it.
3. Sign in the presence of a witness. A director signs for the company and applies the company stamp. Each party initials every page and the Schedule.
4. Scan the signed contracts and upload them to COSOTA with the application. Keep the originals with the company records.

The HTML files next to each PDF are the editable sources. If names or details should be typed rather than handwritten, edit the HTML and regenerate the PDF, or ask for the filled version.

These documents were prepared to support a copyright registration and are not a substitute for advice from an advocate. Have them reviewed by a lawyer before signing if any term is unclear or if a contributor is not a director or employee of the company.

## Completed versions

The `signed/` folder holds the completed contracts dated 12 October 2026, with the parties' details typed in, the company seal and the signatures of Mr Ibrahim Frank Ndunguru (Managing Director, and Author) and Mr Michael Simon Kilunga (Creator and Developer) placed in the signature blocks. The witness blocks carry the typed details of Advocate Allen Albert Kaminda and are left for him to sign and stamp. The company is named by its registered name, HURU DIGITAL LIMITED (registration number 151790429, incorporated 4 May 2021), and both parties' NINs are typed in from their identity cards.

## Executed versions

The `final/` folder holds the executed contracts: the generated pages with the witnessed execution page scanned by Advocate Allen Albert Kaminda on 13 October 2026 inserted in place of the unsigned one (page 5 of the Author's and Creator's contracts, page 6 of the Developer's). The scans he sent are kept in `signed and stamped/`.

## Compiled submission (upload this)

COSOTA's portal accepts a single document, so everything is uploaded as one compiled PDF: `docs/Huru_SMS_COSOTA_Compiled_Submission.pdf` (248 pages, continuously numbered, with a cover page stating it is a compilation, a contents table giving each document's page range, and PDF bookmarks). It contains, in order: the signed Declaration of Originality (kiapo cha uasili, Part A), the source code listing (Part B), the system design and architecture (Part C), the innovation documentation (Part D) and the three executed contracts (Parts E–G). Regenerate it with `py docs/compile_cosota_submission.py` after changing any constituent document. The declaration's editable source is `docs/Huru_SMS_Declaration_of_Originality.template.html`; the signatures and company seal are injected from the signed Creator's contract and the result rendered to PDF with headless Edge (see the script's header).
