<?php
require_once __DIR__ . '/../../includes/functions.php';
$user = $user ?? null;
?>
<!doctype html>
<html lang="en">
<head>
    <?php tailwindHead(SITE_NAME . ' - Profile'); ?>
</head>
<body class="min-h-screen bg-slate-100 text-slate-900">
    <?php renderNav('client'); ?>
    <main class="mx-auto max-w-2xl px-4 py-12 sm:px-6 lg:px-8">
        <section class="rounded-lg bg-white p-8 shadow-sm ring-1 ring-slate-200">
            <h1 class="text-3xl font-semibold">Your Profile</h1>
            <?php if($message = flash('success')): ?>
                <div class="mt-4 rounded-md bg-emerald-50 px-4 py-3 text-sm text-emerald-700"><?php echo e($message); ?></div>
            <?php endif; ?>
            <?php if($user): ?>
                <form method="post" action="index.php?controller=user&action=profile" class="mt-6 grid gap-4">
                    <input name="full_name" value="<?php echo e($user['full_name']); ?>" class="rounded-md border border-slate-300 px-3 py-2 outline-none focus:border-brand-500 focus:ring-2 focus:ring-brand-100">
                    <input name="contact_number" value="<?php echo e($user['contact_number']); ?>" class="rounded-md border border-slate-300 px-3 py-2 outline-none focus:border-brand-500 focus:ring-2 focus:ring-brand-100">
                    <input name="address" value="<?php echo e($user['address']); ?>" class="rounded-md border border-slate-300 px-3 py-2 outline-none focus:border-brand-500 focus:ring-2 focus:ring-brand-100">
                    <button type="submit" class="rounded-md bg-brand-500 px-4 py-2.5 font-semibold text-white hover:bg-brand-600">Save</button>
                </form>
            <?php else: ?>
                <p class="mt-4 text-slate-600">User not found.</p>
            <?php endif; ?>
        </section>
    </main>
</body>
</html>
