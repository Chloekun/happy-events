<?php require_once __DIR__ . '/../../includes/functions.php'; ?>
<!doctype html>
<html lang="en">
<head>
    <?php tailwindHead('Admin - Services'); ?>
</head>
<body class="min-h-screen bg-slate-100 text-slate-900">
    <?php renderNav('admin'); ?>

    <main class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        <div class="mb-6 flex items-center justify-between gap-4">
            <h1 class="text-3xl font-semibold">Services</h1>
            <a href="index.php?controller=admin&action=createService" class="rounded-md bg-brand-500 px-4 py-2 text-sm font-semibold text-white hover:bg-brand-600">
                Add Service
            </a>
        </div>

        <?php if($message = flash('success')): ?>
            <div class="mb-4 rounded-md bg-emerald-50 px-4 py-3 text-sm text-emerald-700"><?php echo e($message); ?></div>
        <?php endif; ?>

        <?php if($message = flash('error')): ?>
            <div class="mb-4 rounded-md bg-red-50 px-4 py-3 text-sm text-red-700"><?php echo e($message); ?></div>
        <?php endif; ?>

        <?php if(empty($services)): ?>
            <div class="rounded-lg bg-white p-8 text-center text-slate-500 shadow-sm ring-1 ring-slate-200">No services.</div>
        <?php else: ?>
            <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
                <?php foreach($services as $s): ?>
                    <div class="rounded-lg bg-white p-5 shadow-sm ring-1 ring-slate-200">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <h2 class="font-semibold"><?php echo e($s['name']); ?></h2>
                                <p class="mt-1 text-sm text-slate-500"><?php echo e($s['category'] ?: 'Uncategorized'); ?></p>
                            </div>
                            <span class="rounded-full px-2.5 py-1 text-xs font-semibold <?php echo !empty($s['is_active']) ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-600'; ?>">
                                <?php echo !empty($s['is_active']) ? 'Active' : 'Inactive'; ?>
                            </span>
                        </div>
                        <p class="mt-3 text-lg font-bold text-brand-700">PHP <?php echo e(number_format($s['price'], 2)); ?></p>
                        <p class="mt-2 line-clamp-2 text-sm text-slate-600"><?php echo e($s['description'] ?? ''); ?></p>
                        <div class="mt-4 flex flex-wrap gap-2">
                            <a href="index.php?controller=admin&action=viewService&id=<?php echo e($s['id']); ?>" class="rounded-md border border-slate-200 px-3 py-1.5 text-xs font-semibold text-slate-700 hover:bg-slate-50">View</a>
                            <a href="index.php?controller=admin&action=editService&id=<?php echo e($s['id']); ?>" class="rounded-md bg-slate-800 px-3 py-1.5 text-xs font-semibold text-white hover:bg-slate-700">Edit</a>
                            <form method="POST" action="index.php?controller=admin&action=deleteService&id=<?php echo e($s['id']); ?>" onsubmit="return confirm('Delete this service? Existing booking service links will also be removed.');">
                                <button type="submit" class="rounded-md border border-red-200 px-3 py-1.5 text-xs font-semibold text-red-700 hover:bg-red-50">Delete</button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </main>
</body>
</html>
