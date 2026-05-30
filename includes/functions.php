<?php
require_once __DIR__ . '/../config/config.php';

function e($v) {
    return htmlspecialchars($v, ENT_QUOTES, 'UTF-8');
}

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function isAdmin() {
    return isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
}

function isCustomer() {
    return isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'user';
}

function requireLogin() {
    if(!isLoggedIn()) {
        $_SESSION['error'] = 'Please login to continue.';
        header('Location: index.php?controller=auth&action=showLogin');
        exit;
    }
}

function requireRole($roles) {
    requireLogin();

    if(!is_array($roles)) {
        $roles = [$roles];
    }

    if(!in_array($_SESSION['user_role'], $roles, true)) {
        $_SESSION['error'] = 'You are not allowed to access that page.';
        header('Location: index.php?controller=home&action=index');
        exit;
    }
}

function requireAdmin() {
    requireRole('admin');
}

function requireCustomer() {
    requireRole('user');
}

function flash($key) {
    if(isset($_SESSION[$key])) {
        $msg = $_SESSION[$key];
        unset($_SESSION[$key]);
        return $msg;
    }
    return null;
}

function unreadNotificationCount($userId) {
    static $counts = [];

    if(!$userId) {
        return 0;
    }

    if(isset($counts[$userId])) {
        return $counts[$userId];
    }

    try {
        require_once __DIR__ . '/../config/database.php';
        $database = new Database();
        $conn = $database->getConnection();

        if(!$conn) {
            $counts[$userId] = 0;
            return 0;
        }

        $stmt = $conn->prepare('SELECT COUNT(*) as total FROM notifications WHERE user_id = :user_id AND is_read = 0');
        $stmt->bindParam(':user_id', $userId);
        $stmt->execute();
        $counts[$userId] = (int)$stmt->fetch(PDO::FETCH_ASSOC)['total'];
        return $counts[$userId];
    } catch(Exception $e) {
        $counts[$userId] = 0;
        return 0;
    }
}

