<?php require_once __DIR__ . '/../../includes/functions.php'; ?>
<!doctype html>
<html lang="en">
<head>
    <?php tailwindHead('Admin - ' . $formTitle); ?>
</head>
<body class="min-h-screen bg-slate-100 text-slate-900">
    <?php renderNav('admin'); ?>

    <main class="mx-auto max-w-2xl px-4 py-8 sm:px-6 lg:px-8">
        <div class="mb-6 flex items-center justify-between gap-4">
            <h1 class="text-3xl font-semibold"><?php echo e($formTitle); ?></h1>
            <a href="index.php?controller=admin&action=services" class="rounded-md bg-slate-200 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-300">Back</a>
        </div>

        <?php if($message = flash('error')): ?>
            <div class="mb-4 rounded-md bg-red-50 px-4 py-3 text-sm text-red-700"><?php echo e($message); ?></div>
        <?php endif; ?>

        <form method="POST" action="<?php echo e($formAction); ?>" class="rounded-lg bg-white p-6 shadow-sm ring-1 ring-slate-200">
            <div class="grid gap-4">
                <div>
                    <label class="mb-1 block text-sm font-medium">Service Name</label>
                    <input type="text" name="name" value="<?php echo e($serviceData['name'] ?? ''); ?>" class="w-full rounded-md border border-slate-300 px-3 py-2 outline-none focus:border-brand-500 focus:ring-2 focus:ring-brand-100" required>
                </div>

                <div>
                    <label class="mb-1 block text-sm font-medium">Description</label>
                    <textarea name="description" rows="4" class="w-full rounded-md border border-slate-300 px-3 py-2 outline-none focus:border-brand-500 focus:ring-2 focus:ring-brand-100"><?php echo e($serviceData['description'] ?? ''); ?></textarea>
                </div>

                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label class="mb-1 block text-sm font-medium">Price</label>
                        <input type="number" step="0.01" min="0" name="price" value="<?php echo e($serviceData['price'] ?? ''); ?>" class="w-full rounded-md border border-slate-300 px-3 py-2 outline-none focus:border-brand-500 focus:ring-2 focus:ring-brand-100" required>
                    </div>

                    <div>
                        <label class="mb-1 block text-sm font-medium">Category</label>
                        <input type="text" name="category" value="<?php echo e($serviceData['category'] ?? ''); ?>" class="w-full rounded-md border border-slate-300 px-3 py-2 outline-none focus:border-brand-500 focus:ring-2 focus:ring-brand-100">
                    </div>
                </div>

                <label class="flex items-center gap-2 text-sm text-slate-600">
                    <input type="checkbox" name="is_active" value="1" <?php echo !isset($serviceData['is_active']) || !empty($serviceData['is_active']) ? 'checked' : ''; ?> class="h-4 w-4 rounded border-slate-300 text-brand-600 focus:ring-brand-500">
                    Active service
                </label>

                <button type="submit" class="rounded-md bg-brand-500 px-4 py-2.5 font-semibold text-white hover:bg-brand-600">
                    Save Service
                </button>
            </div>
        </form>
    </main>
</body>
</html>
