<?php
$file = 'resources/views/dashboard.blade.php';
$c = file_get_contents($file);

$insert = <<<'EOT'
                </div>

                {{-- Top Requestors --}}
                <div class="dk-card" style="justify-content: flex-start; margin-top: 15px;">
                    <div class="dk-section-title">Top Requestors</div>
                    <div style="display: flex; flex-direction: column; gap: 8px;">
                        @foreach($topRequestors as $req)
                            <div style="background: var(--th-bg); padding: 10px 12px; border-radius: 6px; border: 1px solid var(--border-color); display: flex; flex-direction: column; gap: 4px;">
                                <div style="font-weight: 600; color: var(--text-primary); font-size: 0.9rem;">{{ Str::limit($req->requested_by, 20) }}</div>
                                <div style="display: flex; justify-content: space-between; font-size: 0.75rem;">
                                    <span style="color: var(--text-muted);">Today: <strong style="color: var(--text-primary);">{{ $req->today }}</strong></span>
                                    <span style="color: var(--border-color);">|</span>
                                    <span style="color: var(--text-muted);">Week: <strong style="color: var(--text-primary);">{{ $req->this_week }}</strong></span>
                                    <span style="color: var(--border-color);">|</span>
                                    <span style="color: var(--text-muted);">Month: <strong style="color: var(--text-primary);">{{ $req->this_month }}</strong></span>
                                </div>
                            </div>
                        @endforeach
                        @if($topRequestors->isEmpty())
                            <div style="text-align: center; color: var(--text-muted); font-size: 0.85rem; padding: 10px;">No requests found.</div>
                        @endif
                    </div>
                </div>
EOT;

$c = str_replace("                </div>\n            </div>\n\n        </div>", $insert . "\n            </div>\n\n        </div>", $c);

file_put_contents($file, $c);