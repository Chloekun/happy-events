<?php require_once __DIR__ . '/../../includes/functions.php'; ?>
<!doctype html>
<html lang="en">
<head>
    <?php tailwindHead('Admin - View Service'); ?>
</head>
<body class="min-h-screen bg-slate-100 text-slate-900">
    <?php renderNav('admin'); ?>

    <main class="mx-auto max-w-3xl px-4 py-8 sm:px-6 lg:px-8">
        <div class="mb-6 flex items-center justify-between gap-4">
            <h1 class="text-3xl font-semibold">Service Details</h1>
            <a href="index.php?controller=admin&action=services" class="rounded-md bg-slate-200 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-300">Back</a>
        </div>

        <section class="rounded-lg bg-white p-6 shadow-sm ring-1 ring-slate-200">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <h2 class="text-2xl font-semibold"><?php echo e($service['name']); ?></h2>
                    <p class="mt-1 text-sm text-slate-500"><?php echo e($service['category'] ?: 'Uncategorized'); ?></p>
                </div>
                <span class="rounded-full px-3 py-1 text-sm font-semibold <?php echo !empty($service['is_active']) ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-600'; ?>">
                    <?php echo !empty($service['is_active']) ? 'Active' : 'Inactive'; ?>
                </span>
            </div>

            <dl class="mt-6 grid gap-5">
                <div>
                    <dt class="text-sm font-medium text-slate-500">Price</dt>
                    <dd class="mt-1 text-xl font-bold text-brand-700">PHP <?php echo e(number_format((float)$service['price'], 2)); ?></dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-slate-500">Description</dt>
                    <dd class="mt-1 whitespace-pre-line text-slate-700"><?php echo e($service['description'] ?: 'No description.'); ?></dd>
                </div>
            </dl>

            <div class="mt-6 flex flex-wrap gap-2">
                <a href="index.php?controller=admin&action=editService&id=<?php echo e($service['id']); ?>" class="rounded-md bg-slate-800 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-700">Edit</a>
                <form method="POST" action="index.php?controller=admin&action=deleteService&id=<?php echo e($service['id']); ?>" onsubmit="return confirm('Delete this service? Existing booking service links will also be removed.');">
                    <button type="submit" class="rounded-md border border-red-200 px-4 py-2 text-sm font-semibold text-red-700 hover:bg-red-50">Delete</button>
                </form>
            </div>
        </section>
    </main>
</body>
</html>
