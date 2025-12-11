/**
 * GetMarried.site - Main JavaScript
 * Global utilities and functionality
 */

// Global App Object
const App = {
    apiBase: '/getMarried/api',

    // API Request Helper
    async request(endpoint, options = {}) {
        const defaultOptions = {
            headers: {
                'Content-Type': 'application/json',
            },
        };

        try {
            const response = await fetch(`${this.apiBase}${endpoint}`, {
                ...defaultOptions,
                ...options,
            });

            const data = await response.json();
            return data;
        } catch (error) {
            console.error('API Error:', error);
            return {
                success: false,
                error: 'Network error. Please try again.',
            };
        }
    },

    // Show toast notification
    showToast(message, type = 'info') {
        const toast = document.createElement('div');
        toast.className = `toast toast-${type}`;
        toast.textContent = message;
        toast.style.cssText = `
            position: fixed;
            top: 80px;
            right: 20px;
            padding: 1rem 1.5rem;
            background: ${type === 'success' ? '#10b981' : type === 'error' ? '#ef4444' : '#3b82f6'};
            color: white;
            border-radius: 0.5rem;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            z-index: 9999;
            animation: slideIn 0.3s ease-out;
        `;

        document.body.appendChild(toast);

        setTimeout(() => {
            toast.style.animation = 'slideOut 0.3s ease-in';
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    },

    // Show loading state
    showLoading(element) {
        const originalContent = element.innerHTML;
        element.dataset.originalContent = originalContent;
        element.disabled = true;
        element.innerHTML = '<span class="loading"></span> Loading...';
    },

    // Hide loading state
    hideLoading(element) {
        element.disabled = false;
        element.innerHTML = element.dataset.originalContent || element.innerHTML;
    },

    // Format price
    formatPrice(amount) {
        return '₹' + new Intl.NumberFormat('en-IN').format(amount);
    },

    // Validate email
    isValidEmail(email) {
        return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
    },

    // Track analytics event
    trackEvent(eventName, eventData = {}) {
        // Send to backend
        this.request('/analytics/track.php', {
            method: 'POST',
            body: JSON.stringify({
                event_name: eventName,
                event_data: eventData,
            }),
        }).catch(err => console.error('Analytics error:', err));

        // Send to Google Analytics (if configured)
        if (typeof gtag !== 'undefined') {
            gtag('event', eventName, eventData);
        }
    },

    // Debounce function
    debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    },
};

// Mobile Menu Toggle
document.addEventListener('DOMContentLoaded', () => {
    const hamburger = document.querySelector('.hamburger');
    const mobileMenu = document.querySelector('.mobile-menu');

    if (hamburger && mobileMenu) {
        hamburger.addEventListener('click', () => {
            mobileMenu.classList.toggle('active');
            hamburger.classList.toggle('active');
        });

        // Close menu when clicking outside
        document.addEventListener('click', (e) => {
            if (!hamburger.contains(e.target) && !mobileMenu.contains(e.target)) {
                mobileMenu.classList.remove('active');
                hamburger.classList.remove('active');
            }
        });
    }
});

// Modal Helper
class Modal {
    constructor(id) {
        this.modal = document.getElementById(id);
        this.overlay = this.modal?.closest('.modal-overlay');
        this.closeButtons = this.modal?.querySelectorAll('[data-close-modal]');

        this.init();
    }

    init() {
        if (!this.modal) return;

        // Close on overlay click
        this.overlay?.addEventListener('click', (e) => {
            if (e.target === this.overlay) {
                this.close();
            }
        });

        // Close on button click
        this.closeButtons?.forEach(btn => {
            btn.addEventListener('click', () => this.close());
        });

        // Close on ESC key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && !this.overlay.classList.contains('hidden')) {
                this.close();
            }
        });
    }

    open() {
        this.overlay?.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    close() {
        this.overlay?.classList.add('hidden');
        document.body.style.overflow = '';
    }
}

// Form Validation Helper
class FormValidator {
    constructor(formId) {
        this.form = document.getElementById(formId);
        this.errors = {};
    }

    validate(rules) {
        this.errors = {};
        const formData = new FormData(this.form);

        for (const [field, ruleSet] of Object.entries(rules)) {
            const value = formData.get(field);

            for (const rule of ruleSet) {
                if (rule.type === 'required' && !value) {
                    this.errors[field] = rule.message || `${field} is required`;
                    break;
                }

                if (rule.type === 'email' && value && !App.isValidEmail(value)) {
                    this.errors[field] = rule.message || 'Invalid email address';
                    break;
                }

                if (rule.type === 'min' && value && value.length < rule.value) {
                    this.errors[field] = rule.message || `Minimum ${rule.value} characters required`;
                    break;
                }

                if (rule.type === 'max' && value && value.length > rule.value) {
                    this.errors[field] = rule.message || `Maximum ${rule.value} characters allowed`;
                    break;
                }

                if (rule.type === 'pattern' && value && !rule.value.test(value)) {
                    this.errors[field] = rule.message || 'Invalid format';
                    break;
                }
            }
        }

        this.displayErrors();
        return Object.keys(this.errors).length === 0;
    }

    displayErrors() {
        // Clear previous errors
        this.form.querySelectorAll('.form-error').forEach(el => el.remove());
        this.form.querySelectorAll('.form-input, .form-select, .form-textarea').forEach(el => {
            el.style.borderColor = '';
        });

        // Display new errors
        for (const [field, message] of Object.entries(this.errors)) {
            const input = this.form.querySelector(`[name="${field}"]`);
            if (input) {
                input.style.borderColor = 'var(--error)';

                const errorEl = document.createElement('div');
                errorEl.className = 'form-error';
                errorEl.textContent = message;
                input.parentElement.appendChild(errorEl);
            }
        }
    }

    clearErrors() {
        this.errors = {};
        this.displayErrors();
    }
}

// Image Preview Helper
function previewImages(input, previewContainer) {
    if (!input.files) return;

    previewContainer.innerHTML = '';

    Array.from(input.files).forEach((file, index) => {
        if (file.type.startsWith('image/')) {
            const reader = new FileReader();

            reader.onload = (e) => {
                const preview = document.createElement('div');
                preview.className = 'image-preview-item';
                preview.innerHTML = `
                    <img src="${e.target.result}" alt="Preview ${index + 1}">
                    <button type="button" class="remove-image" data-index="${index}">
                        <svg width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>
                            <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>
                        </svg>
                    </button>
                `;
                previewContainer.appendChild(preview);
            };

            reader.readAsDataURL(file);
        }
    });
}

// Lazy Loading Images
if ('IntersectionObserver' in window) {
    const lazyImages = document.querySelectorAll('img[data-src]');

    const imageObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const img = entry.target;
                img.src = img.dataset.src;
                img.removeAttribute('data-src');
                imageObserver.unobserve(img);
            }
        });
    });

    lazyImages.forEach(img => imageObserver.observe(img));
}

// CSS Animations
const style = document.createElement('style');
style.textContent = `
    @keyframes slideIn {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }

    @keyframes slideOut {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(100%);
            opacity: 0;
        }
    }

    .image-preview-item {
        position: relative;
        border-radius: 0.5rem;
        overflow: hidden;
    }

    .image-preview-item img {
        width: 100%;
        height: 150px;
        object-fit: cover;
    }

    .remove-image {
        position: absolute;
        top: 0.5rem;
        right: 0.5rem;
        background: rgba(239, 68, 68, 0.9);
        color: white;
        border: none;
        border-radius: 50%;
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s;
    }

    .remove-image:hover {
        background: #dc2626;
        transform: scale(1.1);
    }
`;
document.head.appendChild(style);
