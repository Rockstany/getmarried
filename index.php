<?php
$pageTitle = 'GetMarried.site - Budget Wedding Planner & Pre-Owned Outfits Marketplace';
$pageDescription = 'Plan your dream wedding on a budget and shop beautiful pre-owned wedding outfits. Budget planner, marketplace, and inspiration - all in one place.';

include 'includes/header.php';

// Get featured listings
try {
    $db = getDB();
    $stmt = $db->prepare("
        SELECT l.*, u.full_name as seller_name, u.is_verified,
               (SELECT file_path FROM listing_images WHERE listing_id = l.id ORDER BY sort_order LIMIT 1) as image_path
        FROM listings l
        JOIN users u ON l.seller_id = u.id
        WHERE l.status = 'published' AND l.featured = TRUE
        ORDER BY l.created_at DESC
        LIMIT 8
    ");
    $stmt->execute();
    $featuredListings = $stmt->fetchAll();
} catch (Exception $e) {
    $featuredListings = [];
}
?>

<!-- Hero Section -->
<section class="section bg-secondary" style="background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%); color: white; padding: 4rem 0;">
    <div class="container">
        <div class="grid grid-cols-1 grid-cols-lg-2 gap-lg items-center">
            <div>
                <h1 style="color: white; font-size: clamp(2rem, 5vw, 3.5rem); margin-bottom: 1.5rem;">
                    Your Dream Wedding, Your Budget
                </h1>
                <p style="font-size: 1.25rem; margin-bottom: 2rem; opacity: 0.95;">
                    Plan the perfect wedding without breaking the bank. Get instant budget breakdowns and shop beautiful pre-owned wedding outfits.
                </p>
                <div class="flex flex-wrap gap-md">
                    <a href="<?php echo SITE_URL; ?>/planner.php" class="btn btn-lg" style="background: white; color: var(--primary);">
                        <svg width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                            <path d="M7.5 7.5A.5.5 0 0 1 8 8v4a.5.5 0 0 1-1 0V8a.5.5 0 0 1 .5-.5z"/>
                            <path d="M8 3.5a.5.5 0 0 1 .5.5v.5a.5.5 0 0 1-1 0V4a.5.5 0 0 1 .5-.5z"/>
                        </svg>
                        Start Planning Free
                    </a>
                    <a href="<?php echo SITE_URL; ?>/marketplace.php" class="btn btn-outline btn-lg" style="border-color: white; color: white;">
                        Shop Pre-Owned
                    </a>
                </div>

                <!-- Trust Indicators -->
                <div class="flex flex-wrap gap-lg mt-xl" style="opacity: 0.9;">
                    <div>
                        <div style="font-size: 2rem; font-weight: 700;">500+</div>
                        <div style="font-size: 0.875rem;">Weddings Planned</div>
                    </div>
                    <div>
                        <div style="font-size: 2rem; font-weight: 700;">₹1L+</div>
                        <div style="font-size: 0.875rem;">Avg. Savings</div>
                    </div>
                    <div>
                        <div style="font-size: 2rem; font-weight: 700;">1000+</div>
                        <div style="font-size: 0.875rem;">Outfits Listed</div>
                    </div>
                </div>
            </div>

            <div class="hide-mobile">
                <img src="<?php echo SITE_URL; ?>/assets/images/hero-illustration.svg" alt="Wedding Planning" style="width: 100%; max-width: 500px; margin: 0 auto;">
            </div>
        </div>
    </div>
</section>

<!-- How It Works -->
<section class="section">
    <div class="container">
        <h2 class="text-center mb-xl">How It Works</h2>

        <div class="grid grid-cols-1 grid-cols-sm-3 gap-lg">
            <!-- Step 1 -->
            <div class="text-center">
                <div style="width: 80px; height: 80px; background: var(--primary-light); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem; font-size: 2rem; font-weight: 700; color: var(--primary);">
                    1
                </div>
                <h3 style="font-size: 1.25rem; margin-bottom: 0.5rem;">Set Your Budget</h3>
                <p class="text-secondary">Tell us your budget, city, and event type. Get instant personalized breakdown.</p>
            </div>

            <!-- Step 2 -->
            <div class="text-center">
                <div style="width: 80px; height: 80px; background: var(--primary-light); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem; font-size: 2rem; font-weight: 700; color: var(--primary);">
                    2
                </div>
                <h3 style="font-size: 1.25rem; margin-bottom: 0.5rem;">Browse & Save</h3>
                <p class="text-secondary">Explore pre-owned outfits, save favorites, and get matched with sellers.</p>
            </div>

            <!-- Step 3 -->
            <div class="text-center">
                <div style="width: 80px; height: 80px; background: var(--primary-light); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem; font-size: 2rem; font-weight: 700; color: var(--primary);">
                    3
                </div>
                <h3 style="font-size: 1.25rem; margin-bottom: 0.5rem;">Plan & Execute</h3>
                <p class="text-secondary">Follow your checklist, track spending, and have a beautiful wedding!</p>
            </div>
        </div>
    </div>
</section>

<!-- Featured Listings -->
<?php if (!empty($featuredListings)): ?>
<section class="section bg-secondary">
    <div class="container">
        <div class="flex justify-between items-center mb-xl">
            <h2>Featured Outfits</h2>
            <a href="<?php echo SITE_URL; ?>/marketplace.php" class="btn btn-outline">View All</a>
        </div>

        <div class="grid grid-cols-2 grid-cols-sm-3 grid-cols-lg-4 gap-md">
            <?php foreach ($featuredListings as $listing): ?>
                <a href="<?php echo SITE_URL; ?>/listing.php?id=<?php echo $listing['id']; ?>" class="card">
                    <?php if ($listing['image_path']): ?>
                        <img src="<?php echo UPLOAD_URL . basename($listing['image_path']); ?>" alt="<?php echo e($listing['title']); ?>" class="card-img">
                    <?php else: ?>
                        <div class="card-img" style="background: var(--bg-tertiary); display: flex; align-items: center; justify-content: center; color: var(--text-light);">
                            No Image
                        </div>
                    <?php endif; ?>

                    <div class="card-body">
                        <div class="flex justify-between items-start gap-sm mb-sm">
                            <h3 class="card-title" style="font-size: 1rem; margin: 0; flex: 1;">
                                <?php echo e(substr($listing['title'], 0, 40)) . (strlen($listing['title']) > 40 ? '...' : ''); ?>
                            </h3>
                            <?php if ($listing['is_verified']): ?>
                                <span class="badge badge-success" style="font-size: 0.6rem;">✓</span>
                            <?php endif; ?>
                        </div>

                        <div style="font-size: 1.25rem; font-weight: 700; color: var(--primary); margin-bottom: 0.5rem;">
                            <?php echo formatPrice($listing['price']); ?>
                        </div>

                        <div class="flex justify-between items-center" style="font-size: 0.875rem; color: var(--text-secondary);">
                            <span><?php echo e($listing['city']); ?></span>
                            <span class="badge badge-primary"><?php echo ucfirst(str_replace('_', ' ', $listing['condition_type'])); ?></span>
                        </div>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Quick Planner Teaser -->
<section class="section">
    <div class="container">
        <div class="card" style="padding: 3rem; background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%); text-align: center;">
            <h2 style="margin-bottom: 1rem;">Not sure where to start?</h2>
            <p style="font-size: 1.125rem; margin-bottom: 2rem; max-width: 600px; margin-left: auto; margin-right: auto;">
                Our free budget planner helps you allocate funds across venues, catering, outfits, and more based on your total budget.
            </p>
            <a href="<?php echo SITE_URL; ?>/planner.php" class="btn btn-primary btn-lg">
                Get Your Free Plan
            </a>
        </div>
    </div>
</section>

<!-- Features Grid -->
<section class="section bg-secondary">
    <div class="container">
        <h2 class="text-center mb-xl">Why GetMarried.site?</h2>

        <div class="grid grid-cols-1 grid-cols-sm-2 grid-cols-lg-3 gap-lg">
            <div class="card">
                <div class="card-body">
                    <div style="font-size: 2.5rem; margin-bottom: 1rem;">💰</div>
                    <h3 class="card-title">Save Money</h3>
                    <p class="card-text">Get pre-owned designer outfits at 50-80% off retail prices.</p>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <div style="font-size: 2.5rem; margin-bottom: 1rem;">📊</div>
                    <h3 class="card-title">Smart Planning</h3>
                    <p class="card-text">Instant budget breakdowns tailored to your city and event type.</p>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <div style="font-size: 2.5rem; margin-bottom: 1rem;">🌱</div>
                    <h3 class="card-title">Sustainable</h3>
                    <p class="card-text">Give beautiful outfits a second life and reduce fashion waste.</p>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <div style="font-size: 2.5rem; margin-bottom: 1rem;">✅</div>
                    <h3 class="card-title">Verified Sellers</h3>
                    <p class="card-text">All sellers are verified for your safety and peace of mind.</p>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <div style="font-size: 2.5rem; margin-bottom: 1rem;">📱</div>
                    <h3 class="card-title">Easy Contact</h3>
                    <p class="card-text">Connect instantly with sellers via WhatsApp or in-app messaging.</p>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <div style="font-size: 2.5rem; margin-bottom: 1rem;">📝</div>
                    <h3 class="card-title">Checklists</h3>
                    <p class="card-text">Never miss a step with our comprehensive wedding checklists.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="section">
    <div class="container">
        <div class="grid grid-cols-1 grid-cols-sm-2 gap-lg">
            <!-- For Buyers -->
            <div class="card" style="background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%); color: white;">
                <div class="card-body" style="padding: 2rem;">
                    <h3 style="color: white; margin-bottom: 1rem;">Planning a Wedding?</h3>
                    <p style="opacity: 0.9; margin-bottom: 1.5rem;">
                        Start with our free budget planner and discover affordable outfit options.
                    </p>
                    <a href="<?php echo SITE_URL; ?>/planner.php" class="btn btn-lg" style="background: white; color: var(--primary);">
                        Start Planning
                    </a>
                </div>
            </div>

            <!-- For Sellers -->
            <div class="card" style="background: linear-gradient(135deg, var(--secondary) 0%, var(--accent) 100%); color: white;">
                <div class="card-body" style="padding: 2rem;">
                    <h3 style="color: white; margin-bottom: 1rem;">Have an Outfit to Sell?</h3>
                    <p style="opacity: 0.9; margin-bottom: 1.5rem;">
                        List your pre-owned wedding outfit for free and reach thousands of buyers.
                    </p>
                    <a href="<?php echo SITE_URL; ?>/sell.php" class="btn btn-lg" style="background: white; color: var(--secondary);">
                        Start Selling
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
// Track homepage view
App.trackEvent('homepage_view');
</script>

<?php include 'includes/footer.php'; ?>
