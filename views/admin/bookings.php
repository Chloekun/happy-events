<?php
require_once __DIR__ . '/../../includes/functions.php';

$filters = $filters ?? [];
$bookingPage = $bookingPage ?? [
    'total' => count($bookings ?? []),
    'page' => 1,
    'per_page' => 10,
    'total_pages' => 1,
    'sort' => 'created_at',
    'direction' => 'desc',
    'payment_status_supported' => false
];
$bookings = $bookings ?? [];
$eventTypes = $eventTypes ?? [];
$bookingStatuses = $bookingStatuses ?? ['pending', 'approved', 'rejected', 'completed'];
$paymentStatuses = $paymentStatuses ?? ['unpaid', 'partial', 'paid', 'refunded'];

$buildUrl = function($overrides = []) use ($filters, $bookingPage) {
    $params = array_merge([
        'controller' => 'admin',
        'action' => 'bookings',
        'search' => $filters['search'] ?? '',
        'status' => $filters['status'] ?? '',
        'payment_status' => $filters['payment_status'] ?? '',
        'event_type_id' => $filters['event_type_id'] ?? '',
        'date_from' => $filters['date_from'] ?? '',
        'date_to' => $filters['date_to'] ?? '',
        'per_page' => $bookingPage['per_page'] ?? 10,
        'sort' => $bookingPage['sort'] ?? 'created_at',
        'direction' => $bookingPage['direction'] ?? 'desc',
        'page' => $bookingPage['page'] ?? 1
    ], $overrides);

    foreach($params as $key => $value) {
        if($value === '' || $value === null) {
            unset($params[$key]);
        }
    }

    return 'index.php?' . http_build_query($params);
};

$sortLink = function($key, $label) use ($bookingPage, $buildUrl) {
    $isActive = ($bookingPage['sort'] ?? '') === $key;
    $nextDirection = $isActive && ($bookingPage['direction'] ?? 'desc') === 'asc' ? 'desc' : 'asc';
    $icon = 'fa-sort';
    if($isActive) {
        $icon = ($bookingPage['direction'] ?? 'desc') === 'asc' ? 'fa-sort-up' : 'fa-sort-down';
    }

    return '<a href="' . e($buildUrl(['sort' => $key, 'direction' => $nextDirection, 'page' => 1])) . '" data-sort="' . e($key) . '" class="sort-link inline-flex items-center gap-2 hover:text-sage">' .
           e($label) . ' <i class="fas ' . e($icon) . ' text-[0.7rem]" data-sort-icon="' . e($key) . '"></i></a>';
};

$statusClasses = [
    'pending' => 'bg-amber-100 text-amber-800',
    'approved' => 'bg-emerald-100 text-emerald-800',
    'rejected' => 'bg-red-100 text-red-800',
    'completed' => 'bg-blue-100 text-blue-800'
];
$paymentClasses = [
    'unpaid' => 'bg-red-50 text-red-700',
    'partial' => 'bg-amber-50 text-amber-700',
    'paid' => 'bg-emerald-50 text-emerald-700',
    'refunded' => 'bg-slate-100 text-slate-700',
    'not_tracked' => 'bg-slate-100 text-slate-600'
];

$page = (int)($bookingPage['page'] ?? 1);
$totalPages = (int)($bookingPage['total_pages'] ?? 1);
$perPage = (int)($bookingPage['per_page'] ?? 10);
$total = (int)($bookingPage['total'] ?? 0);
$start = $total > 0 ? (($page - 1) * $perPage) + 1 : 0;
$end = min($total, $page * $perPage);
?>
<!doctype html>
<html lang="en">
<head>
    <?php tailwindHead('Admin - Bookings'); ?>
