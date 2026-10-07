<!DOCTYPE html>
<html lang="sw" prefix="og: https://ogp.me/ns#">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

    {{-- ═══════════════════════════════════════════════════════
         PRIMARY SEO META
    ═══════════════════════════════════════════════════════ --}}
    <title>HuruLearn – Elimu ya Sheria kwa SMS 15054 | Msaada wa Kisheria Tanzania</title>
    <meta name="description" content="HuruLearn inatoa elimu ya sheria na haki za katiba kupitia SMS ya kawaida (15054) — bila intaneti wala simu janja. Tuma neno HURU kwenda 15054.">
    <meta name="keywords" content="SMS legal education, legal rights Tanzania, constitution Tanzania, offline learning, HuruLearn, Huru Digital, civic education, Kiswahili legal help, 15054, HURU">
    <meta name="author" content="Huru Digital Co. Ltd.">
    <meta name="robots" content="index, follow">

    {{-- Canonical & Language alternates --}}
    <link rel="canonical" href="https://hurulearn.hurudigital.co.tz/">
    <link rel="alternate" hreflang="sw" href="https://hurulearn.hurudigital.co.tz/">
    <link rel="alternate" hreflang="en" href="https://hurulearn.hurudigital.co.tz/">
    <link rel="alternate" hreflang="x-default" href="https://hurulearn.hurudigital.co.tz/">

    {{-- OPEN GRAPH --}}
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="HuruLearn">
    <meta property="og:title" content="HuruLearn – Elimu ya Sheria kwa SMS 15054">
    <meta property="og:description" content="Pata elimu ya sheria na haki za katiba kwa SMS bila intaneti. Tuma HURU kwenda 15054.">
    <meta property="og:url" content="https://hurulearn.hurudigital.co.tz/">
    <meta property="og:image" content="https://hurulearn.hurudigital.co.tz/og-image.svg">

    {{-- PWA & FAVICON --}}
    <link rel="manifest" href="/manifest.json">
    <link rel="icon" href="/logo.svg" type="image/svg+xml">
    <meta name="theme-color" content="#15803d">

    {{-- JSON-LD STRUCTURED DATA --}}
    @verbatim
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "SoftwareApplication",
      "name": "HuruLearn SMS Law Assistant",
      "applicationCategory": "EducationalApplication",
      "operatingSystem": "GSM Mobile Phone",
      "description": "Tuma neno HURU ikifuatiwa na swali la kisheria kwenda 15054 kupata majibu ya papo hapo kwa SMS bila intaneti.",
      "offers": {
        "@type": "Offer",
        "price": "0",
        "priceCurrency": "TZS"
      }
    }
    </script>
    @endverbatim

    {{-- FONTS --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&family=Space+Grotesk:wght@600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --gov-green: #15803d;
            --gov-green-dark: #166534;
            --gov-green-bg: #f0fdf4;
            --gov-green-border: #bbf7d0;
            --gov-green-text: #14532d;
            --gov-blue: #1d4ed8;
            --gov-blue-dark: #1e40af;
            --bg: #f8fafc;
            --card-bg: #ffffff;
            --text-main: #0f172a;
            --text-muted: #475569;
            --border-color: #cbd5e1;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        html, body {
            height: 100vh;
            height: 100dvh;
            max-height: 100vh;
            overflow: hidden;
            font-family: 'Inter', sans-serif;
            background: var(--bg);
            color: var(--text-main);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        /* Top Header */
        header {
            padding: 0.9rem 1.5rem;
            background: #ffffff;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-shrink: 0;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 0.6rem;
            text-decoration: none;
        }

        .brand-logo {
            width: 36px;
            height: 36px;
            border-radius: 6px;
        }

        .brand-title {
            font-family: 'Space Grotesk', sans-serif;
            font-weight: 800;
            font-size: 1.25rem;
            color: var(--gov-green-dark);
        }

        .header-nav {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .nav-link {
            color: var(--text-muted);
            text-decoration: none;
            font-size: 0.85rem;
            font-weight: 600;
            padding: 0.45rem 0.85rem;
            border-radius: 6px;
            transition: all 0.2s;
        }

        .nav-link:hover {
            color: var(--gov-blue);
            background: #eff6ff;
        }

        .nav-btn-chat {
            background: var(--gov-blue);
            color: #ffffff !important;
            padding: 0.45rem 1rem;
            border-radius: 6px;
            font-size: 0.85rem;
            font-weight: 700;
            text-decoration: none;
            transition: background 0.2s;
        }

        .nav-btn-chat:hover {
            background: var(--gov-blue-dark);
        }

        /* Single Viewport Hero Content */
        main {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem 1.5rem;
            overflow: hidden;
        }

        .hero-container {
            width: 100%;
            max-width: 680px;
            background: #ffffff;
            border: 1px solid var(--border-color);
            border-radius: 16px;
            padding: 2rem 2rem;
            text-align: center;
            box-shadow: 0 4px 12px rgba(15,23,42,0.04);
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 1.25rem;
        }

        .campaign-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: var(--gov-green-bg);
            border: 1px solid var(--gov-green-border);
            color: var(--gov-green-dark);
            font-size: 0.8rem;
            font-weight: 700;
            padding: 0.35rem 0.9rem;
            border-radius: 20px;
        }

        .hero-title {
            font-family: 'Space Grotesk', sans-serif;
            font-size: clamp(1.6rem, 3.8vw, 2.3rem);
            font-weight: 800;
            line-height: 1.2;
            color: var(--text-main);
        }

        .hero-title .highlight {
            color: var(--gov-green-dark);
        }

        /* Large SMS Instruction Card */
        .sms-action-card {
            width: 100%;
            background: var(--gov-green-bg);
            border: 2px solid var(--gov-green-border);
            border-radius: 12px;
            padding: 1.25rem 1.5rem;
            text-align: left;
            position: relative;
        }

        .sms-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 0.75rem;
        }

        .sms-header-title {
            font-size: 0.9rem;
            font-weight: 800;
            color: var(--gov-green-dark);
            display: flex;
            align-items: center;
            gap: 6px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .sms-shortcode-badge {
            background: var(--gov-green);
            color: #ffffff;
            font-weight: 900;
            font-size: 0.95rem;
            padding: 0.3rem 0.75rem;
            border-radius: 6px;
            letter-spacing: 0.5px;
        }

        .sms-instruction-body {
            background: #ffffff;
            border: 1.5px dashed var(--gov-green);
            border-radius: 8px;
            padding: 0.85rem 1rem;
            margin-bottom: 0.9rem;
        }

        .sms-step {
            font-size: 0.95rem;
            color: var(--text-main);
            font-weight: 600;
            line-height: 1.5;
        }

        .sms-keyword-box {
            display: inline-block;
            background: #dcfce7;
            color: #14532d;
            font-family: 'Space Grotesk', monospace;
            font-weight: 900;
            font-size: 1.05rem;
            padding: 0.15rem 0.5rem;
            border-radius: 4px;
            border: 1px solid #86efac;
        }

        .sms-example {
            font-size: 0.8rem;
            color: var(--text-muted);
            margin-top: 0.4rem;
            font-style: italic;
        }

        .sms-actions-row {
            display: flex;
            gap: 0.85rem;
            width: 100%;
        }

        .btn-sms-now {
            flex: 1;
            background: var(--gov-green);
            color: #ffffff;
            text-decoration: none;
            font-weight: 800;
            font-size: 0.95rem;
            padding: 0.85rem 1.25rem;
            border-radius: 8px;
            text-align: center;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: background 0.2s;
        }

        .btn-sms-now:hover {
            background: var(--gov-green-dark);
        }

        .btn-web-chat {
            flex: 1;
            background: #ffffff;
            border: 2px solid var(--gov-blue);
            color: var(--gov-blue);
            text-decoration: none;
            font-weight: 700;
            font-size: 0.95rem;
            padding: 0.85rem 1.25rem;
            border-radius: 8px;
            text-align: center;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: all 0.2s;
        }

        .btn-web-chat:hover {
            background: #eff6ff;
        }

        .samia-support {
            font-size: 0.8rem;
            color: var(--gov-green-dark);
            font-weight: 600;
        }

        /* Footer Bar */
        footer {
            padding: 0.75rem 1.5rem;
            background: #ffffff;
            border-top: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 0.78rem;
            color: var(--text-muted);
            flex-shrink: 0;
        }

        .footer-links {
            display: flex;
            gap: 1.25rem;
        }

        .footer-links a {
            color: var(--text-muted);
            text-decoration: none;
            transition: color 0.2s;
        }

        .footer-links a:hover {
            color: var(--gov-blue);
        }

        /* Responsive Mobile Layout */
        @media (max-width: 640px) {
            header { padding: 0.75rem 1rem; }
            main { padding: 0.75rem 1rem; }
            .hero-container { padding: 1.25rem 1rem; gap: 0.9rem; border-radius: 12px; }
            .hero-title { font-size: 1.4rem; }
            .sms-action-card { padding: 1rem; }
            .sms-actions-row { flex-direction: column; gap: 0.6rem; }
            .btn-sms-now, .btn-web-chat { padding: 0.75rem; font-size: 0.9rem; }
            footer { flex-direction: column; gap: 0.4rem; text-align: center; padding: 0.6rem 1rem; }
        }
    </style>
</head>
<body>

    <!-- Header Navigation -->
    <header>
        <a href="/" class="brand" aria-label="HuruLearn Homepage">
            <img src="/logo.svg" alt="HuruLearn" class="brand-logo" width="36" height="36">
            <span class="brand-title">HuruLearn</span>
        </a>
        <div class="header-nav">
            <a href="{{ route('community.index') }}" class="nav-link">Community</a>
            <a href="{{ route('chat.index') }}" class="nav-btn-chat">Web Chat</a>
        </div>
    </header>

    <!-- Main Single Viewport Content -->
    <main>
        <div class="hero-container">
            <div class="campaign-badge">
                Elimu ya Haki na Sheria kwa Kila Mtanzania
            </div>

            <h1 class="hero-title">
                Uliza Swali la Kisheria au Katiba kwa <span class="highlight">SMS Bure</span>
            </h1>

            <!-- SMS Primary Action Box -->
            <div class="sms-action-card">
                <div class="sms-header">
                    <span class="sms-header-title">
                        Njia Kuu ya SMS (Bila Intaneti)
                    </span>
                    <span class="sms-shortcode-badge">15054</span>
                </div>

                <div class="sms-instruction-body">
                    <div class="sms-step">
                        Tuma neno <span class="sms-keyword-box">HURU</span> ikifuatiwa na swali yako kwenda <strong style="color: var(--gov-green-dark);">15054</strong>
                    </div>
                    <div class="sms-example">
                        Mfano: <strong>HURU haki zangu ni zipi nikikamatwa na polisi?</strong>
                    </div>
                </div>

                <div class="sms-actions-row">
                    <a href="sms:15054?body=HURU%20" class="btn-sms-now" title="Tuma SMS kwenda 15054">
                        Tuma SMS Sasa (15054)
                    </a>
                    <a href="{{ route('chat.index') }}" class="btn-web-chat" title="Fungua Web Chat">
                        Au Tumia Web Chat
                    </a>
                </div>
            </div>

            {{-- <div class="samia-support">
                Inasaidia Kampeni ya Msaada wa Kisheria ya Mama Samia (Samia Legal Aid Campaign)
            </div> --}}
        </div>
    </main>

    <!-- Single Screen Compact Footer -->
    <footer>
        <div>
            © {{ date('Y') }} Huru Digital Co. Ltd. · Namba ya SMS: <strong>15054</strong>
        </div>
        <div class="footer-links">
            <a href="{{ route('legal.terms') }}">Vigezo na Masharti</a>
            <a href="{{ route('legal.privacy') }}">Sera ya Faragha</a>
            <a href="{{ route('community.index') }}">Jamii</a>
        </div>
    </footer>

</body>
</html>
