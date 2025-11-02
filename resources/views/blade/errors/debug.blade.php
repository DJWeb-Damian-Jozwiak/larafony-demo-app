<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $exception['class'] }} | Larafony Debug</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Menlo', 'Monaco', 'Courier New', monospace;
            background: #1e1e1e;
            color: #d4d4d4;
            font-size: 14px;
            line-height: 1.6;
        }

        .header {
            background: #252526;
            border-bottom: 1px solid #3e3e42;
            padding: 1.5rem 2rem;
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.3);
        }

        .exception-class {
            font-size: 0.75rem;
            color: #858585;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 0.5rem;
        }

        .exception-message {
            font-size: 1.25rem;
            color: #f48771;
            font-weight: 600;
            margin-bottom: 0.75rem;
        }

        .exception-location {
            color: #9cdcfe;
            font-size: 0.875rem;
        }

        .exception-location .file {
            color: #ce9178;
        }

        .exception-location .line {
            color: #b5cea8;
        }

        .container {
            display: flex;
            height: calc(100vh - 120px);
        }

        .sidebar {
            width: 400px;
            background: #252526;
            border-right: 1px solid #3e3e42;
            overflow-y: auto;
            flex-shrink: 0;
        }

        .content {
            flex: 1;
            overflow-y: auto;
            padding: 1.5rem;
        }

        .frame {
            padding: 1rem 1.5rem;
            border-bottom: 1px solid #3e3e42;
            cursor: pointer;
            transition: background 0.2s;
        }

        .frame:hover {
            background: #2d2d30;
        }

        .frame.active {
            background: #37373d;
            border-left: 3px solid #007acc;
        }

        .frame-function {
            color: #dcdcaa;
            font-weight: 600;
            margin-bottom: 0.25rem;
        }

        .frame-class {
            color: #4ec9b0;
        }

        .frame-location {
            color: #858585;
            font-size: 0.8rem;
        }

        .frame-file {
            color: #ce9178;
        }

        .frame-line {
            color: #b5cea8;
        }

        .code-snippet {
            background: #1e1e1e;
            border: 1px solid #3e3e42;
            border-radius: 6px;
            overflow: hidden;
            margin-bottom: 1.5rem;
        }

        .snippet-header {
            background: #2d2d30;
            padding: 0.75rem 1rem;
            border-bottom: 1px solid #3e3e42;
            color: #9cdcfe;
            font-size: 0.875rem;
        }

        .snippet-body {
            overflow-x: auto;
        }

        .code-line {
            display: flex;
            padding: 0.25rem 0;
        }

        .code-line.error-line {
            background: rgba(244, 135, 113, 0.1);
            border-left: 3px solid #f48771;
        }

        .line-number {
            color: #858585;
            text-align: right;
            padding: 0 1rem;
            user-select: none;
            min-width: 60px;
        }

        .error-line .line-number {
            color: #f48771;
            font-weight: 600;
        }

        .line-content {
            flex: 1;
            padding-right: 1rem;
            white-space: pre;
            color: #d4d4d4;
        }

        .error-line .line-content {
            color: #ffffff;
        }

        ::-webkit-scrollbar {
            width: 10px;
            height: 10px;
        }

        ::-webkit-scrollbar-track {
            background: #1e1e1e;
        }

        ::-webkit-scrollbar-thumb {
            background: #424242;
            border-radius: 5px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #4e4e4e;
        }

        .badge {
            display: inline-block;
            padding: 0.25rem 0.5rem;
            background: #007acc;
            color: white;
            border-radius: 3px;
            font-size: 0.7rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-left: 0.5rem;
        }

        .no-snippet {
            padding: 2rem;
            text-align: center;
            color: #858585;
            font-style: italic;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="exception-class">{{ $exception['class'] }}</div>
        <div class="exception-message">{{ $exception['message'] ?: 'No message' }}</div>
        <div class="exception-location">
            in <span class="file">{{ $exception['file'] }}</span>
            at line <span class="line">{{ $exception['line'] }}</span>
        </div>
    </div>

    <div class="container">
        <div class="sidebar">
            @foreach($backtrace as $index => $frame)
            <div class="frame {{ $index === 0 ? 'active' : '' }}" data-frame="{{ $index }}">
                <div class="frame-function">
                    @if($frame['class'])
                        <span class="frame-class">{{ $frame['class'] }}</span>::
                    @endif
                    {{ $frame['function'] }}()
                </div>
                <div class="frame-location">
                    <span class="frame-file">{{ basename($frame['file']) }}</span>:<span class="frame-line">{{ $frame['line'] }}</span>
                </div>
            </div>
            @endforeach
        </div>

        <div class="content" id="content">
            @foreach($backtrace as $index => $frame)
            <div class="frame-content" id="frame-{{ $index }}" style="{{ $index === 0 ? '' : 'display: none;' }}">
                <div class="code-snippet">
                    <div class="snippet-header">
                        {{ $frame['file'] }}
                        @if($frame['class'])
                            <span class="badge">{{ basename(str_replace('\\', '/', $frame['class'])) }}</span>
                        @endif
                    </div>
                    <div class="snippet-body">
                        @if(!empty($frame['snippet']['lines']))
                            @foreach($frame['snippet']['lines'] as $lineNum => $lineContent)
                            <div class="code-line {{ $lineNum == $frame['snippet']['errorLine'] ? 'error-line' : '' }}">
                                <div class="line-number">{{ $lineNum }}</div>
                                <div class="line-content">{{ $lineContent }}</div>
                            </div>
                            @endforeach
                        @else
                            <div class="no-snippet">No source code available</div>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <script>
        // Frame navigation
        document.querySelectorAll('.frame').forEach(frame => {
            frame.addEventListener('click', function() {
                const frameIndex = this.dataset.frame;

                // Update active frame in sidebar
                document.querySelectorAll('.frame').forEach(f => f.classList.remove('active'));
                this.classList.add('active');

                // Show corresponding content
                document.querySelectorAll('.frame-content').forEach(c => c.style.display = 'none');
                document.getElementById('frame-' + frameIndex).style.display = 'block';
            });
        });
    </script>
</body>
</html>
