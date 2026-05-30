<?php require_once __DIR__ . '/../../includes/functions.php'; ?>
<!doctype html>
<html lang="en">
<head>
    <?php tailwindHead('Login - ' . SITE_NAME); ?>
</head>
<body class="min-h-screen bg-slate-100 text-slate-900">
    <?php renderNav('client'); ?>

    <main class="mx-auto flex max-w-7xl items-center justify-center px-4 py-16">
        <section class="w-full max-w-md rounded-lg bg-white p-8 shadow-sm ring-1 ring-slate-200">
            <h1 class="mb-6 text-center text-2xl font-semibold">Login</h1>

            <?php if($message = flash('error')): ?>
                <div class="mb-4 rounded-md bg-red-50 px-4 py-3 text-sm text-red-700"><?php echo e($message); ?></div>
            <?php endif; ?>

            <?php if($message = flash('success')): ?>
                <div class="mb-4 rounded-md bg-emerald-50 px-4 py-3 text-sm text-emerald-700"><?php echo e($message); ?></div>
            <?php endif; ?>

            <form method="POST" action="index.php?controller=auth&action=login" class="space-y-4">
                <div>
                    <label for="username" class="mb-1 block text-sm font-medium">Username or Email</label>
                    <input type="text" id="username" name="username" class="w-full rounded-md border border-slate-300 px-3 py-2 outline-none focus:border-brand-500 focus:ring-2 focus:ring-brand-100" required>
                </div>
                <div>
                    <label for="password" class="mb-1 block text-sm font-medium">Password</label>
                    <input type="password" id="password" name="password" class="w-full rounded-md border border-slate-300 px-3 py-2 outline-none focus:border-brand-500 focus:ring-2 focus:ring-brand-100" required>
                </div>
                <div class="flex items-center justify-between gap-3 text-sm">
                    <label class="flex items-center gap-2 text-slate-600">
                        <input type="checkbox" id="showPassword" class="h-4 w-4 rounded border-slate-300 text-brand-600 focus:ring-brand-500">
                        Show password
                    </label>
                    <a href="index.php?controller=auth&action=showForgotPassword" class="font-medium text-brand-700 hover:underline">Forgot password?</a>
                </div>
                <button type="submit" class="w-full rounded-md bg-brand-500 px-4 py-2.5 font-semibold text-white hover:bg-brand-600">
                    <i class="fas fa-sign-in-alt"></i> Login
                </button>
            </form>

            <p class="mt-5 text-center text-sm text-slate-600">
                No account yet?
                <a href="index.php?controller=auth&action=showRegister" class="font-medium text-brand-700 hover:underline">Register</a>
            </p>
        </section>
    </main>
    <script>
        document.getElementById('showPassword').addEventListener('change', function() {
            document.getElementById('password').type = this.checked ? 'text' : 'password';
        });
    </script>
</body>
</html>
