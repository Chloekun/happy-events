<?php
require_once __DIR__ . '/../../includes/functions.php';
if(!isset($total)) {
    $services_total = !empty($services) ? array_sum(array_column($services, 'price_at_booking')) : 0;
    $total = $services_total + ($bookingData['venue_fee'] ?? 0);
}
?>
<!doctype html>
<html lang="en">
<head>
    <?php tailwindHead(SITE_NAME . ' - Quotation'); ?>
</head>
<body class="min-h-screen bg-slate-100 text-slate-900">
    <?php renderNav('client'); ?>
    <main class="mx-auto max-w-4xl px-4 py-12 sm:px-6 lg:px-8">
        <section class="rounded-lg bg-white p-8 shadow-sm ring-1 ring-slate-200">
            <div class="flex flex-col justify-between gap-4 border-b border-slate-200 pb-6 sm:flex-row">
                <div>
                    <h1 class="text-3xl font-semibold">Quotation</h1>
                    <p class="mt-1 text-slate-600"><?php echo e($bookingData['event_title'] ?? ''); ?></p>
                </div>
                <div class="text-sm text-slate-500">Reference: <span class="font-semibold text-slate-900"><?php echo e($bookingData['booking_ref'] ?? ''); ?></span></div>
            </div>

            <div class="mt-6">
                <h2 class="font-semibold">Services</h2>
                <?php if(empty($services)): ?>
                    <p class="mt-3 text-slate-500">No services selected.</p>
                <?php else: ?>
                    <div class="mt-3 divide-y divide-slate-100 rounded-lg border border-slate-200">
                        <?php foreach($services as $s): ?>
                            <div class="flex justify-between px-4 py-3">
                                <span><?php echo e($s['name']); ?></span>
                                <span class="font-medium">PHP <?php echo e(number_format($s['price_at_booking'], 2)); ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="mt-6 rounded-lg bg-slate-950 p-5 text-white">
                <p class="flex justify-between text-slate-200"><span>Venue Fee</span><span>PHP <?php echo e(number_format($bookingData['venue_fee'] ?? 0, 2)); ?></span></p>
                <p class="mt-3 flex justify-between border-t border-white/10 pt-3 text-xl font-semibold"><span>Total</span><span>PHP <?php echo e(number_format($total, 2)); ?></span></p>
            </div>
        </section>
    </main>
</body>
</html>
