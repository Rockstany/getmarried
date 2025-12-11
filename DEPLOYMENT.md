# GetMarried.site - Deployment Checklist & Guide

## Pre-Deployment Checklist

### 1. Hostinger Account Setup
- [ ] Purchase hosting plan (Premium or Business recommended)
- [ ] Domain name registered and connected
- [ ] SSL certificate activated (free with Hostinger)
- [ ] Email accounts created (hello@, support@, noreply@)

### 2. File Upload
- [ ] All files uploaded to `public_html/getMarried/` (or domain root)
- [ ] File structure intact (all folders preserved)
- [ ] `.htaccess` file uploaded (check if visible in File Manager)
- [ ] Permissions set: `assets/uploads/` folder → 755 or 777

### 3. Database Setup
- [ ] MySQL database created via Hostinger panel
- [ ] Database user created with strong password
- [ ] Database schema imported via phpMyAdmin (`database/schema.sql`)
- [ ] All tables created successfully (11 tables total)
- [ ] Default admin user exists (check in `users` table)

### 4. Configuration
- [ ] `includes/config.php` updated with:
  - [ ] Correct database credentials (DB_HOST, DB_NAME, DB_USER, DB_PASS)
  - [ ] Correct SITE_URL (your domain)
  - [ ] Correct SITE_EMAIL
  - [ ] ENVIRONMENT set to 'production'
- [ ] Test database connection (visit any page, should not show errors)

### 5. Security
- [ ] Admin password changed from default (`admin123`)
- [ ] Database credentials are strong
- [ ] `.htaccess` security rules active
- [ ] HTTPS (SSL) enabled and working
- [ ] Session security configured

### 6. Testing

#### Homepage Test
- [ ] Visit `https://yourdomain.com/getMarried/`
- [ ] Homepage loads without errors
- [ ] Navigation menu works
- [ ] Mobile menu toggles correctly

#### Budget Planner Test
- [ ] Go to `/planner.php`
- [ ] Fill budget form (budget, city, type)
- [ ] Click "Generate My Budget Plan"
- [ ] Breakdown displays correctly
- [ ] Checklist appears
- [ ] No JavaScript errors in console

#### Authentication Test
- [ ] Click "Login" in header
- [ ] Modal opens
- [ ] Create new account (signup)
- [ ] Verify session persists (refresh page, still logged in)
- [ ] Logout works
- [ ] Login with created account works

#### Marketplace Test
- [ ] Visit `/marketplace.php`
- [ ] No errors (even if no listings yet)
- [ ] Filters display correctly
- [ ] Search box works

#### Listing Creation Test
- [ ] Login as user
- [ ] Go to `/sell.php`
- [ ] Upload 3-6 images (JPG/PNG)
- [ ] Fill all required fields
- [ ] Submit listing
- [ ] Verify redirect to dashboard
- [ ] Check database: listing exists with `status = 'pending'`
- [ ] Check `assets/uploads/YYYY/MM/listing_id/` folder has images

#### Admin Access Test
- [ ] Visit `/admin/` (or `/admin/index.php`)
- [ ] Login with admin credentials
- [ ] Dashboard loads
- [ ] Pending listings queue visible
- [ ] Can approve/reject listings

### 7. Content Seeding

**Before going live, seed with real content:**

- [ ] Create 20-50 sample listings:
  - Mix of categories (lehengas, sherwanis, etc.)
  - Different cities
  - Varied price ranges (₹5,000 - ₹50,000)
  - Use high-quality stock images or real photos
- [ ] Approve listings via admin panel
- [ ] Verify they appear on marketplace

**Where to get sample images:**
- Unsplash.com (search "indian wedding", "lehenga", "sherwani")
- Pexels.com
- Real photos from Instagram wedding groups (with permission)

### 8. Performance Optimization

- [ ] Enable Gzip compression (check `.htaccess`)
- [ ] Enable browser caching (check `.htaccess`)
- [ ] Optimize images (use TinyPNG or similar)
- [ ] Test page load speed (Google PageSpeed Insights)
- [ ] Mobile responsive check (Google Mobile-Friendly Test)

