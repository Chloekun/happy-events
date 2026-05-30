<?php require_once __DIR__ . '/../../includes/functions.php'; ?>
<!doctype html>
<html lang="en">
<head>
    <?php tailwindHead(SITE_NAME . ' - About'); ?>
</head>
<body class="min-h-screen bg-white text-slate-900">
    <?php renderNav('client'); ?>
    <main class="mx-auto grid max-w-7xl gap-10 px-4 py-12 sm:px-6 lg:grid-cols-2 lg:px-8">
        <img src="assets/images/about.jpg" alt="About Happy Events" class="h-96 w-full rounded-lg object-cover shadow-sm">
        <section class="flex flex-col justify-center">
            <h1 class="text-3xl font-semibold">About Us</h1>
            <p class="mt-4 text-slate-600">Welcome to <?php echo e(SITE_NAME); ?>. We plan and manage events with careful coordination, dependable service, and polished execution.</p>
        </section>
    </main>
</body>
</html>
