<?php require_once __DIR__ . '/../../includes/functions.php'; ?>
<!doctype html>
<html lang="en">
<head>
    <?php tailwindHead('Forgot Password - ' . SITE_NAME); ?>
</head>
<body class="min-h-screen bg-slate-100 text-slate-900">
    <?php renderNav('client'); ?>

    <main class="mx-auto flex max-w-7xl items-center justify-center px-4 py-16">
        <section class="w-full max-w-md rounded-lg bg-white p-8 shadow-sm ring-1 ring-slate-200">
            <h1 class="mb-2 text-center text-2xl font-semibold">Reset Password</h1>
            <p class="mb-6 text-center text-sm text-slate-600">Enter your username or email and choose a new password.</p>

            <?php if($message = flash('error')): ?>
                <div class="mb-4 rounded-md bg-red-50 px-4 py-3 text-sm text-red-700"><?php echo e($message); ?></div>
            <?php endif; ?>

            <form method="POST" action="index.php?controller=auth&action=forgotPassword" class="space-y-4">
                <div>
                    <label for="login" class="mb-1 block text-sm font-medium">Username or Email</label>
                    <input type="text" id="login" name="login" class="w-full rounded-md border border-slate-300 px-3 py-2 outline-none focus:border-brand-500 focus:ring-2 focus:ring-brand-100" required>
                </div>
                <div>
                    <label for="password" class="mb-1 block text-sm font-medium">New Password</label>
                    <input type="password" id="password" name="password" class="w-full rounded-md border border-slate-300 px-3 py-2 outline-none focus:border-brand-500 focus:ring-2 focus:ring-brand-100" required>
                </div>
                <div>
                    <label for="confirm_password" class="mb-1 block text-sm font-medium">Confirm New Password</label>
                    <input type="password" id="confirm_password" name="confirm_password" class="w-full rounded-md border border-slate-300 px-3 py-2 outline-none focus:border-brand-500 focus:ring-2 focus:ring-brand-100" required>
                </div>
                <label class="flex items-center gap-2 text-sm text-slate-600">
                    <input type="checkbox" id="showResetPasswords" class="h-4 w-4 rounded border-slate-300 text-brand-600 focus:ring-brand-500">
                    Show passwords
                </label>
                <button type="submit" class="w-full rounded-md bg-brand-500 px-4 py-2.5 font-semibold text-white hover:bg-brand-600">
                    Update Password
                </button>
            </form>

            <p class="mt-5 text-center text-sm text-slate-600">
                Remember your password?
                <a href="index.php?controller=auth&action=showLogin" class="font-medium text-brand-700 hover:underline">Login</a>
            </p>
        </section>
    </main>

    <script>
        document.getElementById('showResetPasswords').addEventListener('change', function() {
            const type = this.checked ? 'text' : 'password';
            document.getElementById('password').type = type;
            document.getElementById('confirm_password').type = type;
        });
    </script>
</body>
</html>