### 9. SEO Setup

- [ ] Google Search Console connected
- [ ] Sitemap created and submitted
- [ ] Robots.txt configured
- [ ] Meta descriptions on all pages
- [ ] Open Graph tags verified
- [ ] Google Analytics 4 installed

#### Create Sitemap
Create `sitemap.xml` in root:
```xml
<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
  <url>
    <loc>https://yourdomain.com/getMarried/</loc>
    <changefreq>daily</changefreq>
    <priority>1.0</priority>
  </url>
  <url>
    <loc>https://yourdomain.com/getMarried/planner.php</loc>
    <changefreq>weekly</changefreq>
    <priority>0.9</priority>
  </url>
  <url>
    <loc>https://yourdomain.com/getMarried/marketplace.php</loc>
    <changefreq>daily</changefreq>
    <priority>0.9</priority>
  </url>
  <url>
    <loc>https://yourdomain.com/getMarried/sell.php</loc>
    <changefreq>monthly</changefreq>
    <priority>0.8</priority>
  </url>
</urlset>
```

#### Create robots.txt
Create `robots.txt` in root:
```
User-agent: *
Allow: /

Disallow: /admin/
Disallow: /api/
Disallow: /includes/
Disallow: /database/

Sitemap: https://yourdomain.com/getMarried/sitemap.xml
```

### 10. Email Configuration

For production emails (seller notifications, password resets), configure SMTP:

**Hostinger SMTP Settings:**
```php
Host: smtp.hostinger.com
Port: 587
Encryption: TLS
Username: your-email@yourdomain.com
Password: your-email-password
```

Update `includes/functions.php` → `sendEmail()` to use PHPMailer or SMTP.

### 11. Analytics & Tracking

- [ ] Google Analytics 4 tracking code added
- [ ] Conversion events configured:
  - `planner_completed`
  - `listing_submitted`
  - `contact_initiated`
  - `user_signup`
- [ ] Google Tag Manager installed (optional)
- [ ] Facebook Pixel installed (if running ads)

### 12. Legal & Compliance

- [ ] Privacy Policy page created
- [ ] Terms of Service page created
- [ ] Cookie Policy page created
- [ ] Contact page with email/phone
- [ ] About page with company info

### 13. Backup Setup

**Hostinger Automatic Backups:**
- Weekly backups enabled (included in Premium/Business plans)

**Manual Backup:**
1. Database: phpMyAdmin → Export → SQL
2. Files: Download entire `getMarried` folder via FTP
3. Store backups locally + cloud (Google Drive, Dropbox)

**Recommended Schedule:**
- Daily: Database backup (automated)
- Weekly: Full file backup
- Before major updates: Complete backup

### 14. Monitoring & Maintenance

- [ ] Uptime monitoring (UptimeRobot.com - free)
- [ ] Error logging enabled (check PHP error logs in Hostinger panel)
- [ ] Regular security updates
- [ ] Weekly check for spam listings

## Post-Launch Tasks

### Week 1
- [ ] Monitor error logs daily
- [ ] Check for spam submissions
- [ ] Respond to user feedback
- [ ] Track analytics (visitors, conversions)
- [ ] Test all user flows on live site

### Week 2
- [ ] Optimize based on user behavior
- [ ] Add more content (blog posts, FAQs)
- [ ] Start marketing campaigns
- [ ] Engage with first users

### Month 1
- [ ] Gather user testimonials
- [ ] Create success stories
- [ ] SEO optimization based on search queries
- [ ] A/B test key pages (planner, sell page)

## Common Issues & Fixes

### Issue: Database Connection Error
**Fix:**
1. Check `includes/config.php` credentials
2. Verify database exists in phpMyAdmin
3. Check if database user has privileges
4. Test connection with:
```php
<?php
require 'includes/db.php';
$db = getDB();
echo "Connected successfully";
?>
```

