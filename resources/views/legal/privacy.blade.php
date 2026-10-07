@extends('layouts.public', ['title' => 'Sera ya Faragha', 'description' => 'Jinsi Huru SMS inavyokusanya, kutumia na kulinda taarifa zako.'])

@section('content')
<h1>Sera ya Faragha · Privacy Policy</h1>
<p class="updated">Ilisasishwa: {{ date('d F Y') }}</p>

<h2>1. Taarifa tunazokusanya</h2>
<ul>
    <li><strong>Namba ya simu</strong> – ndiyo kitambulisho chako cha huduma, kwa SMS na web chat.</li>
    <li><strong>Maswali na majibu</strong> – tunahifadhi mazungumzo ili kuendeleza muktadha wa maswali ya kufuatilia na kukuwezesha kutafuta majibu ya zamani.</li>
    <li><strong>Mapendeleo</strong> – jina (ukilitoa), lugha unayopendelea, mkoa (ukiutaja) na maoni yako kuhusu majibu.</li>
    <li><strong>Taarifa za kiufundi</strong> – muda wa ujumbe, njia uliyotumia (SMS au web) na kumbukumbu za mfumo kwa usalama.</li>
</ul>

<h2>2. Jinsi tunavyotumia taarifa</h2>
<p>Kutoa majibu, kuendeleza mazungumzo, kulinda huduma dhidi ya matumizi mabaya, kuboresha maarifa yaliyohifadhiwa na kutoa takwimu za jumla (bila kukutambulisha) kwa washirika. Maswali yako hutumwa kwa mtoa huduma wa teknolojia ya lugha ili kupata jibu; hatutumi jina lako wala namba yako ya simu pamoja na swali.</p>
<p><em>We use your data to answer you, keep conversation context, protect the service from abuse and produce anonymous statistics. Questions are sent to a language technology provider to generate answers; your name and phone number are not sent with the question.</em></p>

<h2>3. Kushirikisha taarifa</h2>
<p>Hatuuzi taarifa zako. Tunaweza kushirikisha taarifa pale sheria inapotaka au kwa watoa huduma wanaotuwezesha kutoa huduma (mtandao wa SMS, mwenyeji wa seva, mtoa huduma wa teknolojia ya lugha) chini ya masharti ya usiri.</p>

<h2>4. Uhifadhi na usalama</h2>
<p>Taarifa huhifadhiwa kwenye seva zilizolindwa. Namba ya uthibitisho (OTP) huhifadhiwa kwa njia ya siri na hufutwa baada ya kutumika. Tunahifadhi mazungumzo kwa muda unaohitajika kwa huduma; unaweza kuomba kufutwa.</p>

<h2>5. Haki zako</h2>
<ul>
    <li>Kuona mazungumzo yako kupitia web chat.</li>
    <li>Kusitisha majibu wakati wowote kwa kutuma <strong>ACHA</strong> kwa SMS, na kuendelea kwa <strong>ANZA</strong>.</li>
    <li>Kuomba kufutwa kwa taarifa zako kwa kutumia fomu ya mawasiliano.</li>
</ul>

<h2>6. Watoto</h2>
<p>Huduma inaweza kutumiwa na wanafunzi kwa maswali ya masomo. Hatukusanyi kwa makusudi taarifa za ziada za watoto zaidi ya zile zilizo muhimu kwa huduma.</p>

<h2>7. Mawasiliano</h2>
<p>Huru Digital Co. Ltd., Tanzania. Tumia fomu kwenye <a href="{{ route('welcome') }}#wasiliana">ukurasa wa mwanzo</a>.</p>
@endsection
