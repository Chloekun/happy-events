<?php
require_once __DIR__ . '/../../includes/functions.php';

$eventTypes = $eventTypes ?? [];
$services = $services ?? [];
?>
<!doctype html>
<html lang="en">
<head>
    <?php tailwindHead('Book Your Event - ' . SITE_NAME); ?>
</head>
<body class="min-h-screen bg-slate-100 text-slate-900">
    <?php renderNav('client'); ?>

    <main class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        <div class="mb-6">
            <h1 class="text-3xl font-semibold">Book Your Event</h1>
            <p class="mt-1 text-slate-600">Tell us what you need and we will prepare your event quotation.</p>
        </div>

        <?php if($message = flash('error')): ?>
            <div class="mb-4 rounded-md bg-red-50 px-4 py-3 text-sm text-red-700"><?php echo e($message); ?></div>
        <?php endif; ?>

        <form method="POST" action="index.php?controller=booking&action=createBooking" id="bookingForm" class="grid gap-6 lg:grid-cols-2">
            <section class="rounded-lg bg-white shadow-sm ring-1 ring-slate-200">
                <div class="border-b border-slate-200 px-5 py-4">
                    <h2 class="font-semibold">Event Details</h2>
                </div>
                <div class="grid gap-4 p-5">
                    <div>
                        <label class="mb-1 block text-sm font-medium">Event Title <span class="text-red-600">*</span></label>
                        <input type="text" name="event_title" class="w-full rounded-md border border-slate-300 px-3 py-2 outline-none focus:border-brand-500 focus:ring-2 focus:ring-brand-100" required>
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium">Event Type <span class="text-red-600">*</span></label>
                        <select name="event_type" class="w-full rounded-md border border-slate-300 px-3 py-2 outline-none focus:border-brand-500 focus:ring-2 focus:ring-brand-100" required>
                            <option value="">Select Event Type</option>
                            <?php if(!empty($eventTypes)): foreach($eventTypes as $type): ?>
                                <option value="<?php echo e($type['id']); ?>"><?php echo e($type['name']); ?></option>
                            <?php endforeach; endif; ?>
                        </select>
                    </div>
                    <div class="grid gap-4 md:grid-cols-2">
                        <div>
                            <label class="mb-1 block text-sm font-medium">Event Date <span class="text-red-600">*</span></label>
                            <input type="date" name="event_date" id="event_date" class="w-full rounded-md border border-slate-300 px-3 py-2 outline-none focus:border-brand-500 focus:ring-2 focus:ring-brand-100" required>
                        </div>
                        <div>
                            <label class="mb-1 block text-sm font-medium">Event Time <span class="text-red-600">*</span></label>
                            <input type="time" name="event_time" class="w-full rounded-md border border-slate-300 px-3 py-2 outline-none focus:border-brand-500 focus:ring-2 focus:ring-brand-100" required>
                        </div>
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium">Venue Address <span class="text-red-600">*</span></label>
                        <textarea name="venue_address" rows="2" class="w-full rounded-md border border-slate-300 px-3 py-2 outline-none focus:border-brand-500 focus:ring-2 focus:ring-brand-100" required></textarea>
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium">Venue Location <span class="text-red-600">*</span></label>
                        <select name="venue_location" id="venue_location" class="w-full rounded-md border border-slate-300 px-3 py-2 outline-none focus:border-brand-500 focus:ring-2 focus:ring-brand-100" required>
                            <option value="">Select Location</option>
                            <option value="Within San Pablo">Within San Pablo</option>
                            <option value="Laguna Area">Laguna Area</option>
                            <option value="Batangas Area">Batangas Area</option>
                            <option value="Metro Manila">Metro Manila</option>
                            <option value="Quezon Province">Quezon Province</option>
                            <option value="Other Provinces">Other Provinces</option>
                        </select>
                    </div>
                    <div class="grid gap-4 md:grid-cols-2">
                        <div>
                            <label class="mb-1 block text-sm font-medium">Number of Guests</label>
                            <input type="number" name="number_of_guests" class="w-full rounded-md border border-slate-300 px-3 py-2 outline-none focus:border-brand-500 focus:ring-2 focus:ring-brand-100">
                        </div>
                        <div>
                            <label class="mb-1 block text-sm font-medium">Budget Range</label>
                            <select name="budget_range" class="w-full rounded-md border border-slate-300 px-3 py-2 outline-none focus:border-brand-500 focus:ring-2 focus:ring-brand-100">
                                <option value="">Select Budget Range</option>
                                <option>PHP 10,000 - PHP 50,000</option>
                                <option>PHP 50,000 - PHP 100,000</option>
                                <option>PHP 100,000 - PHP 200,000</option>
                                <option>PHP 200,000 - PHP 500,000</option>
                                <option>PHP 500,000+</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium">Additional Notes</label>
                        <textarea name="additional_notes" rows="3" class="w-full rounded-md border border-slate-300 px-3 py-2 outline-none focus:border-brand-500 focus:ring-2 focus:ring-brand-100"></textarea>
                    </div>
                </div>
            </section>

            <div class="space-y-6">
                <section class="rounded-lg bg-white shadow-sm ring-1 ring-slate-200">
                    <div class="border-b border-slate-200 px-5 py-4">
                        <h2 class="font-semibold">Select Services</h2>
                    </div>
                    <div class="grid gap-3 p-5 sm:grid-cols-2">
                        <?php if(!empty($services)): foreach($services as $service): ?>
                            <label class="flex cursor-pointer gap-3 rounded-md border border-slate-200 p-3 hover:border-brand-500">
                                <input type="checkbox" name="services[]" value="<?php echo e($service['id']); ?>" class="service-checkbox mt-1 h-4 w-4 rounded border-slate-300 text-brand-600" data-price="<?php echo e($service['price']); ?>">
                                <span>
                                    <span class="block font-medium"><?php echo e($service['name']); ?></span>
                                    <span class="text-sm text-slate-600">PHP <?php echo e(number_format($service['price'], 2)); ?></span>
                                </span>
                            </label>
                        <?php endforeach; endif; ?>
                    </div>
                </section>

                <section class="rounded-lg bg-slate-950 p-5 text-white shadow-sm">
                    <h2 class="font-semibold">Booking Summary</h2>
                    <div class="mt-4 space-y-2 text-slate-200">
                        <p class="flex justify-between"><span>Services Total</span><span>PHP <span id="servicesTotal">0</span></span></p>
                        <p class="flex justify-between"><span>Venue Fee</span><span>PHP <span id="venueFee">0</span></span></p>
                        <div class="border-t border-white/10 pt-3 text-lg font-semibold text-white">
                            <p class="flex justify-between"><span>Estimated Total</span><span>PHP <span id="grandTotal">0</span></span></p>
                        </div>
                    </div>
                    <button type="submit" class="mt-5 w-full rounded-md bg-brand-500 px-4 py-2.5 font-semibold text-white hover:bg-brand-600">Submit Booking Request</button>
                </section>
            </div>
        </form>
    </main>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.service-checkbox').change(function() {
                let total = 0;
                $('.service-checkbox:checked').each(function() {
                    total += parseFloat($(this).data('price')) || 0;
                });
                $('#servicesTotal').text(total.toLocaleString());
                updateGrandTotal();
            });

            $('#venue_location').change(function() {
                $.ajax({
                    url: 'index.php?controller=booking&action=getVenueFee',
                    method: 'POST',
                    data: {location: $(this).val()},
                    success: function(fee) {
                        $('#venueFee').text(parseInt(fee).toLocaleString());
                        updateGrandTotal();
                    }
                });
            });

            function updateGrandTotal() {
                let servicesTotal = parseFloat($('#servicesTotal').text().replace(/,/g, '')) || 0;
                let venueFee = parseFloat($('#venueFee').text().replace(/,/g, '')) || 0;
                $('#grandTotal').text((servicesTotal + venueFee).toLocaleString());
            }
        });
    </script>
</body>
</html>
