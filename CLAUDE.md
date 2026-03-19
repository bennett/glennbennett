# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

This is **Glenn Bennett's musician website** — a CodeIgniter 3 PHP application for showcasing original music, managing gig/performance listings, handling booking inquiries, and mailing list subscriptions. It runs on Laravel Herd locally.

- **Project folder:** `~/Herd/glennbennett.com` (renamed from `glennbennett` on 2026-03-12)
- **Local domain:** `glennbennett.com.test` (production: `glennbennett.com`)
- **Git repo:** `github.com/bennett/glennbennett` (private)

## Tech Stack

- **Framework:** CodeIgniter 3 (PHP)
- **Databases:** MySQL (`tsgimh_glb1`) for primary data, SQLite (remote at `music.glennbennett.com`) for music catalog
- **Frontend:** jQuery, Bootstrap, custom CSS
- **CDN:** Bunny CDN for audio streaming and cover art
- **External Services:** Google Calendar (iCal feeds), reCAPTCHA v3, SMTP email, Campaign Monitor/GetResponse

## Architecture

### CodeIgniter MVC Structure

- `application/controllers/` — Route handlers. **Site.php** is the main controller (homepage, calendar, music library, mailing list). Other controllers: Contact, Booking, Gcal_reader, Facebook (OG metadata for event sharing), Cortez (todo system).
- `application/models/` — **Music_model.php** queries the remote SQLite music DB and builds Bunny CDN streaming URLs. **Songs_model.php** and **Tracks_model.php** are legacy local song models. **Cortez_todos_model.php** manages the task system.
- `application/views/` — Templates organized with `layouts/` for page shells, `partials/` for reusable components (nav, footer, OG tags), `page_partials/` for page-specific includes, and per-page `css/`/`js/` view files.
- `application/libraries/` — Key custom libraries: **Gcal_gig_reader.php** (parses Google Calendar iCal feeds with timezone handling), **Weather_lib.php**, **MP3File.php** (ID3 metadata), **Date_diff.php**, **Globals.php** (app-wide constants).
- `application/config/` — **routes.php** (default controller is Site; `/calendar` → `site/cal`), **database.php** (MySQL), **music_db.php** (SQLite path + Bunny CDN URLs), **globals.php** (site title, CDN URLs), **email.php** (SMTP), **recaptcha.php**.

### Key Non-MVC Directories

- `gcal/` — Google Calendar utilities loaded via AJAX. **gcal-upcoming.php** and **gcal-gigs.php** fetch and render performance listings. Includes iCalReader class and a cache directory for downloaded calendar data.
- `ical/` — Older iCal parsing implementation with core calendar functionality.
- `include/` — Legacy PHP includes: form handling, PHPMailer, AJAX handlers, subscription service integrations (Campaign Monitor, GetResponse), Twitter OAuth.
- `songs/` — MP3 audio files and album artwork for original songs.
- `venues/` — Venue data fetched via Guzzle HTTP client.
- `weather/` — Weather widget for performance locations.

### Key Workflows

1. **Homepage** loads featured tracks from SQLite music DB, popular songs carousel, and upcoming events via AJAX from `gcal/`.
2. **Calendar page** (`/calendar`) makes AJAX calls to `gcal/gcal-gigs.php` which parses Google Calendar iCal feeds and renders performance listings.
3. **Facebook sharing** pages generate Open Graph metadata for individual events.
4. **Contact/Booking forms** use reCAPTCHA v3 validation and send via SMTP.

## Admin Authentication

**Local dev:** Plain-text login via `.env` (`admin`/`admin`). The `Admin_user_model::attempt()` checks `.env` credentials first, then falls back to the database.

**Production:** Google OAuth via `Google_auth` library (raw cURL, no Composer dependencies). OAuth client "Bennett CI3 Sites" in Google Cloud Console with redirect URI `https://glennbennett.com/admin/login/google_callback`.

**Important:** `ADMIN_USERNAME`/`ADMIN_PASSWORD` must NOT be in the production `.env` — only Google OAuth should work there.

## Environment Variables (.env)

The `.env` file is loaded by a simple built-in parser in `index.php` — **NOT** by `vlucas/phpdotenv`. This is intentional: production (InMotion) runs PHP 7.2 via the web server and cannot use Composer packages that require PHP 8.1+. The `vendor/` directory is excluded from deployment and does not exist on production.

**Local `.env`:**
```
AWS_ACCESS_KEY_ID=...
AWS_SECRET_ACCESS_KEY=...
AWS_DEFAULT_REGION=us-west-1
GOOGLE_AUTH_CLIENT_ID=...
GOOGLE_AUTH_CLIENT_SECRET=...
GOOGLE_AUTH_REDIRECT_URI=https://glennbennett.com/admin/login/google_callback
GOOGLE_AUTH_ALLOWED_EMAIL=gbennett@tsgdev.com
ADMIN_USERNAME=admin
ADMIN_PASSWORD=admin
```

**Production `.env`:** Same but without `ADMIN_USERNAME`/`ADMIN_PASSWORD`.

## Development

### Local Environment

The site runs under Laravel Herd. The web root is this directory. Access via the Herd-configured local domain.

### Dependencies

```bash
composer install
```

Key packages: `spatie/calendar-links` (generate add-to-calendar links), `johngrogg/ics-parser` (parse iCal feeds), `aws/aws-sdk-php` (SES email).

**CRITICAL:** `vendor/` is NOT deployed to production. Production does not use Composer at all. The `.env` parser in `index.php` is self-contained. Do NOT add Composer dependencies that are needed at runtime on production — use raw PHP/cURL instead (see `Google_auth.php` and `Ses_email.php` as examples).

### Configuration

Config files with credentials are in `application/config/` — database.php, email.php, recaptcha.php, music_db.php. These contain environment-specific settings.

### URL Routing

Defined in `application/config/routes.php`. The `.htaccess` rewrites all non-file requests to `index.php` (CodeIgniter front controller pattern).

### Production PHP Version

InMotion shared hosting runs **PHP 7.2** for web requests (set via `AddHandler ea-php72` in `.htaccess`). The CLI PHP is 8.3 but that's irrelevant — the web server uses a different binary. Upgrading the web PHP to 8.1+ causes a 500 error (unresolved as of 2026-03-19). Do NOT deploy `vendor/` or any code that requires PHP 8.1+.

### Deployment

Deploy to InMotion via lftp using the `glennbennett` bookmark:

```bash
lftp glennbennett -e "mirror --reverse --exclude-glob-from ~/.lftp/exclude-list . ."
```

The shared exclude list at `~/.lftp/exclude-list` filters out docs, dev files, vendor/, system/, logs, cache, and uploads.

**Post-deploy checklist:**
- `.env` must exist on the server (created manually via cPanel File Manager)
- `vendor/` must NOT exist on the server (if it does, `rm -rf ~/glennbennett.com/vendor`)
- `.htaccess` must have the `ea-php72` handler (lftp excludes `.htaccess` so it won't be overwritten)
- `system/` must exist on the server (not deployed, already there)
