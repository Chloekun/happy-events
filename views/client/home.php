<?php
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../models/Service.php';

$serviceModel = new Service();
$services = array_slice($serviceModel->getAllServices(), 0, 8);
?>
<!doctype html>
<html lang="en">
<head>
    <?php tailwindHead(SITE_NAME . ' - Celebrate Life\'s Special Moments'); ?>
</head>
<body class="bg-white text-slate-900">
    <?php renderNav('client'); ?>

    <section id="home" class="relative min-h-[680px] overflow-hidden bg-sage">
        <div class="absolute inset-0 bg-[url('https://images.unsplash.com/photo-1526047932273-341f2a7631f9?auto=format&fit=crop&w=1800&q=85')] bg-cover bg-center opacity-65"></div>
        <div class="absolute inset-0 bg-[linear-gradient(90deg,rgba(53,45,42,.72),rgba(53,45,42,.28),rgba(230,207,203,.12))]"></div>
        <div class="relative mx-auto flex max-w-7xl flex-col items-center justify-center px-4 py-28 text-center sm:px-6 lg:min-h-[680px] lg:px-8">
            <p class="floral-label text-brand-100">Professional event planning</p>
            <div class="my-5 flex items-center gap-3 text-white/80">
                <span class="h-px w-12 bg-white/60"></span>
                <i class="fas fa-seedling text-sm"></i>
                <span class="h-px w-12 bg-white/60"></span>
            </div>
            <h1 class="max-w-4xl font-serif text-5xl font-semibold leading-none text-white sm:text-7xl">Create unforgettable memories</h1>
            <p class="mt-6 max-w-2xl text-base leading-7 text-white/90 sm:text-lg">End-to-end event planning and management for weddings, birthdays, corporate events, debuts, and private celebrations with a soft boutique touch.</p>
            <a href="index.php?controller=booking&action=showBookingForm" class="mt-9 rounded-md bg-brand-500 px-7 py-3 text-sm font-semibold uppercase tracking-[0.16em] text-white hover:bg-brand-600">
                <i class="fas fa-calendar-alt"></i> Book Now
            </a>
        </div>
    </section>

    <section id="about" class="bg-blush/60 py-18">
        <div class="mx-auto grid max-w-7xl gap-12 px-4 py-16 sm:px-6 lg:grid-cols-[1.05fr_0.95fr] lg:px-8">
            <div class="relative floral-reveal">
                <div class="image-zoom overflow-hidden rounded-lg shadow-sm ring-1 ring-slate-200">
                    <img src="https://images.unsplash.com/photo-1507504031003-b417219a0fde?auto=format&fit=crop&w=1200&q=80" alt="Elegant floral event arrangement" class="h-[440px] w-full object-cover">
                </div>
                <div class="absolute -bottom-7 right-6 hidden rounded-full bg-cream px-8 py-6 text-center shadow-sm ring-1 ring-slate-200 sm:block">
                    <span class="block font-serif text-3xl text-sage">1000+</span>
                    <span class="text-xs font-semibold uppercase tracking-[0.16em] text-cocoa/70">Events</span>
                </div>
            </div>
            <div class="flex flex-col justify-center floral-reveal">
                <p class="floral-label">Boutique coordination</p>
                <h2 class="mt-3 text-4xl font-semibold sm:text-5xl">Your trusted event partner</h2>
                <p class="mt-5 leading-7 text-slate-600">Happy Events creates polished, well-managed celebrations with thoughtful coordination from planning through event day. The experience now carries a softer floral identity while every booking workflow remains exactly in place.</p>
                <div class="mt-8 grid gap-4 sm:grid-cols-2">
                    <div class="floral-card rounded-lg p-6">
                        <i class="fas fa-smile text-2xl text-brand-500"></i>
                        <h3 class="mt-4 text-2xl font-semibold">500+ Happy Clients</h3>
                        <p class="mt-1 text-sm text-slate-600">Trusted by families and businesses.</p>
                    </div>
                    <div class="floral-card rounded-lg p-6">
                        <i class="fas fa-calendar-check text-2xl text-brand-500"></i>
                        <h3 class="mt-4 text-2xl font-semibold">1000+ Events</h3>
                        <p class="mt-1 text-sm text-slate-600">Coordinated with care and detail.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="bg-white py-16">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="grid items-center gap-10 lg:grid-cols-[0.85fr_1.15fr]">
                <div>
                    <p class="floral-label">Sample UI photo</p>
                    <h2 class="mt-3 text-4xl font-semibold sm:text-5xl">Photo preview for event packages</h2>
                    <p class="mt-4 text-slate-600">A sample photo interface gives clients a quick way to imagine the setup, decor, and atmosphere before booking.</p>
                    <div class="mt-6 flex flex-wrap gap-2 text-sm">
                        <span class="rounded-md bg-brand-50 px-3 py-2 font-medium text-brand-700">Wedding</span>
                        <span class="rounded-md bg-slate-100 px-3 py-2 font-medium text-slate-700">Birthday</span>
                        <span class="rounded-md bg-slate-100 px-3 py-2 font-medium text-slate-700">Corporate</span>
                        <span class="rounded-md bg-slate-100 px-3 py-2 font-medium text-slate-700">Debut</span>
                        <span class="rounded-md bg-slate-100 px-3 py-2 font-medium text-slate-700">Private Party</span>
                    </div>
                </div>
                <div class="overflow-hidden rounded-lg bg-white shadow-sm ring-1 ring-slate-200">
                    <div class="relative">
                        <img
                            src="https://images.unsplash.com/photo-1519167758481-83f550bb49b3?auto=format&fit=crop&w=1400&q=80"
                            alt="Elegant event table setup with warm lights"
                            class="h-80 w-full object-cover sm:h-96"
                        >
                        <div class="absolute left-4 top-4 rounded-md bg-white/95 px-3 py-2 text-xs font-semibold uppercase tracking-[0.14em] text-slate-900 shadow-sm">
                            Featured setup
                        </div>
                        <div class="absolute bottom-4 left-4 right-4 rounded-lg bg-slate-950/80 p-4 text-white backdrop-blur">
                            <p class="font-serif text-2xl font-semibold text-white">Elegant Celebration Setup</p>
                            <p class="mt-1 text-sm text-slate-200">Warm lighting, styled tables, and coordinated decor.</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-3 gap-3 p-4 sm:grid-cols-6">
                        <button type="button" class="overflow-hidden rounded-md ring-2 ring-brand-500">
                            <img src="https://images.unsplash.com/photo-1519167758481-83f550bb49b3?auto=format&fit=crop&w=400&q=80" alt="Wedding table setup thumbnail" class="h-20 w-full object-cover">
                        </button>
                        <button type="button" class="overflow-hidden rounded-md ring-1 ring-slate-200">
                            <img src="https://images.unsplash.com/photo-1464366400600-7168b8af9bc3?auto=format&fit=crop&w=400&q=80" alt="Outdoor celebration thumbnail" class="h-20 w-full object-cover">
                        </button>
                        <button type="button" class="overflow-hidden rounded-md ring-1 ring-slate-200">
                            <img src="https://images.unsplash.com/photo-1505236858219-8359eb29e329?auto=format&fit=crop&w=400&q=80" alt="Event lights thumbnail" class="h-20 w-full object-cover">
                        </button>
                        <button type="button" class="overflow-hidden rounded-md ring-1 ring-slate-200">
                            <img src="https://images.unsplash.com/photo-1530103862676-de8c9debad1d?auto=format&fit=crop&w=400&q=80" alt="Birthday party balloons thumbnail" class="h-20 w-full object-cover">
                        </button>
                        <button type="button" class="overflow-hidden rounded-md ring-1 ring-slate-200">
                            <img src="https://images.unsplash.com/photo-1511795409834-ef04bbd61622?auto=format&fit=crop&w=400&q=80" alt="Corporate event setup thumbnail" class="h-20 w-full object-cover">
                        </button>
                        <button type="button" class="overflow-hidden rounded-md ring-1 ring-slate-200">
                            <img src="https://images.unsplash.com/photo-1527529482837-4698179dc6ce?auto=format&fit=crop&w=400&q=80" alt="Party table details thumbnail" class="h-20 w-full object-cover">
                        </button>
                    </div>
                </div>
            </div>
            <div class="mt-10 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                <figure class="overflow-hidden rounded-lg bg-slate-50 ring-1 ring-slate-200">
                    <img src="https://images.unsplash.com/photo-1464366400600-7168b8af9bc3?auto=format&fit=crop&w=800&q=80" alt="Outdoor garden event setup" class="h-52 w-full object-cover">
                    <figcaption class="p-4 text-sm font-medium text-slate-700">Garden Reception</figcaption>
                </figure>
                <figure class="overflow-hidden rounded-lg bg-slate-50 ring-1 ring-slate-200">
                    <img src="https://images.unsplash.com/photo-1530103862676-de8c9debad1d?auto=format&fit=crop&w=800&q=80" alt="Colorful birthday party balloons" class="h-52 w-full object-cover">
                    <figcaption class="p-4 text-sm font-medium text-slate-700">Birthday Styling</figcaption>
                </figure>
                <figure class="overflow-hidden rounded-lg bg-slate-50 ring-1 ring-slate-200">
                    <img src="https://images.unsplash.com/photo-1511795409834-ef04bbd61622?auto=format&fit=crop&w=800&q=80" alt="Formal corporate event audience" class="h-52 w-full object-cover">
                    <figcaption class="p-4 text-sm font-medium text-slate-700">Corporate Program</figcaption>
                </figure>
                <figure class="overflow-hidden rounded-lg bg-slate-50 ring-1 ring-slate-200">
                    <img src="https://images.unsplash.com/photo-1527529482837-4698179dc6ce?auto=format&fit=crop&w=800&q=80" alt="Party table arrangement with decorations" class="h-52 w-full object-cover">
                    <figcaption class="p-4 text-sm font-medium text-slate-700">Private Party Details</figcaption>
                </figure>
            </div>
        </div>
    </section>

    <section id="services" class="bg-sage py-16 text-white">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="mb-10 flex items-end justify-between gap-4">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.18em] text-white/70">Shop the experience</p>
                    <h2 class="mt-2 text-4xl font-semibold text-white sm:text-5xl">Our Services</h2>
                    <p class="mt-2 text-white/75">Choose the services that fit your event.</p>
                </div>
                <a href="index.php?controller=home&action=services" class="hidden rounded-md bg-cream px-5 py-2.5 text-xs font-semibold uppercase tracking-[0.14em] text-sage hover:bg-white sm:inline-block">View All</a>
            </div>
            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                <?php foreach($services as $service): ?>
                    <div class="rounded-lg bg-white p-6 shadow-sm ring-1 ring-slate-200 transition duration-300 hover:-translate-y-1">
                        <i class="fas fa-music mb-4 text-3xl text-brand-500"></i>
                        <h3 class="text-2xl font-semibold"><?php echo e($service['name']); ?></h3>
                        <p class="mt-2 text-sm text-slate-600"><?php echo e($service['description']); ?></p>
                        <p class="mt-4 font-bold text-brand-700">PHP <?php echo e(number_format($service['price'], 2)); ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <section id="gallery" class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
        <div class="mb-10 text-center">
            <p class="floral-label">Lets be friends</p>
            <h2 class="mt-2 text-4xl font-semibold sm:text-5xl">Event Gallery</h2>
        </div>
        <div class="grid gap-4 md:grid-cols-3">
            <?php
                $gallery = [
                    ['https://images.unsplash.com/photo-1523438885200-e635ba2c371e?auto=format&fit=crop&w=900&q=80', 'Wedding Celebration'],
                    ['https://images.unsplash.com/photo-1530103862676-de8c9debad1d?auto=format&fit=crop&w=900&q=80', 'Birthday Party'],
                    ['https://images.unsplash.com/photo-1511795409834-ef04bbd61622?auto=format&fit=crop&w=900&q=80', 'Corporate Event']
                ];
            ?>
            <?php foreach($gallery as $item): ?>
                <div class="group image-zoom relative overflow-hidden rounded-lg shadow-sm ring-1 ring-slate-200">
                    <img src="<?php echo e($item[0]); ?>" alt="<?php echo e($item[1]); ?>" class="h-72 w-full object-cover transition duration-300 group-hover:scale-105">
                    <div class="absolute inset-x-0 bottom-0 bg-sage/85 p-4 font-serif text-2xl font-semibold text-white"><?php echo e($item[1]); ?></div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <section id="contact" class="bg-blush/70 py-16">
        <div class="mx-auto grid max-w-7xl gap-8 px-4 sm:px-6 lg:grid-cols-2 lg:px-8">
            <div>
                <p class="floral-label">Inquire with us</p>
                <h2 class="mt-2 text-4xl font-semibold sm:text-5xl">Contact Us</h2>
                <div class="mt-6 space-y-3 text-slate-600">
                    <p><i class="fas fa-map-marker-alt text-brand-500"></i> San Pablo City, Laguna</p>
                    <p><i class="fas fa-phone text-brand-500"></i> 09195779787</p>
                    <p><i class="fas fa-envelope text-brand-500"></i> mrsmkdbasa@gmail.com</p>
                </div>
            </div>
            <form method="POST" action="index.php?controller=home&action=contact" class="rounded-lg bg-white p-6 text-slate-900 shadow-sm">
                <div class="grid gap-4">
                    <input type="text" name="name" class="rounded-md border border-slate-300 px-3 py-2 outline-none focus:border-brand-500 focus:ring-2 focus:ring-brand-100" placeholder="Your Name" required>
                    <input type="email" name="email" class="rounded-md border border-slate-300 px-3 py-2 outline-none focus:border-brand-500 focus:ring-2 focus:ring-brand-100" placeholder="Your Email" required>
                    <textarea name="message" rows="5" class="rounded-md border border-slate-300 px-3 py-2 outline-none focus:border-brand-500 focus:ring-2 focus:ring-brand-100" placeholder="Your Message" required></textarea>
                    <button type="submit" class="rounded-md bg-brand-500 px-4 py-2.5 font-semibold text-white hover:bg-brand-600">Send Message</button>
                </div>
            </form>
        </div>
    </section>

    <footer class="bg-sage py-8 text-center text-sm text-white/75">
        &copy; 2024 <?php echo e(SITE_NAME); ?>. All rights reserved.
    </footer>
</body>
</html>
