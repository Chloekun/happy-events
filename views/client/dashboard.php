<?php require_once __DIR__ . '/../../includes/functions.php'; ?>
<!doctype html>
<html lang="en">
<head>
    <?php tailwindHead(SITE_NAME . ' - Dashboard'); ?>
</head>
<body class="min-h-screen bg-slate-100 text-slate-900">
    <?php renderNav('client'); ?>
    <main class="mx-auto max-w-4xl px-4 py-12 sm:px-6 lg:px-8">
        <section class="rounded-lg bg-white p-8 shadow-sm ring-1 ring-slate-200">
            <h1 class="text-3xl font-semibold">Welcome, <?php echo e($_SESSION['user_name'] ?? 'Guest'); ?></h1>
            <p class="mt-2 text-slate-600">Your dashboard has been removed from the user navigation. Use booking and quotation links from your active flow.</p>
        </section>
    </main>
</body>
</html>
