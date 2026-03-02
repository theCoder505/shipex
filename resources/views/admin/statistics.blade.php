@extends('layouts.admin.app')

@section('title', 'Statistics')

@section('style')
<style>
    .stat-card { transition: transform 0.2s ease, box-shadow 0.2s ease; }
    .stat-card:hover { transform: translateY(-4px); box-shadow: 0 10px 25px rgba(0,0,0,0.1); }
</style>
@endsection

@section('content')

<div class="mb-6">
    <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white">Statistics</h1>
    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Real visitors only — bots excluded</p>
</div>

{{-- Summary Cards --}}
<div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-8">
    @foreach([
        ['label' => 'Total Visits',    'value' => $totalVisits,     'icon' => '📊'],
        ['label' => 'Today',           'value' => $todayVisits,      'icon' => '📅'],
        ['label' => 'This Week',       'value' => $thisWeekVisits,   'icon' => '🗓️'],
        ['label' => 'This Month',      'value' => $thisMonthVisits,  'icon' => '📆'],
        ['label' => 'Unique Visitors', 'value' => $uniqueVisitors,   'icon' => '👤'],
    ] as $card)
    <div class="stat-card bg-white dark:bg-gray-800 rounded-xl p-4 shadow-sm border border-gray-100 dark:border-gray-700">
        <div class="text-2xl mb-1">{{ $card['icon'] }}</div>
        <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($card['value']) }}</div>
        <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ $card['label'] }}</div>
    </div>
    @endforeach
</div>

{{-- Daily Visits Chart --}}
<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 mb-8">
    <h2 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Daily Visits — Last 30 Days</h2>
    <canvas id="dailyChart" height="80"></canvas>
</div>

{{-- Breakdown Row --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">

    {{-- By Country --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
        <h2 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">🌍 By Country</h2>
        <div class="space-y-2">
            @foreach($byCountry as $row)
            <div class="flex items-center justify-between text-sm">
                <span class="text-gray-700 dark:text-gray-300 truncate">{{ $row->visitor_country ?? 'Unknown' }}</span>
                <span class="font-semibold text-gray-900 dark:text-white ml-2">{{ number_format($row->total) }}</span>
            </div>
            <div class="w-full bg-gray-100 dark:bg-gray-700 rounded-full h-1.5">
                <div class="bg-blue-500 h-1.5 rounded-full" style="width: {{ $byCountry->first()->total > 0 ? round(($row->total / $byCountry->first()->total) * 100) : 0 }}%"></div>
            </div>
            @endforeach
        </div>
    </div>

    {{-- By Device --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
        <h2 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">📱 By Device</h2>
        <canvas id="deviceChart" height="180"></canvas>
    </div>

    {{-- By Browser --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
        <h2 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">🌐 By Browser</h2>
        <div class="space-y-3">
            @foreach($byBrowser as $row)
            <div class="flex items-center justify-between text-sm">
                <span class="text-gray-700 dark:text-gray-300">{{ $row->visitor_browser ?? 'Unknown' }}</span>
                <span class="font-semibold text-gray-900 dark:text-white">{{ $row->total }}</span>
            </div>
            @endforeach
        </div>
    </div>

</div>

{{-- Top Pages --}}
<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 mb-8">
    <h2 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">🔝 Top Pages</h2>
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead class="text-xs uppercase text-gray-500 dark:text-gray-400 border-b dark:border-gray-700">
                <tr>
                    <th class="pb-3 pr-4">Page</th>
                    <th class="pb-3 pr-4">URL</th>
                    <th class="pb-3 text-right">Visits</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                @foreach($topPages as $page)
                <tr>
                    <td class="py-2 pr-4 text-gray-800 dark:text-gray-200 font-medium">{{ $page->page_name ?? '—' }}</td>
                    <td class="py-2 pr-4 text-gray-500 dark:text-gray-400 truncate max-w-xs">{{ $page->page_url }}</td>
                    <td class="py-2 text-right font-semibold text-gray-900 dark:text-white">{{ number_format($page->total) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

{{-- Recent Visits --}}
<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
    <h2 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">🕐 Recent Visits</h2>
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead class="text-xs uppercase text-gray-500 dark:text-gray-400 border-b dark:border-gray-700">
                <tr>
                    <th class="pb-3 pr-4">Page</th>
                    <th class="pb-3 pr-4">IP</th>
                    <th class="pb-3 pr-4">Country</th>
                    <th class="pb-3 pr-4">Device</th>
                    <th class="pb-3 pr-4">Browser</th>
                    <th class="pb-3">Time</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                @foreach($recentVisits as $visit)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                    <td class="py-2 pr-4 text-gray-800 dark:text-gray-200">{{ $visit->page_name ?? $visit->page_url }}</td>
                    <td class="py-2 pr-4 text-gray-500 dark:text-gray-400 font-mono">{{ $visit->visitor_ip_address }}</td>
                    <td class="py-2 pr-4 text-gray-700 dark:text-gray-300">{{ $visit->visitor_country ?? '—' }}</td>
                    <td class="py-2 pr-4 capitalize text-gray-700 dark:text-gray-300">{{ $visit->visitor_device ?? '—' }}</td>
                    <td class="py-2 pr-4 text-gray-700 dark:text-gray-300">{{ $visit->visitor_browser ?? '—' }}</td>
                    <td class="py-2 text-gray-500 dark:text-gray-400 whitespace-nowrap">{{ $visit->created_at->diffForHumans() }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.0/chart.umd.min.js"></script>
<script>
    $(".statistics").addClass("active_tab");

    // Daily visits chart
    const dailyData = @json($dailyVisits);
    new Chart(document.getElementById('dailyChart'), {
        type: 'line',
        data: {
            labels: dailyData.map(d => d.date),
            datasets: [{
                label: 'Visits',
                data: dailyData.map(d => d.total),
                borderColor: '#3b82f6',
                backgroundColor: 'rgba(59,130,246,0.08)',
                borderWidth: 2,
                fill: true,
                tension: 0.4,
                pointRadius: 3,
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: {
                x: { grid: { display: false } },
                y: { beginAtZero: true, grid: { color: 'rgba(0,0,0,0.05)' } }
            }
        }
    });

    // Device doughnut chart
    const deviceData = @json($byDevice);
    new Chart(document.getElementById('deviceChart'), {
        type: 'doughnut',
        data: {
            labels: deviceData.map(d => d.visitor_device ?? 'Unknown'),
            datasets: [{
                data: deviceData.map(d => d.total),
                backgroundColor: ['#3b82f6', '#10b981', '#f59e0b', '#ef4444'],
                borderWidth: 0,
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'bottom', labels: { padding: 16, font: { size: 12 } } }
            },
            cutout: '65%'
        }
    });
</script>
@endsection