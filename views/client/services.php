<?php require_once __DIR__ . '/../../includes/functions.php'; ?>
<!doctype html>
<html lang="en">
<head>
    <?php tailwindHead(SITE_NAME . ' - Services'); ?>
</head>
<body class="min-h-screen bg-slate-100 text-slate-900">
    <?php renderNav('client'); ?>
    <main class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        <h1 class="mb-6 text-3xl font-semibold">Services</h1>
        <?php if(empty($services)): ?>
            <div class="rounded-lg bg-white p-8 text-center text-slate-500 shadow-sm ring-1 ring-slate-200">No services available.</div>
        <?php else: ?>
            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                <?php foreach($services as $s): ?>
                    <div class="rounded-lg bg-white p-5 shadow-sm ring-1 ring-slate-200">
                        <h2 class="font-semibold"><?php echo e($s['name']); ?></h2>
                        <p class="mt-2 text-sm text-slate-600"><?php echo e($s['description'] ?? ''); ?></p>
                        <p class="mt-4 font-bold text-brand-700">PHP <?php echo e(number_format($s['price'], 2)); ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </main>
</body>
</html>
