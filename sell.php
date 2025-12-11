<?php
$pageTitle = 'Sell Your Wedding Outfit - GetMarried.site';
$pageDescription = 'List your pre-owned wedding outfit for free. Reach thousands of buyers looking for affordable wedding wear.';

include 'includes/header.php';

// Redirect if not logged in
if (!isLoggedIn()) {
    echo '<script>openLoginModal();</script>';
}
?>

<div class="section">
    <div class="container" style="max-width: 800px;">

        <!-- Header -->
        <div class="text-center mb-xl">
            <h1>Sell Your Wedding Outfit</h1>
            <p class="text-secondary" style="font-size: 1.125rem;">
                List your outfit for free • Reach thousands of buyers • Get paid directly
            </p>
        </div>

        <!-- How It Works -->
        <div class="card mb-lg" style="background: var(--bg-secondary); padding: 2rem;">
            <h3 class="mb-md">How It Works</h3>
            <div class="grid grid-cols-1 grid-cols-sm-3 gap-md">
                <div class="text-center">
                    <div style="font-size: 2rem; margin-bottom: 0.5rem;">📸</div>
                    <div style="font-weight: 600; margin-bottom: 0.25rem;">Upload Photos</div>
                    <div style="font-size: 0.875rem; color: var(--text-secondary);">Add 3-6 clear photos</div>
                </div>
                <div class="text-center">
                    <div style="font-size: 2rem; margin-bottom: 0.5rem;">✅</div>
                    <div style="font-weight: 600; margin-bottom: 0.25rem;">Get Verified</div>
                    <div style="font-size: 0.875rem; color: var(--text-secondary);">We'll review in 24hrs</div>
                </div>
                <div class="text-center">
                    <div style="font-size: 2rem; margin-bottom: 0.5rem;">💰</div>
                    <div style="font-weight: 600; margin-bottom: 0.25rem;">Sell & Earn</div>
                    <div style="font-size: 0.875rem; color: var(--text-secondary);">Connect with buyers</div>
                </div>
            </div>
        </div>

        <!-- Listing Form -->
        <div class="card" style="padding: 2rem;">
            <form id="createListingForm" enctype="multipart/form-data">

                <!-- Step 1: Photos -->
                <div class="mb-xl">
                    <h3 class="mb-md">Step 1: Upload Photos</h3>
                    <p class="text-secondary mb-md">Add 3-6 clear photos (JPG, PNG, WEBP, max 5MB each)</p>

                    <div class="form-group">
                        <label class="btn btn-secondary btn-block" style="cursor: pointer;">
                            <input
                                type="file"
                                name="images[]"
                                id="imageInput"
                                accept="image/jpeg,image/jpg,image/png,image/webp"
                                multiple
                                required
                                style="display: none;"
                                onchange="handleImagePreview(this)"
                            >
                            📷 Choose Images (3-6 photos)
                        </label>
                    </div>

                    <!-- Image Previews -->
                    <div id="imagePreviews" class="grid grid-cols-2 grid-cols-sm-3 gap-md mt-md"></div>
                </div>

                <!-- Step 2: Details -->
                <div class="mb-xl">
                    <h3 class="mb-md">Step 2: Outfit Details</h3>

                    <div class="form-group">
                        <label class="form-label">Category *</label>
                        <select name="category" class="form-select" required>
                            <option value="">Select category</option>
                            <option value="bridal_lehenga">Bridal Lehenga</option>
                            <option value="groom_sherwani">Groom Sherwani</option>
                            <option value="bridesmaid">Bridesmaid Outfit</option>
                            <option value="accessories">Accessories</option>
                            <option value="jewelry">Jewelry</option>
                            <option value="other">Other</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Title *</label>
                        <input
                            type="text"
                            name="title"
                            class="form-input"
                            placeholder="e.g., Red Bridal Lehenga with Heavy Embroidery"
                            required
                            maxlength="255"
                        >
                        <div style="font-size: 0.875rem; color: var(--text-secondary); margin-top: 0.25rem;">
                            Be specific and descriptive
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Description *</label>
                        <textarea
                            name="description"
                            class="form-textarea"
                            placeholder="Describe your outfit: brand, size, condition, worn once or multiple times, any defects, etc."
                            required
                            rows="6"
                        ></textarea>
                    </div>

                    <div class="grid grid-cols-1 grid-cols-sm-2 gap-md">
                        <div class="form-group">
                            <label class="form-label">Condition *</label>
                            <select name="condition" class="form-select" required>
                                <option value="">Select condition</option>
                                <option value="new">New (Never worn)</option>
                                <option value="like_new">Like New (Worn once)</option>
                                <option value="gently_used">Gently Used (2-3 times)</option>
                                <option value="used">Used (Multiple times)</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Price (₹) *</label>
                            <input
                                type="number"
                                name="price"
                                class="form-input"
                                placeholder="10000"
                                min="500"
                                max="1000000"
                                required
                            >
                        </div>
                    </div>
                </div>

                <!-- Step 3: Location & Contact -->
                <div class="mb-xl">
                    <h3 class="mb-md">Step 3: Location & Contact</h3>

                    <div class="form-group">
                        <label class="form-label">City *</label>
                        <select name="city" class="form-select" required>
                            <option value="">Select city</option>
                            <option value="Bengaluru">Bengaluru</option>
                            <option value="Mumbai">Mumbai</option>
                            <option value="Delhi">Delhi</option>
                            <option value="Hyderabad">Hyderabad</option>
                            <option value="Chennai">Chennai</option>
                            <option value="Kolkata">Kolkata</option>
                            <option value="Pune">Pune</option>
                            <option value="Ahmedabad">Ahmedabad</option>
                            <option value="Jaipur">Jaipur</option>
                            <option value="Lucknow">Lucknow</option>
                            <option value="Chandigarh">Chandigarh</option>
                            <option value="Kochi">Kochi</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Contact Method *</label>
                        <select name="contact_method" class="form-select" required>
                            <option value="whatsapp">WhatsApp</option>
                            <option value="phone">Phone</option>
                            <option value="email">Email</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Contact Number/Email *</label>
                        <input
                            type="text"
                            name="contact_value"
                            class="form-input"
                            placeholder="Enter phone number or email"
                            required
                        >
                        <div style="font-size: 0.875rem; color: var(--text-secondary); margin-top: 0.25rem;">
                            Buyers will use this to contact you
                        </div>
                    </div>
                </div>

                <!-- Terms & Submit -->
                <div class="form-group">
                    <label style="display: flex; align-items: start; gap: 0.5rem; cursor: pointer;">
                        <input type="checkbox" required style="margin-top: 0.25rem;">
                        <span style="font-size: 0.875rem;">
                            I agree to the <a href="<?php echo SITE_URL; ?>/terms.php" target="_blank">Terms of Service</a> and confirm that this listing is accurate and I own this outfit.
                        </span>
                    </label>
                </div>

                <button type="submit" class="btn btn-primary btn-block btn-lg">
                    Submit Listing for Review
                </button>

                <div class="mt-md text-center text-secondary" style="font-size: 0.875rem;">
                    Your listing will be reviewed within 24 hours and published after approval.
                </div>
            </form>
        </div>

    </div>
