<?php require_once __DIR__ . '/../../includes/functions.php'; ?>
<!doctype html>
<html lang="en">
<head>
    <?php tailwindHead('Register - ' . SITE_NAME); ?>
</head>
<body class="min-h-screen bg-slate-100 text-slate-900">
    <?php renderNav('client'); ?>

    <main class="mx-auto flex max-w-7xl justify-center px-4 py-12">
        <section class="w-full max-w-2xl rounded-lg bg-white p-8 shadow-sm ring-1 ring-slate-200">
            <h1 class="mb-6 text-center text-2xl font-semibold">Register</h1>

            <?php if($message = flash('error')): ?>
                <div class="mb-4 rounded-md bg-red-50 px-4 py-3 text-sm text-red-700"><?php echo e($message); ?></div>
            <?php endif; ?>

            <form method="POST" action="index.php?controller=auth&action=register" class="space-y-4">
                <div>
                    <label for="full_name" class="mb-1 block text-sm font-medium">Full Name</label>
                    <input type="text" id="full_name" name="full_name" class="w-full rounded-md border border-slate-300 px-3 py-2 outline-none focus:border-brand-500 focus:ring-2 focus:ring-brand-100" required>
                </div>
                <div class="grid gap-4 md:grid-cols-2">
                    <div>
                        <label for="email" class="mb-1 block text-sm font-medium">Email</label>
                        <input type="email" id="email" name="email" class="w-full rounded-md border border-slate-300 px-3 py-2 outline-none focus:border-brand-500 focus:ring-2 focus:ring-brand-100" required>
                    </div>
                    <div>
                        <label for="username" class="mb-1 block text-sm font-medium">Username</label>
                        <input type="text" id="username" name="username" class="w-full rounded-md border border-slate-300 px-3 py-2 outline-none focus:border-brand-500 focus:ring-2 focus:ring-brand-100" required>
                    </div>
                </div>
                <div class="grid gap-4 md:grid-cols-2">
                    <div>
                        <label for="password" class="mb-1 block text-sm font-medium">Password</label>
                        <input type="password" id="password" name="password" class="w-full rounded-md border border-slate-300 px-3 py-2 outline-none focus:border-brand-500 focus:ring-2 focus:ring-brand-100" required>
                    </div>
                    <div>
                        <label for="confirm_password" class="mb-1 block text-sm font-medium">Confirm Password</label>
                        <input type="password" id="confirm_password" name="confirm_password" class="w-full rounded-md border border-slate-300 px-3 py-2 outline-none focus:border-brand-500 focus:ring-2 focus:ring-brand-100" required>
                    </div>
                </div>
                <div>
                    <label for="contact_number" class="mb-1 block text-sm font-medium">Contact Number</label>
                    <input type="text" id="contact_number" name="contact_number" class="w-full rounded-md border border-slate-300 px-3 py-2 outline-none focus:border-brand-500 focus:ring-2 focus:ring-brand-100" required>
                </div>
                <div>
                    <label for="address" class="mb-1 block text-sm font-medium">Address</label>
                    <textarea id="address" name="address" rows="3" class="w-full rounded-md border border-slate-300 px-3 py-2 outline-none focus:border-brand-500 focus:ring-2 focus:ring-brand-100" required></textarea>
                </div>
                <button type="submit" class="w-full rounded-md bg-brand-500 px-4 py-2.5 font-semibold text-white hover:bg-brand-600">
                    <i class="fas fa-user-plus"></i> Register
                </button>
            </form>

            <p class="mt-5 text-center text-sm text-slate-600">
                Already have an account?
                <a href="index.php?controller=auth&action=showLogin" class="font-medium text-brand-700 hover:underline">Login</a>
            </p>
        </section>
    </main>
</body>
</html>
