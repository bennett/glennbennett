# Glenn Bennett Website

Public musician portfolio and gig calendar at glennbennett.com. Showcases original music, lists upcoming live performances, and handles booking inquiries.

## Software Versions

| Component | Version |
|-----------|---------|
| Framework | CodeIgniter 3 |
| PHP (production web) | 7.2 |
| PHP (local) | 8.4 |
| Database | MySQL (MariaDB 10.6 on production) |
| Admin theme | AdminLTE 2.4.2 (skin-blue-light) |
| Frontend theme | Canvas White |

## Hosting & Access

| Environment | URL | Host |
|-------------|-----|------|
| Production | glennbennett.com | InMotion (ftp.tsgimh.com) |
| Local | glennbennett.com.test | Laravel Herd |
| Database | tsgimh_glb1 | localhost MySQL, root, no password |
| Git | github.com/bennett/glennbennett | Private |

## Key Features

- **Homepage** with featured track audio player, hero section, and upcoming gig carousel
- **Live calendar** — pulls from Google Calendar iCal feeds, displays venue details with weather widgets
- **Social sharing** — generates 1200x630 OG images dynamically using template backgrounds + artist photo overlays (GD)
- **Booking form** — multi-field event inquiry with reCAPTCHA v3 and HTML email notifications via SES
- **Music integration** — reads from shared SQLite database at music.glennbennett.com, streams audio via Bunny CDN
- **Calendar links** — Google, Apple, Outlook "add to calendar" buttons
- **Admin panel** — share template management, venue management, database migrations, cleanup tools

## Share Image System

The share image system generates 1200x630 OG images for Facebook/social media event sharing. Images are rendered dynamically using GD with composited backgrounds, artist photos, and event text.

### How It Works

1. Calendar page generates a share link: `/facebook?event_id=...&event_date=...`
2. Facebook controller creates a hash in the `share_images` table
3. OG meta tag points to `/cal-image/{hash}` (8-character short URL)
4. Image is rendered dynamically using the template system:
   - **Background** (1200x630 JPG/PNG) + **Artist photo** (PNG cutout) composited via GD
   - Text overlay: event name, date, time, location with configurable fonts, glow, stroke, shadow
   - Past events show "This event has passed" overlay
5. If no template matches, a simple fallback image is generated

### Template Workflow

1. **Upload background** → auto-resized to 1200x630 (allows up to 10% upscale)
2. **Upload artist photo** (PNG cutout) → auto-trimmed of transparent pixels
3. **Set defaults** for each → configures photo position/scale and text layout
4. **Templates auto-generate** when both photo and background have defaults set
5. **Customize per-template** in the template editor (override defaults)
6. **Mark as Ready** → template enters the active pool for rendering

### Template Selection Priority

1. **Venue-specific templates** — assigned directly to a venue
2. **Venue type templates** — assigned to a venue type (e.g., "Farmers Market")
3. **All active templates** — global fallback pool
4. **Legacy cal_images** — old system (being retired)
5. **Static Cal-Event-N.jpg** — original 25 images (being retired)
6. **Fallback** — plain dark image with event name (never 500s)

### Background Text Presets

Text layout settings (font sizes, margins, glow, stroke) can be saved per-background as presets. When editing a template, you can load/save these presets to quickly apply consistent text settings across templates sharing the same background.

### Naming Convention

- Photos: `photo-{id}` (e.g., `photo-2`)
- Backgrounds: `bg-{id}` (e.g., `bg-1`)
- Templates: `{bg_name}_{photo_name}` (e.g., `bg-1_photo-2`)
- All names are editable inline via click-to-rename

### Orphaned Templates

When a background or photo is deleted, its templates are marked as orphaned (`is_orphaned = 1`) rather than deleted. This preserves any cached rendered images that may still be referenced by active share links. Orphaned templates can be bulk-deleted from the Share Templates page.

## Admin Panel

### Navigation Structure

**NAVIGATION**
- Dashboard

**Calendar Tools**
- Venues — manage venue names, match patterns, logos, and template assignments
- Venue Types — categorize venues, assign templates by type
- Duplicate Events — browse past events, duplicate to new dates, export CSV for Google Calendar import

**Share Image Management**
- Backgrounds — upload/manage 1200x630 background images, set text layout defaults
- Artist Photos — upload/manage PNG cutout photos, set position/scale defaults
- Share Templates — view all generated templates, mark ready, rename, delete orphans

**TOOLS**
- Migrations — run database migrations on production after deploy
- Share Link Cleanup — retire the legacy cal_images system (Cal-Event-*.jpg files, old gcal scripts, legacy DB tables)
- Test Email — SES email test sender

**ACCOUNT**
- Change Password

### Admin Authentication

- **Production:** Google OAuth ("Sign in with Google") via `Google_auth.php` library — raw cURL, no Composer dependencies
- **Local dev:** Username/password fallback via `.env` (`ADMIN_USERNAME`/`ADMIN_PASSWORD`)
- Admin base controller enforces auth on all `/admin/*` routes
- Credentials stored in `.env` (see `.env.example` for required variables)

## Endpoints

### Public Pages

