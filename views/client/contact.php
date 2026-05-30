<?php require_once __DIR__ . '/../../includes/functions.php'; ?>
<!doctype html>
<html lang="en">
<head>
    <?php tailwindHead(SITE_NAME . ' - Contact'); ?>
</head>
<body class="min-h-screen bg-slate-100 text-slate-900">
    <?php renderNav('client'); ?>
    <main class="mx-auto max-w-3xl px-4 py-12 sm:px-6 lg:px-8">
        <section class="rounded-lg bg-white p-8 shadow-sm ring-1 ring-slate-200">
            <h1 class="text-3xl font-semibold">Contact</h1>
            <form method="post" action="index.php?controller=home&action=contact" class="mt-6 grid gap-4">
                <input name="name" class="rounded-md border border-slate-300 px-3 py-2 outline-none focus:border-brand-500 focus:ring-2 focus:ring-brand-100" placeholder="Your name" required>
                <input name="email" type="email" class="rounded-md border border-slate-300 px-3 py-2 outline-none focus:border-brand-500 focus:ring-2 focus:ring-brand-100" placeholder="Email" required>
                <textarea name="message" rows="5" class="rounded-md border border-slate-300 px-3 py-2 outline-none focus:border-brand-500 focus:ring-2 focus:ring-brand-100" placeholder="Message"></textarea>
                <button type="submit" class="rounded-md bg-brand-500 px-4 py-2.5 font-semibold text-white hover:bg-brand-600">Send</button>
            </form>
        </section>
    </main>
</body>
</html>
