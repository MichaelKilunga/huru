<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AiLog;
use App\Models\Feedback;
use App\Models\Message;
use App\Models\User;
use App\Services\AiService;
use App\Services\SmsService;
use App\Services\TopicClassifier;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(AiService $ai, SmsService $sms)
    {
        $today = now()->startOfDay();
        $week = now()->subDays(7);

        $stats = [
            'users' => User::query()->where('role', User::ROLE_USER)->count(),
            'users_today' => User::query()->where('role', User::ROLE_USER)->where('created_at', '>=', $today)->count(),
            'questions_today' => Message::query()->where('direction', Message::DIRECTION_IN)->where('created_at', '>=', $today)->count(),
            'questions_week' => Message::query()->where('direction', Message::DIRECTION_IN)->where('created_at', '>=', $week)->count(),
            'sms_week' => Message::query()->where('direction', Message::DIRECTION_IN)->where('channel', 'sms')->where('created_at', '>=', $week)->count(),
            'web_week' => Message::query()->where('direction', Message::DIRECTION_IN)->where('channel', 'web')->where('created_at', '>=', $week)->count(),
            'tokens_today' => (int) AiLog::query()->where('created_at', '>=', $today)->sum('total_tokens'),
            'latency_avg' => (int) round((float) AiLog::query()->where('created_at', '>=', $week)->where('status', 'ok')->avg('latency_ms')),
            'errors_week' => AiLog::query()->where('created_at', '>=', $week)->where('status', '!=', 'ok')->count(),
            'banned' => User::query()->where('is_banned', true)->count(),
            'helpful' => Feedback::query()->where('rating', 1)->count(),
            'unhelpful' => Feedback::query()->where('rating', -1)->count(),
        ];

        $byCategory = Message::query()
            ->select('category', DB::raw('count(*) as total'))
            ->where('direction', Message::DIRECTION_IN)
            ->where('created_at', '>=', $week)
            ->whereNotNull('category')
            ->groupBy('category')
            ->orderByDesc('total')
            ->get()
            ->map(fn ($row) => ['key' => $row->category, 'label' => TopicClassifier::label($row->category, 'en'), 'total' => (int) $row->total]);

        $daily = Message::query()
            ->select(DB::raw('DATE(created_at) as day'), DB::raw('count(*) as total'))
            ->where('direction', Message::DIRECTION_IN)
            ->where('created_at', '>=', now()->subDays(13)->startOfDay())
            ->groupBy('day')
            ->orderBy('day')
            ->pluck('total', 'day');

        $recent = Message::query()->with(['user', 'aiLog', 'feedback'])->latest('id')->take(25)->get();

        $health = [
            'engine_configured' => $ai->isConfigured(),
            'sms_configured' => $sms->isConfigured(),
            'webhook_secret' => (bool) config('services.at.webhook_secret'),
            'queue' => config('queue.default'),
        ];

        return view('admin.dashboard', compact('stats', 'byCategory', 'daily', 'recent', 'health'));
    }
}
