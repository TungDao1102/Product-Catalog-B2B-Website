# Deployment Checklist — Shared Hosting

## Prerequisites

- PHP 8.1+ with extensions: `bcmath, ctype, fileinfo, json, mbstring, openssl, pdo, pdo_mysql, tokenxml, xml, gd, imagick`
- MySQL 5.7+ or MariaDB 10.3+
- Composer
- Node.js & npm (for building frontend assets)

## Step-by-Step

### 1. Upload Files

Upload the entire project to your hosting's document root (usually `public_html` or `www`).

**Important:** Point the domain to the `public/` directory (not the project root).

### 2. Environment Configuration

```bash
# Copy environment file
cp .env.example .env

# Generate app key
php artisan key:generate
```

Edit `.env` with your production values:

```dotenv
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_password

CACHE_DRIVER=file
SESSION_DRIVER=file
QUEUE_CONNECTION=database
MAIL_MAILER=smtp
```

### 3. Database Migration

```bash
php artisan migrate --force
php artisan db:seed --force
```

### 4. Storage Link

```bash
php artisan storage:link
```

### 5. Cache & Optimization

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize
```

### 6. File Permissions

```bash
chmod -R 775 storage bootstrap/cache
chmod -R 775 public/storage
```

### 7. OPcache

See `OPCACHE.md` for PHP OPcache configuration.

### 8. Queue Setup (for email sending)

Add the following cron job (runs every minute):

```cron
* * * * * cd /path-to-project && php artisan schedule:run >> /dev/null 2>&1
```

This ensures queued emails (contact inquiries, quote requests) are sent.

### 9. Sitemap Generation

```bash
php artisan sitemap:generate
```

Set up a cron job to regenerate the sitemap daily:

```cron
0 6 * * * cd /path-to-project && php artisan sitemap:generate >> /dev/null 2>&1
```

### 10. Verify

- [ ] Homepage loads: `https://yourdomain.com`
- [ ] Admin login: `https://yourdomain.com/admin`
- [ ] Sitemap accessible: `https://yourdomain.com/sitemap.xml`
- [ ] robots.txt accessible: `https://yourdomain.com/robots.txt`
- [ ] Language switcher works (VI / EN)
- [ ] Contact form sends email
- [ ] Quote request sends email

## Troubleshooting

| Issue | Solution |
|-------|----------|
| 500 Server Error | Check `storage/logs/laravel.log` |
| Blank page | Run `php artisan optimize` |
| Asset 404 | Run `php artisan storage:link` |
| Email not sending | Check MAIL_* env vars + queue worker |
| Database connection | Verify DB credentials in `.env` |
