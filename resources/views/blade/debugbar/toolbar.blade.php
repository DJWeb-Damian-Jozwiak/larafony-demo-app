<style>
    #larafony-debugbar * {
        box-sizing: border-box;
    }
    #larafony-debugbar table {
        width: 100%;
        border-collapse: collapse;
        font-size: 11px;
    }
    #larafony-debugbar table th {
        background: #2a2a2a;
        padding: 8px;
        text-align: left;
        font-weight: 600;
        color: #aaa;
        border-bottom: 1px solid #3d3d3d;
    }
    #larafony-debugbar table td {
        padding: 8px;
        border-bottom: 1px solid #2a2a2a;
        vertical-align: top;
    }
    #larafony-debugbar table tr:hover {
        background: #252525;
    }
    #larafony-debugbar .badge {
        display: inline-block;
        padding: 2px 6px;
        border-radius: 3px;
        font-size: 10px;
        font-weight: 600;
    }
    #larafony-debugbar .badge-success { background: #4CAF50; color: #fff; }
    #larafony-debugbar .badge-warning { background: #ff9800; color: #fff; }
    #larafony-debugbar .badge-danger { background: #f44336; color: #fff; }
    #larafony-debugbar .badge-info { background: #2196F3; color: #fff; }
    #larafony-debugbar code {
        background: #2a2a2a;
        padding: 2px 6px;
        border-radius: 3px;
        font-size: 11px;
        color: #4CAF50;
    }
</style>

