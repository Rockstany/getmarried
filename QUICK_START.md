# GetMarried.site - Quick Start Guide

## 🚀 Get Your Site Running in 30 Minutes

This guide will get you from zero to live website in 6 simple steps.

---

## Step 1: Prepare Hostinger Account (5 mins)

1. **Login to Hostinger:** https://hostinger.com
2. **Go to Hosting** → Select your plan
3. **File Manager** → Navigate to `public_html`
4. **Create folder:** `getMarried` (or use root if dedicated domain)

---

## Step 2: Upload Files (5 mins)

### Option A: Via Hostinger File Manager
1. Click **Upload Files**
2. Select all files from this folder
3. Upload to `public_html/getMarried/`
4. Wait for upload to complete

### Option B: Via FTP (FileZilla)
1. Download FileZilla: https://filezilla-project.org
2. Connect using FTP credentials from Hostinger panel
3. Drag & drop entire `getMarried` folder
4. Upload to `public_html/`

**✅ Verify:** All folders uploaded (assets, includes, api, database, admin)

---

## Step 3: Create Database (5 mins)

1. **Hostinger Panel** → **Databases** → **MySQL Databases**
2. Click **Create Database**
3. Fill in:
   - Database name: `getmarried_db`
   - Username: (auto-generated or custom)
   - Password: (generate strong password)
4. Click **Create**
5. **Note down:**
   - Database name
   - Username
   - Password
   - Host (usually `localhost`)

---

## Step 4: Import Database Schema (3 mins)

1. **Hostinger Panel** → **phpMyAdmin**
2. Select your database (`getmarried_db`)
3. Click **Import** tab
4. Click **Choose File**
5. Select `database/schema.sql` from your computer
6. Click **Go** (bottom of page)
7. Wait for "Import has been successfully finished"

**✅ Verify:** 11 tables created (users, listings, budget_plans, etc.)

---

## Step 5: Configure Settings (5 mins)

1. **File Manager** → `getMarried/includes/config.php`
2. Click **Edit**
3. Update these lines:

```php
// Database Configuration
define('DB_HOST', 'localhost');              // Keep as localhost
define('DB_NAME', 'getmarried_db');          // Your database name
define('DB_USER', 'your_username_here');     // From Step 3
define('DB_PASS', 'your_password_here');     // From Step 3

// Site Configuration
define('SITE_URL', 'https://yourdomain.com/getMarried');  // Your actual domain
define('SITE_EMAIL', 'hello@yourdomain.com');             // Your email

// Environment (IMPORTANT!)
define('ENVIRONMENT', 'production');  // Change from 'development' to 'production'
```

4. Click **Save & Close**

---

## Step 6: Set Permissions (2 mins)

