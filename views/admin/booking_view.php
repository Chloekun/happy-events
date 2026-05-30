<?php require_once __DIR__ . '/../../includes/functions.php'; ?>
<!doctype html>
<html lang="en">
<head>
    <?php tailwindHead('Admin - View Booking'); ?>
</head>
<body class="min-h-screen bg-slate-100 text-slate-900">
    <?php renderNav('admin'); ?>

    <main class="mx-auto max-w-6xl px-4 py-8 sm:px-6 lg:px-8">
        <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="floral-label">Booking details</p>
                <h1 class="mt-2 text-4xl font-semibold"><?php echo e($bookingData['booking_ref']); ?></h1>
                <p class="mt-1 text-sm text-slate-600"><?php echo e($bookingData['event_title']); ?></p>
            </div>
            <a href="index.php?controller=admin&action=bookings" class="inline-flex items-center justify-center gap-2 rounded-md border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>

        <div class="grid gap-6 lg:grid-cols-3">
            <section class="rounded-lg bg-white p-6 shadow-sm ring-1 ring-slate-200 lg:col-span-2">
                <h2 class="mb-5 text-2xl font-semibold">Event Information</h2>
                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.14em] text-slate-500">Event Type</p>
                        <p class="mt-1 font-medium"><?php echo e($bookingData['event_type_name']); ?></p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.14em] text-slate-500">Status</p>
                        <p class="mt-1 capitalize"><?php echo e($bookingData['status']); ?></p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.14em] text-slate-500">Date</p>
                        <p class="mt-1 font-medium"><?php echo e($bookingData['event_date']); ?></p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.14em] text-slate-500">Time</p>
                        <p class="mt-1 font-medium"><?php echo e($bookingData['event_time']); ?></p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.14em] text-slate-500">Guests</p>
                        <p class="mt-1 font-medium"><?php echo e($bookingData['number_of_guests'] ?? 'Not specified'); ?></p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.14em] text-slate-500">Budget Range</p>
                        <p class="mt-1 font-medium"><?php echo e($bookingData['budget_range'] ?? 'Not specified'); ?></p>
                    </div>
                    <div class="sm:col-span-2">
                        <p class="text-xs font-semibold uppercase tracking-[0.14em] text-slate-500">Venue</p>
                        <p class="mt-1 font-medium"><?php echo e($bookingData['venue_location']); ?></p>
                        <p class="mt-1 text-sm text-slate-600"><?php echo e($bookingData['venue_address']); ?></p>
                    </div>
                    <div class="sm:col-span-2">
                        <p class="text-xs font-semibold uppercase tracking-[0.14em] text-slate-500">Additional Notes</p>
                        <p class="mt-1 text-sm text-slate-600"><?php echo e($bookingData['additional_notes'] ?: 'No additional notes.'); ?></p>
                    </div>
                    <div class="sm:col-span-2">
                        <p class="text-xs font-semibold uppercase tracking-[0.14em] text-slate-500">Admin Remarks</p>
                        <p class="mt-1 text-sm text-slate-600"><?php echo e($bookingData['admin_remarks'] ?: 'No admin remarks.'); ?></p>
                    </div>
                </div>
            </section>

            <aside class="space-y-6">
                <section class="rounded-lg bg-white p-6 shadow-sm ring-1 ring-slate-200">
                    <h2 class="mb-4 text-2xl font-semibold">Customer</h2>
                    <div class="space-y-3 text-sm">
                        <p><span class="font-semibold">Name:</span> <?php echo e($bookingData['full_name']); ?></p>
                        <p><span class="font-semibold">Email:</span> <?php echo e($bookingData['email']); ?></p>
                        <p><span class="font-semibold">Phone:</span> <?php echo e($bookingData['contact_number']); ?></p>
                        <p><span class="font-semibold">Address:</span> <?php echo e($bookingData['address']); ?></p>
                    </div>
                </section>

                <section class="rounded-lg bg-white p-6 shadow-sm ring-1 ring-slate-200">
                    <h2 class="mb-4 text-2xl font-semibold">Services</h2>
                    <?php if(empty($bookingServices)): ?>
                        <p class="text-sm text-slate-600">No services selected.</p>
                    <?php else: ?>
                        <div class="space-y-3">
                            <?php foreach($bookingServices as $service): ?>
                                <div class="flex items-start justify-between gap-3 border-b border-slate-100 pb-3 last:border-b-0 last:pb-0">
                                    <div>
                                        <p class="font-medium"><?php echo e($service['name']); ?></p>
                                        <p class="text-xs text-slate-500"><?php echo e($service['category'] ?? ''); ?></p>
                                    </div>
                                    <p class="whitespace-nowrap text-sm font-semibold">PHP <?php echo e(number_format((float)$service['price_at_booking'], 2)); ?></p>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </section>

                <section class="rounded-lg bg-slate-950 p-6 text-white shadow-sm">
                    <h2 class="mb-4 text-2xl font-semibold text-white">Summary</h2>
                    <div class="space-y-2 text-sm text-white/85">
                        <p class="flex justify-between gap-3"><span>Services Total</span><span>PHP <?php echo e(number_format((float)($bookingData['total_amount'] ?? 0), 2)); ?></span></p>
                        <p class="flex justify-between gap-3"><span>Venue Fee</span><span>PHP <?php echo e(number_format((float)($bookingData['venue_fee'] ?? 0), 2)); ?></span></p>
                        <p class="flex justify-between gap-3 border-t border-white/15 pt-3 text-base font-semibold text-white">
                            <span>Estimated Total</span>
                            <span>PHP <?php echo e(number_format((float)($bookingData['total_amount'] ?? 0) + (float)($bookingData['venue_fee'] ?? 0), 2)); ?></span>
                        </p>
                    </div>
                </section>
            </aside>
        </div>
    </main>
</body>
</html>
