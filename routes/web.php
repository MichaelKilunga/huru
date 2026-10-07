<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\ConversationController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\KnowledgeController;
use App\Http\Controllers\Admin\LocalResourceController;
use App\Http\Controllers\Admin\PromptTemplateController;
use App\Http\Controllers\Admin\ReferenceContactController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\CommunityController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\WebChatController;
use App\Models\CommunityPost;
use App\Support\Settings;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $communityPosts = CommunityPost::query()
        ->where('is_approved', true)
        ->whereHas('thread', fn ($q) => $q->where('slug', 'general-advice'))
        ->with('user')
        ->latest()
        ->take(6)
        ->get();

    return view('welcome', [
        'communityPosts' => $communityPosts,
        'settings' => [
            'app_name' => Settings::get('app_name'),
            'sms_keyword' => Settings::get('sms_keyword'),
            'sms_shortcode' => Settings::get('sms_shortcode'),
        ],
    ]);
})->name('welcome');

// Web chat (citizen)
Route::prefix('chat')->name('chat.')->group(function () {
    Route::get('/', [WebChatController::class, 'index'])->name('index');
    Route::get('/session', [WebChatController::class, 'session'])->name('session');
    Route::post('/request-otp', [WebChatController::class, 'requestOtp'])->middleware('throttle:10,1')->name('otp.request');
    Route::post('/verify-otp', [WebChatController::class, 'verifyOtp'])->middleware('throttle:10,1')->name('otp.verify');
    Route::get('/messages', [WebChatController::class, 'messages'])->name('messages');
    Route::post('/send', [WebChatController::class, 'send'])->middleware('throttle:20,1')->name('send');
    Route::post('/feedback', [WebChatController::class, 'feedback'])->name('feedback');
    Route::post('/preferences', [WebChatController::class, 'preferences'])->name('preferences');
    Route::post('/logout', [WebChatController::class, 'logout'])->name('logout');
});

// Legal pages
Route::view('/terms-and-conditions', 'legal.terms')->name('legal.terms');
Route::view('/privacy-policy', 'legal.privacy')->name('legal.privacy');
Route::view('/offline', 'offline')->name('offline');

// Community
Route::prefix('community')->name('community.')->group(function () {
    Route::get('/', [CommunityController::class, 'index'])->name('index');
    Route::post('/threads', [CommunityController::class, 'storeThread'])->name('threads.store');
    Route::get('/threads/{thread:slug}', [CommunityController::class, 'show'])->name('show');
    Route::post('/threads/{thread:slug}/posts', [CommunityController::class, 'storePost'])->middleware('throttle:30,1')->name('posts.store');
    Route::post('/threads/{thread:slug}/join', [CommunityController::class, 'join'])->name('join');
    Route::post('/threads/{thread:slug}/leave', [CommunityController::class, 'leave'])->name('leave');
});

Route::post('/contact', [ContactController::class, 'submit'])->name('contact.submit');

// Admin
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:10,1')->name('login.post');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::middleware(['auth', 'admin'])->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        Route::get('/conversations', [ConversationController::class, 'index'])->name('conversations.index');
        Route::get('/conversations/{user}', [ConversationController::class, 'show'])->name('conversations.show');

        Route::get('/knowledge', [KnowledgeController::class, 'index'])->name('knowledge.index');
        Route::get('/knowledge/create', [KnowledgeController::class, 'create'])->name('knowledge.create');
        Route::post('/knowledge', [KnowledgeController::class, 'store'])->name('knowledge.store');
        Route::post('/knowledge/import', [KnowledgeController::class, 'import'])->name('knowledge.import');
        Route::get('/knowledge/{knowledge}/edit', [KnowledgeController::class, 'edit'])->name('knowledge.edit');
        Route::put('/knowledge/{knowledge}', [KnowledgeController::class, 'update'])->name('knowledge.update');
        Route::patch('/knowledge/{knowledge}/toggle', [KnowledgeController::class, 'toggle'])->name('knowledge.toggle');
        Route::delete('/knowledge/{knowledge}', [KnowledgeController::class, 'destroy'])->name('knowledge.destroy');

        Route::get('/templates', [PromptTemplateController::class, 'index'])->name('templates.index');
        Route::get('/templates/create', [PromptTemplateController::class, 'create'])->name('templates.create');
        Route::post('/templates', [PromptTemplateController::class, 'store'])->name('templates.store');
        Route::get('/templates/{template}/edit', [PromptTemplateController::class, 'edit'])->name('templates.edit');
        Route::put('/templates/{template}', [PromptTemplateController::class, 'update'])->name('templates.update');
        Route::patch('/templates/{template}/toggle', [PromptTemplateController::class, 'toggle'])->name('templates.toggle');
        Route::delete('/templates/{template}', [PromptTemplateController::class, 'destroy'])->name('templates.destroy');

        Route::get('/contacts', [ReferenceContactController::class, 'index'])->name('contacts.index');
        Route::get('/contacts/create', [ReferenceContactController::class, 'create'])->name('contacts.create');
        Route::post('/contacts', [ReferenceContactController::class, 'store'])->name('contacts.store');
        Route::get('/contacts/{contact}/edit', [ReferenceContactController::class, 'edit'])->name('contacts.edit');
        Route::put('/contacts/{contact}', [ReferenceContactController::class, 'update'])->name('contacts.update');
        Route::patch('/contacts/{contact}/verify', [ReferenceContactController::class, 'verify'])->name('contacts.verify');
        Route::delete('/contacts/{contact}', [ReferenceContactController::class, 'destroy'])->name('contacts.destroy');

        Route::get('/resources', [LocalResourceController::class, 'index'])->name('resources.index');
        Route::get('/resources/create', [LocalResourceController::class, 'create'])->name('resources.create');
        Route::post('/resources', [LocalResourceController::class, 'store'])->name('resources.store');
        Route::get('/resources/{resource}/edit', [LocalResourceController::class, 'edit'])->name('resources.edit');
        Route::put('/resources/{resource}', [LocalResourceController::class, 'update'])->name('resources.update');
        Route::delete('/resources/{resource}', [LocalResourceController::class, 'destroy'])->name('resources.destroy');

        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::patch('/users/{user}/ban', [UserController::class, 'toggleBan'])->name('users.ban');
        Route::patch('/users/{user}/strikes', [UserController::class, 'resetStrikes'])->name('users.strikes');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

        Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
        Route::post('/settings', [SettingController::class, 'update'])->name('settings.update');
    });
});