<div id="larafony-debugbar" style="position: fixed; bottom: 0; left: 0; right: 0; z-index: 999999; background: #1e1e1e; color: #ddd; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; font-size: 12px; box-shadow: 0 -2px 10px rgba(0,0,0,0.5); height: 436px; max-height: 80vh; display: flex; flex-direction: column;">

    <!-- Collapsed Icon Mode -->
    <div id="debugbar-icon" style="display: none; position: fixed; bottom: 10px; right: 10px; background: #2d2d2d; border-radius: 50%; width: 50px; height: 50px; cursor: pointer; box-shadow: 0 2px 10px rgba(0,0,0,0.3); align-items: center; justify-content: center;" onclick="toggleDebugbar()">
        <span style="font-size: 20px;">🐛</span>
    </div>

    <!-- Main Toolbar -->
    <div id="debugbar-main" style="display: flex; flex-direction: column; flex: 1; min-height: 0;">
        <!-- Resizer -->
        <div id="debugbar-resizer" style="height: 4px; background: #3d3d3d; cursor: ns-resize; position: relative; flex-shrink: 0;">
            <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 40px; height: 2px; background: #666; border-radius: 2px;"></div>
        </div>

        <!-- Tab Bar -->
        <div style="display: flex; align-items: center; background: #2d2d2d; border-bottom: 1px solid #3d3d3d; height: 36px; flex-shrink: 0;">
            <!-- Tabs -->
            <div style="display: flex; flex: 1; overflow-x: auto;">
                <div class="debugbar-tab" data-panel="request" onclick="switchTab('request')" style="padding: 0 16px; height: 36px; display: flex; align-items: center; cursor: pointer; border-right: 1px solid #3d3d3d; font-weight: 500; color: #aaa;">
                    📨 Request
                </div>
                <div class="debugbar-tab" data-panel="timeline" onclick="switchTab('timeline')" style="padding: 0 16px; height: 36px; display: flex; align-items: center; cursor: pointer; border-right: 1px solid #3d3d3d; font-weight: 500; color: #aaa;">
                    ⏱️ Timeline
                </div>
                <div class="debugbar-tab" data-panel="views" onclick="switchTab('views')" style="padding: 0 16px; height: 36px; display: flex; align-items: center; cursor: pointer; border-right: 1px solid #3d3d3d; font-weight: 500; color: #aaa;">
                    👁️ Views
                </div>
                <div class="debugbar-tab" data-panel="queries" onclick="switchTab('queries')" style="padding: 0 16px; height: 36px; display: flex; align-items: center; cursor: pointer; border-right: 1px solid #3d3d3d; font-weight: 500; color: #aaa;">
                    🗄️ Queries @isset($data['queries'])<span class="badge badge-{{ $data['queries']['count'] > 10 ? 'warning' : 'success' }}" style="margin-left: 6px;">{{ $data['queries']['count'] }}</span>@endisset
                </div>
                <div class="debugbar-tab" data-panel="cache" onclick="switchTab('cache')" style="padding: 0 16px; height: 36px; display: flex; align-items: center; cursor: pointer; border-right: 1px solid #3d3d3d; font-weight: 500; color: #aaa;">
                    💾 Cache @isset($data['cache'])<span class="badge badge-{{ $data['cache']['hit_ratio'] > 80 ? 'success' : 'warning' }}" style="margin-left: 6px;">{{ $data['cache']['hit_ratio'] }}%</span>@endisset
                </div>
            </div>

            <!-- Right Side Info -->
            <div style="display: flex; align-items: center; gap: 12px; padding: 0 12px; border-left: 1px solid #3d3d3d;">
                @isset($data['route'])
                <div style="font-size: 11px;">
                    <span style="color: #888;">{{ $data['route']['method'] }}</span>
                    <span style="color: #4CAF50; margin-left: 4px;">{{ $data['route']['uri'] }}</span>
                </div>
                @endisset

                @isset($data['performance'])
                <div style="font-size: 11px;">
                    <span style="color: #888;">💾</span>
                    <span style="color: #2196F3;">{{ $data['performance']['peak_memory'] }}</span>
                </div>
                <div style="font-size: 11px;">
                    <span style="color: #888;">⏱️</span>
                    <span style="color: #4CAF50;">{{ $data['performance']['execution_time'] }}ms</span>
                </div>
                @endisset

                <button onclick="minimizeDebugbar()" style="background: transparent; border: none; color: #888; cursor: pointer; font-size: 16px; padding: 4px; line-height: 1;">−</button>
                <button onclick="closeDebugbar()" style="background: transparent; border: none; color: #888; cursor: pointer; font-size: 16px; padding: 4px; line-height: 1;">✕</button>
            </div>
        </div>

        <!-- Content Area -->
        <div style="flex: 1; overflow-y: auto; background: #1e1e1e; min-height: 0;">

            <!-- Request Panel -->
            <div id="debugbar-panel-request" class="debugbar-panel" style="padding: 16px; display: none;">
                <h3 style="margin: 0 0 16px 0; color: #4CAF50; font-size: 14px; font-weight: 600;">Request Information</h3>

                @isset($data['request'])
                <!-- General Info -->
                <table style="margin-bottom: 16px;">
                    <thead>
                        <tr><th colspan="2">General</th></tr>
                    </thead>
                    <tbody>
                        <tr><td style="color: #888; width: 150px;">Method</td><td><code>{{ $data['request']['method'] }}</code></td></tr>
                        <tr><td style="color: #888;">URI</td><td><code>{{ $data['request']['uri'] }}</code></td></tr>
                        <tr><td style="color: #888;">IP Address</td><td>{{ $data['request']['ip'] }}</td></tr>
                        <tr><td style="color: #888;">User Agent</td><td style="font-size: 10px;">{{ $data['request']['user_agent'] }}</td></tr>
                    </tbody>
                </table>

                <!-- Query Parameters -->
                @if(!empty($data['request']['query']))
                <table style="margin-bottom: 16px;">
                    <thead>
                        <tr><th>Query Parameter</th><th>Value</th></tr>
                    </thead>
                    <tbody>
                        @foreach($data['request']['query'] as $key => $value)
                        <tr>
                            <td style="color: #888;">{{ $key }}</td>
                            <td><code>{{ is_array($value) ? json_encode($value) : $value }}</code></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @endif

                <!-- POST Data -->
                @if(!empty($data['request']['post']))
                <table style="margin-bottom: 16px;">
                    <thead>
                        <tr><th>POST Parameter</th><th>Value</th></tr>
                    </thead>
                    <tbody>
                        @foreach($data['request']['post'] as $key => $value)
                        <tr>
                            <td style="color: #888;">{{ $key }}</td>
                            <td><code>{{ is_array($value) ? json_encode($value) : $value }}</code></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @endif

                <!-- Session -->
                @if(!empty($data['request']['session']))
                <table style="margin-bottom: 16px;">
                    <thead>
                        <tr><th>Session Key</th><th>Value</th></tr>
                    </thead>
                    <tbody>
                        @foreach($data['request']['session'] as $key => $value)
                        <tr>
                            <td style="color: #888;">{{ $key }}</td>
                            <td><code>{{ is_array($value) || is_object($value) ? json_encode($value) : $value }}</code></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @endif

                <!-- Cookies -->
                @if(!empty($data['request']['cookies']))
                <table style="margin-bottom: 16px;">
                    <thead>
                        <tr><th>Cookie Name</th><th>Value</th></tr>
                    </thead>
                    <tbody>
                        @foreach($data['request']['cookies'] as $key => $value)
                        <tr>
                            <td style="color: #888;">{{ $key }}</td>
                            <td><code>{{ strlen($value) > 50 ? substr($value, 0, 50) . '...' : $value }}</code></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @endif

                <!-- Headers -->
                <table>
                    <thead>
                        <tr><th>Header</th><th>Value</th></tr>
                    </thead>
                    <tbody>
                        @foreach($data['request']['headers'] as $name => $values)
                        <tr>
                            <td style="color: #888;">{{ $name }}</td>
                            <td style="font-size: 10px;">{{ is_array($values) ? implode(', ', $values) : $values }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @endisset
            </div>

            <!-- Timeline Panel -->
            <div id="debugbar-panel-timeline" class="debugbar-panel" style="padding: 16px; display: none;">
                <h3 style="margin: 0 0 16px 0; color: #4CAF50; font-size: 14px; font-weight: 600;">Execution Timeline</h3>

                @isset($data['timeline'])
                <?php
                    $totalTime = 0;
                    $totalMemory = 0;
                ?>
                <table>
                    <thead>
                        <tr>
                            <th>Event Name</th>
                            <th style="width: 100px; text-align: right;">Duration</th>
                            <th style="width: 120px; text-align: right;">Total Time</th>
                            <th style="width: 120px; text-align: right;">Total Memory</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($data['timeline']['events'] as $event)
                        <?php
                            $totalTime += $event['duration'];
                            $totalMemory = $event['memory'];
                        ?>
                        <tr>
                            <td>{{ $event['label'] }}</td>
                            <td style="text-align: right;">
                                <span class="badge badge-{{ $event['duration'] > 100 ? 'danger' : ($event['duration'] > 50 ? 'warning' : 'info') }}">{{ $event['duration'] }}ms</span>
                            </td>
                            <td style="text-align: right; color: #888;">{{ round($totalTime, 2) }}ms</td>
                            <td style="text-align: right; color: #888;">{{ round($totalMemory / 1024 / 1024, 2) }} MB</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                <div style="color: #888; text-align: center; padding: 40px;">No timeline data available</div>
                @endisset
            </div>

            <!-- Views Panel -->
            <div id="debugbar-panel-views" class="debugbar-panel" style="padding: 16px; display: none;">
                <h3 style="margin: 0 0 16px 0; color: #4CAF50; font-size: 14px; font-weight: 600;">Rendered Views</h3>

                @isset($data['views'])
                @foreach($data['views']['views'] as $index => $view)
                <div style="margin-bottom: 16px; background: #2a2a2a; border-left: 3px solid #00BCD4; border-radius: 3px; overflow: hidden;">
                    <div style="padding: 12px; background: #2d2d2d; display: flex; justify-content: space-between; align-items: center;">
                        <div style="font-weight: 600; color: #fff;">{{ $view['view'] }}</div>
                        <span class="badge badge-info">{{ $view['renderTime'] }}ms</span>
                    </div>
                    <div style="padding: 12px;">
                        <div style="color: #888; font-size: 11px; margin-bottom: 8px;">View Data:</div>
                        <table>
                            <thead>
                                <tr><th>Variable</th><th>Value</th></tr>
                            </thead>
                            <tbody>
                                @foreach($view['data'] as $key => $value)
                                <tr>
                                    <td style="color: #888;">{{ $key }}</td>
                                    <td><code>{{ is_array($value) || is_object($value) ? json_encode($value) : (is_string($value) && strlen($value) > 100 ? substr($value, 0, 100) . '...' : $value) }}</code></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @endforeach
                @else
                <div style="color: #888; text-align: center; padding: 40px;">No views rendered</div>
                @endisset
            </div>

            <!-- Queries Panel -->
            <div id="debugbar-panel-queries" class="debugbar-panel" style="padding: 16px; display: block;">
                <h3 style="margin: 0 0 16px 0; color: #4CAF50; font-size: 14px; font-weight: 600;">Database Queries @isset($data['queries'])<span style="color: #888; font-weight: normal; font-size: 12px;">({{ $data['queries']['count'] }} queries, {{ $data['queries']['total_time'] }}ms total)</span>@endisset</h3>

                @isset($data['queries'])
                @foreach($data['queries']['queries'] as $index => $query)
                <div style="margin-bottom: 12px; background: #2a2a2a; border-left: 3px solid {{ $query['time'] > 100 ? '#f44336' : ($query['time'] > 50 ? '#ff9800' : '#4CAF50') }}; border-radius: 3px; overflow: hidden;">
                    <div style="padding: 10px 12px; background: #2d2d2d; display: flex; justify-content: space-between; align-items: center; cursor: pointer;" onclick="toggleQueryDetails({{ $index }})">
                        <div style="flex: 1;">
                            <span style="color: #888; font-size: 10px; margin-right: 8px;">#{{ $index + 1 }}</span>
                            <code style="font-size: 11px;">{{ strlen($query['rawSql']) > 120 ? substr($query['rawSql'], 0, 120) . '...' : $query['rawSql'] }}</code>
                        </div>
                        <div style="display: flex; gap: 8px; align-items: center;">
                            <span class="badge badge-{{ $query['time'] > 100 ? 'danger' : ($query['time'] > 50 ? 'warning' : 'success') }}">{{ $query['time'] }}ms</span>
                            <span style="color: #888; font-size: 10px;">{{ $query['connection'] }}</span>
                        </div>
                    </div>

                    <div id="query-details-{{ $index }}" style="display: none; padding: 12px; border-top: 1px solid #3d3d3d;">
                        <div style="margin-bottom: 12px;">
                            <div style="color: #888; font-size: 10px; margin-bottom: 4px;">Full SQL:</div>
                            <code style="display: block; white-space: pre-wrap; word-break: break-all; background: #1e1e1e; padding: 8px; border-radius: 3px;">{{ $query['rawSql'] }}</code>
                        </div>

                        @if(!empty($query['backtrace']))
                        <div>
                            <div style="color: #888; font-size: 10px; margin-bottom: 4px;">Called by:</div>
                            <table style="font-size: 10px;">
                                <thead>
                                    <tr><th style="width: 50px;">#</th><th>Location</th><th>Function</th></tr>
                                </thead>
                                <tbody>
                                    @foreach($query['backtrace'] as $frameIndex => $frame)
                                        @if(!isset($frame['file']) || !str_contains($frame['file'], 'vendor/'))
                                        <tr>
                                            <td style="color: #888;">{{ $frameIndex }}</td>
                                            <td style="font-size: 9px;">
                                                @if(isset($frame['file']) && isset($frame['line']))
                                                    <div style="color: #2196F3;">{{ str_replace('/var/www/projekty/book/', '', $frame['file']) }}:{{ $frame['line'] }}</div>
                                                @endif
                                                @if(isset($frame['compiled_file']) && isset($frame['compiled_line']))
                                                    <div style="color: #ff9800;">📄 {{ basename($frame['compiled_file']) }}:{{ $frame['compiled_line'] }}</div>
                                                @endif
                                            </td>
                                            <td>
                                                @if(isset($frame['class']) && isset($frame['function']))
                                                    <code>{{ $frame['class'] }}::{{ $frame['function'] }}()</code>
                                                @elseif(isset($frame['function']))
                                                    <code>{{ $frame['function'] }}()</code>
                                                @endif
                                            </td>
                                        </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @endif
                    </div>
                </div>
                @endforeach
                @else
                <div style="color: #888; text-align: center; padding: 40px;">No queries executed</div>
                @endisset
            </div>

            <!-- Cache Panel -->
            <div id="debugbar-panel-cache" class="debugbar-panel" style="display: none; padding: 20px; background: #1e1e1e; color: #d4d4d4;">
                @isset($data['cache'])
                <?php
                use Larafony\Framework\Support\ByteFormatter;
                $formattedSize = ByteFormatter::format($data['cache']['total_size'] ?? 0);
                ?>
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                    <h3 style="margin: 0; color: #4CAF50; font-size: 14px; font-weight: 600;">Cache Operations
                        <span style="color: #888; font-weight: normal; font-size: 12px;">({{ $data['cache']['hits'] }} hits, {{ $data['cache']['misses'] }} misses, {{ $data['cache']['writes'] }} writes | Hit ratio: {{ $data['cache']['hit_ratio'] }}% | Size: {{ $formattedSize }})</span>
                    </h3>
                    <button onclick="clearCache()" style="padding: 4px 8px; background: #f44336; color: white; border: none; border-radius: 3px; cursor: pointer; font-size: 10px; font-weight: 500; transition: background 0.2s; max-width: 80px;" onmouseover="this.style.background='#d32f2f'" onmouseout="this.style.background='#f44336'">
                        🗑️ Clear
                    </button>
                </div>

                @if(!empty($data['cache']['operations']))
                <div style="background: #252526; border-radius: 4px; overflow: hidden;">
                    <table class="stripped-table" style="width: 100%; border-collapse: collapse;">
                        <thead style="background: #2d2d30;">
                            <tr style="border-bottom: 1px solid #3d3d3d;">
                                <th style="padding: 12px; text-align: left; font-weight: 600; color: #aaa; font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px;">Type</th>
                                <th style="padding: 12px; text-align: left; font-weight: 600; color: #aaa; font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px;">Key</th>
                                <th style="padding: 12px; text-align: left; font-weight: 600; color: #aaa; font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px;">Expires At / TTL</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data['cache']['operations'] as $op)
                            <tr style="border-bottom: 1px solid #2d2d30;">
                                <td style="padding: 12px;">
                                    @if($op['type'] === 'hit')
                                    <span style="color: #4CAF50; font-weight: 600;">✓ HIT</span>
                                    @elseif($op['type'] === 'miss')
                                    <span style="color: #FF9800; font-weight: 600;">✗ MISS</span>
                                    @elseif($op['type'] === 'write')
                                    <span style="color: #2196F3; font-weight: 600;">✎ WRITE</span>
                                    @else
                                    <span style="color: #f44336; font-weight: 600;">🗑 DELETE</span>
                                    @endif
                                </td>
                                <td style="padding: 12px; font-family: 'Consolas', 'Monaco', monospace; color: #ce9178;">{{ $op['key'] }}</td>
                                <td style="padding: 12px; color: #b5cea8;">
                                    @if(isset($op['expires_at']))
                                        <span style="color: #b5cea8;">{{ $op['expires_at'] }}</span>
                                    @elseif($op['type'] === 'write')
                                        <span style="color: #4CAF50;">{{ $op['ttl'] ?? 'forever' }}{{ isset($op['ttl']) ? 's' : '' }}</span>
                                    @else
                                        <span style="color: #666;">-</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div style="color: #888; text-align: center; padding: 40px;">No cache operations</div>
                @endif
                @else
                <div style="color: #888; text-align: center; padding: 40px;">Cache collector not available</div>
                @endisset
            </div>

        </div>
    </div>
