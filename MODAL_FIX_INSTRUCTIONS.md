# 🔧 Modal Close Button Fix - URGENT

## Problem
The X button in modals is not responding to clicks.

## Solution: Update main.js

### Step 1: Open File in Hostinger
1. Go to Hostinger File Manager
2. Navigate to: `assets/js/main.js`
3. Click **Edit**

### Step 2: Find This Line (around line 8)
```javascript
apiBase: '/getMarried/api',
```

### Step 3: Change It To
```javascript
apiBase: '/api',
```

### Step 4: Save and Test
1. Click **Save & Close**
2. Go to your website: `https://getmarried.site`
3. Clear cache: `Ctrl + Shift + Delete` → Clear cached images
4. Hard refresh: `Ctrl + F5`
5. Click Login → Try closing with X button

---

## Alternative Fix (If Above Doesn't Work)

### Test Page Method:
1. Upload `test-modal.html` to your website root
2. Visit: `https://getmarried.site/test-modal.html`
3. Click "Open Test Modal"
4. Try all 4 close methods
5. Check browser console (F12) for errors

### If test-modal.html works but main site doesn't:

The issue is likely that the Modal class is not being instantiated properly in header.php.

**Fix in header.php:**

Find this code (at the bottom of header.php):
```javascript
const loginModal = new Modal('loginModal');
const signupModal = new Modal('signupModal');
```

Replace with:
```javascript
// Wait for DOM to be ready
document.addEventListener('DOMContentLoaded', () => {
    window.loginModal = new Modal('loginModal');
    window.signupModal = new Modal('signupModal');
    console.log('✅ Modals initialized');
});
```

---

## Quick Debug Commands

Open browser console (F12) and type:

### Check if Modal class exists:
```javascript
typeof Modal
```
Should show: `"function"`

### Check if modals are initialized:
```javascript
loginModal
signupModal
```
Should show: `Modal {modal: div, overlay: div, ...}`

### Manually close modal:
```javascript
document.querySelector('#signupModal').classList.add('hidden');
document.body.style.overflow = '';
```

### Force reinitialize:
```javascript
window.signupModal = new Modal('signupModal');
```

---

## Root Cause Analysis

The modal X button should work through these methods:

1. **X Button Click** - `data-close-modal` attribute
2. **ESC Key** - Keyboard event listener
3. **Click Outside** - Click on `.modal-overlay`

If none work, check:
- ✅ Is `main.js` loaded? (View page source, search for "main.js")
- ✅ Any JavaScript errors? (Console tab in F12)
- ✅ Is Modal class initialized? (Type `Modal` in console)
- ✅ Are instances created? (Type `loginModal` in console)

---

## Emergency Workaround

Add this directly to `includes/header.php` at the bottom, inside `<script>` tag:

```javascript
// Emergency modal close fix
document.addEventListener('DOMContentLoaded', () => {
    // Add click handler to all close buttons
    document.querySelectorAll('[data-close-modal]').forEach(btn => {
        btn.addEventListener('click', function() {
            const modal = this.closest('.modal-overlay');
            if (modal) {
                modal.classList.add('hidden');
                document.body.style.overflow = '';
            }
        });
    });

    // Close on ESC
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            document.querySelectorAll('.modal-overlay').forEach(m => {
                m.classList.add('hidden');
            });
            document.body.style.overflow = '';
        }
    });

    // Close on overlay click
    document.querySelectorAll('.modal-overlay').forEach(overlay => {
        overlay.addEventListener('click', (e) => {
            if (e.target === overlay) {
                overlay.classList.add('hidden');
                document.body.style.overflow = '';
            }
        });
    });

    console.log('✅ Emergency modal fix applied');
});
```

This will work even if the Modal class isn't properly initialized.

---

## Contact
If none of these work, send screenshot of:
1. Browser console (F12 → Console tab)
2. Network tab showing which files loaded
3. The exact error message
