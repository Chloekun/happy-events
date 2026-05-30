<?php require_once __DIR__ . '/../../includes/functions.php'; ?>
<!doctype html>
<html lang="en">
<head>
    <?php tailwindHead('Admin - ' . $formTitle); ?>
</head>
<body class="min-h-screen bg-slate-100 text-slate-900">
    <?php renderNav('admin'); ?>

    <main class="mx-auto max-w-5xl px-4 py-8 sm:px-6 lg:px-8">
        <div class="mb-6 flex items-center justify-between gap-4">
            <h1 class="text-3xl font-semibold"><?php echo e($formTitle); ?></h1>
            <a href="index.php?controller=admin&action=bookings" class="rounded-md bg-slate-200 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-300">Back</a>
        </div>

        <form method="POST" action="<?php echo e($formAction); ?>" class="grid gap-6 lg:grid-cols-3">
            <section class="rounded-lg bg-white p-5 shadow-sm ring-1 ring-slate-200 lg:col-span-2">
                <h2 class="mb-4 text-lg font-semibold">Booking Details</h2>

                <div class="grid gap-4 md:grid-cols-2">
                    <div>
                        <label class="mb-1 block text-sm font-medium">Customer</label>
                        <select name="user_id" class="w-full rounded-md border border-slate-300 px-3 py-2 outline-none focus:border-brand-500 focus:ring-2 focus:ring-brand-100" required>
                            <option value="">Select customer</option>
                            <?php foreach($customers as $customer): ?>
                                <option value="<?php echo e($customer['id']); ?>" <?php echo (string)($bookingData['user_id'] ?? '') === (string)$customer['id'] ? 'selected' : ''; ?>>
                                    <?php echo e($customer['full_name']); ?> - <?php echo e($customer['email']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div>
                        <label class="mb-1 block text-sm font-medium">Event Type</label>
                        <select name="event_type_id" class="w-full rounded-md border border-slate-300 px-3 py-2 outline-none focus:border-brand-500 focus:ring-2 focus:ring-brand-100" required>
                            <option value="">Select event type</option>
                            <?php foreach($eventTypes as $type): ?>
                                <option value="<?php echo e($type['id']); ?>" <?php echo (string)($bookingData['event_type_id'] ?? '') === (string)$type['id'] ? 'selected' : ''; ?>>
                                    <?php echo e($type['name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="md:col-span-2">
                        <label class="mb-1 block text-sm font-medium">Event Title</label>
                        <input type="text" name="event_title" value="<?php echo e($bookingData['event_title'] ?? ''); ?>" class="w-full rounded-md border border-slate-300 px-3 py-2 outline-none focus:border-brand-500 focus:ring-2 focus:ring-brand-100" required>
                    </div>

                    <div>
                        <label class="mb-1 block text-sm font-medium">Event Date</label>
                        <input type="date" name="event_date" value="<?php echo e($bookingData['event_date'] ?? ''); ?>" class="w-full rounded-md border border-slate-300 px-3 py-2 outline-none focus:border-brand-500 focus:ring-2 focus:ring-brand-100" required>
                    </div>

                    <div>
                        <label class="mb-1 block text-sm font-medium">Event Time</label>
                        <input type="time" name="event_time" value="<?php echo e($bookingData['event_time'] ?? ''); ?>" class="w-full rounded-md border border-slate-300 px-3 py-2 outline-none focus:border-brand-500 focus:ring-2 focus:ring-brand-100" required>
                    </div>

                    <div class="md:col-span-2">
                        <label class="mb-1 block text-sm font-medium">Venue Address</label>
                        <textarea name="venue_address" rows="2" class="w-full rounded-md border border-slate-300 px-3 py-2 outline-none focus:border-brand-500 focus:ring-2 focus:ring-brand-100" required><?php echo e($bookingData['venue_address'] ?? ''); ?></textarea>
                    </div>

                    <div>
                        <label class="mb-1 block text-sm font-medium">Venue Location</label>
                        <select name="venue_location" class="w-full rounded-md border border-slate-300 px-3 py-2 outline-none focus:border-brand-500 focus:ring-2 focus:ring-brand-100" required>
                            <?php foreach(['Within San Pablo', 'Laguna Area', 'Batangas Area', 'Metro Manila', 'Quezon Province', 'Other Provinces'] as $location): ?>
                                <option value="<?php echo e($location); ?>" <?php echo ($bookingData['venue_location'] ?? '') === $location ? 'selected' : ''; ?>>
                                    <?php echo e($location); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div>
                        <label class="mb-1 block text-sm font-medium">Guests</label>
                        <input type="number" name="number_of_guests" value="<?php echo e($bookingData['number_of_guests'] ?? ''); ?>" class="w-full rounded-md border border-slate-300 px-3 py-2 outline-none focus:border-brand-500 focus:ring-2 focus:ring-brand-100">
                    </div>

                    <div>
                        <label class="mb-1 block text-sm font-medium">Budget Range</label>
                        <select name="budget_range" class="w-full rounded-md border border-slate-300 px-3 py-2 outline-none focus:border-brand-500 focus:ring-2 focus:ring-brand-100">
                            <option value="">Select budget</option>
                            <?php foreach(['PHP 10,000 - PHP 50,000', 'PHP 50,000 - PHP 100,000', 'PHP 100,000 - PHP 200,000', 'PHP 200,000 - PHP 500,000', 'PHP 500,000+'] as $budget): ?>
                                <option value="<?php echo e($budget); ?>" <?php echo ($bookingData['budget_range'] ?? '') === $budget ? 'selected' : ''; ?>>
                                    <?php echo e($budget); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div>
                        <label class="mb-1 block text-sm font-medium">Status</label>
                        <select name="status" class="w-full rounded-md border border-slate-300 px-3 py-2 outline-none focus:border-brand-500 focus:ring-2 focus:ring-brand-100">
                            <?php foreach($statuses as $status): ?>
                                <option value="<?php echo e($status); ?>" <?php echo ($bookingData['status'] ?? 'pending') === $status ? 'selected' : ''; ?>>
                                    <?php echo e(ucfirst($status)); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="md:col-span-2">
                        <label class="mb-1 block text-sm font-medium">Additional Notes</label>
                        <textarea name="additional_notes" rows="3" class="w-full rounded-md border border-slate-300 px-3 py-2 outline-none focus:border-brand-500 focus:ring-2 focus:ring-brand-100"><?php echo e($bookingData['additional_notes'] ?? ''); ?></textarea>
                    </div>

                    <div class="md:col-span-2">
                        <label class="mb-1 block text-sm font-medium">Admin Remarks</label>
                        <textarea name="admin_remarks" rows="2" class="w-full rounded-md border border-slate-300 px-3 py-2 outline-none focus:border-brand-500 focus:ring-2 focus:ring-brand-100"><?php echo e($bookingData['admin_remarks'] ?? ''); ?></textarea>
                    </div>
                </div>
            </section>

            <aside class="rounded-lg bg-white p-5 shadow-sm ring-1 ring-slate-200">
                <h2 class="mb-4 text-lg font-semibold">Services</h2>
                <div class="space-y-3">
                    <?php foreach($services as $service): ?>
                        <label class="flex cursor-pointer items-start gap-3 rounded-md border border-slate-200 p-3 hover:border-brand-500">
                            <input type="checkbox" name="services[]" value="<?php echo e($service['id']); ?>" <?php echo in_array($service['id'], $selectedServices) ? 'checked' : ''; ?> class="mt-1 h-4 w-4 rounded border-slate-300 text-brand-600">
                            <span>
                                <span class="block text-sm font-medium"><?php echo e($service['name']); ?></span>
                                <span class="text-xs text-slate-500">PHP <?php echo e(number_format($service['price'], 2)); ?></span>
                            </span>
                        </label>
                    <?php endforeach; ?>
                </div>

                <button type="submit" class="mt-5 w-full rounded-md bg-brand-500 px-4 py-2.5 font-semibold text-white hover:bg-brand-600">
                    Save Booking
                </button>
            </aside>
        </form>
    </main>
</body>
</html>
