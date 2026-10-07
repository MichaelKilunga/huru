@if ($paginator->hasPages())
    <nav style="display:flex;gap:8px;justify-content:center;align-items:center;margin:12px 0;font-size:.88rem">
        @if ($paginator->onFirstPage())
            <span style="color:#94a3b8">‹ Nyuma</span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" rel="prev" style="color:#1d4ed8;font-weight:600;text-decoration:none">‹ Nyuma</a>
        @endif
        <span style="color:#475569">{{ $paginator->currentPage() }} / {{ $paginator->lastPage() }}</span>
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" rel="next" style="color:#1d4ed8;font-weight:600;text-decoration:none">Mbele ›</a>
        @else
            <span style="color:#94a3b8">Mbele ›</span>
        @endif
    </nav>
@endif
