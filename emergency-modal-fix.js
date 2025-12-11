// 🚨 EMERGENCY MODAL FIX
// Add this code to the bottom of includes/header.php inside <script> tags
// This will make the close buttons work immediately

document.addEventListener('DOMContentLoaded', () => {
    console.log('🔧 Applying emergency modal fix...');

    // Fix 1: Add click handler to all close buttons
    document.querySelectorAll('[data-close-modal]').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();

            const modal = this.closest('.modal-overlay');
            if (modal) {
                modal.classList.add('hidden');
                document.body.style.overflow = '';
                console.log('✅ Modal closed via X button');
            }
        });
    });

    // Fix 2: Close on ESC key
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            const openModals = document.querySelectorAll('.modal-overlay:not(.hidden)');
            openModals.forEach(m => {
                m.classList.add('hidden');
            });
            document.body.style.overflow = '';
            console.log('✅ Modal closed via ESC key');
        }
    });

    // Fix 3: Close on overlay click (clicking outside)
    document.querySelectorAll('.modal-overlay').forEach(overlay => {
        overlay.addEventListener('click', (e) => {
            if (e.target === overlay) {
                overlay.classList.add('hidden');
                document.body.style.overflow = '';
                console.log('✅ Modal closed via overlay click');
            }
        });
    });

    console.log('✅ Emergency modal fix applied successfully!');
});
