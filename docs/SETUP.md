# Setup Guide — glennbennett.com

How to set up this project from scratch on a new machine.

## 1. Config Files

Copy the three example configs:

```bash
cp .env.example .env
cp application/config/database.php.example application/config/database.php
cp application/config/email.php.example application/config/email.php
cp application/config/recaptcha.php.example application/config/recaptcha.php
```

## 2. Environment Variables (.env)

```
AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=us-west-1

GOOGLE_AUTH_CLIENT_ID=
GOOGLE_AUTH_CLIENT_SECRET=
GOOGLE_AUTH_REDIRECT_URI=https://glennbennett.com.test/admin/login/google_callback
GOOGLE_AUTH_ALLOWED_EMAIL=gbennett@tsgdev.com

# Local dev only — omit in production
ADMIN_USERNAME=admin
ADMIN_PASSWORD=admin
```

## 3. Database

Create the MySQL database and load the schema:

```bash
mysql -u root -e "CREATE DATABASE tsgimh_glb1"
mysql -u root tsgimh_glb1 < sql/admin_schema.sql
mysql -u root tsgimh_glb1 < sql/seed_venues.sql
```

Edit `application/config/database.php`:
- **Local:** hostname `localhost`, username `root`, password empty, database `tsgimh_glb1`
- **Production:** hostname `localhost`, username `tsgimh_glb`, password from cPanel

Run pending migrations by visiting `/migrate` in the admin panel.

## 4. Composer

```bash
composer install
```

Packages: `spatie/calendar-links`, `johngrogg/ics-parser`, `aws/aws-sdk-php`

**Note:** `vendor/` is NOT deployed to production. Production code uses raw PHP/cURL only.

## 5. Admin Authentication

Two methods, depending on environment:

### Local Dev (username/password)

If Google OAuth is not configured, the app falls back to the `ADMIN_USERNAME` and `ADMIN_PASSWORD` values in `.env`. This is for local dev only.

### Production (Google OAuth)

1. Create OAuth 2.0 credentials in [Google Cloud Console](https://console.cloud.google.com/)
2. Set **Authorized redirect URI** to `https://glennbennett.com/admin/login/google_callback`
3. Copy Client ID and Client Secret to `.env`
4. Set `GOOGLE_AUTH_ALLOWED_EMAIL` to the Google account email that should have admin access

Only the matching email gets in. The library is at `application/libraries/Google_auth.php` — raw cURL, no SDK.

## 6. reCAPTCHA v3

Used on the booking/contact form for spam prevention.

1. Go to [Google reCAPTCHA Admin Console](https://www.google.com/recaptcha/admin)
2. Create a v3 site for your domain
3. Copy Site Key and Secret Key into `application/config/recaptcha.php`

The same keys work on `.test` local domains.

## 7. SQLite Music Database

The homepage featured track pulls from a shared SQLite database owned by the Music Player app (`music.glennbennett.com`).

Config is in `application/config/music_db.php`:

```php
$config['music_db_path'] = '/home/tsgimh/music.glennbennett.com/database/music.db';
```

**Local dev:** Adjust path to wherever your local copy of `music.db` lives, or music features will fail silently.

## 8. Bunny CDN

Audio streaming and cover art use Bunny CDN zone `glb-songs`:

| Purpose | URL |
|---------|-----|
| Audio streaming | `https://glb-songs.b-cdn.net/songs` |
| Cover art | `https://glb-songs.b-cdn.net/songs/imgs` |

Configured in `application/config/music_db.php`. No API key needed — public pull zone.

## 9. Email

**Local dev:** Email is disabled by default (empty SMTP config in `email.php`).

**Production (InMotion):** Uses PHP `mail()` function — no SMTP credentials needed.

**Alternative:** Brevo SMTP is available (`smtp-relay.brevo.com`, port 587). Configure in `email.php` if needed.

**SES:** Legacy support exists via `application/libraries/Ses_email.php`, uses the AWS keys from `.env`.

## 10. Production Server Notes

### PHP Version

Production runs PHP 8.3 on InMotion (EasyApache). The `.htaccess` contains:

```apache
AddHandler application/x-httpd-ea-php83 .php .php7 .phtml
```

### CI3 PHP 8.2+ Patch

`system/core/Common.php` has a patch to suppress dynamic property deprecation warnings. This file is deployed manually (not via lftp since `system/` is excluded):

```bash
lftp glennbennett -e "put -O system/core/ system/core/Common.php; quit"
```

Must be reapplied if the `system/` directory is ever replaced.

### Deployment

```bash
lftp glennbennett -e "mirror --reverse --exclude-glob-from ~/.lftp/exclude-list . ."
```

After deploying, run migrations at `/migrate` if there are pending schema changes.

## Setup Checklist

- [ ] Copy 3 config files from `.example` templates
- [ ] Fill in `.env` values
- [ ] Create MySQL database and load schema + seeds
- [ ] Run `composer install`
- [ ] Verify admin login works (local username/password or Google OAuth)
- [ ] Check homepage loads (music database connection)
- [ ] Test contact form (reCAPTCHA)
- [ ] Verify calendar feed displays upcoming gigs
