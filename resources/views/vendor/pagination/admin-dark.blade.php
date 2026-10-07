@if ($paginator->hasPages())
    <nav style="display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:1rem;">

        {{-- Result info --}}
        <div style="font-size:.78rem; color:var(--gray-500);">
            Showing
            <span style="color:var(--gray-300); font-weight:600;">{{ $paginator->firstItem() }}</span>
            –
            <span style="color:var(--gray-300); font-weight:600;">{{ $paginator->lastItem() }}</span>
            of
            <span style="color:var(--gray-300); font-weight:600;">{{ $paginator->total() }}</span>
            results
        </div>

        {{-- Page buttons --}}
        <div style="display:flex; align-items:center; gap:.4rem;">

            {{-- Previous --}}
            @if ($paginator->onFirstPage())
                <span style="padding:.45rem .85rem; border-radius:8px; font-size:.82rem; font-weight:600;
                             background:rgba(255,255,255,0.04); border:1px solid rgba(255,255,255,0.07);
                             color:var(--gray-600); cursor:default; user-select:none;">
                    ← Prev
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}"
                   style="padding:.45rem .85rem; border-radius:8px; font-size:.82rem; font-weight:600;
                          background:rgba(255,255,255,0.06); border:1px solid rgba(255,255,255,0.12);
                          color:var(--gray-300); text-decoration:none; transition:all .2s;"
                   onmouseover="this.style.background='rgba(255,255,255,0.1)'; this.style.color='#fff';"
                   onmouseout="this.style.background='rgba(255,255,255,0.06)'; this.style.color='var(--gray-300)';">
                    ← Prev
                </a>
            @endif

            {{-- Page numbers --}}
            @foreach ($elements as $element)
                @if (is_string($element))
                    <span style="padding:.45rem .6rem; font-size:.82rem; color:var(--gray-600); user-select:none;">…</span>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span style="padding:.45rem .8rem; border-radius:8px; font-size:.82rem; font-weight:700;
                                         background:linear-gradient(135deg, var(--amber), #e67e22);
                                         color:#fff; min-width:36px; text-align:center; cursor:default;
                                         box-shadow:0 4px 12px rgba(245,158,11,0.3);">
                                {{ $page }}
                            </span>
                        @else
                            <a href="{{ $url }}"
                               style="padding:.45rem .8rem; border-radius:8px; font-size:.82rem; font-weight:600;
                                      background:rgba(255,255,255,0.05); border:1px solid rgba(255,255,255,0.1);
                                      color:var(--gray-400); text-decoration:none; min-width:36px;
                                      text-align:center; transition:all .2s;"
                               onmouseover="this.style.background='rgba(245,158,11,0.12)'; this.style.borderColor='rgba(245,158,11,0.3)'; this.style.color='var(--amber-light)';"
                               onmouseout="this.style.background='rgba(255,255,255,0.05)'; this.style.borderColor='rgba(255,255,255,0.1)'; this.style.color='var(--gray-400)';">
                                {{ $page }}
                            </a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}"
                   style="padding:.45rem .85rem; border-radius:8px; font-size:.82rem; font-weight:600;
                          background:rgba(255,255,255,0.06); border:1px solid rgba(255,255,255,0.12);
                          color:var(--gray-300); text-decoration:none; transition:all .2s;"
                   onmouseover="this.style.background='rgba(255,255,255,0.1)'; this.style.color='#fff';"
                   onmouseout="this.style.background='rgba(255,255,255,0.06)'; this.style.color='var(--gray-300)';">
                    Next →
                </a>
            @else
                <span style="padding:.45rem .85rem; border-radius:8px; font-size:.82rem; font-weight:600;
                             background:rgba(255,255,255,0.04); border:1px solid rgba(255,255,255,0.07);
                             color:var(--gray-600); cursor:default; user-select:none;">
                    Next →
                </span>
            @endif

        </div>
    </nav>
@endif
