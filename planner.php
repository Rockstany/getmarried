<?php
$pageTitle = 'Wedding Budget Planner - GetMarried.site';
$pageDescription = 'Free wedding budget planner. Get instant budget breakdown, checklist, and outfit recommendations based on your budget and city.';

include 'includes/header.php';
?>

<div class="section">
    <div class="container" style="max-width: 900px;">

        <!-- Header -->
        <div class="text-center mb-xl">
            <h1>Wedding Budget Planner</h1>
            <p class="text-secondary" style="font-size: 1.125rem;">
                Answer 4 quick questions to get your personalized wedding budget breakdown
            </p>
        </div>

        <!-- Planner Form -->
        <div class="card" style="padding: 2rem;">
            <form id="plannerForm">

                <!-- Step 1: Budget -->
                <div class="form-group">
                    <label class="form-label">What's your total wedding budget?</label>
                    <div class="grid grid-cols-2 grid-cols-sm-3 gap-sm mb-sm">
                        <button type="button" class="btn btn-secondary budget-chip" data-value="100000">₹1 Lakh</button>
                        <button type="button" class="btn btn-secondary budget-chip" data-value="200000">₹2 Lakhs</button>
                        <button type="button" class="btn btn-secondary budget-chip" data-value="300000">₹3 Lakhs</button>
                        <button type="button" class="btn btn-secondary budget-chip" data-value="500000">₹5 Lakhs</button>
                        <button type="button" class="btn btn-secondary budget-chip" data-value="700000">₹7 Lakhs</button>
                        <button type="button" class="btn btn-secondary budget-chip" data-value="custom">Custom</button>
                    </div>
                    <input
                        type="number"
                        name="budget"
                        id="budgetInput"
                        class="form-input"
                        placeholder="Enter custom amount"
                        min="50000"
                        max="10000000"
                        required
                        style="display: none;"
                    >
                </div>

                <!-- Step 2: City -->
                <div class="form-group">
                    <label class="form-label">Where is the wedding?</label>
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

                <!-- Step 3: Event Type -->
                <div class="form-group">
                    <label class="form-label">What type of event?</label>
                    <div class="grid grid-cols-2 gap-sm">
                        <label class="radio-card">
                            <input type="radio" name="type" value="engagement" required>
                            <div class="radio-card-content">
                                <div style="font-size: 2rem; margin-bottom: 0.5rem;">💍</div>
                                <div style="font-weight: 600;">Engagement</div>
                            </div>
                        </label>
                        <label class="radio-card">
                            <input type="radio" name="type" value="haldi" required>
                            <div class="radio-card-content">
                                <div style="font-size: 2rem; margin-bottom: 0.5rem;">🌼</div>
                                <div style="font-weight: 600;">Haldi</div>
                            </div>
                        </label>
                        <label class="radio-card">
                            <input type="radio" name="type" value="wedding" required>
                            <div class="radio-card-content">
                                <div style="font-size: 2rem; margin-bottom: 0.5rem;">👰</div>
                                <div style="font-weight: 600;">Wedding</div>
                            </div>
                        </label>
                        <label class="radio-card">
                            <input type="radio" name="type" value="micro-wedding" required>
                            <div class="radio-card-content">
                                <div style="font-size: 2rem; margin-bottom: 0.5rem;">🎉</div>
                                <div style="font-weight: 600;">Micro Wedding</div>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Step 4: Date (Optional) -->
                <div class="form-group">
                    <label class="form-label">Event date (optional)</label>
                    <input type="date" name="date" class="form-input" min="<?php echo date('Y-m-d'); ?>">
                </div>

                <button type="submit" class="btn btn-primary btn-block btn-lg">
                    Generate My Budget Plan
                </button>
            </form>
        </div>

        <!-- Results Section (Hidden initially) -->
        <div id="resultsSection" class="hidden mt-xl">

            <!-- Summary Card -->
            <div class="card mb-lg" style="background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%); color: white; padding: 2rem;">
                <h2 style="color: white; margin-bottom: 1rem;">Your Budget Breakdown</h2>
                <div id="summaryContent"></div>

                <div class="flex flex-wrap gap-sm mt-lg">
                    <?php if (isLoggedIn()): ?>
                        <button id="savePlanBtn" class="btn btn-lg" style="background: white; color: var(--primary);">
                            💾 Save Plan
                        </button>
                    <?php else: ?>
                        <button onclick="openLoginModal()" class="btn btn-lg" style="background: white; color: var(--primary);">
                            💾 Save Plan (Login Required)
                        </button>
                    <?php endif; ?>
                    <button id="downloadPdfBtn" class="btn btn-outline btn-lg" style="border-color: white; color: white;">
                        📄 Download PDF
                    </button>
                    <button id="shareWhatsAppBtn" class="btn btn-outline btn-lg" style="border-color: white; color: white;">
                        📱 Share
                    </button>
                </div>
            </div>

            <!-- Breakdown Details -->
            <div class="card mb-lg">
                <div class="card-body">
                    <h3 class="mb-md">Detailed Breakdown</h3>
                    <div id="breakdownDetails"></div>
                </div>
            </div>

            <!-- Checklist -->
            <div class="card mb-lg">
                <div class="card-body">
                    <h3 class="mb-md">Wedding Checklist</h3>
                    <div id="checklistContent"></div>
                </div>
            </div>

            <!-- Recommended Outfits -->
            <div id="recommendedSection" class="card">
                <div class="card-body">
                    <h3 class="mb-md">Recommended Outfits for Your Budget</h3>
                    <div id="recommendedListings" class="grid grid-cols-2 grid-cols-sm-3 grid-cols-lg-4 gap-md"></div>
                </div>
            </div>
        </div>

    </div>
