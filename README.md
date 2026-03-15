# Glenn Bennett Website

Public musician portfolio and gig calendar at glennbennett.com. Showcases original music, lists upcoming live performances, and handles booking inquiries.

## Software Versions

| Component | Version |
|-----------|---------|
| CodeIgniter | 3.1.11 |
| PHP | 8.4 |
| MySQL | 9.6.0 |
| Bootstrap | 3 |
| jQuery | (bundled) |
| AWS SDK | via Composer |

## Hosting & Access

| Environment | Details |
|-------------|---------|
| **Local** | glennbennett.com.test (Herd — Nginx + PHP-FPM) |
| **Production** | glennbennett.com (InMotion shared hosting — Apache) |
| **FTP** | `lftp glennbennett` → ftp.tsgimh.com |
| **Database** | `tsgimh_glb1` — localhost, root, no password (local) |
| **SSL (local)** | `herd secure glennbennett.com` for HTTPS |

## Email

- **Provider:** Amazon SES (us-west-1, production mode)
- **Library:** `Ses_email` in `application/libraries/` — uses AWS SDK directly (API, not SMTP)
- **Send from:** `gbennett@tsgdev.com` (verified in SES)
- **Domain:** glennbennett.com — fully verified with DKIM, SPF, DMARC
- **Config:** `application/config/amazon_ses.php` (gitignored, credentials from `.env`)
- **Used by:** Booking form, contact form, admin test email sender

## Local Setup

- **Domain:** glennbennett.com.test (via Herd)
- **Config files:** Copy `.php.example` files in `application/config/` (database, email, recaptcha, amazon_ses)
- **SSL:** Run `herd secure glennbennett.com` for HTTPS

## Key Features

- **Homepage** with featured track audio player, hero section, and upcoming gig carousel
- **Live calendar** — pulls from Google Calendar iCal feeds, displays venue details with weather widgets
- **Social sharing** — generates 1200x630 OG images with text overlay (GD), plus Facebook/Twitter/WhatsApp/SMS/email share buttons
- **Booking form** — multi-field event inquiry with reCAPTCHA v3 and HTML email notifications via SES
- **Music integration** — reads from shared SQLite database at music.glennbennett.com, streams audio via Bunny CDN
- **Calendar links** — Google, Apple, Outlook "add to calendar" buttons
- **Admin panel** — sharing image management with layout editor, venue images, test email sender

## Endpoints

### Public Pages (linked in navigation)

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

### Calendar Utilities (not linked in nav)

| URL | Description |
|-----|-------------|
| `/facebook` | Open Graph metadata page for event sharing |
| `/fb` | Facebook sharing page for calendar events |
| `/cal-image` | Generate 1200x630 OG image with text overlay (GD) |
| `/gcal/gcal-gigs.php` | Render upcoming performances (AJAX) |
| `/gcal/gcal-upcoming.php` | Next 14 days of performances (AJAX) |
| `/gcal/gcal-gigs-past.php` | Render past performances (AJAX) |
| `/gcal/gcal-gigs-dup.php` | Render duplicate events (AJAX) |
| `/gcal/fb_image.php` | Redirects to `/cal-image` controller |

### Admin Panel (`/admin` — login required)

| URL | Description |
|-----|-------------|
| `/admin` | Dashboard |
| `/admin/images` | Calendar sharing image management |
| `/admin/image_layout/{id}` | Text overlay layout editor with GD preview |
| `/admin/venues` | Venue image management |
| `/admin/test_email` | SES email test sender |
| `/admin/change_password` | Change admin password |
| `/admin/dup_events` | Duplicate events — browse past 360 days of performances, pick one to duplicate to a new date, and download a Google Calendar CSV import file |
| `/admin/dup_events/day?date=` | Select events from a specific date and set a new target date |
| `/admin/dup_events/generate_csv` | Preview and download the CSV for import into Google Calendar |

### Internal / Legacy (on production but not actively used)

| URL | Description |
|-----|-------------|
| `/cortez` | Internal todo/task list |
| `/cortez_edit` | Task management CRUD (Grocery CRUD) |
| `/examples/*` | Grocery CRUD demo pages |
| `/raw.php` | TinyFileManager (file browser with auth) |
| `/caltest/` | Calendar testing utility |
| `/wtest/` | Weather widget testing |
| `/gcal/test.php` | Calendar debug/test page |
| `/ical/` | Legacy iCal calendar viewer |
| `/hap/fix_encoding.php` | Fix HTML encoding for emoji characters |
| `/css/colors.php` | Dynamic CSS color configuration |

## File Structure

```
application/
  controllers/    — Admin, Booking, Contact, Calendar, Home, etc.
  models/         — Cal_image_model, Venue_model, etc.
  views/          — admin/ (AdminLTE 2.4.2), public pages (Canvas White theme)
  core/           — MY_Controller, MY_Model
  libraries/      — Ses_email (AWS SDK, API-based)
  config/         — routes, database, email, amazon_ses, recaptcha
assets/           — JS, CSS, images, fonts
imgs/cal-backgrounds/ — sharing image backgrounds
vendor/           — Composer packages (johngrogg/ics-parser, spatie/calendar-links, aws/aws-sdk-php)
```

## Deployment

Deploy to InMotion via lftp: `lftp glennbennett` then mirror/sync files. Production runs Apache with `.htaccess` URL rewriting. Email delivered via SES API (not SMTP).