</div>

<style>
#imagePreviews .preview-item {
    position: relative;
    border-radius: var(--radius-md);
    overflow: hidden;
    aspect-ratio: 1;
}

#imagePreviews .preview-item img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

#imagePreviews .remove-btn {
    position: absolute;
    top: 0.5rem;
    right: 0.5rem;
    background: rgba(239, 68, 68, 0.95);
    color: white;
    border: none;
    border-radius: 50%;
    width: 30px;
    height: 30px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    font-size: 1.25rem;
    line-height: 1;
}

#imagePreviews .remove-btn:hover {
    background: #dc2626;
}
</style>

<script>
let selectedFiles = [];

// Handle image preview
function handleImagePreview(input) {
    const files = Array.from(input.files);

    // Validate count
    if (files.length < 3 || files.length > 6) {
        App.showToast('Please select 3-6 images', 'error');
        input.value = '';
        return;
    }

    // Validate file size
    for (let file of files) {
        if (file.size > 5 * 1024 * 1024) {
            App.showToast(`${file.name} exceeds 5MB limit`, 'error');
            input.value = '';
            return;
        }
    }

    selectedFiles = files;
    displayPreviews();
}

// Display image previews
function displayPreviews() {
    const container = document.getElementById('imagePreviews');
    container.innerHTML = '';

    selectedFiles.forEach((file, index) => {
        const reader = new FileReader();

        reader.onload = (e) => {
            const div = document.createElement('div');
            div.className = 'preview-item';
            div.innerHTML = `
                <img src="${e.target.result}" alt="Preview ${index + 1}">
                <button type="button" class="remove-btn" onclick="removeImage(${index})">&times;</button>
            `;
            container.appendChild(div);
        };

        reader.readAsDataURL(file);
    });
}

// Remove image
function removeImage(index) {
    selectedFiles.splice(index, 1);

    if (selectedFiles.length < 3) {
        App.showToast('Minimum 3 images required', 'error');
        document.getElementById('imageInput').value = '';
        selectedFiles = [];
    }

    displayPreviews();
}

// Form submission
document.getElementById('createListingForm').addEventListener('submit', async (e) => {
    e.preventDefault();

    if (!<?php echo isLoggedIn() ? 'true' : 'false'; ?>) {
        openLoginModal();
        return;
    }

    const btn = e.target.querySelector('button[type="submit"]');
    App.showLoading(btn);

    const formData = new FormData(e.target);

    // Add selected files
    selectedFiles.forEach((file, index) => {
        formData.append('images[]', file);
    });

    try {
        const response = await fetch('<?php echo SITE_URL; ?>/api/listings/create.php', {
            method: 'POST',
            body: formData
        });

        const data = await response.json();

        App.hideLoading(btn);

        if (data.success) {
            App.showToast('Listing submitted for review!', 'success');
            App.trackEvent('listing_submitted');

            setTimeout(() => {
                window.location.href = '<?php echo SITE_URL; ?>/dashboard.php';
            }, 2000);
        } else {
            App.showToast(data.error || 'Failed to create listing', 'error');
        }
    } catch (error) {
        App.hideLoading(btn);
        App.showToast('Network error. Please try again.', 'error');
    }
});

// Track page view
App.trackEvent('sell_page_view');
</script>

<?php include 'includes/footer.php'; ?>