</div>

<style>
/* Budget Chip Styles */
.budget-chip {
    transition: all 0.2s;
}

.budget-chip.active {
    background: var(--primary);
    color: white;
}

/* Radio Card Styles */
.radio-card {
    position: relative;
    cursor: pointer;
}

.radio-card input[type="radio"] {
    position: absolute;
    opacity: 0;
}

.radio-card-content {
    padding: 1.5rem;
    border: 2px solid var(--border-color);
    border-radius: var(--radius-md);
    text-align: center;
    transition: all 0.2s;
}

.radio-card:hover .radio-card-content {
    border-color: var(--primary);
    background: var(--bg-secondary);
}

.radio-card input[type="radio"]:checked + .radio-card-content {
    border-color: var(--primary);
    background: var(--primary-light);
    color: var(--primary-dark);
}

/* Breakdown Item */
.breakdown-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem;
    border-bottom: 1px solid var(--border-color);
}

.breakdown-item:last-child {
    border-bottom: none;
}

.breakdown-bar {
    height: 8px;
    background: var(--primary);
    border-radius: var(--radius-full);
    margin-top: 0.5rem;
}

/* Checklist Item */
.checklist-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 0.75rem;
    border-bottom: 1px solid var(--border-color);
}

.checklist-item:last-child {
    border-bottom: none;
}

.checklist-item input[type="checkbox"] {
    width: 20px;
    height: 20px;
    cursor: pointer;
}

.checklist-item.completed {
    opacity: 0.6;
    text-decoration: line-through;
}
</style>

<script>
// Budget chips interaction
const budgetChips = document.querySelectorAll('.budget-chip');
const budgetInput = document.getElementById('budgetInput');

budgetChips.forEach(chip => {
    chip.addEventListener('click', (e) => {
        e.preventDefault();
        budgetChips.forEach(c => c.classList.remove('active'));
        chip.classList.add('active');

        const value = chip.dataset.value;
        if (value === 'custom') {
            budgetInput.style.display = 'block';
            budgetInput.value = '';
            budgetInput.focus();
        } else {
            budgetInput.style.display = 'block';
            budgetInput.value = value;
        }
    });
});

// Form submission
document.getElementById('plannerForm').addEventListener('submit', async (e) => {
    e.preventDefault();

    const btn = e.target.querySelector('button[type="submit"]');
    App.showLoading(btn);

    const formData = new FormData(e.target);
    const data = Object.fromEntries(formData);

    // Track event
    App.trackEvent('planner_started', { budget: data.budget, city: data.city, type: data.type });

    const response = await App.request('/planner/generate.php', {
        method: 'POST',
        body: JSON.stringify(data)
    });

    App.hideLoading(btn);

    if (response.success) {
        displayResults(response.data);
        App.trackEvent('planner_completed', { budget: data.budget });

        // Scroll to results
        document.getElementById('resultsSection').scrollIntoView({ behavior: 'smooth' });
    } else {
        App.showToast(response.error || 'Failed to generate plan', 'error');
    }
});

