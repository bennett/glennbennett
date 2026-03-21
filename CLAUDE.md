# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

> **MCP Server:** Call `get_site_context(identifier: "glennbennett.com")` from the macman MCP server for deployment commands, email config, hosting details, and related sites. Call `get_playbook_docs(category: "google")` for the full Google OAuth setup guide.

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

- **Production:** Google OAuth via `Google_auth` library (raw cURL, no Composer). See `get_playbook_docs(category: "google")` for the full setup guide.
- **Local dev:** Username/password fallback via `.env` (`ADMIN_USERNAME`/`ADMIN_PASSWORD`).
- **Important:** `ADMIN_USERNAME`/`ADMIN_PASSWORD` must NOT be in the production `.env` — only Google OAuth should work there.
- Credentials and env vars are documented in `.env.example`.

## Development

### Dependencies

```bash
composer install
```

Key packages: `spatie/calendar-links` (generate add-to-calendar links), `johngrogg/ics-parser` (parse iCal feeds), `aws/aws-sdk-php` (SES email).

**CRITICAL:** `vendor/` is NOT deployed to production. Production does not use Composer at all. The `.env` parser in `index.php` is self-contained. Do NOT add Composer dependencies that are needed at runtime on production — use raw PHP/cURL instead (see `Google_auth.php` and `Ses_email.php` as examples).

### Production PHP Version

Production now runs **PHP 8.3** (upgraded 2026-03-21 from 7.2). Local dev runs **PHP 8.4** via Herd.

**!! CRITICAL — DO NOT TOUCH THESE FILES !!**

The PHP 8.3 upgrade required a surgical patch to CodeIgniter 3's core to suppress dynamic property deprecation warnings (PHP 8.2+ deprecated dynamic properties, which CI3 uses everywhere). Two files make this work:

1. **`.htaccess`** — contains `AddHandler application/x-httpd-ea-php83` which tells InMotion's Apache to use PHP 8.3. If you change this to `ea-php72` the site reverts to PHP 7.2. If you use a version that doesn't exist (e.g. `ea-php84`) the site returns 406 errors.

2. **`system/core/Common.php`** — patched `_error_handler()` function (around line 615) to suppress `Creation of dynamic property` deprecation warnings. Without this patch, the homepage dumps 24+ PHP error blocks to every visitor. The patch is:
   ```php
   // PHP 8.2+ CI3 compatibility: suppress dynamic property deprecation
   if ($severity === E_DEPRECATED && strpos($message, 'Creation of dynamic property') !== false)
   {
       return;
   }
   ```

**Why these are dangerous:**
- `system/` is normally gitignored and never deployed — this is the ONE exception
- The `.htaccess` handler name must exactly match an EasyApache package installed on InMotion — `ea-php83` is the only confirmed working version above 7.2
- Removing the Common.php patch while on PHP 8.3 will display raw PHP errors to every visitor
- The lftp exclude list does NOT deploy `system/` by default — this file was deployed manually via `lftp put -O system/core/ system/core/Common.php`
- If CI3's `system/` folder is ever replaced/updated, this patch must be reapplied

**What we tried that didn't work:**
- `error_reporting(E_ALL & ~E_DEPRECATED)` in `index.php` — CI3's error handler ignores `error_reporting()` for display purposes
- `ini_set('display_errors', 0)` — CI3's `str_ireplace` check treats `'0'` as truthy (only matches `'off'`, `'none'`, `'no'`, `'false'`, `'null'`)
- `ini_set('display_errors', 'off')` — still didn't work because CI3's handler runs before the setting takes effect
- Custom `set_error_handler()` before `require CodeIgniter.php` — CI3 replaces it with its own handler
