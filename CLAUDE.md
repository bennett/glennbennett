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

## Development

### Local Environment

The site runs under Laravel Herd. The web root is this directory. Access via the Herd-configured local domain.

### Dependencies

```bash
composer install
```

Key packages: `spatie/calendar-links` (generate add-to-calendar links), `johngrogg/ics-parser` (parse iCal feeds).

### Configuration

Config files with credentials are in `application/config/` — database.php, email.php, recaptcha.php, music_db.php. These contain environment-specific settings.

### URL Routing

Defined in `application/config/routes.php`. The `.htaccess` rewrites all non-file requests to `index.php` (CodeIgniter front controller pattern).
