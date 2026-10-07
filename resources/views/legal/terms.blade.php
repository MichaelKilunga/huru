@extends('layouts.public', ['title' => 'Vigezo na Masharti', 'description' => 'Vigezo na masharti ya kutumia huduma ya Huru SMS kwa SMS na mtandaoni.'])

@section('content')
<h1>Vigezo na Masharti · Terms and Conditions</h1>
<p class="updated">Ilisasishwa: {{ date('d F Y') }}</p>

<h2>1. Huduma</h2>
<p>Huru SMS ni huduma ya maelezo na mwongozo wa jumla inayotolewa na Huru Digital Co. Ltd. kwa SMS (shortcode 15054, neno HURU) na kupitia mtandao. Huru SMS hujibu maswali ya kila siku kuhusu maisha Tanzania: sheria, afya, kilimo, elimu, huduma za serikali, fedha, ajira na mada nyingine.</p>
<p><em>Huru SMS is a general information and guidance service provided by Huru Digital Co. Ltd. via SMS (shortcode 15054, keyword HURU) and the web.</em></p>

<h2>2. Si ushauri wa kitaalamu</h2>
<p>Majibu ni maelezo ya jumla yanayotolewa kiotomatiki kwa msaada wa teknolojia na maarifa yaliyokusanywa. Si ushauri wa kisheria, wa kitabibu, wa kifedha wala uwakilishi wa aina yoyote. Kwa maamuzi muhimu, thibitisha na ofisi, mtaalamu au mamlaka husika. Kwa dharura piga 112 (polisi), 114 (zimamoto), 115 (gari la wagonjwa) au 116 (msaada kwa mtoto).</p>
<p><em>Answers are general information generated automatically. They are not legal, medical or financial advice and do not create any professional relationship. Verify important decisions with the responsible office or professional.</em></p>

<h2>3. Matumizi yanayokubalika</h2>
<ul>
    <li>Usitumie lugha ya matusi, vitisho au maudhui ya chuki. Akaunti hupewa maonyo na baadaye kufungiwa.</li>
    <li>Usitumie huduma kwa udanganyifu, utapeli au kuwadhuru wengine.</li>
    <li>Usijaribu kuvuruga, kupakia kupita kiasi au kudukua mifumo ya huduma.</li>
</ul>

<h2>4. Gharama</h2>
<p>SMS kwenda 15054 hutozwa na mtandao wako wa simu kwa viwango vya kawaida vya SMS isipokuwa ikitangazwa vinginevyo. Web chat hutumia bando la intaneti la mtumiaji. Huru SMS haitozi ada ya ziada kwa sasa.</p>

<h2>5. Taarifa zako</h2>
<p>Tunahifadhi namba yako ya simu, maswali na majibu ili kuendeleza mazungumzo na kuboresha huduma. Soma <a href="{{ route('legal.privacy') }}">Sera ya Faragha</a> kwa maelezo kamili. Tuma ACHA kwa SMS kusitisha majibu wakati wowote.</p>

<h2>6. Upatikanaji na mabadiliko</h2>
<p>Huduma inaweza kusitishwa kwa muda kwa matengenezo. Tunaweza kubadilisha vigezo hivi; toleo jipya litawekwa kwenye ukurasa huu.</p>

<h2>7. Mawasiliano</h2>
<p>Huru Digital Co. Ltd., Tanzania. Tumia fomu ya mawasiliano kwenye <a href="{{ route('welcome') }}#wasiliana">ukurasa wa mwanzo</a>.</p>
@endsection
