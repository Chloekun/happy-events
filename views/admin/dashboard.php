<?php
require_once __DIR__ . '/../../includes/functions.php';

$dailyBookings = $stats['daily_bookings'] ?? [];
$chartJsonFlags = JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT;
$analyticsPeriod = $analyticsPeriod ?? ($stats['analytics_period'] ?? 'yearly');
$periodLabels = [
    'day' => 'Today',
    'month' => 'This Month',
    'quarter' => 'This Quarter',
    'yearly' => 'This Year'
];
?>
<!doctype html>
<html lang="en">
<head>
    <?php tailwindHead('Admin - Dashboard'); ?>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="min-h-screen bg-slate-100 text-slate-900">
    <?php renderNav('admin'); ?>

    <main class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        <div class="mb-6 flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <h1 class="text-3xl font-semibold tracking-tight">Analytics</h1>
                <p class="mt-1 text-sm text-slate-600">Overview of customers, bookings, and reservation activity for <?php echo e(strtolower($periodLabels[$analyticsPeriod] ?? 'this year')); ?>.</p>
            </div>
            <form method="GET" action="index.php" class="rounded-lg bg-white p-4 shadow-sm ring-1 ring-slate-200">
                <input type="hidden" name="controller" value="admin">
                <input type="hidden" name="action" value="dashboard">
                <label for="period" class="mb-1 block text-xs font-semibold uppercase tracking-[0.14em] text-slate-500">Filter Analytics</label>
                <select id="period" name="period" class="min-w-48 rounded-md border border-slate-300 px-3 py-2 text-sm outline-none focus:border-brand-500 focus:ring-2 focus:ring-brand-100" onchange="this.form.submit()">
                    <option value="day" <?php echo $analyticsPeriod === 'day' ? 'selected' : ''; ?>>By Day</option>
                    <option value="month" <?php echo $analyticsPeriod === 'month' ? 'selected' : ''; ?>>By Month</option>
                    <option value="quarter" <?php echo $analyticsPeriod === 'quarter' ? 'selected' : ''; ?>>Quarterly</option>
                    <option value="yearly" <?php echo $analyticsPeriod === 'yearly' ? 'selected' : ''; ?>>Yearly</option>
                </select>
            </form>
        </div>

        <section class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
            <?php
                $cards = [
                    ['Customers', $stats['total_customers'] ?? 0, 'fa-users'],
                    ['Reservations', $stats['total_reservations'] ?? 0, 'fa-calendar-check'],
                    ['Pending', $stats['pending_requests'] ?? 0, 'fa-clock'],
                    ['Estimated Revenue', 'PHP ' . number_format((float)($stats['estimated_revenue'] ?? 0), 2), 'fa-coins'],
                    ['Upcoming Events', $stats['upcoming_events'] ?? 0, 'fa-calendar-day'],
                    ['Approval Rate', ($stats['approval_rate'] ?? 0) . '%', 'fa-chart-line'],
                    ['Avg. Guests', $stats['average_guests'] ?? 0, 'fa-user-group'],
                    ['Active Services', $stats['active_services'] ?? 0, 'fa-list-check']
                ];
            ?>
            <?php foreach($cards as $card): ?>
                <div class="rounded-lg bg-white p-5 shadow-sm ring-1 ring-slate-200">
                    <div class="flex items-center justify-between">
                        <p class="text-sm font-medium text-slate-500"><?php echo e($card[0]); ?></p>
                        <i class="fas <?php echo e($card[2]); ?> text-brand-500"></i>
                    </div>
                    <div class="mt-3 text-3xl font-semibold"><?php echo e($card[1]); ?></div>
                </div>
            <?php endforeach; ?>
        </section>

        <section class="mt-6 grid gap-6 lg:grid-cols-2">
            <div class="rounded-lg bg-white p-5 shadow-sm ring-1 ring-slate-200">
                <h2 class="mb-4 text-lg font-semibold">Booking Status Bar Graph - <?php echo e($periodLabels[$analyticsPeriod] ?? 'This Year'); ?></h2>
                <div class="h-72">
                    <canvas id="statusBarChart"></canvas>
                </div>
            </div>

            <div class="rounded-lg bg-white p-5 shadow-sm ring-1 ring-slate-200">
                <h2 class="mb-4 text-lg font-semibold">Bookings Trend - <?php echo e($periodLabels[$analyticsPeriod] ?? 'This Year'); ?></h2>
                <div class="h-72">
                    <canvas id="bookingDateLineChart"></canvas>
                </div>
            </div>
        </section>

        <section class="mt-6 grid gap-6 lg:grid-cols-2">
            <div class="rounded-lg bg-white p-5 shadow-sm ring-1 ring-slate-200">
                <h2 class="mb-4 text-lg font-semibold">Top Event Types - <?php echo e($periodLabels[$analyticsPeriod] ?? 'This Year'); ?></h2>
                <div class="h-80">
                    <canvas id="eventTypeBarChart"></canvas>
                </div>
            </div>

            <div class="rounded-lg bg-white shadow-sm ring-1 ring-slate-200">
                <div class="border-b border-slate-200 px-5 py-4">
                    <h2 class="text-lg font-semibold">Recent Bookings</h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200 text-sm">
                        <thead class="bg-slate-50 text-left text-xs uppercase tracking-wide text-slate-500">
                            <tr>
                                <th class="px-5 py-3">Ref</th>
                                <th class="px-5 py-3">Customer</th>
                                <th class="px-5 py-3">Event</th>
                                <th class="px-5 py-3">Date</th>
                                <th class="px-5 py-3">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <?php if(empty($stats['recent_bookings'])): ?>
                                <tr><td colspan="5" class="px-5 py-8 text-center text-slate-500">No bookings yet.</td></tr>
                            <?php else: ?>
                                <?php foreach($stats['recent_bookings'] as $booking): ?>
                                    <tr>
                                        <td class="px-5 py-3 font-medium"><?php echo e($booking['booking_ref']); ?></td>
                                        <td class="px-5 py-3"><?php echo e($booking['full_name']); ?></td>
                                        <td class="px-5 py-3"><?php echo e($booking['event_title']); ?></td>
                                        <td class="px-5 py-3"><?php echo e($booking['event_date']); ?></td>
                                        <td class="px-5 py-3"><span class="rounded-full bg-slate-100 px-2.5 py-1 text-xs font-semibold capitalize"><?php echo e($booking['status']); ?></span></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>

        <section class="mt-6 grid gap-6 lg:grid-cols-2">
            <div class="rounded-lg bg-white p-5 shadow-sm ring-1 ring-slate-200">
                <h2 class="mb-4 text-lg font-semibold">Top Venue Locations</h2>
                <?php if(empty($stats['location_counts'])): ?>
                    <p class="text-sm text-slate-500">No location data yet.</p>
                <?php else: ?>
                    <div class="space-y-3">
                        <?php foreach($stats['location_counts'] as $row): ?>
                            <div class="flex items-center justify-between border-b border-slate-100 py-3 last:border-b-0">
                                <span class="text-slate-600"><?php echo e($row['location']); ?></span>
                                <span class="rounded-full bg-slate-100 px-3 py-1 text-sm font-semibold"><?php echo e($row['total']); ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="rounded-lg bg-white shadow-sm ring-1 ring-slate-200">
                <div class="border-b border-slate-200 px-5 py-4">
                    <h2 class="text-lg font-semibold">Popular Services</h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200 text-sm">
                        <thead class="bg-slate-50 text-left text-xs uppercase tracking-wide text-slate-500">
                            <tr>
                                <th class="px-5 py-3">Service</th>
                                <th class="px-5 py-3">Times Booked</th>
                                <th class="px-5 py-3">Service Revenue</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <?php if(empty($stats['popular_services'])): ?>
                                <tr><td colspan="3" class="px-5 py-8 text-center text-slate-500">No service usage yet.</td></tr>
                            <?php else: ?>
                                <?php foreach($stats['popular_services'] as $service): ?>
                                    <tr>
                                        <td class="px-5 py-3 font-medium"><?php echo e($service['name']); ?></td>
                                        <td class="px-5 py-3"><?php echo e($service['total']); ?></td>
                                        <td class="px-5 py-3">PHP <?php echo e(number_format((float)$service['revenue'], 2)); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </main>

    <script>
        const statusLabels = <?php echo json_encode(array_map('ucfirst', array_keys($stats['status_counts'] ?? [])), $chartJsonFlags); ?>;
        const statusValues = <?php echo json_encode(array_values($stats['status_counts'] ?? []), $chartJsonFlags); ?>;
        const dateLabels = <?php echo json_encode(array_column($dailyBookings, 'booking_date'), $chartJsonFlags); ?>;
        const dateValues = <?php echo json_encode(array_map('intval', array_column($dailyBookings, 'total')), $chartJsonFlags); ?>;
        const eventTypeLabels = <?php echo json_encode(array_column($stats['event_type_counts'] ?? [], 'name'), $chartJsonFlags); ?>;
        const eventTypeValues = <?php echo json_encode(array_map('intval', array_column($stats['event_type_counts'] ?? [], 'total')), $chartJsonFlags); ?>;

        const baseOptions = {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { precision: 0 }
                }
            }
        };

        new Chart(document.getElementById('statusBarChart'), {
            type: 'bar',
            data: {
                labels: statusLabels,
                datasets: [{
                    data: statusValues,
                    backgroundColor: ['#B89C94', '#6F8774', '#C87979', '#E6CFCB'],
                    borderRadius: 6
                }]
            },
            options: baseOptions
        });

        new Chart(document.getElementById('bookingDateLineChart'), {
            type: 'line',
            data: {
                labels: dateLabels,
                datasets: [{
                    data: dateValues,
                    borderColor: '#6F8774',
                    backgroundColor: 'rgba(111, 135, 116, 0.16)',
                    fill: true,
                    tension: 0.35,
                    pointRadius: 4
                }]
            },
            options: baseOptions
        });

        new Chart(document.getElementById('eventTypeBarChart'), {
            type: 'bar',
            data: {
                labels: eventTypeLabels,
                datasets: [{
                    data: eventTypeValues,
                    backgroundColor: '#6F8774',
                    borderRadius: 6
                }]
            },
            options: {
                ...baseOptions,
                indexAxis: 'y'
            }
        });
    </script>
</body>
</html>
