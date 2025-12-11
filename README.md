# GetMarried.site - Wedding Budget Planner & Marketplace

A complete wedding planning platform with budget calculator and pre-owned wedding outfit marketplace. Built with PHP, MySQL, HTML, CSS, and JavaScript for Hostinger shared hosting.

## Features

### ✅ Phase 1 (Implemented)
- 💰 **Budget Planner** - Instant personalized wedding budget breakdowns
- 🛍️ **Marketplace** - Browse and list pre-owned wedding outfits
- 🔐 **User Authentication** - Secure login/signup system
- 📱 **Mobile-First Design** - Fully responsive across all devices
- 🔍 **Advanced Filters** - Search by category, city, price, condition
- 💬 **WhatsApp Integration** - Direct seller contact
- ✓ **Verified Sellers** - Trust and safety features
- 📊 **Analytics Tracking** - Built-in event tracking

## Tech Stack

- **Frontend**: HTML5, CSS3, JavaScript (Vanilla)
- **Backend**: PHP 7.4+
- **Database**: MySQL 5.7+ / MariaDB
- **Hosting**: Hostinger (or any LAMP stack)

## Project Structure

```
getMarried/
├── index.php              # Homepage
├── planner.php            # Budget planner tool
├── marketplace.php        # Outfit listings grid
├── listing.php           # Individual listing detail
├── sell.php              # Create listing form
├── dashboard.php         # User dashboard
├── admin/                # Admin panel
│   └── index.php
├── api/                  # API endpoints
│   ├── auth/
│   │   ├── login.php
│   │   ├── signup.php
│   │   └── logout.php
│   ├── planner/
│   │   ├── generate.php
│   │   └── save.php
│   └── listings/
│       ├── create.php
│       └── get.php
├── assets/
│   ├── css/
│   │   └── style.css     # Global styles
│   ├── js/
│   │   └── main.js       # Global JavaScript
│   ├── images/           # Static images
│   └── uploads/          # User uploads (created dynamically)
├── includes/
│   ├── config.php        # Configuration
│   ├── db.php            # Database connection
│   ├── functions.php     # Helper functions
│   ├── header.php        # Global header
│   └── footer.php        # Global footer
└── database/
    └── schema.sql        # Database schema
```

## Installation Guide (Hostinger)

### Step 1: Upload Files

1. Download all files from this repository
2. Login to your Hostinger account
3. Go to **File Manager** (or use FTP client like FileZilla)
4. Navigate to `public_html/` folder
5. Create a folder `getMarried` (or use domain root)
6. Upload all project files to this folder

### Step 2: Create MySQL Database

1. In Hostinger panel, go to **Databases** → **MySQL Databases**
2. Click **Create Database**
3. Database name: `getmarried_db` (or your choice)
4. Create a database user with password
5. Note down:
   - Database name
   - Username
   - Password
   - Host (usually `localhost`)

### Step 3: Import Database Schema

1. Go to **phpMyAdmin** in Hostinger panel
2. Select your database (`getmarried_db`)
3. Click **Import** tab
4. Upload `database/schema.sql`
5. Click **Go** to execute

This will create all tables and insert a default admin user.

### Step 4: Configure Application

1. Open `includes/config.php` in File Manager
2. Update these values:

```php
// Database Configuration
define('DB_HOST', 'localhost');           // Usually localhost
define('DB_NAME', 'your_database_name');  // Your database name
define('DB_USER', 'your_db_username');    // Your database user
define('DB_PASS', 'your_db_password');    // Your database password

// Site Configuration
define('SITE_URL', 'https://yourdomain.com'); // Your domain
define('SITE_EMAIL', 'hello@yourdomain.com'); // Your email

// Environment
define('ENVIRONMENT', 'production'); // Change to 'production' for live site
```

3. Save the file

### Step 5: Set Folder Permissions

1. In File Manager, right-click `assets/uploads` folder
2. Click **Permissions**
3. Set to `755` or `777` (to allow image uploads)
4. Click **Change Permissions**

### Step 6: Access Your Site

1. Visit: `https://yourdomain.com/getMarried/`
2. You should see the homepage!

### Step 7: Admin Access

**Default Admin Login:**
- Email: `admin@getmarried.site`
- Password: `admin123`

⚠️ **IMPORTANT**: Change this password immediately after first login!

To change admin password:
1. Login with default credentials
2. Go to Dashboard → Settings
3. Or manually hash a new password using PHP:
```php
echo password_hash('your_new_password', PASSWORD_BCRYPT);
```
4. Update in database:
```sql
UPDATE users SET password_hash = 'hashed_password_here' WHERE email = 'admin@getmarried.site';
```

## Configuration Options

### File Upload Settings

In `includes/config.php`:

```php
define('MAX_FILE_SIZE', 5 * 1024 * 1024);  // 5MB max per image
define('ALLOWED_EXTENSIONS', ['jpg', 'jpeg', 'png', 'webp']);
```

### Email Settings (for production)

For sending emails (seller notifications, etc.), configure SMTP in `includes/functions.php`:

```php
// Configure with your Hostinger SMTP settings
function sendEmail($to, $subject, $body) {
    $headers = "From: noreply@yourdomain.com\r\n";
    $headers .= "Reply-To: hello@yourdomain.com\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";

    return mail($to, $subject, $body, $headers);
}
```

