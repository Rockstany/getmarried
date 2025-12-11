<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/functions.php';

$currentPage = basename($_SERVER['PHP_SELF'], '.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?php echo $pageDescription ?? 'Plan your dream budget wedding and shop pre-owned wedding outfits. GetMarried.site - Making weddings affordable and beautiful.'; ?>">
    <title><?php echo $pageTitle ?? 'GetMarried.site - Budget Wedding Planner & Pre-Owned Outfits'; ?></title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="<?php echo SITE_URL; ?>/assets/images/favicon.png">

    <!-- CSS -->
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>/assets/css/style.css">

    <!-- Google Fonts (Optional) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <!-- Open Graph Meta Tags -->
    <meta property="og:title" content="<?php echo $pageTitle ?? SITE_NAME; ?>">
    <meta property="og:description" content="<?php echo $pageDescription ?? 'Plan your budget wedding'; ?>">
    <meta property="og:image" content="<?php echo SITE_URL; ?>/assets/images/og-image.jpg">
    <meta property="og:url" content="<?php echo SITE_URL . $_SERVER['REQUEST_URI']; ?>">
    <meta property="og:type" content="website">

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?php echo $pageTitle ?? SITE_NAME; ?>">
    <meta name="twitter:description" content="<?php echo $pageDescription ?? 'Plan your budget wedding'; ?>">
    <meta name="twitter:image" content="<?php echo SITE_URL; ?>/assets/images/og-image.jpg">

    <?php if (isset($additionalHead)) echo $additionalHead; ?>
</head>
<body>

<!-- Header -->
<header class="header">
    <div class="container">
        <div class="flex items-center justify-between">
            <!-- Logo -->
            <a href="<?php echo SITE_URL; ?>/index.php" class="logo">
                GetMarried
            </a>

            <!-- Desktop Navigation -->
            <nav class="nav-desktop hide-mobile">
                <ul class="nav-links">
                    <li><a href="<?php echo SITE_URL; ?>/index.php" class="<?php echo $currentPage === 'index' ? 'active' : ''; ?>">Home</a></li>
                    <li><a href="<?php echo SITE_URL; ?>/planner.php" class="<?php echo $currentPage === 'planner' ? 'active' : ''; ?>">Plan</a></li>
                    <li><a href="<?php echo SITE_URL; ?>/marketplace.php" class="<?php echo $currentPage === 'marketplace' ? 'active' : ''; ?>">Shop</a></li>
                    <li><a href="<?php echo SITE_URL; ?>/ideas.php" class="<?php echo $currentPage === 'ideas' ? 'active' : ''; ?>">Ideas</a></li>
                    <li><a href="<?php echo SITE_URL; ?>/stories.php" class="<?php echo $currentPage === 'stories' ? 'active' : ''; ?>">Stories</a></li>
                </ul>
            </nav>

            <!-- Right Actions -->
            <div class="nav-actions">
                <a href="<?php echo SITE_URL; ?>/sell.php" class="btn btn-primary btn-sm hide-mobile">
                    Sell
                </a>

                <?php if (isLoggedIn()): ?>
                    <a href="<?php echo SITE_URL; ?>/dashboard.php" class="btn btn-secondary btn-sm">
                        Dashboard
                    </a>
                    <a href="<?php echo SITE_URL; ?>/api/auth/logout.php" class="btn btn-outline btn-sm hide-mobile">
                        Logout
                    </a>
                <?php else: ?>
                    <button onclick="openLoginModal()" class="btn btn-outline btn-sm">
                        Login
                    </button>
                <?php endif; ?>

                <!-- Mobile Hamburger -->
                <button class="hamburger hide-desktop" aria-label="Menu">
                    <span></span>
                    <span></span>
                    <span></span>
                </button>
            </div>
        </div>
    </div>
</header>

<!-- Mobile Menu -->
<div class="mobile-menu">
    <ul>
        <li><a href="<?php echo SITE_URL; ?>/index.php">Home</a></li>
        <li><a href="<?php echo SITE_URL; ?>/planner.php">Plan</a></li>
        <li><a href="<?php echo SITE_URL; ?>/marketplace.php">Shop</a></li>
        <li><a href="<?php echo SITE_URL; ?>/sell.php">Sell</a></li>
        <li><a href="<?php echo SITE_URL; ?>/ideas.php">Ideas</a></li>
        <li><a href="<?php echo SITE_URL; ?>/stories.php">Stories</a></li>
        <?php if (isLoggedIn()): ?>
            <li><a href="<?php echo SITE_URL; ?>/dashboard.php">Dashboard</a></li>
            <li><a href="<?php echo SITE_URL; ?>/api/auth/logout.php">Logout</a></li>
        <?php else: ?>
            <li><a href="#" onclick="openLoginModal()">Login</a></li>
            <li><a href="#" onclick="openSignupModal()">Sign Up</a></li>
        <?php endif; ?>
    </ul>
</div>

<!-- Login Modal -->
<div id="loginModal" class="modal-overlay hidden">
    <div class="modal">
        <div class="modal-header">
            <h3>Welcome Back</h3>
            <button data-close-modal aria-label="Close">&times;</button>
        </div>
        <div class="modal-body">
            <form id="loginForm">
                <div class="form-group">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-input" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-input" required>
                </div>
                <button type="submit" class="btn btn-primary btn-block">Login</button>
            </form>
            <p class="text-center mt-md">
                Don't have an account? <a href="#" onclick="openSignupModal()">Sign up</a>
            </p>
        </div>
    </div>
</div>

<!-- Signup Modal -->
<div id="signupModal" class="modal-overlay hidden">
    <div class="modal">
        <div class="modal-header">
            <h3>Create Account</h3>
            <button data-close-modal aria-label="Close">&times;</button>
        </div>
        <div class="modal-body">
            <form id="signupForm">
                <div class="form-group">
                    <label class="form-label">Full Name</label>
                    <input type="text" name="full_name" class="form-input" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-input" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Phone</label>
                    <input type="tel" name="phone" class="form-input" placeholder="10-digit number">
                </div>
                <div class="form-group">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-input" required minlength="6">
                </div>
                <button type="submit" class="btn btn-primary btn-block">Sign Up</button>
            </form>
            <p class="text-center mt-md">
                Already have an account? <a href="#" onclick="openLoginModal()">Login</a>
            </p>
        </div>
    </div>
</div>

<script>
// Modal Controls
const loginModal = new Modal('loginModal');
const signupModal = new Modal('signupModal');

function openLoginModal() {
    signupModal.close();
    loginModal.open();
}

function openSignupModal() {
    loginModal.close();
    signupModal.open();
}

// Login Form Handler
document.getElementById('loginForm')?.addEventListener('submit', async (e) => {
    e.preventDefault();
    const btn = e.target.querySelector('button[type="submit"]');
    App.showLoading(btn);

    const formData = new FormData(e.target);
    const response = await App.request('/auth/login.php', {
        method: 'POST',
        body: JSON.stringify(Object.fromEntries(formData))
    });

    App.hideLoading(btn);

    if (response.success) {
        App.showToast('Login successful!', 'success');
        setTimeout(() => window.location.reload(), 1000);
    } else {
        App.showToast(response.error || 'Login failed', 'error');
    }
});

// Signup Form Handler
document.getElementById('signupForm')?.addEventListener('submit', async (e) => {
    e.preventDefault();
    const btn = e.target.querySelector('button[type="submit"]');
    App.showLoading(btn);

    const formData = new FormData(e.target);
    const response = await App.request('/auth/signup.php', {
        method: 'POST',
        body: JSON.stringify(Object.fromEntries(formData))
    });

    App.hideLoading(btn);

    if (response.success) {
        App.showToast('Account created successfully!', 'success');
        setTimeout(() => window.location.reload(), 1000);
    } else {
        App.showToast(response.error || 'Signup failed', 'error');
    }
});

// 🔧 MODAL CLOSE FIX
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('[data-close-modal]').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            const modal = this.closest('.modal-overlay');
            if (modal) {
                modal.classList.add('hidden');
                document.body.style.overflow = '';
            }
        });
    });
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            document.querySelectorAll('.modal-overlay:not(.hidden)').forEach(m => m.classList.add('hidden'));
            document.body.style.overflow = '';
        }
    });
    document.querySelectorAll('.modal-overlay').forEach(overlay => {
        overlay.addEventListener('click', (e) => {
            if (e.target === overlay) {
                overlay.classList.add('hidden');
                document.body.style.overflow = '';
            }
        });
    });
});
</script>

<!-- Main Content -->
<main>