function tailwindHead($title) {
    echo '<meta charset="utf-8">' . "\n";
    echo '<meta name="viewport" content="width=device-width, initial-scale=1">' . "\n";
    echo '<title>' . e($title) . '</title>' . "\n";
    echo '<link rel="preconnect" href="https://fonts.googleapis.com">' . "\n";
    echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>' . "\n";
    echo '<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;500;600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">' . "\n";
    echo '<script src="https://cdn.tailwindcss.com"></script>' . "\n";
    echo '<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">' . "\n";
    echo '<script>tailwind.config = { theme: { extend: { fontFamily: { serif: ["Cormorant Garamond", "serif"], sans: ["Inter", "sans-serif"] }, colors: { brand: { 50: "#F6F1EC", 100: "#E6CFCB", 500: "#B89C94", 600: "#8F6F68", 700: "#6F8774" }, sage: "#6F8774", cream: "#F6F1EC", blush: "#E6CFCB", taupe: "#B89C94", cocoa: "#6B5550" } } } }</script>' . "\n";
    echo '<style>
        :root {
            --cream: #F6F1EC;
            --blush: #E6CFCB;
            --sage: #6F8774;
            --taupe: #B89C94;
            --cocoa: #6B5550;
            --ink: #352D2A;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            background: var(--cream) !important;
            color: var(--ink) !important;
            font-family: "Inter", sans-serif;
        }

        h1, h2, h3 {
            font-family: "Cormorant Garamond", serif;
            letter-spacing: 0;
            color: var(--ink);
        }

        h1 {
            font-weight: 600;
        }

        h2, h3 {
            font-weight: 600;
        }

        main {
            animation: floralFadeUp .55s ease both;
        }

        nav {
            border-bottom: 1px solid rgba(111, 135, 116, .18);
            background: rgba(246, 241, 236, .94) !important;
            color: var(--ink) !important;
            box-shadow: 0 10px 30px rgba(107, 85, 80, .06) !important;
            backdrop-filter: blur(16px);
        }

        nav a {
            transition: all .22s ease;
        }

        nav a:hover {
            color: var(--sage);
        }

        .bg-white {
            background-color: rgba(255, 252, 249, .92) !important;
        }

        .bg-slate-100,
        .bg-slate-50 {
            background-color: var(--cream) !important;
        }

        .bg-slate-950,
        .bg-slate-900 {
            background-color: var(--sage) !important;
        }

        .text-slate-900,
        .text-slate-800 {
            color: var(--ink) !important;
        }

        .text-slate-700,
        .text-slate-600,
        .text-slate-500 {
            color: rgba(53, 45, 42, .72) !important;
        }

        .text-white .text-slate-200,
        .bg-slate-950 .text-slate-200,
        .bg-slate-900 .text-slate-300 {
            color: rgba(255, 252, 249, .82) !important;
        }

        .ring-slate-200,
        .border-slate-200,
        .border-slate-300,
        .divide-slate-200 > :not([hidden]) ~ :not([hidden]),
        .divide-slate-100 > :not([hidden]) ~ :not([hidden]) {
            border-color: rgba(184, 156, 148, .28) !important;
            --tw-ring-color: rgba(184, 156, 148, .28) !important;
        }

        .rounded-lg,
        .rounded-md {
            border-radius: 1rem !important;
        }

        .shadow-sm {
            box-shadow: 0 18px 45px rgba(107, 85, 80, .09) !important;
        }

        input,
        select,
        textarea {
            background-color: rgba(255, 252, 249, .95) !important;
            border-color: rgba(184, 156, 148, .42) !important;
            transition: border-color .22s ease, box-shadow .22s ease, background-color .22s ease;
        }

        input:focus,
        select:focus,
        textarea:focus {
            border-color: var(--sage) !important;
            box-shadow: 0 0 0 4px rgba(111, 135, 116, .14) !important;
        }

        button,
        a[class*="bg-brand"],
        a[class*="bg-slate"],
        button[class*="bg-brand"],
        button[class*="bg-slate"] {
            transition: transform .22s ease, box-shadow .22s ease, background-color .22s ease;
        }

        button:hover,
        a[class*="bg-brand"]:hover,
        a[class*="bg-slate"]:hover,
        button[class*="bg-brand"]:hover,
        button[class*="bg-slate"]:hover {
            transform: translateY(-1px);
            box-shadow: 0 12px 24px rgba(107, 85, 80, .14);
        }

        .bg-brand-500,
        .bg-brand-600 {
            background-color: var(--sage) !important;
        }

        .text-brand-500,
        .text-brand-600,
        .text-brand-700 {
            color: var(--sage) !important;
        }

        .bg-brand-50,
        .bg-brand-100 {
            background-color: rgba(230, 207, 203, .54) !important;
        }

        table thead {
            background: rgba(230, 207, 203, .28) !important;
        }

        tbody tr {
            transition: background-color .18s ease;
        }

        tbody tr:hover {
            background-color: rgba(246, 241, 236, .62);
        }

        .floral-reveal {
            animation: floralFadeUp .7s ease both;
        }

        .floral-card {
            border: 1px solid rgba(184, 156, 148, .24);
            background: rgba(255, 252, 249, .88);
            box-shadow: 0 22px 50px rgba(107, 85, 80, .1);
        }

        .floral-label {
            color: var(--sage);
            font-size: .72rem;
            font-weight: 700;
            letter-spacing: .18em;
            text-transform: uppercase;
        }

        .floral-serif {
            font-family: "Cormorant Garamond", serif;
        }

        .image-zoom img {
            transition: transform .7s ease;
        }

        .image-zoom:hover img {
            transform: scale(1.045);
        }

        @keyframes floralFadeUp {
            from {
                opacity: 0;
                transform: translateY(18px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>' . "\n";
}

function renderNav($area = 'client') {
    $isAdminArea = $area === 'admin';
    ?>
    <nav class="<?php echo $isAdminArea ? 'bg-slate-950 text-white' : 'bg-white text-slate-900 shadow-sm'; ?> sticky top-0 z-50">
        <div class="mx-auto flex max-w-7xl flex-col items-center justify-between gap-3 px-4 py-4 sm:px-6 lg:flex-row lg:px-8">
            <a href="index.php?controller=home&action=index" class="flex flex-col items-center gap-0 text-center text-lg font-semibold lg:items-start lg:text-left">
                <span class="font-serif text-2xl font-semibold uppercase tracking-[0.14em] text-sage"><?php echo e(SITE_NAME); ?></span>
                <span class="text-[0.64rem] font-semibold uppercase tracking-[0.32em] text-cocoa/70"><?php echo $isAdminArea ? 'Boutique Admin Studio' : 'Events Boutique'; ?></span>
            </a>
            <div class="flex flex-wrap items-center justify-center gap-2 text-xs font-semibold uppercase tracking-[0.14em]">
                <?php if($isAdminArea): ?>
                    <a class="rounded-md px-3 py-2 hover:bg-white/40" href="index.php?controller=admin&action=dashboard">Dashboard</a>
                    <a class="rounded-md px-3 py-2 hover:bg-white/40" href="index.php?controller=admin&action=bookings">Bookings</a>
                    <a class="rounded-md px-3 py-2 hover:bg-white/40" href="index.php?controller=admin&action=services">Services</a>
                <?php else: ?>
                    <a class="rounded-md px-3 py-2 hover:bg-slate-100" href="index.php?controller=home&action=index#about">About</a>
                    <a class="rounded-md px-3 py-2 hover:bg-slate-100" href="index.php?controller=home&action=index#services">Services</a>
                    <a class="rounded-md px-3 py-2 hover:bg-slate-100" href="index.php?controller=home&action=index#gallery">Gallery</a>
                    <a class="rounded-md px-3 py-2 hover:bg-slate-100" href="index.php?controller=home&action=index#contact">Contact</a>
                <?php endif; ?>

                <?php if(isLoggedIn()): ?>
                    <?php if(isAdmin() && !$isAdminArea): ?>
                        <a class="rounded-md bg-slate-900 px-3 py-2 font-medium text-white" href="index.php?controller=admin&action=dashboard">Admin</a>
                    <?php endif; ?>
                    <?php if(!$isAdminArea && isCustomer()): ?>
                        <?php $unreadCount = unreadNotificationCount($_SESSION['user_id'] ?? null); ?>
                        <a class="relative inline-flex h-10 w-10 items-center justify-center rounded-full border border-slate-200 bg-white/70 text-sage shadow-sm hover:bg-slate-100" href="index.php?controller=home&action=notifications" aria-label="Notifications" title="Notifications">
                            <i class="fas fa-bell text-sm"></i>
                            <?php if($unreadCount > 0): ?>
                                <span class="absolute -right-1 -top-1 flex h-5 min-w-5 items-center justify-center rounded-full bg-red-600 px-1 text-[0.65rem] font-bold leading-none text-white">
                                    <?php echo e($unreadCount > 9 ? '9+' : $unreadCount); ?>
                                </span>
                            <?php endif; ?>
                        </a>
                    <?php endif; ?>
                    <a class="rounded-md bg-red-600 px-3 py-2 font-medium text-white hover:bg-red-700" href="index.php?controller=auth&action=logout">Logout</a>
                <?php else: ?>
                    <a class="rounded-md px-3 py-2 hover:bg-slate-100" href="index.php?controller=auth&action=showLogin">Login</a>
                    <a class="rounded-md bg-brand-500 px-3 py-2 font-medium text-white hover:bg-brand-600" href="index.php?controller=auth&action=showRegister">Register</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>
    <?php
}

?>