</div>

<script>
let currentTab = 'queries';

function switchTab(tabName) {
    // Hide all panels
    document.querySelectorAll('.debugbar-panel').forEach(panel => {
        panel.style.display = 'none';
    });

    // Remove active state from all tabs
    document.querySelectorAll('.debugbar-tab').forEach(tab => {
        tab.style.background = '';
        tab.style.color = '#aaa';
        tab.style.borderBottom = '';
    });

    // Show selected panel
    const panel = document.getElementById('debugbar-panel-' + tabName);
    if (panel) {
        panel.style.display = 'block';
    }

    // Add active state to selected tab
    const tab = document.querySelector('.debugbar-tab[data-panel="' + tabName + '"]');
    if (tab) {
        tab.style.background = '#1e1e1e';
        tab.style.color = '#4CAF50';
        tab.style.borderBottom = '2px solid #4CAF50';
    }

    currentTab = tabName;
}

function toggleQueryDetails(index) {
    const details = document.getElementById('query-details-' + index);
    if (details) {
        details.style.display = details.style.display === 'none' ? 'block' : 'none';
    }
}

function clearCache() {
    if (!confirm('Are you sure you want to clear all cache?')) {
        return;
    }

    fetch('/debugbar/cache/clear', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Cache cleared successfully!');
            location.reload();
        } else {
            alert('Failed to clear cache: ' + (data.message || 'Unknown error'));
        }
    })
    .catch(error => {
        alert('Error clearing cache: ' + error.message);
    });
}

