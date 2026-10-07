@extends("layouts.admin", ["title" => "Dashboard"])

@section("content")
    <!-- Stats -->
    <div class="stats-grid">
        <div class="stat-card" style="border-color:rgba(59,130,246,0.25);">
            <div class="stat-card-glow" style="background:var(--blue);"></div>
            <div class="stat-label">Total SMS Users</div>
            <div class="stat-value">{{ $totalUsers }}</div>
            <div class="stat-icon">👤</div>
        </div>
        <div class="stat-card" style="border-color:rgba(245,158,11,0.25);">
            <div class="stat-card-glow" style="background:var(--amber);"></div>
            <div class="stat-label">Total Messages</div>
            <div class="stat-value">{{ $totalMessages }}</div>
            <div class="stat-icon">💬</div>
        </div>
        <div class="stat-card" style="border-color:rgba(20,184,166,0.25);">
            <div class="stat-card-glow" style="background:var(--teal);"></div>
            <div class="stat-label">Messages Today</div>
            <div class="stat-value">{{ $messagesToday }}</div>
            <div class="stat-icon">📈</div>
        </div>
        <div class="stat-card" style="border-color:rgba(239,68,68,0.25);">
            <div class="stat-card-glow" style="background:var(--danger);"></div>
            <div class="stat-label">Banned Users</div>
            <div class="stat-value">{{ $bannedUsers }}</div>
            <div class="stat-icon">🚫</div>
        </div>
    </div>

    <!-- Recent Interactions -->
    <div class="card">
        <div class="card-header">
            <div>
                <div class="card-title">Recent Interactions</div>
                <div class="card-subtitle">Live SMS traffic — inbound questions & AI replies</div>
            </div>
        </div>
        <div style="overflow-x:auto;">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Time</th>
                        <th>Phone Number</th>
                        <th>Direction</th>
                        <th>Content</th>
                        <th>AI Details</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($interactions as $msg)
                        <tr>
                            <td style="white-space:nowrap; color:var(--gray-500); font-size:.78rem;">
                                {{ $msg->created_at->format("H:i:s") }}
                                <div style="color:var(--gray-600); font-size:.7rem;">{{ $msg->created_at->diffForHumans() }}</div>
                            </td>
                            <td style="font-weight:600; color:var(--gray-200);">
                                {{ $msg->user->phone_number }}
                                @if($msg->user->is_banned)
                                    <div style="font-size:.65rem; color:var(--danger); font-weight:700; margin-top:.2rem;">🚩 BANNED</div>
                                @elseif($msg->user->abuse_count > 0)
                                    <div style="font-size:.65rem; color:var(--warning); font-weight:700; margin-top:.2rem;">⚠️ Strike {{ $msg->user->abuse_count }}</div>
                                @endif
                            </td>
                            <td>
                                @if($msg->direction === 'inbound')
                                    <span class="badge badge-green">⬇ Inbound</span>
                                @else
                                    <span class="badge badge-blue">⬆ Outbound</span>
                                @endif
                            </td>
                            <td style="max-width:320px;" title="{{ $msg->content }}">
                                @php $isModerated = str_contains($msg->content, 'ONYO') || str_contains($msg->content, 'WARNING') || str_contains($msg->content, 'BANNED'); @endphp
                                <div style="overflow:hidden; text-overflow:ellipsis; white-space:nowrap; max-width:300px; @if($isModerated) color:var(--danger); font-style:italic; @endif">{{ $msg->content }}</div>
                            </td>
                            <td>
                                @if($msg->aiLog)
                                    <div>
                                        <div style="font-size:.78rem; font-weight:600; color:var(--amber-light);">{{ $msg->aiLog->model }}</div>
                                        <div style="font-size:.7rem; color:var(--gray-500);">{{ $msg->aiLog->total_tokens ?? 0 }} tokens</div>
                                    </div>
                                @elseif($isModerated)
                                    <span class="badge badge-red">Moderated</span>
                                @else
                                    <span style="color:var(--gray-600);">—</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align:center; padding:3rem; color:var(--gray-600);">
                                <div style="font-size:2rem; margin-bottom:.5rem;">📭</div>
                                No interactions yet. Waiting for SMS traffic.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div style="padding:1rem 1.5rem; border-top:1px solid rgba(255,255,255,0.07);">
            {{ $interactions->links('vendor.pagination.admin-dark') }}
        </div>
    </div>
@endsection
