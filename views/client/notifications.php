<?php require_once __DIR__ . '/../../includes/functions.php'; ?>
<!doctype html>
<html lang="en">
<head>
    <?php tailwindHead(SITE_NAME . ' - Notifications'); ?>
</head>
<body class="min-h-screen bg-slate-100 text-slate-900">
    <?php renderNav('client'); ?>

    <main class="mx-auto max-w-4xl px-4 py-10 sm:px-6 lg:px-8">
        <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="floral-label">User notifications</p>
                <h1 class="mt-2 text-4xl font-semibold">Notifications</h1>
                <p class="mt-1 text-sm text-slate-600">
                    <?php echo e($unreadCount); ?> unread notification<?php echo (int)$unreadCount === 1 ? '' : 's'; ?>
                </p>
            </div>

            <?php if(!empty($notifications)): ?>
                <form method="POST" action="index.php?controller=home&action=markAllNotificationsRead">
                    <button type="submit" class="rounded-md bg-brand-500 px-4 py-2 text-sm font-semibold text-white hover:bg-brand-600">
                        <i class="fas fa-check-double"></i> Mark all read
                    </button>
                </form>
            <?php endif; ?>
        </div>

        <section class="overflow-hidden rounded-lg bg-white shadow-sm ring-1 ring-slate-200">
            <?php if(empty($notifications)): ?>
                <div class="px-6 py-14 text-center">
                    <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-brand-50 text-brand-700">
                        <i class="fas fa-bell-slash"></i>
                    </div>
                    <h2 class="mt-4 text-2xl font-semibold">No notifications yet</h2>
                    <p class="mt-1 text-sm text-slate-600">Updates about your bookings will appear here.</p>
                </div>
            <?php else: ?>
                <div class="divide-y divide-slate-100">
                    <?php foreach($notifications as $notification): ?>
                        <?php $isUnread = (int)($notification['is_read'] ?? 0) === 0; ?>
                        <article class="flex flex-col gap-4 px-5 py-5 transition hover:bg-brand-50/40 sm:flex-row sm:items-start sm:justify-between <?php echo $isUnread ? 'bg-brand-50/30' : ''; ?>">
                            <div class="flex gap-4">
                                <div class="mt-1 flex h-10 w-10 shrink-0 items-center justify-center rounded-full <?php echo $isUnread ? 'bg-brand-500 text-white' : 'bg-slate-100 text-slate-500'; ?>">
                                    <i class="fas <?php echo ($notification['notification_type'] ?? '') === 'booking' ? 'fa-calendar-check' : 'fa-bell'; ?>"></i>
                                </div>
                                <div>
                                    <div class="flex flex-wrap items-center gap-2">
                                        <h2 class="text-xl font-semibold"><?php echo e($notification['title'] ?? 'Notification'); ?></h2>
                                        <?php if($isUnread): ?>
                                            <span class="rounded-full bg-red-50 px-2 py-0.5 text-xs font-semibold text-red-700">New</span>
                                        <?php endif; ?>
                                    </div>
                                    <p class="mt-1 text-sm leading-6 text-slate-600"><?php echo e($notification['message'] ?? ''); ?></p>
                                    <p class="mt-2 text-xs font-semibold uppercase tracking-[0.14em] text-slate-500">
                                        <?php echo e(date('M d, Y h:i A', strtotime($notification['created_at']))); ?>
                                    </p>
                                </div>
                            </div>

                            <div class="flex shrink-0 flex-wrap gap-2 sm:justify-end">
                                <?php if(!empty($notification['booking_id'])): ?>
                                    <a href="index.php?controller=home&action=processNotification&id=<?php echo e($notification['id']); ?>" class="rounded-md border border-slate-200 px-3 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50">
                                        View Booking
                                    </a>
                                <?php endif; ?>

                                <?php if($isUnread): ?>
                                    <a href="index.php?controller=home&action=markNotificationRead&id=<?php echo e($notification['id']); ?>" class="rounded-md bg-slate-900 px-3 py-2 text-xs font-semibold text-white hover:bg-slate-800">
                                        Mark read
                                    </a>
                                <?php endif; ?>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </section>
    </main>
</body>
</html>
