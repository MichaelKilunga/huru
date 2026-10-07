<!DOCTYPE html>
<html lang="sw">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $settings['app_name'] }} – Uliza lolote, kwa SMS {{ $settings['sms_shortcode'] }} au mtandaoni</title>
    <meta name="description" content="{{ $settings['app_name'] }} hujibu swali lolote la maisha ya kila siku Tanzania: sheria, afya, kilimo, masomo, huduma za serikali, fedha, ajira. Tuma {{ $settings['sms_keyword'] }} kwenda {{ $settings['sms_shortcode'] }} bila intaneti, au tumia web chat.">
    <meta name="keywords" content="Huru, SMS {{ $settings['sms_shortcode'] }}, maswali Tanzania, sheria, afya, kilimo, NIDA, TRA, elimu, msaada wa kisheria, Kiswahili">
    <meta name="author" content="Huru Digital Co. Ltd.">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="{{ url('/') }}">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="{{ $settings['app_name'] }}">
    <meta property="og:title" content="{{ $settings['app_name'] }} – Uliza lolote kwa SMS {{ $settings['sms_shortcode'] }}">
    <meta property="og:description" content="Majibu ya haraka kwa maswali ya sheria, afya, kilimo, masomo, huduma za serikali na zaidi. Kiswahili au Kiingereza.">
    <meta property="og:url" content="{{ url('/') }}">
    <meta property="og:image" content="{{ url('/og-image.svg') }}">
    <link rel="manifest" href="/manifest.json">
    <link rel="icon" href="/logo.svg" type="image/svg+xml">
    <link rel="apple-touch-icon" href="/logo.svg">
    <meta name="theme-color" content="#15803d">
    @verbatim
    <script type="application/ld+json">
    {"@context":"https://schema.org","@type":"WebApplication","name":"Huru","applicationCategory":"UtilitiesApplication","operatingSystem":"Any","description":"Everyday question answering service for Tanzanians over SMS and web, in Swahili and English.","offers":{"@type":"Offer","price":"0","priceCurrency":"TZS"}}
    </script>
    @endverbatim
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root{--green:#15803d;--green-dark:#166534;--green-bg:#f0fdf4;--green-border:#bbf7d0;--blue:#1d4ed8;--blue-dark:#1e40af;--blue-bg:#eff6ff;--bg:#f8fafc;--card:#ffffff;--text:#0f172a;--muted:#475569;--border:#cbd5e1;--radius:12px}
        *{margin:0;padding:0;box-sizing:border-box}
        html{scroll-behavior:smooth}
        body{font-family:'Inter',system-ui,sans-serif;background:var(--bg);color:var(--text);line-height:1.55}
        a{color:var(--blue)}
        .wrap{max-width:1080px;margin:0 auto;padding:0 16px}
        header{background:#fff;border-bottom:1px solid var(--border);position:sticky;top:0;z-index:20}
        .nav{display:flex;align-items:center;justify-content:space-between;height:64px}
        .brand{display:flex;align-items:center;gap:10px;text-decoration:none;color:var(--green-dark);font-weight:800;font-size:1.3rem}
        .brand img{width:36px;height:36px;border-radius:8px}
        .nav-links{display:flex;gap:8px;align-items:center}
        .nav-links a{text-decoration:none;font-weight:600;font-size:.9rem;padding:8px 14px;border-radius:8px;color:var(--muted)}
        .nav-links a:hover{background:var(--bg);color:var(--text)}
        .nav-links a.primary{background:var(--green);color:#fff}
        .nav-links a.primary:hover{background:var(--green-dark)}
        .sms-pill{display:inline-flex;align-items:center;gap:6px;background:var(--green-bg);border:1px solid var(--green-border);color:var(--green-dark);font-weight:700;font-size:.85rem;padding:6px 12px;border-radius:999px}
        .hero{padding:48px 0 32px;display:grid;grid-template-columns:1.2fr 1fr;gap:32px;align-items:center}
        .hero h1{font-size:2.4rem;line-height:1.15;font-weight:800;letter-spacing:-.02em;margin:14px 0}
        .hero h1 span{color:var(--green)}
        .hero p.lead{font-size:1.1rem;color:var(--muted);max-width:52ch}
        .hero p.sub{font-size:.95rem;color:var(--muted);margin-top:8px}
        .chips{display:flex;flex-wrap:wrap;gap:8px;margin-top:20px}
        .chip{background:#fff;border:1px solid var(--border);border-radius:999px;padding:6px 12px;font-size:.85rem;font-weight:600;color:var(--muted)}
        .sms-card{background:var(--card);border:2px solid var(--green);border-radius:var(--radius);padding:24px}
        .sms-card .label{font-size:.75rem;letter-spacing:.08em;text-transform:uppercase;font-weight:800;color:var(--green-dark)}
        .sms-card .code{font-size:3rem;font-weight:800;color:var(--green-dark);line-height:1;margin:8px 0}
        .sms-card .how{background:var(--green-bg);border:1px solid var(--green-border);border-radius:8px;padding:12px 14px;margin:14px 0;font-size:.95rem}
        .kw{display:inline-block;background:#dcfce7;color:#14532d;font-weight:800;padding:2px 8px;border-radius:4px;border:1px solid #86efac}
        .sms-card .example{font-size:.85rem;color:var(--muted);font-style:italic;margin-top:6px}
        .btn{display:inline-flex;align-items:center;justify-content:center;gap:8px;text-decoration:none;font-weight:700;padding:12px 18px;border-radius:8px;border:2px solid transparent;font-size:.95rem;cursor:pointer}
        .btn-green{background:var(--green);color:#fff}
        .btn-green:hover{background:var(--green-dark)}
        .btn-blue-outline{background:#fff;border-color:var(--blue);color:var(--blue)}
        .btn-blue-outline:hover{background:var(--blue-bg)}
        .btn-row{display:flex;gap:10px;flex-wrap:wrap;margin-top:6px}
        .btn-row .btn{flex:1;min-width:160px}
        section{padding:36px 0}
        h2{font-size:1.5rem;font-weight:800;letter-spacing:-.01em;margin-bottom:6px}
        .section-sub{color:var(--muted);margin-bottom:22px}
        .steps{display:grid;grid-template-columns:repeat(3,1fr);gap:16px}
        .step{background:var(--card);border:1px solid var(--border);border-radius:var(--radius);padding:20px}
        .step .n{width:34px;height:34px;border-radius:8px;background:var(--blue);color:#fff;font-weight:800;display:flex;align-items:center;justify-content:center;margin-bottom:12px}
        .step h3{font-size:1.05rem;margin-bottom:6px}
        .step p{color:var(--muted);font-size:.93rem}
        .topics{display:grid;grid-template-columns:repeat(3,1fr);gap:14px}
        .topic{background:var(--card);border:1px solid var(--border);border-radius:var(--radius);padding:18px;text-decoration:none;color:var(--text);display:block;transition:border-color .15s}
        .topic:hover{border-color:var(--green)}
        .topic h3{font-size:1rem;color:var(--green-dark);margin-bottom:6px}
        .topic p{font-size:.88rem;color:var(--muted)}
        .topic .q{display:block;margin-top:10px;font-size:.85rem;color:var(--blue);font-weight:600}
        .voices{display:grid;grid-template-columns:repeat(3,1fr);gap:14px}
        .voice{background:var(--card);border:1px solid var(--border);border-radius:var(--radius);padding:18px;font-size:.93rem}
        .voice .who{margin-top:10px;font-size:.8rem;color:var(--muted);font-weight:600}
        .trust{display:grid;grid-template-columns:repeat(4,1fr);gap:14px}
        .trust div{background:var(--card);border:1px solid var(--border);border-radius:var(--radius);padding:16px;font-size:.9rem}
        .trust strong{display:block;color:var(--green-dark);margin-bottom:4px}
        .contact{background:var(--card);border:1px solid var(--border);border-radius:var(--radius);padding:24px;display:grid;grid-template-columns:1fr 1fr;gap:24px}
        .contact form{display:grid;gap:10px}
        .contact input,.contact textarea,.contact select{width:100%;padding:11px 12px;border:1.5px solid var(--border);border-radius:8px;font:inherit;font-size:.93rem;background:#fff}
        .contact input:focus,.contact textarea:focus{outline:none;border-color:var(--green)}
        .alert{padding:12px 14px;border-radius:8px;font-size:.9rem;font-weight:600;margin-bottom:12px}
        .alert-ok{background:var(--green-bg);border:1px solid var(--green-border);color:var(--green-dark)}
        .alert-err{background:#fef2f2;border:1px solid #fecaca;color:#991b1b}
        .hp{position:absolute;left:-9999px}
        footer{background:#fff;border-top:1px solid var(--border);padding:24px 0;font-size:.85rem;color:var(--muted)}
        footer .wrap{display:flex;justify-content:space-between;gap:12px;flex-wrap:wrap}
        footer a{color:var(--muted);text-decoration:none;margin-left:16px}
        footer a:hover{color:var(--blue)}
        @media (max-width:860px){.hero{grid-template-columns:1fr;padding-top:28px}.steps,.topics,.voices{grid-template-columns:1fr}.trust{grid-template-columns:1fr 1fr}.contact{grid-template-columns:1fr}.hero h1{font-size:1.9rem}.nav-links a:not(.primary){display:none}}
    </style>
</head>
<body>
<header>
    <div class="wrap nav">
        <a href="/" class="brand" aria-label="{{ $settings['app_name'] }}"><img src="/logo.svg" alt="" width="36" height="36">{{ $settings['app_name'] }}</a>
        <div class="nav-links">
            <span class="sms-pill">SMS: {{ $settings['sms_keyword'] }} → {{ $settings['sms_shortcode'] }}</span>
            <a href="#mada">Mada</a>
            <a href="{{ route('community.index') }}">Jamii</a>
            <a href="{{ route('chat.index') }}" class="primary">Fungua Web Chat</a>
        </div>
    </div>
</header>

<main>
    <div class="wrap">
        <div class="hero">
            <div>
                <span class="sms-pill">Kwa kila Mtanzania · For every Tanzanian</span>
                <h1>Uliza <span>lolote</span>. Pata jibu la haraka, kwa lugha yako.</h1>
                <p class="lead">Sheria, afya, kilimo, masomo, huduma za serikali, fedha, ajira, teknolojia au swali la kawaida tu. {{ $settings['app_name'] }} hujibu kwa Kiswahili au Kiingereza, kwa muktadha wa Tanzania, kwa simu yoyote.</p>
                <p class="sub">Ask anything about life in Tanzania and get a clear, practical answer in Swahili or English, on any phone.</p>
                <div class="chips">
                    <span class="chip">Sheria na haki</span><span class="chip">Afya</span><span class="chip">Kilimo na mifugo</span><span class="chip">Masomo</span><span class="chip">NIDA · TRA · RITA</span><span class="chip">Fedha na biashara</span><span class="chip">Ajira</span><span class="chip">Familia</span>
                </div>
            </div>

            <div class="sms-card">
                <div class="label">Njia kuu: SMS bila intaneti</div>
                <div class="code">{{ $settings['sms_shortcode'] }}</div>
                <div class="how">
                    Tuma neno <span class="kw">{{ $settings['sms_keyword'] }}</span> kisha swali lako kwenda <strong>{{ $settings['sms_shortcode'] }}</strong> kutoka simu yoyote.
                    <div class="example">Mfano: <strong>{{ $settings['sms_keyword'] }} nifanyeje kupata kitambulisho cha NIDA?</strong></div>
                </div>
                <div class="btn-row">
                    <a class="btn btn-green" href="sms:{{ $settings['sms_shortcode'] }}?body={{ $settings['sms_keyword'] }}%20">Tuma SMS sasa</a>
                    <a class="btn btn-blue-outline" href="{{ route('chat.index') }}">Au tumia Web Chat</a>
                </div>
                <p class="example" style="margin-top:12px">Una intaneti? Web chat inakumbuka mazungumzo yako na inakuruhusu kutafuta majibu ya zamani.</p>
            </div>
        </div>

        <section id="jinsi">
            <h2>Jinsi inavyofanya kazi</h2>
            <p class="section-sub">Hatua tatu tu. Hakuna programu ya kupakua, hakuna usajili mrefu.</p>
            <div class="steps">
                <div class="step"><div class="n">1</div><h3>Uliza</h3><p>Andika swali lako kwa Kiswahili au Kiingereza. Taja mahali ulipo (mfano "nipo Kahama") ukihitaji huduma iliyo karibu.</p></div>
                <div class="step"><div class="n">2</div><h3>Tunaelewa muktadha</h3><p>{{ $settings['app_name'] }} hutambua mada, lugha na eneo lako, kisha huchanganya maarifa yaliyothibitishwa ya Tanzania na anwani rasmi.</p></div>
                <div class="step"><div class="n">3</div><h3>Pata jibu la vitendo</h3><p>Jibu fupi, la kweli, lenye hatua za kuchukua na ofisi au namba ya kuwasiliana nayo. Uliza swali la kufuatilia wakati wowote.</p></div>
            </div>
        </section>

        <section id="mada">
            <h2>Mada tunazoshughulikia</h2>
            <p class="section-sub">Bonyeza mada kuanza na mfano wa swali kwenye web chat.</p>
            <div class="topics">
                <a class="topic" href="{{ route('chat.index') }}?q=Haki zangu ni zipi nikikamatwa na polisi?"><h3>Sheria na haki</h3><p>Kukamatwa, ardhi, ndoa, mirathi, mikataba, msaada wa kisheria karibu nawe.</p><span class="q">"Haki zangu ni zipi nikikamatwa na polisi?"</span></a>
                <a class="topic" href="{{ route('chat.index') }}?q=Dalili za malaria kwa mtoto na nifanye nini?"><h3>Afya</h3><p>Dalili za hatari, kliniki, chanjo, bima ya afya (NHIF/iCHF), lishe.</p><span class="q">"Dalili za malaria kwa mtoto ni zipi?"</span></a>
                <a class="topic" href="{{ route('chat.index') }}?q=Nipande mahindi lini Dodoma na nitumie mbegu gani?"><h3>Kilimo na mifugo</h3><p>Misimu, mbegu bora, wadudu, chanjo za mifugo, bei na masoko.</p><span class="q">"Nipande mahindi lini Dodoma?"</span></a>
                <a class="topic" href="{{ route('chat.index') }}?q=Nieleze photosynthesis kwa Kiswahili kwa mwanafunzi wa kidato cha pili"><h3>Masomo</h3><p>Hisabati, sayansi, lugha, NECTA, mikopo ya HESLB, vyuo.</p><span class="q">"Nieleze photosynthesis kwa kidato cha pili"</span></a>
                <a class="topic" href="{{ route('chat.index') }}?q=Nifanyeje kupata kitambulisho cha NIDA?"><h3>Huduma za serikali</h3><p>NIDA, RITA, pasipoti, TIN, GePG, LUKU, leseni.</p><span class="q">"Nifanyeje kupata kitambulisho cha NIDA?"</span></a>
                <a class="topic" href="{{ route('chat.index') }}?q=Nianzeje biashara ndogo na nisajili wapi?"><h3>Fedha na biashara</h3><p>Kodi za TRA, mikopo, pesa za simu, kuepuka utapeli, kusajili biashara.</p><span class="q">"Nianzeje biashara ndogo?"</span></a>
                <a class="topic" href="{{ route('chat.index') }}?q=Nimefukuzwa kazi bila notisi, nifanye nini?"><h3>Ajira na kazi</h3><p>Mikataba, likizo, kuachishwa kazi, CMA, kutafuta kazi.</p><span class="q">"Nimefukuzwa kazi bila notisi, nifanye nini?"</span></a>
                <a class="topic" href="{{ route('chat.index') }}?q=Nipate wapi msaada kwa ukatili wa kijinsia Mwanza?"><h3>Familia na jamii</h3><p>Ukatili wa kijinsia, haki za mtoto, ustawi wa jamii, mahusiano.</p><span class="q">"Nipate wapi msaada kwa ukatili Mwanza?"</span></a>
                <a class="topic" href="{{ route('chat.index') }}?q=Laini yangu imepotea, nifanyeje kuirudisha?"><h3>Teknolojia na usafiri</h3><p>Laini za simu, udukuzi, faini za trafiki, leseni ya udereva, nauli.</p><span class="q">"Laini yangu imepotea, nifanyeje?"</span></a>
            </div>
        </section>

        <section>
            <h2>Kwa nini {{ $settings['app_name'] }}</h2>
            <div class="trust">
                <div><strong>Muktadha wa Tanzania</strong>Sheria, taasisi, misimu, sarafu na namba za dharura za hapa, si za nchi nyingine.</div>
                <div><strong>Lugha yako</strong>Kiswahili au Kiingereza, inafuata lugha unayoandika. Tuma LUGHA SW au LUGHA EN kubadilisha.</div>
                <div><strong>Huduma zilizo karibu</strong>Taja mkoa, wilaya au mtaa wako na upate watoa huduma walio karibu nawe.</div>
                <div><strong>Uaminifu</strong>Tunasema wazi pale jambo linapohitaji kuthibitishwa na ofisi husika. Hatuzushi namba wala bei.</div>
            </div>
        </section>

        @if($communityPosts->isNotEmpty())
        <section>
            <h2>Sauti za wananchi</h2>
            <p class="section-sub">Maoni yaliyothibitishwa kutoka kwenye jamii ya {{ $settings['app_name'] }}.</p>
            <div class="voices">
                @foreach($communityPosts as $post)
                    <div class="voice">"{{ \Illuminate\Support\Str::limit($post->content, 180) }}"<div class="who">{{ $post->user?->displayName() ?? 'Mwananchi' }} · {{ $post->created_at->diffForHumans() }}</div></div>
                @endforeach
            </div>
        </section>
        @endif

        <section id="wasiliana">
            <h2>Wasiliana nasi</h2>
            <p class="section-sub">Taasisi, halmashauri, shule au shirika linalotaka kushirikiana, au maoni yoyote.</p>
            <div class="contact">
                <div>
                    @if(session('contact_success'))<div class="alert alert-ok">{{ session('contact_success') }}</div>@endif
                    @if(session('contact_error'))<div class="alert alert-err">{{ session('contact_error') }}</div>@endif
                    @if($errors->any())<div class="alert alert-err">{{ $errors->first() }}</div>@endif
                    <form method="POST" action="{{ route('contact.submit') }}">
                        @csrf
                        <input class="hp" type="text" name="website" tabindex="-1" autocomplete="off">
                        <select name="type"><option value="general">Ujumbe wa kawaida</option><option value="partner">Ushirikiano / Partnership</option><option value="subscribe">Nitumie taarifa za maendeleo</option></select>
                        <input type="text" name="name" placeholder="Jina lako" maxlength="80">
                        <input type="email" name="email" placeholder="Barua pepe" required>
                        <input type="text" name="organisation" placeholder="Taasisi / Shirika (si lazima)" maxlength="120">
                        <textarea name="message" rows="4" placeholder="Ujumbe wako"></textarea>
                        <button class="btn btn-green" type="submit">Tuma</button>
                    </form>
                </div>
                <div>
                    <h3 style="margin-bottom:8px">Kwa simu yoyote, popote</h3>
                    <p style="color:var(--muted);font-size:.93rem">Huduma hii imejengwa kwa simu za kawaida kwanza. Hata bila bando, tuma <strong>{{ $settings['sms_keyword'] }}</strong> kwenda <strong>{{ $settings['sms_shortcode'] }}</strong>.</p>
                    <p style="color:var(--muted);font-size:.93rem;margin-top:12px"><strong>Amri za SMS:</strong><br>MSAADA – orodha ya msaada<br>LUGHA SW / LUGHA EN – chagua lugha<br>MKOA Mwanza – hifadhi mkoa wako<br>JINA Asha – hifadhi jina lako<br>ACHA / ANZA – sitisha au endelea</p>
                    <p style="color:var(--muted);font-size:.85rem;margin-top:12px">Dharura: Polisi 112 · Zimamoto 114 · Gari la wagonjwa 115 · Msaada kwa mtoto 116</p>
                </div>
            </div>
        </section>
    </div>
</main>

<footer>
    <div class="wrap">
        <div>© {{ date('Y') }} Huru Digital Co. Ltd. · SMS: <strong>{{ $settings['sms_keyword'] }}</strong> kwenda <strong>{{ $settings['sms_shortcode'] }}</strong></div>
        <div><a href="{{ route('legal.terms') }}">Vigezo na Masharti</a><a href="{{ route('legal.privacy') }}">Sera ya Faragha</a><a href="{{ route('community.index') }}">Jamii</a></div>
    </div>
</footer>
<script>if('serviceWorker' in navigator){navigator.serviceWorker.register('/sw.js').catch(()=>{});}</script>
</body>
</html>
