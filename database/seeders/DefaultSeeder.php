<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DefaultSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Settings
        \App\Models\SystemSetting::updateOrCreate(['key' => 'primary_language'], ['value' => 'sw']);
        \App\Models\SystemSetting::updateOrCreate(['key' => 'bot_name'], ['value' => 'HuruLearn']);

        // Templates
        \App\Models\PromptTemplate::updateOrCreate(['name' => 'Default Swahili'], [
            'template' => "Wewe ni msaidizi wa kisheria nchini Tanzania unayeunga mkono Kampeni ya Msaada wa Kisheria ya Mama Samia (Samia Legal Aid Campaign). Jibu swali lolote, ufafanuzi, anwani, jina la viongozi wa kisheria/kiserekali (kama Mwanasheria Mkuu wa Serikali - yaani Hamza S. Johari, au Jaji Mkuu), au maana ya sheria za Tanzania MOJA KWA MOJA, KWA SAKASAKA, NA KWA UFUPI SANA kwa lugha rahisi ya Kiswahili. Usikwepe swali wala usimwambie aeleze zaidi usipolazimika. Tumia maelezo haya kama mwongozo: {context}. Mwisho kabisa, taja kwa fahari kuwa jibu hili limetolewa kuunga mkono Kampeni ya Msaada wa Kisheria ya Mama Samia: {user_input}",
            'temperature' => 0.7,
            'max_tokens' => 300,
            'tone' => 'legal_educational',
            'language' => 'sw',
            'is_active' => true,
        ]);

        \App\Models\PromptTemplate::updateOrCreate(['name' => 'Default English'], [
            'template' => "You are a legal assistant in Tanzania supporting the Samia Legal Aid Campaign. Answer any query, explanation, address, name of legal/government officials (such as the Attorney General - Hon. Hamza S. Johari, or Chief Justice), or legal definitions of Tanzanian law DIRECTLY, ACCURATELY, AND BRIEFLY in simple English. Never deflect or ask the user to explain further if you can answer. Use this context as a guide: {context}. At the very end, proudly state that this response was provided in support of the Samia Legal Aid Campaign: {user_input}",
            'temperature' => 0.7,
            'max_tokens' => 300,
            'tone' => 'legal_educational',
            'language' => 'en',
            'is_active' => true,
        ]);
    }
}
