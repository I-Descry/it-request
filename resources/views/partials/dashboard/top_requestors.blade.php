                              <table style="width: 100%; border-collapse: collapse; font-size: 0.8rem;">
                                  <thead>
                                      <tr style="border-bottom: 1px solid var(--border-color); color: var(--text-secondary); text-align: left;">
                                          <th style="padding-bottom: 8px; font-weight: 600; text-transform: uppercase; font-size: 0.7rem; letter-spacing: 0.5px;">Name</th>
                                          <th style="padding-bottom: 8px; text-align: center; font-weight: 600; text-transform: uppercase; font-size: 0.7rem; letter-spacing: 0.5px;">Total Requests</th>
                                      </tr>
                                  </thead>
                                  <tbody>
                                      @foreach($topRequestors as $req)
                                      <tr style="border-bottom: 1px solid var(--border-color);">
                                          <td style="padding: 10px 0; font-weight: 600; color: var(--text-primary);">
                                              <div style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 140px;">
                                                  {{ $req->requested_by }}
                                              </div>
                                          </td>
                                          <td style="padding: 10px 0; text-align: center; color: #3b82f6; font-weight: 700;">{{ $req->total }}</td>
                                      </tr>
                                      @endforeach
                                  </tbody>
                              </table>
                              @if($topRequestors->isEmpty())
                                  <div style="text-align: center; color: var(--text-muted); font-size: 0.85rem; padding: 15px 10px;">No requests found.</div>
                              @endif
