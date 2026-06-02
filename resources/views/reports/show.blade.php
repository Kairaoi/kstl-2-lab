<x-app-layout>
    <x-slot name="title">
        Report Results — {{ $report->name }}
    </x-slot>

    @push('styles')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Noto+Serif:ital,wght@0,400;0,600;0,700;1,400&family=Noto+Sans:wght@300;400;500;600&display=swap');
        :root{
            --primary:#0b2040;
            --primary-mid:#133060;
            --accent:#00a8c8;
            --accent-light:#e6f7fb;
            --ink:#0d1f2d;
            --mist:#f0f4f8;
            --white:#fff;
            --border:#c8d4e0;
            --success:#1a8a5a;
            --error:#c0392b;
            --radius:4px;
            --radius-lg:6px;
            --shadow:0 4px 32px rgba(11,32,64,.10)
        }
        *,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
        body{font-family:'Noto Sans',sans-serif;background:var(--mist);color:var(--ink)}
        .shell{max-width:1400px;margin:0 auto;padding:2.5rem 2rem 4rem}
        
        .page-header{display:flex;align-items:center;justify-content:space-between;margin-bottom:2rem;flex-wrap:wrap;gap:1rem}
        .page-header-left{display:flex;align-items:center;gap:1rem}
        .badge{width:48px;height:48px;background:var(--primary);border-radius:6px;display:flex;align-items:center;justify-content:center}
        .badge svg{width:24px;height:24px;stroke:var(--accent);fill:none}
        .eyebrow{font-family:'Noto Serif',serif;font-size:.68rem;font-weight:700;letter-spacing:.14em;text-transform:uppercase;color:var(--primary-mid);margin-bottom:.2rem}
        .page-title{font-family:'Noto Serif',serif;font-size:1.4rem;font-weight:800;color:var(--primary)}
        
        .btn{display:inline-flex;align-items:center;gap:.45rem;padding:.65rem 1.4rem;border-radius:var(--radius);font-family:'Noto Serif',serif;font-size:.85rem;font-weight:700;cursor:pointer;border:none;text-decoration:none;transition:all .2s}
        .btn svg{width:16px;height:16px}
        .btn-primary{background:var(--primary);color:#fff}
        .btn-primary:hover{background:var(--primary-mid);transform:translateY(-1px)}
        .btn-secondary{background:var(--mist);color:var(--primary);border:1.5px solid var(--border)}
        .btn-secondary:hover{background:var(--white);border-color:var(--primary-mid)}
        .btn-sm{padding:.5rem 1rem;font-size:.78rem}
        
        .view-toggle{display:flex;gap:.5rem;background:var(--white);padding:.25rem;border-radius:var(--radius);border:1px solid var(--border)}
        .view-btn{padding:.5rem 1rem;border-radius:3px;font-size:.8rem;font-weight:600;cursor:pointer;border:none;background:transparent;color:var(--primary-mid);transition:all .15s}
        .view-btn.active{background:var(--primary);color:#fff}
        
        .chart-selector{background:var(--white);border-radius:var(--radius-lg);box-shadow:var(--shadow);border:1px solid var(--border);padding:1.5rem;margin-bottom:2rem}
        .selector-label{font-family:'Noto Serif',serif;font-size:.9rem;font-weight:700;color:var(--primary);margin-bottom:1rem}
        .chart-types{display:grid;grid-template-columns:repeat(auto-fit,minmax(140px,1fr));gap:.75rem}
        .chart-type-btn{padding:.85rem 1rem;border-radius:var(--radius);border:2px solid var(--border);background:var(--white);cursor:pointer;transition:all .2s;text-align:center}
        .chart-type-btn:hover{border-color:var(--accent);transform:translateY(-2px)}
        .chart-type-btn.active{border-color:var(--primary);background:var(--primary);color:#fff}
        .chart-type-icon{font-size:1.5rem;margin-bottom:.5rem}
        .chart-type-name{font-size:.8rem;font-weight:600}
        
        .stats-row{display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:1rem;margin-bottom:2rem}
        .stat-card{background:var(--white);border-radius:var(--radius-lg);box-shadow:var(--shadow);border:1px solid var(--border);padding:1.25rem}
        .stat-label{font-size:.75rem;font-weight:600;letter-spacing:.08em;text-transform:uppercase;color:var(--primary-mid);margin-bottom:.5rem}
        .stat-value{font-family:'Noto Serif',serif;font-size:1.75rem;font-weight:800;color:var(--primary)}
        .stat-sub{font-size:.8rem;color:#6a7a8a;margin-top:.25rem}
        
        .chart-card{background:var(--white);border-radius:var(--radius-lg);box-shadow:var(--shadow);border:1px solid var(--border);padding:2rem;margin-bottom:2rem}
        
        .table-card{background:var(--white);border-radius:var(--radius-lg);box-shadow:var(--shadow);border:1px solid var(--border);overflow:hidden}
        .table-wrap{overflow-x:auto}
        table{width:100%;border-collapse:collapse}
        thead{background:var(--primary)}
        thead th{padding:.85rem 1.25rem;text-align:left;font-family:'Noto Serif',serif;font-size:.72rem;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:rgba(255,255,255,.85);white-space:nowrap}
        tbody tr{border-bottom:1px solid var(--border);transition:background .15s}
        tbody tr:last-child{border-bottom:none}
        tbody tr:hover{background:var(--mist)}
        tbody td{padding:.9rem 1.25rem;font-size:.875rem;vertical-align:middle}
        
        .view-section{display:none}
        .view-section.active{display:block}
    </style>
    @endpush

    <div class="shell">

        <div class="page-header">
            <div class="page-header-left">
                <div class="badge">
                    <svg viewBox="0 0 24 24" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" />
                    </svg>
                </div>
                <div>
                    <div class="eyebrow">{{ ucfirst($report->category ?? 'Audit') }} Report</div>
                    <h1 class="page-title">{{ $report->name }}</h1>
                </div>
            </div>
            <div style="display:flex;gap:.5rem;flex-wrap:wrap;align-items:center">
                <div class="view-toggle">
                    <button class="view-btn active" onclick="switchView('chart')">📊 Charts</button>
                    <button class="view-btn" onclick="switchView('table')">📋 Table</button>
                </div>
                
                <a href="{{ route('reports.index') }}" class="btn btn-secondary btn-sm">
                    ← Back to Reports
                </a>
                <a href="{{ route('reports.export', ['code' => $report->code, 'format' => 'csv']) }}" class="btn btn-primary btn-sm">
                    ⬇ Export CSV
                </a>
            </div>
        </div>

        {{-- Statistics --}}
        <div class="stats-row">
            <div class="stat-card">
                <div class="stat-label">Results</div>
                <div class="stat-value">{{ number_format($count) }}</div>
                <div class="stat-sub">{{ $count === 1 ? 'row' : 'rows' }} returned</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Execution Time</div>
                <div class="stat-value">{{ number_format($execution_time, 2) }}<span style="font-size:.9rem;font-weight:400">ms</span></div>
                <div class="stat-sub">Query performance</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Generated</div>
                <div class="stat-value" style="font-size:1.2rem">{{ now()->format('g:i A') }}</div>
                <div class="stat-sub">{{ now()->format('M j, Y') }}</div>
            </div>
        </div>

        @if(!empty($results) && count($results) > 0)
            {{-- CHART VIEW --}}
            <div id="chartView" class="view-section active">
                <div class="chart-selector">
                    <div class="selector-label">Select Chart Type</div>
                    <div class="chart-types">
                        <button class="chart-type-btn active" onclick="switchChartType('column')">📊 Bar Chart</button>
                        <button class="chart-type-btn" onclick="switchChartType('line')">📈 Line Chart</button>
                        <button class="chart-type-btn" onclick="switchChartType('area')">🏔️ Area Chart</button>
                        <button class="chart-type-btn" onclick="switchChartType('pie')">🥧 Pie Chart</button>
                        <button class="chart-type-btn" onclick="switchChartType('donut')">🍩 Donut Chart</button>
                    </div>
                </div>

                <div class="chart-card">
                    <div id="mainChart" style="height:500px"></div>
                </div>
            </div>

            {{-- TABLE VIEW --}}
            <div id="tableView" class="view-section">
                <div class="table-card">
                    <div class="table-wrap">
                        <table>
                            <thead>
                                <tr>
                                    @foreach(array_keys((array)$results[0]) as $column)
                                        <th>{{ str_replace('_', ' ', ucfirst($column)) }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($results as $row)
                                    <tr>
                                        @foreach((array)$row as $value)
                                            <td>{{ $value ?? '—' }}</td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @else
            <div style="text-align:center;padding:4rem 2rem;background:var(--white);border-radius:var(--radius-lg);box-shadow:var(--shadow)">
                <h3>No Results Found</h3>
                <p>This report returned no data.</p>
            </div>
        @endif

    </div>

    {{-- Highcharts --}}
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>

    <script>
    function switchView(view) {
        document.querySelectorAll('.view-btn').forEach(btn => btn.classList.remove('active'));
        document.querySelectorAll('.view-section').forEach(section => section.classList.remove('active'));
        
        event.target.classList.add('active');
        document.getElementById(view + 'View').classList.add('active');
    }

    @if(!empty($results) && count($results) > 0)
    const reportData = @json($results);
    const categories = reportData.map(row => String(Object.values(row)[0]));
    const values = reportData.map(row => parseFloat(Object.values(row)[1]) || 0);

    let mainChart = null;

    function renderChart(type) {
        const config = {
            chart: { type: type || 'column' },
            title: { text: '{{ $report->name }}' },
            xAxis: { categories: categories.slice(0, 30) },
            yAxis: { min: 0 },
            series: [{ name: 'Value', data: values.slice(0, 30) }]
        };

        if (type === 'pie' || type === 'donut') {
            config.chart.type = 'pie';
            delete config.xAxis;
            delete config.yAxis;
            config.series = [{
                name: 'Count',
                colorByPoint: true,
                data: categories.slice(0, 12).map((cat, i) => ({ name: cat, y: values[i] || 0 }))
            }];
            if (type === 'donut') config.plotOptions = { pie: { innerSize: '55%' } };
        }

        if (mainChart) mainChart.destroy();
        mainChart = Highcharts.chart('mainChart', config);
    }

    function switchChartType(type) {
        document.querySelectorAll('.chart-type-btn').forEach(btn => btn.classList.remove('active'));
        event.target.classList.add('active');
        renderChart(type);
    }

    renderChart('column');
    @endif
    </script>
</x-app-layout>