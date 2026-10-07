<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Privacy Policy &mdash; HuruLearn</title>
    <meta name="description" content="Read HuruLearn's Privacy Policy to understand how we collect, use, and protect your data when using our SMS education service in Tanzania.">
    <meta name="robots" content="noindex, follow">
    <link rel="canonical" href="https://hurulearn.hurudigital.co.tz/privacy-policy">

    {{-- Open Graph --}}
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="HuruLearn">
    <meta property="og:title" content="Privacy Policy &mdash; HuruLearn">
    <meta property="og:description" content="Read HuruLearn's Privacy Policy for our SMS education platform.">
    <meta property="og:url" content="https://hurulearn.hurudigital.co.tz/privacy-policy">
    <meta property="og:image" content="https://hurulearn.hurudigital.co.tz/og-image.svg">

    {{-- JSON-LD Breadcrumb --}}
    @verbatim
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "BreadcrumbList",
      "itemListElement": [
        { "@type": "ListItem", "position": 1, "name": "Home", "item": "https://hurulearn.hurudigital.co.tz/" },
        { "@type": "ListItem", "position": 2, "name": "Privacy Policy", "item": "https://hurulearn.hurudigital.co.tz/privacy-policy" }
      ]
    }
    </script>
    @endverbatim
    <link rel="icon" href="/logo.svg" type="image/svg+xml">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Space+Grotesk:wght@500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --indigo-dark: #0f0d2e; --indigo-bg: #07061a;
            --blue: #3b82f6; --amber: #f59e0b;
            --white: #ffffff; --gray-300: #cbd5e1; --gray-400: #94a3b8; --gray-600: #475569;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background: var(--indigo-bg); color: var(--gray-300); line-height: 1.7; }
        .container { max-width: 800px; margin: 4rem auto; padding: 0 2rem; }
        .header { margin-bottom: 3rem; text-align: center; }
        .logo { display: inline-flex; align-items: center; gap: 0.8rem; text-decoration: none; margin-bottom: 2rem; }
        .logo img { width: 42px; height: 42px; }
        .logo span { font-family: 'Space Grotesk', sans-serif; font-weight: 700; font-size: 1.5rem; color: #fff; }
        h1 { font-family: 'Space Grotesk', sans-serif; color: #fff; font-size: 2.5rem; margin-bottom: 1rem; }
        h2 { font-family: 'Space Grotesk', sans-serif; color: var(--amber); font-size: 1.5rem; margin: 2.5rem 0 1rem; }
        p { margin-bottom: 1.2rem; }
        ul { margin-bottom: 1.5rem; padding-left: 1.5rem; list-style: square; color: var(--gray-400); }
        li { margin-bottom: 0.5rem; }
        .last-updated { font-size: 0.875rem; color: var(--gray-600); margin-top: 1rem; }
        .footer-link { display: inline-block; margin-top: 4rem; color: var(--blue); text-decoration: none; font-weight: 600; border-bottom: 1px solid transparent; transition: all 0.2s; }
        .footer-link:hover { border-bottom-color: var(--blue); }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <a href="/" class="logo">
                <img src="/logo.svg" alt="HuruLearn Logo">
                <span>HuruLearn</span>
            </a>
            <h1>Privacy Policy</h1>
            <p class="last-updated">Last Updated: February 26, 2026</p>
        </div>

        <section>
            <h2>1. Information We Collect</h2>
            <p>We collect minimal information required to provide our service:</p>
            <ul>
                <li><strong>Phone Number:</strong> We store your phone number to track your learning progress, manage sessions, and enforce moderation.</li>
                <li><strong>SMS Content:</strong> We store the content of your questions and our system answers to improve our educational quality.</li>
            </ul>
        </section>

        <section>
            <h2>2. Data Usage</h2>
            <p>Your data is used solely for educational purposes and platform monitoring. We do not sell your personal data or phone number to third-party advertisers. We use your data to:</p>
            <ul>
                <li>Respond to your educational queries via SMS and Web.</li>
                <li>Analyse popular topics to improve our service delivery.</li>
                <li>Identify and prevent abusive usage.</li>
            </ul>
        </section>

        <section>
            <h2>3. Third-Party Services</h2>
            <p>HuruLearn utilizes industry-standard third-party providers:</p>
            <ul>
                <li><strong>Africa's Talking:</strong> For SMS gateway routing and delivery.</li>
                <li><strong>Cloud Infrastructure:</strong> To process and generate responses.</li>
            </ul>
            <p>These providers only receive the data necessary for their specific function (e.g., the text of your question is passed without your personal identity).</p>
        </section>

        <section>
            <h2>4. Security</h2>
            <p>We implement appropriate security measures to protect against unauthorized access or alteration of your data. However, no data transmission over GSM or the internet can be guaranteed as 100% secure.</p>
        </section>

        <section>
            <h2>5. Your Rights</h2>
            <p>If you wish to have your phone number and message history deleted from our system, please contact us at <strong>info@hurulearn.hurudigital.co.tz</strong> with your request.</p>
        </section>

        <a href="/" class="footer-link">← Back to Homepage</a>
        <nav style="margin-top:1rem; display:flex; gap:1rem; flex-wrap:wrap;">
            <a href="{{ route('legal.terms') }}" style="color:var(--blue); text-decoration:none; font-size:.875rem;">Terms &amp; Conditions →</a>
        </nav>
    </div>
</body>
</html>
