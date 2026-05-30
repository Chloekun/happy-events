<?php require_once __DIR__ . '/../../includes/functions.php'; ?>
<!doctype html>
<html lang="en">
<head>
    <?php tailwindHead(SITE_NAME . ' - Gallery'); ?>
</head>
<body class="min-h-screen bg-white text-slate-900">
    <?php renderNav('client'); ?>
    <main class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
        <h1 class="mb-6 text-3xl font-semibold">Gallery</h1>
        <div class="grid gap-4 md:grid-cols-3">
            <?php foreach([['assets/images/gallery1.jpg','Wedding'], ['assets/images/gallery2.jpg','Birthday'], ['assets/images/gallery3.jpg','Corporate']] as $item): ?>
                <img src="<?php echo e($item[0]); ?>" alt="<?php echo e($item[1]); ?>" class="h-72 w-full rounded-lg object-cover shadow-sm">
            <?php endforeach; ?>
        </div>
    </main>
</body>
</html>