1. **File Manager** → Navigate to `assets/uploads`
2. Right-click → **Permissions**
3. Set to **755** (or 777 if 755 doesn't work)
4. Check **Recursive** (apply to all subdirectories)
5. Click **Change Permissions**

---

## 🎉 Test Your Site (5 mins)

### Test 1: Homepage
Visit: `https://yourdomain.com/getMarried/`

**✅ Should see:**
- Homepage with hero section
- Navigation menu
- Footer with links
- No error messages

**❌ If error:**
- Check database credentials in `config.php`
- Check database was imported successfully

---

### Test 2: Budget Planner
1. Click **Plan** or **Start Planning**
2. Fill in:
   - Budget: ₹2,00,000
   - City: Bengaluru
   - Type: Wedding
3. Click **Generate My Budget Plan**

**✅ Should see:**
- Budget breakdown with categories
- Checklist items
- Recommended outfits section (may be empty initially)

---

### Test 3: User Signup
1. Click **Login** (top right)
2. Switch to **Sign Up** tab
3. Create account:
   - Name: Your Name
   - Email: test@example.com
   - Password: test123
4. Click **Sign Up**

**✅ Should see:**
- "Account created successfully" message
- Page refreshes
- Logged in state (shows "Dashboard" and "Logout")

---

### Test 4: Create Listing
1. **Must be logged in** (from Test 3)
2. Click **Sell** button
3. Upload 3 images (JPG/PNG)
4. Fill in all fields:
   - Category: Bridal Lehenga
   - Title: Test Red Lehenga
   - Description: Beautiful red lehenga, worn once... (write 50+ characters)
   - Condition: Like New
   - Price: 15000
   - City: Mumbai
   - Contact: Your phone number
5. Check terms checkbox
6. Click **Submit Listing for Review**

**✅ Should see:**
- "Listing submitted for review" message
- Redirect to dashboard (or marketplace)

---

### Test 5: Admin Login
1. Logout (if logged in as regular user)
2. Click **Login**
3. Use default admin credentials:
   - Email: `admin@getmarried.site`
   - Password: `admin123`
4. Login successful

**⚠️ IMPORTANT:** Change admin password immediately!

To change password:
```sql
-- Go to phpMyAdmin → SQL tab
-- Run this (replace 'your_new_password' with your actual password):

UPDATE users
SET password_hash = '$2y$10$NEW_HASHED_PASSWORD_HERE'
WHERE email = 'admin@getmarried.site';

-- To generate hash, use online bcrypt generator or PHP:
-- echo password_hash('your_new_password', PASSWORD_BCRYPT);
```

---

## ✅ Post-Launch Checklist

**Security:**
- [ ] Admin password changed
- [ ] Database password is strong
- [ ] SSL certificate enabled (Hostinger provides free SSL)
- [ ] HTTPS working (green padlock in browser)

**Content:**
- [ ] Create 20-30 sample listings (use real photos)
- [ ] Approve listings via admin panel (when built)
- [ ] Test all listings appear on marketplace
- [ ] Test contact buttons work

**SEO:**
- [ ] Google Analytics installed (replace GA4 ID in footer.php)
- [ ] Google Search Console connected
- [ ] Submit sitemap.xml
- [ ] Meta descriptions added to all pages

**Performance:**
- [ ] Test site speed (Google PageSpeed Insights)
- [ ] Images optimized (use TinyPNG)
- [ ] Mobile responsive check
- [ ] Cross-browser test (Chrome, Safari, Firefox)

---

## 🔧 Troubleshooting

### Problem: "Database connection failed"

**Fix:**
1. Double-check credentials in `includes/config.php`
2. Verify database exists in phpMyAdmin
3. Check database user has privileges
4. Try connecting via phpMyAdmin manually

---

### Problem: Images not uploading

**Fix:**
1. Set `assets/uploads/` permissions to 777
2. Check PHP upload limits in Hostinger (10MB default)
3. Verify file extensions are allowed (JPG, PNG, WEBP)

---

### Problem: "Page not found" (404) on API endpoints

**Fix:**
1. Check `.htaccess` file exists in root
2. Verify mod_rewrite enabled (usually enabled by default)
3. Check file paths in API calls match your folder structure

---

### Problem: Styles not loading

**Fix:**
1. Check `SITE_URL` in `config.php` matches your actual URL
2. Hard refresh browser (Ctrl+Shift+R or Cmd+Shift+R)
3. Check `assets/css/style.css` uploaded correctly

---

## 📈 Next Steps After Launch

### Week 1: Content & Testing
- [ ] Seed 50+ real listings
- [ ] Test all user flows
- [ ] Monitor error logs daily
- [ ] Fix any bugs found

### Week 2: Marketing
- [ ] Create Instagram account
- [ ] Post in wedding planning groups (Facebook, Reddit)
- [ ] Reach out to wedding bloggers
- [ ] Run trial Instagram ads

### Month 1: Growth
- [ ] Build admin panel for moderation
- [ ] Add user dashboard
- [ ] Implement email notifications
- [ ] Create blog/content section
- [ ] Gather user testimonials

---

## 📞 Need Help?

**Common Resources:**
- **Hostinger Support:** 24/7 chat (bottom right of Hostinger panel)
- **Hostinger Tutorials:** https://www.hostinger.com/tutorials
- **PHP Docs:** https://www.php.net/manual/
- **Stack Overflow:** https://stackoverflow.com (search your error)

**Documentation in This Project:**
- `README.md` - Detailed setup guide
- `DEPLOYMENT.md` - Full deployment checklist
- `PROJECT_SUMMARY.md` - Complete feature list
- `database/schema.sql` - Database structure

---

## 🎯 Success Metrics to Track

**First Week:**
- Site uptime: 99.9%
- Page load time: < 3 seconds
- Mobile responsive: Yes
- User signups: Track in database

**First Month:**
- Listings created: Target 50+
- Approved listings: Target 30+
- Unique visitors: Track in Google Analytics
- Budget plans generated: Track in analytics_events table

---

## 🚨 Critical Actions

**Before Opening to Public:**

1. **Change admin password** ⚠️ CRITICAL
2. **Test SSL certificate** (green padlock)
3. **Backup database** (phpMyAdmin → Export)
4. **Set ENVIRONMENT to 'production'** in config.php
5. **Enable error logging** (check Hostinger panel)

---

## 🎊 You're Ready to Launch!

Once all tests pass, you can:

1. **Announce on social media**
2. **Share with friends/family**
3. **Post in wedding groups**
4. **Start collecting real listings**
5. **Help couples save money!**

**Good luck! 🚀**

---

**Questions?** Create an issue on GitHub or email support@getmarried.site

**Last Updated:** December 2025