// Display results
function displayResults(data) {
    const resultsSection = document.getElementById('resultsSection');
    resultsSection.classList.remove('hidden');

    // Summary
    const totalBudget = parseFloat(data.budget);
    document.getElementById('summaryContent').innerHTML = `
        <div style="font-size: 3rem; font-weight: 700; margin-bottom: 0.5rem;">
            ${App.formatPrice(totalBudget)}
        </div>
        <div style="font-size: 1.125rem; opacity: 0.9;">
            Total Budget • ${data.city} • ${data.type.replace('-', ' ').replace(/\b\w/g, l => l.toUpperCase())}
        </div>
    `;

    // Breakdown details
    let breakdownHTML = '';
    data.breakdown.forEach(item => {
        const percentage = ((item.amount / totalBudget) * 100).toFixed(0);
        breakdownHTML += `
            <div class="breakdown-item">
                <div style="flex: 1;">
                    <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                        <span style="font-weight: 600;">${item.name}</span>
                        <span style="color: var(--primary); font-weight: 700;">${App.formatPrice(item.amount)}</span>
                    </div>
                    <div style="background: var(--bg-tertiary); border-radius: var(--radius-full); height: 8px;">
                        <div class="breakdown-bar" style="width: ${percentage}%;"></div>
                    </div>
                    <div style="font-size: 0.875rem; color: var(--text-secondary); margin-top: 0.25rem;">
                        ${percentage}% of total budget
                    </div>
                </div>
            </div>
        `;
    });
    document.getElementById('breakdownDetails').innerHTML = breakdownHTML;

    // Checklist
    let checklistHTML = '';
    data.checklist.forEach((item, index) => {
        checklistHTML += `
            <div class="checklist-item">
                <input type="checkbox" id="check${index}" onchange="toggleChecklistItem(this)">
                <label for="check${index}" style="flex: 1; cursor: pointer;">${item}</label>
            </div>
        `;
    });
    document.getElementById('checklistContent').innerHTML = checklistHTML;

    // Recommended listings
    if (data.recommended_listings && data.recommended_listings.length > 0) {
        let listingsHTML = '';
        data.recommended_listings.forEach(listing => {
            listingsHTML += `
                <a href="<?php echo SITE_URL; ?>/listing.php?id=${listing.id}" class="card">
                    <img src="${listing.image_url || '<?php echo SITE_URL; ?>/assets/images/placeholder.jpg'}" alt="${listing.title}" class="card-img">
                    <div class="card-body">
                        <h4 style="font-size: 0.875rem; margin-bottom: 0.5rem;">${listing.title.substring(0, 40)}...</h4>
                        <div style="font-weight: 700; color: var(--primary);">${App.formatPrice(listing.price)}</div>
                    </div>
                </a>
            `;
        });
        document.getElementById('recommendedListings').innerHTML = listingsHTML;
    } else {
        document.getElementById('recommendedSection').style.display = 'none';
    }

    // Store plan data for saving/sharing
    window.currentPlan = data;
}

// Toggle checklist item
function toggleChecklistItem(checkbox) {
    checkbox.closest('.checklist-item').classList.toggle('completed', checkbox.checked);
}

// Save plan
document.getElementById('savePlanBtn')?.addEventListener('click', async () => {
    if (!window.currentPlan) return;

    const btn = document.getElementById('savePlanBtn');
    App.showLoading(btn);

    const response = await App.request('/planner/save.php', {
        method: 'POST',
        body: JSON.stringify(window.currentPlan)
    });

    App.hideLoading(btn);

    if (response.success) {
        App.showToast('Plan saved to your dashboard!', 'success');
        App.trackEvent('plan_saved');
    } else {
        App.showToast(response.error || 'Failed to save plan', 'error');
    }
});

// Share on WhatsApp
document.getElementById('shareWhatsAppBtn')?.addEventListener('click', () => {
    if (!window.currentPlan) return;

    const message = `Check out my wedding budget plan on GetMarried.site!\n\nBudget: ${App.formatPrice(window.currentPlan.budget)}\nCity: ${window.currentPlan.city}\n\nPlan your wedding: ${window.location.href}`;
    const url = `https://wa.me/?text=${encodeURIComponent(message)}`;
    window.open(url, '_blank');

    App.trackEvent('plan_shared', { method: 'whatsapp' });
});

// Download PDF (placeholder - would need server-side PDF generation)
document.getElementById('downloadPdfBtn')?.addEventListener('click', () => {
    App.showToast('PDF download coming soon!', 'info');
    App.trackEvent('plan_download_attempted');
});
</script>

<?php include 'includes/footer.php'; ?>