function minimizeDebugbar() {
    document.getElementById('debugbar-main').style.display = 'none';
    document.getElementById('debugbar-icon').style.display = 'flex';
}

function toggleDebugbar() {
    document.getElementById('debugbar-main').style.display = 'block';
    document.getElementById('debugbar-icon').style.display = 'none';
}

function closeDebugbar() {
    document.getElementById('larafony-debugbar').remove();
}

// Resizer functionality
(function() {
    const debugbar = document.getElementById('larafony-debugbar');
    const resizer = document.getElementById('debugbar-resizer');
    let isResizing = false;
    let startY = 0;
    let startHeight = 0;

    resizer.addEventListener('mousedown', function(e) {
        isResizing = true;
        startY = e.clientY;
        startHeight = debugbar.offsetHeight;
        document.body.style.cursor = 'ns-resize';
        document.body.style.userSelect = 'none';
        e.preventDefault();
    });

    document.addEventListener('mousemove', function(e) {
        if (!isResizing) return;

        const deltaY = startY - e.clientY;
        const newHeight = startHeight + deltaY;

        // Min height: 40px (just the toolbar), Max height: 80vh
        const minHeight = 40;
        const maxHeight = window.innerHeight * 0.8;

        if (newHeight >= minHeight && newHeight <= maxHeight) {
            debugbar.style.height = newHeight + 'px';
        }
    });

    document.addEventListener('mouseup', function() {
        if (isResizing) {
            isResizing = false;
            document.body.style.cursor = '';
            document.body.style.userSelect = '';
        }
    });
})();

// Initialize - show queries tab by default
switchTab('queries');
</script>
