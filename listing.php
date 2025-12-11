<?php
require_once 'includes/config.php';
require_once 'includes/db.php';
require_once 'includes/functions.php';

// Get listing ID
$listingId = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($listingId <= 0) {
    header('Location: ' . SITE_URL . '/marketplace.php');
    exit;
}

try {
    $db = getDB();

    // Get listing details
    $stmt = $db->prepare("
        SELECT l.*, u.id as seller_id, u.full_name as seller_name, u.is_verified as seller_verified, u.email as seller_email
        FROM listings l
        JOIN users u ON l.seller_id = u.id
        WHERE l.id = ? AND l.status = 'published'
    ");
    $stmt->execute([$listingId]);
    $listing = $stmt->fetch();

    if (!$listing) {
        header('Location: ' . SITE_URL . '/marketplace.php');
        exit;
    }

    // Get images
    $stmt = $db->prepare("SELECT * FROM listing_images WHERE listing_id = ? ORDER BY sort_order");
    $stmt->execute([$listingId]);
    $images = $stmt->fetchAll();

    // Update view count
    $stmt = $db->prepare("UPDATE listings SET views_count = views_count + 1 WHERE id = ?");
    $stmt->execute([$listingId]);

    // Get similar listings
    $stmt = $db->prepare("
        SELECT l.*, (SELECT file_path FROM listing_images WHERE listing_id = l.id ORDER BY sort_order LIMIT 1) as image_path
        FROM listings l
        WHERE l.id != ? AND l.status = 'published'
          AND (l.category = ? OR l.city = ?)
        ORDER BY RAND()
        LIMIT 4
    ");
    $stmt->execute([$listingId, $listing['category'], $listing['city']]);
    $similarListings = $stmt->fetchAll();

} catch (Exception $e) {
    error_log("Listing Error: " . $e->getMessage());
    header('Location: ' . SITE_URL . '/marketplace.php');
    exit;
}

$pageTitle = e($listing['title']) . ' - GetMarried.site';
$pageDescription = e(substr($listing['description'], 0, 150));

include 'includes/header.php';
?>

<div class="section">
    <div class="container" style="max-width: 1200px;">

        <!-- Breadcrumb -->
        <div class="mb-md" style="font-size: 0.875rem; color: var(--text-secondary);">
            <a href="<?php echo SITE_URL; ?>/index.php">Home</a> /
            <a href="<?php echo SITE_URL; ?>/marketplace.php">Marketplace</a> /
            <span><?php echo e($listing['title']); ?></span>
        </div>

        <div class="grid grid-cols-1 grid-cols-lg-2 gap-xl">

            <!-- Left Column: Images -->
            <div>
                <!-- Main Image -->
                <div class="card mb-md" style="padding: 0; overflow: hidden;">
                    <?php if (!empty($images)): ?>
                        <img
                            id="mainImage"
                            src="<?php echo UPLOAD_URL . basename($images[0]['file_path']); ?>"
                            alt="<?php echo e($images[0]['alt_text'] ?: $listing['title']); ?>"
                            style="width: 100%; height: auto; max-height: 500px; object-fit: contain; background: var(--bg-secondary);"
                        >
                    <?php else: ?>
                        <div style="width: 100%; height: 400px; background: var(--bg-tertiary); display: flex; align-items: center; justify-content: center; color: var(--text-light);">
                            <div style="text-align: center;">
                                <div style="font-size: 4rem; margin-bottom: 1rem;">📷</div>
                                <div>No images available</div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Thumbnail Gallery -->
                <?php if (count($images) > 1): ?>
                    <div class="grid grid-cols-4 gap-sm">
                        <?php foreach ($images as $index => $image): ?>
                            <div
                                class="thumbnail-item <?php echo $index === 0 ? 'active' : ''; ?>"
                                onclick="changeMainImage('<?php echo UPLOAD_URL . basename($image['file_path']); ?>', this)"
                                style="cursor: pointer; border: 3px solid transparent; border-radius: var(--radius-md); overflow: hidden; transition: var(--transition-fast);"
                            >
                                <img
                                    src="<?php echo UPLOAD_URL . basename($image['file_path']); ?>"
                                    alt="Thumbnail <?php echo $index + 1; ?>"
                                    style="width: 100%; height: 80px; object-fit: cover;"
                                >
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Right Column: Details -->
            <div>
                <!-- Title & Price -->
                <div class="mb-lg">
                    <h1 class="mb-sm"><?php echo e($listing['title']); ?></h1>

                    <div class="flex items-center gap-md mb-md flex-wrap">
                        <div style="font-size: 2.5rem; font-weight: 700; color: var(--primary);">
                            <?php echo formatPrice($listing['price']); ?>
                        </div>
                        <span class="badge badge-primary">
                            <?php echo ucfirst(str_replace('_', ' ', $listing['condition_type'])); ?>
                        </span>
                        <span class="badge badge-success">
                            <?php echo ucfirst(str_replace('_', ' ', $listing['category'])); ?>
                        </span>
                    </div>

                    <div class="flex items-center gap-md text-secondary">
                        <span>📍 <?php echo e($listing['city']); ?></span>
                        <span>•</span>
                        <span>👁️ <?php echo number_format($listing['views_count']); ?> views</span>
                        <span>•</span>
                        <span>⏰ <?php echo timeAgo($listing['created_at']); ?></span>
                    </div>
                </div>

                <!-- Description -->
                <div class="card mb-lg">
                    <div class="card-body">
                        <h3 class="mb-md">Description</h3>
                        <p style="white-space: pre-line;"><?php echo e($listing['description']); ?></p>
                    </div>
                </div>

                <!-- Seller Card -->
                <div class="card mb-lg" style="background: var(--bg-secondary);">
                    <div class="card-body">
                        <h3 class="mb-md">Seller Information</h3>
                        <div class="flex items-center gap-md mb-md">
                            <div style="width: 60px; height: 60px; border-radius: 50%; background: var(--primary-light); display: flex; align-items: center; justify-content: center; font-size: 1.5rem; font-weight: 700; color: var(--primary);">
                                <?php echo strtoupper(substr($listing['seller_name'], 0, 1)); ?>
                            </div>
                            <div style="flex: 1;">
                                <div style="font-weight: 600; font-size: 1.125rem;">
                                    <?php echo e($listing['seller_name']); ?>
                                    <?php if ($listing['seller_verified']): ?>
                                        <span class="badge badge-success" style="margin-left: 0.5rem;">✓ Verified</span>
                                    <?php endif; ?>
                                </div>
                                <div class="text-secondary">Member since <?php echo date('M Y', strtotime($listing['created_at'])); ?></div>
                            </div>
                        </div>

                        <!-- Contact Buttons -->
                        <div class="flex flex-col gap-sm">
                            <?php if ($listing['contact_method'] === 'whatsapp' || $listing['contact_method'] === 'phone'): ?>
                                <?php
                                    $message = "Hi! I'm interested in your listing: " . $listing['title'] . " (" . formatPrice($listing['price']) . ") on GetMarried.site";
                                    $whatsappUrl = getWhatsAppLink($listing['contact_value'], $message);
                                ?>
                                <a href="<?php echo $whatsappUrl; ?>" target="_blank" class="btn btn-primary btn-lg" onclick="trackContact('whatsapp')">
                                    <svg width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                                        <path d="M13.601 2.326A7.854 7.854 0 0 0 7.994 0C3.627 0 .068 3.558.064 7.926c0 1.399.366 2.76 1.057 3.965L0 16l4.204-1.102a7.933 7.933 0 0 0 3.79.965h.004c4.368 0 7.926-3.558 7.93-7.93A7.898 7.898 0 0 0 13.6 2.326zM7.994 14.521a6.573 6.573 0 0 1-3.356-.92l-.24-.144-2.494.654.666-2.433-.156-.251a6.56 6.56 0 0 1-1.007-3.505c0-3.626 2.957-6.584 6.591-6.584a6.56 6.56 0 0 1 4.66 1.931 6.557 6.557 0 0 1 1.928 4.66c-.004 3.639-2.961 6.592-6.592 6.592zm3.615-4.934c-.197-.099-1.17-.578-1.353-.646-.182-.065-.315-.099-.445.099-.133.197-.513.646-.627.775-.114.133-.232.148-.43.05-.197-.1-.836-.308-1.592-.985-.59-.525-.985-1.175-1.103-1.372-.114-.198-.011-.304.088-.403.087-.088.197-.232.296-.346.1-.114.133-.198.198-.33.065-.134.034-.248-.015-.347-.05-.099-.445-1.076-.612-1.47-.16-.389-.323-.335-.445-.34-.114-.007-.247-.007-.38-.007a.729.729 0 0 0-.529.247c-.182.198-.691.677-.691 1.654 0 .977.71 1.916.81 2.049.098.133 1.394 2.132 3.383 2.992.47.205.84.326 1.129.418.475.152.904.129 1.246.08.38-.058 1.171-.48 1.338-.943.164-.464.164-.86.114-.943-.049-.084-.182-.133-.38-.232z"/>
                                    </svg>
                                    Contact on WhatsApp
                                </a>
                            <?php endif; ?>

                            <?php if (isLoggedIn()): ?>
                                <button
                                    id="saveListingBtn"
                                    class="btn btn-outline btn-lg"
                                    onclick="toggleSaveListing(<?php echo $listingId; ?>)"
                                >
                                    💜 Save Listing
                                </button>
                            <?php else: ?>
                                <button onclick="openLoginModal()" class="btn btn-outline btn-lg">
                                    💜 Save Listing (Login Required)
                                </button>
                            <?php endif; ?>
                        </div>

                        <div class="mt-md text-center text-secondary" style="font-size: 0.875rem;">
                            <strong>Safety Tip:</strong> Meet in public places. Never transfer money before seeing the outfit.
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Similar Listings -->
        <?php if (!empty($similarListings)): ?>
            <div class="mt-xl">
                <h2 class="mb-lg">Similar Outfits</h2>
                <div class="grid grid-cols-2 grid-cols-sm-3 grid-cols-lg-4 gap-md">
                    <?php foreach ($similarListings as $similar): ?>
                        <a href="<?php echo SITE_URL; ?>/listing.php?id=<?php echo $similar['id']; ?>" class="card">
                            <?php if ($similar['image_path']): ?>
                                <img src="<?php echo UPLOAD_URL . basename($similar['image_path']); ?>" alt="<?php echo e($similar['title']); ?>" class="card-img">
                            <?php else: ?>
                                <div class="card-img" style="background: var(--bg-tertiary);"></div>
                            <?php endif; ?>
                            <div class="card-body">
                                <h4 style="font-size: 0.875rem; margin-bottom: 0.5rem;">
                                    <?php echo e(substr($similar['title'], 0, 40)) . (strlen($similar['title']) > 40 ? '...' : ''); ?>
                                </h4>
                                <div style="font-weight: 700; color: var(--primary);"><?php echo formatPrice($similar['price']); ?></div>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

    </div>
</div>

<style>
.thumbnail-item.active {
    border-color: var(--primary) !important;
}
</style>

<script>
// Change main image on thumbnail click
function changeMainImage(imageUrl, thumbnail) {
    document.getElementById('mainImage').src = imageUrl;

    // Update active state
    document.querySelectorAll('.thumbnail-item').forEach(item => {
        item.classList.remove('active');
    });
    thumbnail.classList.add('active');
}

// Track contact
function trackContact(method) {
    App.trackEvent('contact_initiated', {
        listing_id: <?php echo $listingId; ?>,
        method: method
    });
}

// Toggle save listing
async function toggleSaveListing(listingId) {
    const btn = document.getElementById('saveListingBtn');
    App.showLoading(btn);

    const response = await App.request('/listings/toggle-save.php', {
        method: 'POST',
        body: JSON.stringify({ listing_id: listingId })
    });

    App.hideLoading(btn);

    if (response.success) {
        const isSaved = response.data.saved;
        btn.innerHTML = isSaved ? '💚 Saved' : '💜 Save Listing';
        App.showToast(isSaved ? 'Listing saved!' : 'Listing removed from saved', 'success');
    } else {
        App.showToast(response.error || 'Failed to save listing', 'error');
    }
}

// Track listing view
App.trackEvent('listing_view', {
    listing_id: <?php echo $listingId; ?>,
    category: '<?php echo $listing['category']; ?>',
    price: <?php echo $listing['price']; ?>
});
</script>

<?php include 'includes/footer.php'; ?>
