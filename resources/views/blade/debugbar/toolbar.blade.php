<div id="larafony-debugbar" style="position: fixed; bottom: 0; left: 0; right: 0; z-index: 999999; background: #1e1e1e; color: #fff; font-family: 'Monaco', 'Menlo', monospace; font-size: 12px; box-shadow: 0 -2px 10px rgba(0,0,0,0.3); height: auto; max-height: 80vh; display: flex; flex-direction: column;">
    <div id="debugbar-resizer" style="height: 4px; background: #3d3d3d; cursor: ns-resize; position: relative; flex-shrink: 0;">
        <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 40px; height: 2px; background: #666; border-radius: 2px;"></div>
    </div>
    <div style="display: flex; align-items: center; padding: 8px 16px; background: #2d2d2d; border-bottom: 1px solid #3d3d3d; flex-shrink: 0;">
        <div style="flex: 1; display: flex; gap: 20px;">
            @isset($data['performance'])
            <div class="debugbar-item">
                <span style="color: #888;">⏱️ Time:</span>
                <span style="color: #4CAF50; font-weight: bold;">{{ $data['performance']['execution_time'] }}ms</span>
            </div>
            <div class="debugbar-item">
                <span style="color: #888;">💾 Memory:</span>
                <span style="color: #2196F3; font-weight: bold;">{{ $data['performance']['peak_memory'] }}</span>
            </div>
            @endisset

            @isset($data['queries'])
            <div class="debugbar-item" onclick="togglePanel('queries')" style="cursor: pointer;">
                <span style="color: #888;">🗄️ Queries:</span>
                <span style="color: {{ $data['queries']['count'] > 10 ? '#ff9800' : '#4CAF50' }}; font-weight: bold;">
                    {{ $data['queries']['count'] }} ({{ $data['queries']['total_time'] }}ms)
                </span>
            </div>
            @endisset

            @isset($data['cache'])
            <div class="debugbar-item" onclick="togglePanel('cache')" style="cursor: pointer;">
                <span style="color: #888;">🔥 Cache:</span>
                <span style="color: #9C27B0; font-weight: bold;">
                    {{ $data['cache']['hits'] }}/{{ $data['cache']['total'] }} ({{ $data['cache']['hit_ratio'] }}%)
                </span>
            </div>
            @endisset

            @isset($data['views'])
            <div class="debugbar-item" onclick="togglePanel('views')" style="cursor: pointer;">
                <span style="color: #888;">👁️ Views:</span>
                <span style="color: #00BCD4; font-weight: bold;">
                    {{ $data['views']['count'] }} ({{ $data['views']['total_time'] }}ms)
                </span>
            </div>
            @endisset

            @isset($data['route'])
            <div class="debugbar-item" onclick="togglePanel('route')" style="cursor: pointer;">
                <span style="color: #888;">🛣️ Route:</span>
                <span style="color: #FF5722; font-weight: bold;">
                    {{ $data['route']['name'] ?? $data['route']['uri'] }}
                </span>
            </div>
            @endisset
        </div>
        <button onclick="document.getElementById('larafony-debugbar').remove()"
                style="background: transparent; border: none; color: #888; cursor: pointer; font-size: 16px; padding: 0 8px;">
            ✕
        </button>
    </div>

    @isset($data['queries'])
    <div id="debugbar-panel-queries" class="debugbar-panel" style="display: none; overflow-y: auto; background: #1e1e1e; padding: 16px; flex: 1; min-height: 0;">
        <h3 style="margin: 0 0 12px 0; color: #4CAF50;">Database Queries ({{ $data['queries']['count'] }})</h3>
        @foreach($data['queries']['queries'] as $index => $query)
        <div style="margin-bottom: 12px; padding: 12px; background: #2d2d2d; border-left: 3px solid #4CAF50; border-radius: 3px;">
            <div style="color: #888; font-size: 10px; margin-bottom: 4px;">
                Query #{{ $index + 1 }} • {{ $query['time'] }}ms • {{ $query['connection'] }}
            </div>
            <code style="color: #fff; display: block; white-space: pre-wrap; word-break: break-all; margin-bottom: 8px;">{{ $query['rawSql'] }}</code>
            @if(!empty($query['backtrace']))
            <details style="margin-top: 8px;">
                <summary style="color: #888; font-size: 10px; cursor: pointer; user-select: none;">📍 Backtrace ({{ count($query['backtrace']) }} frames)</summary>
                <div style="margin-top: 8px; padding: 8px; background: #1e1e1e; border-radius: 3px; font-size: 10px;">
                    @foreach($query['backtrace'] as $frameIndex => $frame)
                    <div style="padding: 4px 0; border-bottom: 1px solid #3d3d3d;">
                        <div style="color: #888;">Frame #{{ $frameIndex }}</div>
                        @if(isset($frame['class']) && isset($frame['function']))
                        <div style="color: #4CAF50;">{{ $frame['class'] }}::{{ $frame['function'] }}()</div>
                        @elseif(isset($frame['function']))
                        <div style="color: #4CAF50;">{{ $frame['function'] }}()</div>
                        @endif
                        @if(isset($frame['file']) && isset($frame['line']))
                        <div style="color: #2196F3; font-size: 9px;">{{ $frame['file'] }}:{{ $frame['line'] }}</div>
                        @endif
                        @if(isset($frame['compiled_file']) && isset($frame['compiled_line']))
                        <div style="color: #ff9800; font-size: 8px;">Compiled: {{ basename($frame['compiled_file']) }}:{{ $frame['compiled_line'] }}</div>
                        @endif
                    </div>
                    @endforeach
                </div>
            </details>
            @endif
        </div>
        @endforeach
    </div>
    @endisset

    @isset($data['cache'])
    <div id="debugbar-panel-cache" class="debugbar-panel" style="display: none; overflow-y: auto; background: #1e1e1e; padding: 16px; flex: 1; min-height: 0;">
        <h3 style="margin: 0 0 12px 0; color: #9C27B0;">Cache Operations ({{ $data['cache']['total'] }})</h3>
        <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 12px; margin-bottom: 16px;">
            <div style="padding: 12px; background: #2d2d2d; border-radius: 3px; text-align: center;">
                <div style="color: #4CAF50; font-size: 24px; font-weight: bold;">{{ $data['cache']['hits'] }}</div>
                <div style="color: #888; font-size: 10px;">Hits</div>
            </div>
            <div style="padding: 12px; background: #2d2d2d; border-radius: 3px; text-align: center;">
                <div style="color: #ff9800; font-size: 24px; font-weight: bold;">{{ $data['cache']['misses'] }}</div>
                <div style="color: #888; font-size: 10px;">Misses</div>
            </div>
            <div style="padding: 12px; background: #2d2d2d; border-radius: 3px; text-align: center;">
                <div style="color: #2196F3; font-size: 24px; font-weight: bold;">{{ $data['cache']['writes'] }}</div>
                <div style="color: #888; font-size: 10px;">Writes</div>
            </div>
            <div style="padding: 12px; background: #2d2d2d; border-radius: 3px; text-align: center;">
                <div style="color: #F44336; font-size: 24px; font-weight: bold;">{{ $data['cache']['deletes'] }}</div>
                <div style="color: #888; font-size: 10px;">Deletes</div>
            </div>
        </div>
        @foreach($data['cache']['operations'] as $index => $op)
        <div style="margin-bottom: 8px; padding: 8px; background: #2d2d2d; border-left: 3px solid
            {{ $op['type'] === 'hit' ? '#4CAF50' : ($op['type'] === 'miss' ? '#ff9800' : ($op['type'] === 'write' ? '#2196F3' : '#F44336')) }};
            border-radius: 3px;">
            <span style="color: #888;">{{ strtoupper($op['type']) }}:</span>
            <span style="color: #fff;">{{ $op['key'] }}</span>
        </div>
        @endforeach
    </div>
    @endisset

    @isset($data['views'])
    <div id="debugbar-panel-views" class="debugbar-panel" style="display: none; overflow-y: auto; background: #1e1e1e; padding: 16px; flex: 1; min-height: 0;">
        <h3 style="margin: 0 0 12px 0; color: #00BCD4;">Rendered Views ({{ $data['views']['count'] }})</h3>
        @foreach($data['views']['views'] as $index => $view)
        <div style="margin-bottom: 12px; padding: 12px; background: #2d2d2d; border-left: 3px solid #00BCD4; border-radius: 3px;">
            <div style="color: #fff; font-weight: bold; margin-bottom: 4px;">{{ $view['view'] }}</div>
            <div style="color: #888; font-size: 10px; margin-bottom: 4px;">
                Render time: {{ $view['renderTime'] }}ms
            </div>
            <div style="color: #888; font-size: 10px;">
                Data: {{ implode(', ', $view['data']) }}
            </div>
        </div>
        @endforeach
    </div>
    @endisset

    @isset($data['route'])
    <div id="debugbar-panel-route" class="debugbar-panel" style="display: none; background: #1e1e1e; padding: 16px; flex: 1; min-height: 0; overflow-y: auto;">
        <h3 style="margin: 0 0 12px 0; color: #FF5722;">Route Information</h3>
        <div style="background: #2d2d2d; padding: 12px; border-radius: 3px;">
            <div style="margin-bottom: 8px;">
                <span style="color: #888;">URI:</span>
                <span style="color: #fff;">{{ $data['route']['uri'] }}</span>
            </div>
            @if($data['route']['name'])
            <div style="margin-bottom: 8px;">
                <span style="color: #888;">Name:</span>
                <span style="color: #fff;">{{ $data['route']['name'] }}</span>
            </div>
            @endif
            <div style="margin-bottom: 8px;">
                <span style="color: #888;">Method:</span>
                <span style="color: #fff;">{{ $data['route']['method']  }}</span>
            </div>
            <div style="margin-bottom: 8px;">
                <span style="color: #888;">Action:</span>
                <span style="color: #fff;">{{ $data['route']['action'] }}</span>
            </div>
            @if(!empty($data['route']['parameters']))
            <div style="margin-bottom: 8px;">
                <span style="color: #888;">Parameters:</span>
                <code style="color: #fff;">{{ json_encode($data['route']['parameters']) }}</code>
            </div>
            @endif
            @if(!empty($data['route']['middleware']))
            <div>
                <span style="color: #888;">Middleware:</span>
                <span style="color: #fff;">{{ implode(', ', $data['route']['middleware']) }}</span>
            </div>
            @endif
        </div>
    </div>
    @endisset
</div>

<script>
function togglePanel(name) {
    const panel = document.getElementById('debugbar-panel-' + name);
    const allPanels = document.querySelectorAll('.debugbar-panel');

    allPanels.forEach(p => {
        if (p !== panel) {
            p.style.display = 'none';
        }
    });

    panel.style.display = panel.style.display === 'none' ? 'flex' : 'none';
    panel.style.flexDirection = 'column';
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
</script>