Or use PHPMailer with SMTP for better deliverability.

### Google Analytics

Replace placeholder in `includes/footer.php`:

```javascript
gtag('config', 'G-XXXXXXXXXX'); // Replace with your GA4 ID
```

## Key Features Setup

### 1. Budget Planner

The budget planner uses preset allocation templates in `api/planner/generate.php`. You can customize percentages:

```php
$allocationTemplates = [
    'wedding' => [
        ['name' => 'Venue', 'percent' => 30],
        ['name' => 'Catering', 'percent' => 30],
        // ... customize as needed
    ],
];
```

### 2. WhatsApp Integration

Listings show WhatsApp contact buttons. The phone number format is auto-detected (adds +91 for Indian numbers).

### 3. Image Uploads

Images are saved to: `assets/uploads/{year}/{month}/{listing_id}/`

This organizes uploads by date and prevents naming conflicts.

### 4. Admin Moderation

New listings default to `pending` status. Admins must approve them via `/admin/` panel before they're visible to buyers.

## Customization Guide

### Change Color Scheme

Edit `assets/css/style.css`:

```css
:root {
    --primary: #d946ef;      /* Main brand color */
    --secondary: #ec4899;    /* Secondary color */
    --accent: #f59e0b;       /* Accent color */
}
```

### Add New Cities

Edit dropdown in:
- `planner.php` (budget planner)
- `marketplace.php` (filters)
- `sell.php` (create listing)

### Modify Budget Allocations

Edit `api/planner/generate.php` → `$allocationTemplates` array

## Testing

### Test Budget Planner
1. Go to `/planner.php`
2. Fill in budget, city, event type
3. Click "Generate My Budget Plan"
4. Verify breakdown appears correctly

### Test User Signup
1. Click "Login" in header
2. Switch to "Sign Up"
3. Create account with email/password
4. Verify session is created

### Test Marketplace
1. Go to `/marketplace.php`
2. Apply filters (category, city, price)
3. Verify listings filter correctly

### Test Listing Creation
1. Login as user
2. Go to `/sell.php`
3. Upload 3 images, fill details
4. Submit listing
5. Verify it appears in Admin → Pending queue

## Troubleshooting

### Database Connection Error
- Check `includes/config.php` credentials
- Verify database exists in phpMyAdmin
- Ensure database user has privileges

### Images Not Uploading
- Check `assets/uploads/` folder permissions (755 or 777)
- Verify `MAX_FILE_SIZE` in `config.php`
- Check PHP `upload_max_filesize` in hosting settings

### 404 Errors
- Ensure `.htaccess` exists (create if missing):
```apache
RewriteEngine On
RewriteBase /getMarried/

# Remove .php extension (optional)
RewriteCond %{REQUEST_FILENAME} !-f
RewriteRule ^([^\.]+)$ $1.php [NC,L]
```

### Session Not Persisting
- Check PHP session settings in hosting
- Ensure cookies are enabled in browser
- Verify `session_start()` at top of pages

## Security Best Practices

✅ **Implemented:**
- Password hashing with `bcrypt`
- Prepared statements (SQL injection prevention)
- Input sanitization
- Session management
- CSRF protection (add tokens in forms)

⚠️ **Recommendations:**
- Use HTTPS (SSL certificate - Hostinger provides free SSL)
- Change default admin password
- Regularly backup database
- Keep PHP version updated
- Implement rate limiting on API endpoints

## Performance Optimization

- Images lazy-loaded on marketplace
- CSS and JS minification (use build tools)
- Enable Gzip compression in `.htaccess`:
```apache
<IfModule mod_deflate.c>
    AddOutputFilterByType DEFLATE text/html text/plain text/xml text/css text/javascript application/javascript
</IfModule>
```
- Enable browser caching:
```apache
<IfModule mod_expires.c>
    ExpiresActive On
    ExpiresByType image/jpg "access plus 1 year"
    ExpiresByType image/png "access plus 1 year"
    ExpiresByType text/css "access plus 1 month"
    ExpiresByType application/javascript "access plus 1 month"
</IfModule>
```

## Future Enhancements (Phase 2)

- [ ] User messaging system (in-app chat)
- [ ] SMS/OTP authentication
- [ ] Payment gateway integration
- [ ] Advanced search with Elasticsearch
- [ ] Progressive Web App (PWA)
- [ ] Email notifications
- [ ] Social media sharing
- [ ] Review and rating system
- [ ] Wishlist and favorites
- [ ] Saved searches
- [ ] Bulk upload for sellers
- [ ] Analytics dashboard for admins

## Support

For issues or questions:
- Email: support@getmarried.site
- Documentation: See `docs/` folder
- Hostinger Support: https://www.hostinger.com/tutorials

## License

Proprietary - All rights reserved

---

## Quick Start Checklist

- [ ] Upload files to Hostinger
- [ ] Create MySQL database
- [ ] Import `database/schema.sql`
- [ ] Configure `includes/config.php`
- [ ] Set `assets/uploads/` permissions
- [ ] Change admin password
- [ ] Test budget planner
- [ ] Test user signup
- [ ] Test marketplace
- [ ] Enable SSL certificate
- [ ] Configure Google Analytics
- [ ] Seed 10-20 sample listings

---

**Built with ❤️ for couples planning budget weddings**
#   g e t m a r r i e d  
 