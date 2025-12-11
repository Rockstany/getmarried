<?php
$pageTitle = 'Shop Pre-Owned Wedding Outfits - GetMarried.site';
$pageDescription = 'Browse beautiful pre-owned wedding outfits at affordable prices. Lehengas, sherwanis, and more from verified sellers.';

include 'includes/header.php';

// Get filter parameters
$searchQuery = isset($_GET['q']) ? sanitize($_GET['q']) : '';
$category = isset($_GET['category']) ? sanitize($_GET['category']) : '';
$city = isset($_GET['city']) ? sanitize($_GET['city']) : '';
$minPrice = isset($_GET['min_price']) ? intval($_GET['min_price']) : 0;
$maxPrice = isset($_GET['max_price']) ? intval($_GET['max_price']) : 1000000;
$condition = isset($_GET['condition']) ? sanitize($_GET['condition']) : '';
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$offset = ($page - 1) * ITEMS_PER_PAGE;

try {
    $db = getDB();

    // Build query
    $whereClauses = ["l.status = 'published'"];
    $params = [];

    if (!empty($searchQuery)) {
        $whereClauses[] = "(l.title LIKE ? OR l.description LIKE ?)";
        $searchParam = '%' . $searchQuery . '%';
        $params[] = $searchParam;
        $params[] = $searchParam;
    }

    if (!empty($category)) {
        $whereClauses[] = "l.category = ?";
        $params[] = $category;
    }

    if (!empty($city)) {
        $whereClauses[] = "l.city = ?";
        $params[] = $city;
    }

    if ($minPrice > 0) {
        $whereClauses[] = "l.price >= ?";
        $params[] = $minPrice;
    }

    if ($maxPrice < 1000000) {
        $whereClauses[] = "l.price <= ?";
        $params[] = $maxPrice;
    }

    if (!empty($condition)) {
        $whereClauses[] = "l.condition_type = ?";
        $params[] = $condition;
    }

    $whereSQL = implode(' AND ', $whereClauses);

    // Get total count
    $countStmt = $db->prepare("SELECT COUNT(*) as total FROM listings l WHERE $whereSQL");
    $countStmt->execute($params);
    $totalItems = $countStmt->fetch()['total'];
    $totalPages = ceil($totalItems / ITEMS_PER_PAGE);

    // Get listings
    $stmt = $db->prepare("
        SELECT l.*, u.full_name as seller_name, u.is_verified,
               (SELECT file_path FROM listing_images WHERE listing_id = l.id ORDER BY sort_order LIMIT 1) as image_path
        FROM listings l
        JOIN users u ON l.seller_id = u.id
        WHERE $whereSQL
        ORDER BY l.featured DESC, l.created_at DESC
        LIMIT " . ITEMS_PER_PAGE . " OFFSET $offset
    ");
    $stmt->execute($params);
    $listings = $stmt->fetchAll();

} catch (Exception $e) {
    error_log("Marketplace Error: " . $e->getMessage());
    $listings = [];
    $totalItems = 0;
    $totalPages = 0;
}
?>

<div class="section">
    <div class="container">

        <!-- Page Header -->
        <div class="flex justify-between items-center mb-lg flex-wrap gap-md">
            <div>
                <h1>Pre-Owned Wedding Outfits</h1>
                <p class="text-secondary">
                    <?php echo number_format($totalItems); ?> outfit<?php echo $totalItems !== 1 ? 's' : ''; ?> available
                </p>
            </div>
            <a href="<?php echo SITE_URL; ?>/sell.php" class="btn btn-primary">
                Sell Your Outfit
            </a>
        </div>

        <!-- Filters -->
        <div class="card mb-lg" style="padding: 1.5rem;">
            <form id="filterForm" method="GET" action="marketplace.php">
                <div class="grid grid-cols-1 grid-cols-sm-2 grid-cols-lg-4 gap-md">

                    <!-- Search -->
                    <div class="form-group" style="margin-bottom: 0;">
                        <input
                            type="text"
                            name="q"
                            class="form-input"
                            placeholder="Search outfits..."
                            value="<?php echo e($searchQuery); ?>"
                        >
                    </div>

                    <!-- Category -->
                    <div class="form-group" style="margin-bottom: 0;">
                        <select name="category" class="form-select">
                            <option value="">All Categories</option>
                            <option value="bridal_lehenga" <?php echo $category === 'bridal_lehenga' ? 'selected' : ''; ?>>Bridal Lehenga</option>
                            <option value="groom_sherwani" <?php echo $category === 'groom_sherwani' ? 'selected' : ''; ?>>Groom Sherwani</option>
                            <option value="bridesmaid" <?php echo $category === 'bridesmaid' ? 'selected' : ''; ?>>Bridesmaid</option>
                            <option value="accessories" <?php echo $category === 'accessories' ? 'selected' : ''; ?>>Accessories</option>
                            <option value="jewelry" <?php echo $category === 'jewelry' ? 'selected' : ''; ?>>Jewelry</option>
                            <option value="other" <?php echo $category === 'other' ? 'selected' : ''; ?>>Other</option>
                        </select>
                    </div>

                    <!-- City -->
                    <div class="form-group" style="margin-bottom: 0;">
                        <select name="city" class="form-select">
                            <option value="">All Cities</option>
                            <option value="Bengaluru" <?php echo $city === 'Bengaluru' ? 'selected' : ''; ?>>Bengaluru</option>
                            <option value="Mumbai" <?php echo $city === 'Mumbai' ? 'selected' : ''; ?>>Mumbai</option>
                            <option value="Delhi" <?php echo $city === 'Delhi' ? 'selected' : ''; ?>>Delhi</option>
                            <option value="Hyderabad" <?php echo $city === 'Hyderabad' ? 'selected' : ''; ?>>Hyderabad</option>
                            <option value="Chennai" <?php echo $city === 'Chennai' ? 'selected' : ''; ?>>Chennai</option>
                            <option value="Kolkata" <?php echo $city === 'Kolkata' ? 'selected' : ''; ?>>Kolkata</option>
                            <option value="Pune" <?php echo $city === 'Pune' ? 'selected' : ''; ?>>Pune</option>
                        </select>
                    </div>

                    <!-- Condition -->
                    <div class="form-group" style="margin-bottom: 0;">
                        <select name="condition" class="form-select">
                            <option value="">All Conditions</option>
                            <option value="new" <?php echo $condition === 'new' ? 'selected' : ''; ?>>New</option>
                            <option value="like_new" <?php echo $condition === 'like_new' ? 'selected' : ''; ?>>Like New</option>
                            <option value="gently_used" <?php echo $condition === 'gently_used' ? 'selected' : ''; ?>>Gently Used</option>
                            <option value="used" <?php echo $condition === 'used' ? 'selected' : ''; ?>>Used</option>
                        </select>
                    </div>

                </div>

                <!-- Price Range -->
                <div class="mt-md">
                    <label class="form-label">Price Range: <span id="priceRangeLabel"><?php echo formatPrice($minPrice) . ' - ' . formatPrice($maxPrice); ?></span></label>
                    <div class="flex gap-md items-center">
                        <input
                            type="range"
                            name="min_price"
                            id="minPrice"
                            min="0"
                            max="500000"
                            step="10000"
                            value="<?php echo $minPrice; ?>"
                            style="flex: 1;"
                        >
                        <input
                            type="range"
                            name="max_price"
                            id="maxPrice"
                            min="10000"
                            max="1000000"
                            step="10000"
                            value="<?php echo $maxPrice; ?>"
                            style="flex: 1;"
                        >
                    </div>
                </div>

                <div class="flex gap-sm mt-md">
                    <button type="submit" class="btn btn-primary">Apply Filters</button>
                    <a href="marketplace.php" class="btn btn-secondary">Clear</a>
                </div>
            </form>
        </div>

        <!-- Listings Grid -->
        <?php if (empty($listings)): ?>
            <div class="text-center" style="padding: 4rem 0;">
                <div style="font-size: 4rem; margin-bottom: 1rem;">🔍</div>
                <h3>No outfits found</h3>
                <p class="text-secondary mb-md">Try adjusting your filters or search query</p>
                <a href="marketplace.php" class="btn btn-outline">View All Outfits</a>
            </div>
        <?php else: ?>
            <div class="grid grid-cols-2 grid-cols-sm-3 grid-cols-lg-4 gap-md mb-lg">
                <?php foreach ($listings as $listing): ?>
                    <a href="<?php echo SITE_URL; ?>/listing.php?id=<?php echo $listing['id']; ?>" class="card">
                        <?php if ($listing['image_path']): ?>
                            <img
                                data-src="<?php echo UPLOAD_URL . basename($listing['image_path']); ?>"
                                alt="<?php echo e($listing['title']); ?>"
                                class="card-img skeleton"
                                loading="lazy"
                            >
                        <?php else: ?>
                            <div class="card-img" style="background: var(--bg-tertiary); display: flex; align-items: center; justify-content: center; color: var(--text-light);">
                                No Image
                            </div>
                        <?php endif; ?>

                        <div class="card-body">
                            <div class="flex justify-between items-start gap-sm mb-sm">
                                <h3 class="card-title" style="font-size: 1rem; margin: 0; flex: 1;">
                                    <?php echo e(substr($listing['title'], 0, 50)) . (strlen($listing['title']) > 50 ? '...' : ''); ?>
                                </h3>
                                <?php if ($listing['is_verified']): ?>
                                    <span class="badge badge-success" style="font-size: 0.6rem;" title="Verified Seller">✓</span>
                                <?php endif; ?>
                            </div>

                            <div style="font-size: 1.25rem; font-weight: 700; color: var(--primary); margin-bottom: 0.5rem;">
                                <?php echo formatPrice($listing['price']); ?>
                            </div>

                            <div class="flex justify-between items-center" style="font-size: 0.875rem; color: var(--text-secondary);">
                                <span>📍 <?php echo e($listing['city']); ?></span>
                                <span class="badge badge-primary" style="font-size: 0.65rem;">
                                    <?php echo ucfirst(str_replace('_', ' ', $listing['condition_type'])); ?>
                                </span>
                            </div>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>

            <!-- Pagination -->
            <?php if ($totalPages > 1): ?>
                <div class="flex justify-center gap-sm">
                    <?php if ($page > 1): ?>
                        <a href="?<?php echo http_build_query(array_merge($_GET, ['page' => $page - 1])); ?>" class="btn btn-secondary">
                            ← Previous
                        </a>
                    <?php endif; ?>

                    <span class="btn btn-secondary" style="pointer-events: none;">
                        Page <?php echo $page; ?> of <?php echo $totalPages; ?>
                    </span>

                    <?php if ($page < $totalPages): ?>
                        <a href="?<?php echo http_build_query(array_merge($_GET, ['page' => $page + 1])); ?>" class="btn btn-secondary">
                            Next →
                        </a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        <?php endif; ?>

    </div>
</div>

<script>
// Price range slider update
const minPriceInput = document.getElementById('minPrice');
const maxPriceInput = document.getElementById('maxPrice');
const priceRangeLabel = document.getElementById('priceRangeLabel');

function updatePriceLabel() {
    const min = parseInt(minPriceInput.value);
    const max = parseInt(maxPriceInput.value);
    priceRangeLabel.textContent = `${App.formatPrice(min)} - ${App.formatPrice(max)}`;
}

minPriceInput?.addEventListener('input', updatePriceLabel);
maxPriceInput?.addEventListener('input', updatePriceLabel);

// Track marketplace view
App.trackEvent('marketplace_view', {
    filters: {
        category: '<?php echo $category; ?>',
        city: '<?php echo $city; ?>',
        search: '<?php echo $searchQuery; ?>'
    }
});
</script>

<?php include 'includes/footer.php'; ?>