| URL | Description |
|-----|-------------|
| `/` | Homepage — featured tracks, hero, upcoming gigs |
| `/calendar` | Live performance calendar (Google Calendar iCal feed) |
| `/past` | Past calendar events |
| `/contact` | Contact form (reCAPTCHA + SES email) |
| `/booking` | Booking inquiry form |
| `/about` | About page |
| `/follow` | Mailing list signup |
| `/samples` | Performance samples (YouTube) |
| `/vids` | YouTube playlists (originals, covers, samples) |
| `/tip` | Online tip jar |
| `/links` | Quick links dashboard |
| `/album` | 2016 Live Album player |
| `/qr` | QR code landing page |

### Calendar & Share Utilities

| URL | Description |
|-----|-------------|
| `/facebook` | Open Graph metadata page for event sharing |
| `/cal-image/{hash}` | Generate share image by hash (new system) |
| `/cal-image?start_date=&end_date=&summary=&location=` | Legacy query-string image generation |
| `/gcal/gcal-gigs.php` | Render upcoming performances (AJAX) |
| `/gcal/gcal-upcoming.php` | Next 14 days of performances (AJAX) |
| `/gcal/gcal-gigs-past.php` | Render past performances (AJAX) |
| `/gcal/gcal-gigs-dup.php` | Render duplicate events (AJAX) |
| `/gcal/fb_image.php` | Redirects to `/cal-image` (backwards compatibility) |

### Admin Panel (`/admin` — login required)

| URL | Description |
|-----|-------------|
| `/admin` | Dashboard |
| `/admin/template_backgrounds` | Background image management |
| `/admin/template_background_defaults/{id}` | Text layout defaults editor for a background |
| `/admin/template_photos` | Artist photo management |
| `/admin/template_photo_defaults/{id}` | Position/scale defaults editor for a photo |
| `/admin/templates` | Share template grid — all bg+photo combinations |
| `/admin/template_editor/{id}` | Per-template customization editor |
| `/admin/venues` | Venue management |
| `/admin/venue_edit/{id}` | Edit venue details and template assignments |
| `/admin/venue_types` | Venue type management |
| `/admin/venue_type_edit/{id}` | Edit venue type and template assignments |
| `/admin/dup_events` | Duplicate events tool |
| `/admin/share_cleanup` | Legacy system cleanup tool |
| `/admin/test_email` | SES email test sender |
| `/admin/images` | Legacy calendar image management (being retired) |
| `/admin/image_layout/{id}` | Legacy text overlay layout editor |
| `/migrate` | Database migration runner |

## Database Migrations

Migrations live in `database/migrations/` and run via the web UI at `/migrate` (behind admin auth).

| Migration | Description |
|-----------|-------------|
| `001_create_venue_types_table` | Venue types with seed data |
| `002_create_venue_details_table` | Venue detail records |
| `003_create_venue_template_junctions` | venue_type_templates + venue_templates junction tables |
| `004_add_venue_type_id_to_venues` | FK from venues to venue_types |
| `005_add_share_template_columns` | All new columns: templates (is_ready, is_orphaned, name, image adjustments), template_photos (has_defaults, image adjustments, text defaults), template_backgrounds (has_defaults) |

## File Structure

```
application/
  controllers/    — Admin, Site, Facebook, Migrate, Booking, Contact
  models/         — Template_model, Venue_model, Venue_type_model, Cal_image_model, Share_image_model
  views/
    admin/        — AdminLTE admin pages (templates, venues, cleanup, migrate)
    layouts/      — Page shells (Canvas White theme)
    partials/     — Reusable components (nav, footer, OG tags)
  core/           — MY_Controller, MY_Model, Admin_Controller
  libraries/      — Cal_image_renderer (GD), Gcal_gig_reader, Google_auth, Ses_email, Migration_runner
  config/         — routes, database, email, amazon_ses, recaptcha, globals
assets/           — JS, CSS, AdminLTE skins, fonts
database/
  migrations/     — Schema migration files
imgs/
  template-backgrounds/ — 1200x630 background images (gitignored)
  template-photos/      — PNG artist photo cutouts (gitignored)
  template-cache/       — Rendered preview cache (gitignored)
  cal/                  — Venue logos
  Cal-Event-*.jpg       — Legacy background images (being retired)
gcal/             — Google Calendar AJAX scripts, iCal parser, legacy share scripts
fonts/            — TTF fonts (Aladin, Georgia Bold) for image rendering
vendor/           — Composer packages (NOT deployed to production)
docs/             — Reference SQL, design assets
```

## Deployment

```bash
lftp glennbennett -e "mirror --reverse --exclude-glob-from ~/.lftp/exclude-list . ."
```

After deploying new schema changes, log in to admin and visit `/migrate` to run pending migrations.

**Important:** Production runs PHP 7.2 for web requests. Do not deploy `vendor/` or code requiring PHP 8.1+. Use raw PHP/cURL for any runtime dependencies (see `Google_auth.php`, `Ses_email.php`).

## Local Setup

```bash
cd ~/Herd/glennbennett.com
composer install
cp .env.example .env        # Edit with local credentials
```

Database: `tsgimh_glb1` on localhost MySQL (root, no password). Config files with credentials (`database.php`, `email.php`, `recaptcha.php`) are gitignored — use `.example` files as templates.
