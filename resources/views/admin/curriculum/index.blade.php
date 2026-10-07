@extends("layouts.admin", ["title" => "Legal Database Management"])

@section("content")
    <div style="display:grid; grid-template-columns:320px 1fr; gap:1.5rem; align-items:start;">

        <!-- Import Panel -->
        <div class="card" style="position:sticky; top:80px;">
            <div class="card-header">
                <div class="card-title">📤 Import Legal Corpora</div>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.curriculum.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label class="form-label">Source Document (CSV, TXT, JSON)</label>
                        <div style="border:2px dashed rgba(255,255,255,0.15); border-radius:12px; padding:1.5rem; text-align:center; cursor:pointer; transition:border-color .2s;"
                             onmouseover="this.style.borderColor='rgba(245,158,11,0.5)'" onmouseout="this.style.borderColor='rgba(255,255,255,0.15)'">
                            <div style="font-size:2rem; margin-bottom:.5rem;">📂</div>
                            <label for="doc_file" style="cursor:pointer; color:var(--amber-light); font-size:.85rem; font-weight:600;">Click to choose file</label>
                            <input type="file" id="doc_file" name="doc_file" required accept=".csv,.txt,.json"
                                   style="display:none;" onchange="document.getElementById('file-name').textContent = this.files[0]?.name || ''">
                            <div id="file-name" style="font-size:.75rem; color:var(--gray-500); margin-top:.4rem;"></div>
                        </div>
                        <div class="form-hint">CSV columns: title, content, summary, tags.<br>JSON keys: title, content, summary, tags (array or single).<br>TXT splits by double-newlines.</div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Document Language</label>
                        <select name="language" class="form-select">
                            <option value="sw">🇹🇿 Swahili (Kiswahili)</option>
                            <option value="en">🇬🇧 English</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary btn-full">⬆ Import Data</button>
                </form>
            </div>
        </div>

        <!-- Entries Table -->
        <div class="card">
            <div class="card-header">
                <div>
                    <div class="card-title">⚖️ Legal Database Entries</div>
                    <div class="card-subtitle">{{ $curriculums->total() }} total records</div>
                </div>
            </div>
            <div style="overflow-x:auto;">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Language</th>
                            <th>Tags</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($curriculums as $item)
                            <tr>
                                <td style="font-weight:600; color:var(--gray-200); max-width:260px;">
                                    <div style="overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">{{ $item->title }}</div>
                                </td>
                                <td>
                                    @if($item->language === 'sw')
                                        <span class="badge badge-amber">🇹🇿 SW</span>
                                    @else
                                        <span class="badge badge-blue">🇬🇧 EN</span>
                                    @endif
                                </td>
                                <td style="color:var(--gray-500); font-size:.8rem;">{{ $item->tags ?? '—' }}</td>
                                <td>
                                    <form action="{{ route('admin.curriculum.destroy', $item) }}" method="POST"
                                          onsubmit="return confirm('Delete this legal database entry?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">🗑 Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" style="text-align:center; padding:3rem; color:var(--gray-600);">
                                    <div style="font-size:2rem; margin-bottom:.5rem;">📭</div>
                                    No legal data entries yet. Import your first CSV, TXT, or JSON file!
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div style="padding:1rem 1.5rem; border-top:1px solid rgba(255,255,255,0.07);">
                {{ $curriculums->links('vendor.pagination.admin-dark') }}
            </div>
        </div>
    </div>
@endsection