</head>
<body class="min-h-screen bg-slate-100 text-slate-900">
    <?php renderNav('admin'); ?>

    <main class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        <div class="mb-6">
            <div>
                <p class="floral-label">Booking management</p>
                <h1 class="mt-2 text-4xl font-semibold">Bookings</h1>
                <p class="mt-1 text-sm text-slate-600">Live search, filter, sort, and manage event reservations.</p>
            </div>
        </div>

        <?php if($message = flash('success')): ?>
            <div class="mb-4 rounded-md bg-emerald-50 px-4 py-3 text-sm text-emerald-700"><?php echo e($message); ?></div>
        <?php endif; ?>

        <?php if($message = flash('error')): ?>
            <div class="mb-4 rounded-md bg-red-50 px-4 py-3 text-sm text-red-700"><?php echo e($message); ?></div>
        <?php endif; ?>

        <section class="mb-5 rounded-lg bg-white p-5 shadow-sm ring-1 ring-slate-200">
            <form id="bookingFilters" method="GET" action="index.php" class="grid gap-4 lg:grid-cols-12">
                <input type="hidden" name="controller" value="admin">
                <input type="hidden" name="action" value="bookings">
                <input id="sortInput" type="hidden" name="sort" value="<?php echo e($bookingPage['sort']); ?>">
                <input id="directionInput" type="hidden" name="direction" value="<?php echo e($bookingPage['direction']); ?>">
                <input id="pageInput" type="hidden" name="page" value="<?php echo e($page); ?>">

                <div class="lg:col-span-4">
                    <label class="mb-1 block text-xs font-semibold uppercase tracking-[0.14em] text-slate-500">Search</label>
                    <div class="relative">
                        <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-sm text-slate-400"></i>
                        <input id="searchInput" type="search" name="search" value="<?php echo e($filters['search'] ?? ''); ?>" placeholder="ID, customer, email, phone, or event type" class="w-full rounded-md border border-slate-300 py-2 pl-9 pr-3 text-sm outline-none focus:border-brand-500 focus:ring-2 focus:ring-brand-100">
                    </div>
                </div>

                <div class="lg:col-span-2">
                    <label class="mb-1 block text-xs font-semibold uppercase tracking-[0.14em] text-slate-500">Booking Status</label>
                    <select name="status" class="live-control w-full rounded-md border border-slate-300 px-3 py-2 text-sm outline-none focus:border-brand-500 focus:ring-2 focus:ring-brand-100">
                        <option value="">All statuses</option>
                        <?php foreach($bookingStatuses as $status): ?>
                            <option value="<?php echo e($status); ?>" <?php echo ($filters['status'] ?? '') === $status ? 'selected' : ''; ?>><?php echo e(ucfirst($status)); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="lg:col-span-2">
                    <label class="mb-1 block text-xs font-semibold uppercase tracking-[0.14em] text-slate-500">Payment Status</label>
                    <select name="payment_status" class="live-control w-full rounded-md border border-slate-300 px-3 py-2 text-sm outline-none focus:border-brand-500 focus:ring-2 focus:ring-brand-100" <?php echo empty($bookingPage['payment_status_supported']) ? 'disabled' : ''; ?>>
                        <?php if(empty($bookingPage['payment_status_supported'])): ?>
                            <option value="">Not tracked</option>
                        <?php else: ?>
                            <option value="">All payments</option>
                            <?php foreach($paymentStatuses as $paymentStatus): ?>
                                <option value="<?php echo e($paymentStatus); ?>" <?php echo ($filters['payment_status'] ?? '') === $paymentStatus ? 'selected' : ''; ?>><?php echo e(ucfirst($paymentStatus)); ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>

                <div class="lg:col-span-2">
                    <label class="mb-1 block text-xs font-semibold uppercase tracking-[0.14em] text-slate-500">Event Type</label>
                    <select name="event_type_id" class="live-control w-full rounded-md border border-slate-300 px-3 py-2 text-sm outline-none focus:border-brand-500 focus:ring-2 focus:ring-brand-100">
                        <option value="">All event types</option>
                        <?php foreach($eventTypes as $type): ?>
                            <option value="<?php echo e($type['id']); ?>" <?php echo (string)($filters['event_type_id'] ?? '') === (string)$type['id'] ? 'selected' : ''; ?>><?php echo e($type['name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="lg:col-span-2">
                    <label class="mb-1 block text-xs font-semibold uppercase tracking-[0.14em] text-slate-500">Rows</label>
                    <select name="per_page" class="live-control w-full rounded-md border border-slate-300 px-3 py-2 text-sm outline-none focus:border-brand-500 focus:ring-2 focus:ring-brand-100">
                        <?php foreach([10, 25, 50, 100] as $option): ?>
                            <option value="<?php echo e($option); ?>" <?php echo $perPage === $option ? 'selected' : ''; ?>><?php echo e($option); ?> rows</option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="lg:col-span-2">
                    <label class="mb-1 block text-xs font-semibold uppercase tracking-[0.14em] text-slate-500">From</label>
                    <input type="date" name="date_from" value="<?php echo e($filters['date_from'] ?? ''); ?>" class="live-control w-full rounded-md border border-slate-300 px-3 py-2 text-sm outline-none focus:border-brand-500 focus:ring-2 focus:ring-brand-100">
                </div>

                <div class="lg:col-span-2">
                    <label class="mb-1 block text-xs font-semibold uppercase tracking-[0.14em] text-slate-500">To</label>
                    <input type="date" name="date_to" value="<?php echo e($filters['date_to'] ?? ''); ?>" class="live-control w-full rounded-md border border-slate-300 px-3 py-2 text-sm outline-none focus:border-brand-500 focus:ring-2 focus:ring-brand-100">
                </div>

                <div class="flex items-end gap-2 lg:col-span-8">
                    <a id="resetFilters" href="index.php?controller=admin&action=bookings" class="rounded-md border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50">Reset</a>
                    <span class="hidden items-center gap-2 text-sm text-slate-500 sm:inline-flex">
                        <i class="fas fa-bolt text-brand-500"></i>
                        Updates automatically
                    </span>
                </div>
            </form>
        </section>

        <section class="overflow-hidden rounded-lg bg-white shadow-sm ring-1 ring-slate-200">
            <div class="flex flex-col gap-3 border-b border-slate-200 px-5 py-4 sm:flex-row sm:items-center sm:justify-between">
                <p id="resultSummary" class="text-sm text-slate-600">
                    Showing <span class="font-semibold text-slate-900"><?php echo e($start); ?></span> to <span class="font-semibold text-slate-900"><?php echo e($end); ?></span> of <span class="font-semibold text-slate-900"><?php echo e($total); ?></span> bookings
                </p>
                <p id="pageSummary" class="text-xs font-semibold uppercase tracking-[0.14em] text-slate-500">Page <?php echo e($page); ?> of <?php echo e($totalPages); ?></p>
            </div>

            <div class="relative">
                <div id="tableLoading" class="pointer-events-none absolute inset-0 z-20 hidden items-start justify-center bg-white/70 pt-20 backdrop-blur-sm">
                    <div class="flex items-center gap-3 rounded-full bg-white px-5 py-3 text-sm font-semibold text-slate-700 shadow-sm ring-1 ring-slate-200">
                        <span class="h-4 w-4 animate-spin rounded-full border-2 border-brand-100 border-t-brand-700"></span>
                        Loading bookings
                    </div>
                </div>

                <div class="max-h-[68vh] overflow-auto">
                    <table class="min-w-[1180px] divide-y divide-slate-200 text-sm">
                        <thead class="sticky top-0 z-10 bg-slate-50 text-left text-xs uppercase tracking-wide text-slate-500 shadow-sm">
                            <tr>
                                <th class="px-5 py-3"><?php echo $sortLink('booking_id', 'Booking ID'); ?></th>
                                <th class="px-5 py-3"><?php echo $sortLink('customer_name', 'Customer'); ?></th>
                                <th class="px-5 py-3">Email</th>
                                <th class="px-5 py-3">Event</th>
                                <th class="px-5 py-3"><?php echo $sortLink('event_type', 'Event Type'); ?></th>
                                <th class="px-5 py-3"><?php echo $sortLink('event_date', 'Booking Date'); ?></th>
                                <th class="px-5 py-3"><?php echo $sortLink('status', 'Status'); ?></th>
                                <th class="px-5 py-3"><?php echo $sortLink('payment_status', 'Payment'); ?></th>
                                <th class="px-5 py-3"><?php echo $sortLink('created_at', 'Created'); ?></th>
                                <th class="px-5 py-3 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="bookingTableBody" class="divide-y divide-slate-100">
                            <?php if(empty($bookings)): ?>
                                <tr>
                                    <td colspan="10" class="px-5 py-14 text-center">
                                        <div class="mx-auto max-w-sm">
                                            <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-brand-50 text-brand-700">
                                                <i class="fas fa-calendar-xmark"></i>
                                            </div>
                                            <h2 class="mt-4 text-2xl font-semibold">No bookings found</h2>
                                            <p class="mt-1 text-sm text-slate-600">Try adjusting your search or filter settings.</p>
                                        </div>
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach($bookings as $b): ?>
                                    <?php
                                        $status = $b['status'] ?? 'pending';
                                        $paymentStatus = $b['payment_status'] ?? 'not_tracked';
                                        $badgeClass = $statusClasses[$status] ?? 'bg-slate-100 text-slate-800';
                                        $paymentClass = $paymentClasses[$paymentStatus] ?? 'bg-slate-100 text-slate-700';
                                    ?>
                                    <tr class="transition hover:bg-brand-50/40">
                                        <td class="px-5 py-4 align-top">
                                            <div class="font-semibold text-slate-900"><?php echo e($b['booking_ref']); ?></div>
                                            <div class="text-xs text-slate-500">#<?php echo e($b['id']); ?></div>
                                        </td>
                                        <td class="px-5 py-4 align-top">
                                            <div class="font-semibold text-slate-900"><?php echo e($b['full_name']); ?></div>
                                            <div class="text-xs text-slate-500"><?php echo e($b['contact_number'] ?? ''); ?></div>
                                        </td>
                                        <td class="px-5 py-4 align-top text-slate-600"><?php echo e($b['email']); ?></td>
                                        <td class="px-5 py-4 align-top">
                                            <div class="max-w-[220px] font-medium text-slate-900"><?php echo e($b['event_title']); ?></div>
                                            <div class="text-xs text-slate-500"><?php echo e($b['venue_location'] ?? ''); ?></div>
                                        </td>
                                        <td class="px-5 py-4 align-top text-slate-600"><?php echo e($b['event_type_name']); ?></td>
                                        <td class="px-5 py-4 align-top">
                                            <div class="font-medium text-slate-900"><?php echo e($b['event_date']); ?></div>
                                            <div class="text-xs text-slate-500"><?php echo e($b['event_time'] ?? ''); ?></div>
                                        </td>
                                        <td class="px-5 py-4 align-top">
                                            <span class="rounded-full px-2.5 py-1 text-xs font-semibold capitalize <?php echo e($badgeClass); ?>"><?php echo e($status); ?></span>
                                        </td>
                                        <td class="px-5 py-4 align-top">
                                            <span class="rounded-full px-2.5 py-1 text-xs font-semibold capitalize <?php echo e($paymentClass); ?>"><?php echo e(str_replace('_', ' ', $paymentStatus)); ?></span>
                                        </td>
                                        <td class="px-5 py-4 align-top text-slate-600"><?php echo e(date('M d, Y', strtotime($b['created_at']))); ?></td>
                                        <td class="px-5 py-4 align-top">
                                            <div class="flex flex-wrap justify-end gap-2">
                                                <a href="index.php?controller=admin&action=viewBooking&id=<?php echo e($b['id']); ?>" class="inline-flex items-center gap-1 rounded-md bg-slate-800 px-3 py-1.5 text-xs font-semibold text-white hover:bg-slate-700">
                                                    <i class="fas fa-eye"></i> View
                                                </a>
                                                <?php if($b['status'] === 'pending'): ?>
                                                    <form method="POST" action="index.php?controller=admin&action=updateBookingStatus&id=<?php echo e($b['id']); ?>">
                                                        <input type="hidden" name="status" value="approved">
                                                        <button type="submit" class="inline-flex items-center gap-1 rounded-md bg-emerald-600 px-3 py-1.5 text-xs font-semibold text-white hover:bg-emerald-700">
                                                            <i class="fas fa-check"></i> Accept
                                                        </button>
                                                    </form>
                                                    <form method="POST" action="index.php?controller=admin&action=updateBookingStatus&id=<?php echo e($b['id']); ?>" onsubmit="return confirm('Decline this booking?');">
                                                        <input type="hidden" name="status" value="rejected">
                                                        <button type="submit" class="inline-flex items-center gap-1 rounded-md bg-red-600 px-3 py-1.5 text-xs font-semibold text-white hover:bg-red-700">
                                                            <i class="fas fa-xmark"></i> Decline
                                                        </button>
                                                    </form>
                                                <?php endif; ?>
                                                <form method="POST" action="index.php?controller=admin&action=deleteBooking&id=<?php echo e($b['id']); ?>" onsubmit="return confirm('Delete this booking permanently?');">
                                                    <button type="submit" class="inline-flex items-center gap-1 rounded-md border border-red-200 px-3 py-1.5 text-xs font-semibold text-red-700 hover:bg-red-50">
                                                        <i class="fas fa-trash"></i> Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div id="paginationControls" class="flex flex-col gap-3 border-t border-slate-200 px-5 py-4 sm:flex-row sm:items-center sm:justify-between">
                <a href="<?php echo e($buildUrl(['page' => max(1, $page - 1)])); ?>" data-page="<?php echo e(max(1, $page - 1)); ?>" class="page-link inline-flex items-center justify-center gap-2 rounded-md border border-slate-200 px-4 py-2 text-sm font-semibold <?php echo $page <= 1 ? 'pointer-events-none opacity-50' : 'hover:bg-slate-50'; ?>">
                    <i class="fas fa-chevron-left"></i> Previous
                </a>

                <div class="flex flex-wrap justify-center gap-2">
                    <?php
                        $firstPage = max(1, $page - 2);
                        $lastPage = min($totalPages, $page + 2);
                        for($i = $firstPage; $i <= $lastPage; $i++):
                    ?>
                        <a href="<?php echo e($buildUrl(['page' => $i])); ?>" data-page="<?php echo e($i); ?>" class="page-link inline-flex h-10 min-w-10 items-center justify-center rounded-md px-3 text-sm font-semibold <?php echo $i === $page ? 'bg-brand-500 text-white' : 'border border-slate-200 text-slate-700 hover:bg-slate-50'; ?>">
                            <?php echo e($i); ?>
                        </a>
                    <?php endfor; ?>
                </div>

                <a href="<?php echo e($buildUrl(['page' => min($totalPages, $page + 1)])); ?>" data-page="<?php echo e(min($totalPages, $page + 1)); ?>" class="page-link inline-flex items-center justify-center gap-2 rounded-md border border-slate-200 px-4 py-2 text-sm font-semibold <?php echo $page >= $totalPages ? 'pointer-events-none opacity-50' : 'hover:bg-slate-50'; ?>">
                    Next <i class="fas fa-chevron-right"></i>
                </a>
            </div>
        </section>
    </main>

    <script>
        const bookingState = {
            page: <?php echo (int)$page; ?>,
            per_page: <?php echo (int)$perPage; ?>,
            sort: <?php echo json_encode($bookingPage['sort']); ?>,
            direction: <?php echo json_encode($bookingPage['direction']); ?>
        };
        const form = document.getElementById('bookingFilters');
        const tbody = document.getElementById('bookingTableBody');
        const loading = document.getElementById('tableLoading');
        const resultSummary = document.getElementById('resultSummary');
        const pageSummary = document.getElementById('pageSummary');
        const pagination = document.getElementById('paginationControls');
        const sortInput = document.getElementById('sortInput');
        const directionInput = document.getElementById('directionInput');
        const pageInput = document.getElementById('pageInput');
        let debounceTimer = null;
        let activeRequest = null;

        const statusClasses = {
            pending: 'bg-amber-100 text-amber-800',
            approved: 'bg-emerald-100 text-emerald-800',
            rejected: 'bg-red-100 text-red-800',
            completed: 'bg-blue-100 text-blue-800'
        };
        const paymentClasses = {
            unpaid: 'bg-red-50 text-red-700',
            partial: 'bg-amber-50 text-amber-700',
            paid: 'bg-emerald-50 text-emerald-700',
            refunded: 'bg-slate-100 text-slate-700',
            not_tracked: 'bg-slate-100 text-slate-600'
        };

        function escapeHtml(value) {
            return String(value ?? '').replace(/[&<>"']/g, function(char) {
                return ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;' })[char];
            });
        }

        function titleText(value) {
            return String(value ?? '').replace(/_/g, ' ').replace(/\b\w/g, match => match.toUpperCase());
        }

        function formatDate(value) {
            if(!value) return '';
            const date = new Date(String(value).replace(' ', 'T'));
            if(Number.isNaN(date.getTime())) return escapeHtml(value);
            return date.toLocaleDateString(undefined, { month: 'short', day: '2-digit', year: 'numeric' });
        }

        function paramsFor(action = 'bookingsData') {
            const data = new FormData(form);
            data.set('controller', 'admin');
            data.set('action', action);
            data.set('sort', bookingState.sort);
            data.set('direction', bookingState.direction);
            data.set('page', bookingState.page);
            return new URLSearchParams(data);
        }

        function scheduleLoad(resetPage = true) {
            if(resetPage) bookingState.page = 1;
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(loadBookings, 380);
        }

        async function loadBookings() {
            sortInput.value = bookingState.sort;
            directionInput.value = bookingState.direction;
            pageInput.value = bookingState.page;

            if(activeRequest) activeRequest.abort();
            activeRequest = new AbortController();
            loading.classList.remove('hidden');
            loading.classList.add('flex');

            try {
                const response = await fetch('index.php?' + paramsFor('bookingsData').toString(), {
                    headers: { 'Accept': 'application/json' },
                    signal: activeRequest.signal
                });
                if(!response.ok) throw new Error('Request failed');
                const payload = await response.json();
                renderRows(payload.bookings || []);
                renderMeta(payload.meta);
                renderPagination(payload.meta);
                updateSortIcons(payload.meta);
                history.replaceState(null, '', 'index.php?' + paramsFor('bookings').toString());
            } catch(error) {
                if(error.name !== 'AbortError') {
                    tbody.innerHTML = emptyState('Unable to load bookings', 'Please refresh the page or try again.');
                }
            } finally {
                loading.classList.add('hidden');
                loading.classList.remove('flex');
            }
        }

        function renderRows(bookings) {
            if(bookings.length === 0) {
                tbody.innerHTML = emptyState('No bookings found', 'Try adjusting your search or filter settings.');
                return;
            }

            tbody.innerHTML = bookings.map(rowHtml).join('');
        }

        function rowHtml(b) {
            const status = b.status || 'pending';
            const paymentStatus = b.payment_status || 'not_tracked';
            const statusClass = statusClasses[status] || 'bg-slate-100 text-slate-800';
            const paymentClass = paymentClasses[paymentStatus] || 'bg-slate-100 text-slate-700';
            const pendingActions = status === 'pending' ? `
                <form method="POST" action="index.php?controller=admin&action=updateBookingStatus&id=${encodeURIComponent(b.id)}">
                    <input type="hidden" name="status" value="approved">
                    <button type="submit" class="inline-flex items-center gap-1 rounded-md bg-emerald-600 px-3 py-1.5 text-xs font-semibold text-white hover:bg-emerald-700">
                        <i class="fas fa-check"></i> Accept
                    </button>
                </form>
                <form method="POST" action="index.php?controller=admin&action=updateBookingStatus&id=${encodeURIComponent(b.id)}" onsubmit="return confirm('Decline this booking?');">
                    <input type="hidden" name="status" value="rejected">
                    <button type="submit" class="inline-flex items-center gap-1 rounded-md bg-red-600 px-3 py-1.5 text-xs font-semibold text-white hover:bg-red-700">
                        <i class="fas fa-xmark"></i> Decline
                    </button>
                </form>` : '';

            return `
                <tr class="transition hover:bg-brand-50/40">
                    <td class="px-5 py-4 align-top">
                        <div class="font-semibold text-slate-900">${escapeHtml(b.booking_ref)}</div>
                        <div class="text-xs text-slate-500">#${escapeHtml(b.id)}</div>
                    </td>
                    <td class="px-5 py-4 align-top">
                        <div class="font-semibold text-slate-900">${escapeHtml(b.full_name)}</div>
                        <div class="text-xs text-slate-500">${escapeHtml(b.contact_number)}</div>
                    </td>
                    <td class="px-5 py-4 align-top text-slate-600">${escapeHtml(b.email)}</td>
                    <td class="px-5 py-4 align-top">
                        <div class="max-w-[220px] font-medium text-slate-900">${escapeHtml(b.event_title)}</div>
                        <div class="text-xs text-slate-500">${escapeHtml(b.venue_location)}</div>
                    </td>
                    <td class="px-5 py-4 align-top text-slate-600">${escapeHtml(b.event_type_name)}</td>
                    <td class="px-5 py-4 align-top">
                        <div class="font-medium text-slate-900">${escapeHtml(b.event_date)}</div>
                        <div class="text-xs text-slate-500">${escapeHtml(b.event_time)}</div>
                    </td>
                    <td class="px-5 py-4 align-top">
                        <span class="rounded-full px-2.5 py-1 text-xs font-semibold capitalize ${statusClass}">${escapeHtml(status)}</span>
                    </td>
                    <td class="px-5 py-4 align-top">
                        <span class="rounded-full px-2.5 py-1 text-xs font-semibold capitalize ${paymentClass}">${escapeHtml(titleText(paymentStatus))}</span>
                    </td>
                    <td class="px-5 py-4 align-top text-slate-600">${formatDate(b.created_at)}</td>
                    <td class="px-5 py-4 align-top">
                        <div class="flex flex-wrap justify-end gap-2">
                            <a href="index.php?controller=admin&action=viewBooking&id=${encodeURIComponent(b.id)}" class="inline-flex items-center gap-1 rounded-md bg-slate-800 px-3 py-1.5 text-xs font-semibold text-white hover:bg-slate-700">
                                <i class="fas fa-eye"></i> View
                            </a>
                            ${pendingActions}
                            <form method="POST" action="index.php?controller=admin&action=deleteBooking&id=${encodeURIComponent(b.id)}" onsubmit="return confirm('Delete this booking permanently?');">
                                <button type="submit" class="inline-flex items-center gap-1 rounded-md border border-red-200 px-3 py-1.5 text-xs font-semibold text-red-700 hover:bg-red-50">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>`;
        }

        function emptyState(title, message) {
            return `
                <tr>
                    <td colspan="10" class="px-5 py-14 text-center">
                        <div class="mx-auto max-w-sm">
                            <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-brand-50 text-brand-700">
                                <i class="fas fa-calendar-xmark"></i>
                            </div>
                            <h2 class="mt-4 text-2xl font-semibold">${escapeHtml(title)}</h2>
                            <p class="mt-1 text-sm text-slate-600">${escapeHtml(message)}</p>
                        </div>
                    </td>
                </tr>`;
        }

        function renderMeta(meta) {
            bookingState.page = Number(meta.page || 1);
            bookingState.per_page = Number(meta.per_page || 10);
            bookingState.sort = meta.sort || bookingState.sort;
            bookingState.direction = meta.direction || bookingState.direction;
            const total = Number(meta.total || 0);
            const start = total > 0 ? ((bookingState.page - 1) * bookingState.per_page) + 1 : 0;
            const end = Math.min(total, bookingState.page * bookingState.per_page);
            resultSummary.innerHTML = `Showing <span class="font-semibold text-slate-900">${start}</span> to <span class="font-semibold text-slate-900">${end}</span> of <span class="font-semibold text-slate-900">${total}</span> bookings`;
            pageSummary.textContent = `Page ${bookingState.page} of ${meta.total_pages || 1}`;
        }

        function renderPagination(meta) {
            const totalPages = Number(meta.total_pages || 1);
            const page = Number(meta.page || 1);
            const firstPage = Math.max(1, page - 2);
            const lastPage = Math.min(totalPages, page + 2);
            let pages = '';

            for(let i = firstPage; i <= lastPage; i++) {
                pages += `<a href="#" data-page="${i}" class="page-link inline-flex h-10 min-w-10 items-center justify-center rounded-md px-3 text-sm font-semibold ${i === page ? 'bg-brand-500 text-white' : 'border border-slate-200 text-slate-700 hover:bg-slate-50'}">${i}</a>`;
            }

            pagination.innerHTML = `
                <a href="#" data-page="${Math.max(1, page - 1)}" class="page-link inline-flex items-center justify-center gap-2 rounded-md border border-slate-200 px-4 py-2 text-sm font-semibold ${page <= 1 ? 'pointer-events-none opacity-50' : 'hover:bg-slate-50'}">
                    <i class="fas fa-chevron-left"></i> Previous
                </a>
                <div class="flex flex-wrap justify-center gap-2">${pages}</div>
                <a href="#" data-page="${Math.min(totalPages, page + 1)}" class="page-link inline-flex items-center justify-center gap-2 rounded-md border border-slate-200 px-4 py-2 text-sm font-semibold ${page >= totalPages ? 'pointer-events-none opacity-50' : 'hover:bg-slate-50'}">
                    Next <i class="fas fa-chevron-right"></i>
                </a>`;
        }

        function updateSortIcons(meta) {
            document.querySelectorAll('[data-sort-icon]').forEach(icon => {
                icon.classList.remove('fa-sort-up', 'fa-sort-down');
                icon.classList.add('fa-sort');
            });
            const activeIcon = document.querySelector(`[data-sort-icon="${meta.sort}"]`);
            if(activeIcon) {
                activeIcon.classList.remove('fa-sort');
                activeIcon.classList.add(meta.direction === 'asc' ? 'fa-sort-up' : 'fa-sort-down');
            }
        }

        document.getElementById('searchInput').addEventListener('input', () => scheduleLoad(true));
        document.querySelectorAll('.live-control').forEach(control => {
            control.addEventListener('change', () => scheduleLoad(true));
        });

        form.addEventListener('submit', event => {
            event.preventDefault();
            scheduleLoad(true);
        });

        document.addEventListener('click', event => {
            const sortLink = event.target.closest('.sort-link');
            if(sortLink) {
                event.preventDefault();
                const sort = sortLink.dataset.sort;
                bookingState.direction = bookingState.sort === sort && bookingState.direction === 'asc' ? 'desc' : 'asc';
                bookingState.sort = sort;
                scheduleLoad(true);
                return;
            }

            const pageLink = event.target.closest('.page-link');
            if(pageLink) {
                event.preventDefault();
                bookingState.page = Number(pageLink.dataset.page || 1);
                scheduleLoad(false);
            }
        });

        document.getElementById('resetFilters').addEventListener('click', event => {
            event.preventDefault();
            form.querySelector('[name="search"]').value = '';
            form.querySelector('[name="status"]').value = '';
            if(form.querySelector('[name="payment_status"]:not(:disabled)')) {
                form.querySelector('[name="payment_status"]').value = '';
            }
            form.querySelector('[name="event_type_id"]').value = '';
            form.querySelector('[name="date_from"]').value = '';
            form.querySelector('[name="date_to"]').value = '';
            form.querySelector('[name="per_page"]').value = '10';
            bookingState.page = 1;
            bookingState.per_page = 10;
            bookingState.sort = 'created_at';
            bookingState.direction = 'desc';
            scheduleLoad(false);
        });
    </script>
</body>
</html>