### Issue: Images Not Uploading
**Fix:**
1. Check folder permissions: `chmod 777 assets/uploads/`
2. Verify PHP upload limits in Hostinger:
   - `upload_max_filesize = 10M`
   - `post_max_size = 10M`
3. Check `.htaccess` for upload directives

### Issue: 404 on API Endpoints
**Fix:**
1. Ensure `.htaccess` exists and is readable
2. Check mod_rewrite is enabled (contact Hostinger support)
3. Verify API file paths are correct

### Issue: Session Not Persisting
**Fix:**
1. Check PHP session path is writable
2. Ensure cookies are not blocked
3. Verify `session_start()` at top of pages
4. Check browser privacy settings

### Issue: Email Not Sending
**Fix:**
1. Configure SMTP (default PHP `mail()` often blocked on shared hosting)
2. Use Hostinger's SMTP settings
3. Verify SPF/DKIM records for domain
4. Test with simple email first

## Performance Benchmarks

**Target Metrics:**
- Homepage load: < 2 seconds
- Time to Interactive: < 3 seconds
- Lighthouse Score: > 85
- Mobile-friendly: Yes
- HTTPS: A+ rating (SSL Labs)

**Test Tools:**
- Google PageSpeed Insights
- GTmetrix
- Pingdom
- WebPageTest

## Security Hardening

**Additional Security (Optional):**

1. **Install Security Plugin:**
   - Wordfence (if using WordPress alongside)
   - iThemes Security

2. **Add CSP Headers:**
```apache
Header set Content-Security-Policy "default-src 'self'; script-src 'self' 'unsafe-inline' https://www.googletagmanager.com; style-src 'self' 'unsafe-inline';"
```

3. **Disable Directory Listing:**
```apache
Options -Indexes
```

4. **Hide PHP Version:**
```apache
Header unset X-Powered-By
ServerSignature Off
```

5. **Rate Limiting:**
Implement API rate limiting in PHP (store in session/DB):
```php
// Check if user has exceeded 10 requests in last minute
```

## Marketing Launch Plan

### Pre-Launch (1 week before)
- [ ] Create social media accounts (Instagram, Facebook)
- [ ] Post teasers (coming soon posts)
- [ ] Build email list (landing page)
- [ ] Contact wedding bloggers/influencers

### Launch Day
- [ ] Announce on social media
- [ ] Send email to list
- [ ] Post in wedding planning groups (Facebook, Reddit)
- [ ] Submit to Indian startup directories

### Post-Launch (Week 1)
- [ ] Run Instagram/Facebook ads (budget weddings, pre-owned outfits)
- [ ] Engage with users who list/buy
- [ ] Create shareable content (budget tips, success stories)
- [ ] Collaborate with wedding vendors

## Support & Maintenance

**Regular Tasks:**
- **Daily:** Check error logs, moderate new listings
- **Weekly:** Database backup, performance check
- **Monthly:** Security updates, feature improvements
- **Quarterly:** Server optimization, content audit

**Getting Help:**
- Hostinger Support: https://www.hostinger.com/tutorials
- PHP Documentation: https://www.php.net/
- Community: Stack Overflow, Reddit r/webdev

---

## Final Pre-Launch Checklist

**24 Hours Before Launch:**
- [ ] All tests passed
- [ ] Content seeded (20+ listings)
- [ ] Admin credentials secure
- [ ] Backups taken
- [ ] SSL working
- [ ] Analytics installed
- [ ] Legal pages live
- [ ] Email configured
- [ ] Performance optimized
- [ ] Mobile tested
- [ ] Cross-browser tested (Chrome, Safari, Firefox)

**Launch Day:**
- [ ] Monitor server logs
- [ ] Test all critical flows
- [ ] Be ready to fix issues quickly
- [ ] Announce on social media
- [ ] Celebrate! 🎉

---

**Need Help?**
- Technical Issues: Check Hostinger Knowledge Base
- Feature Requests: Create GitHub issue
- Business Questions: Email support@getmarried.site

**Good luck with your launch! 🚀**
