# Changelog

## 2026-03-19 — Share Images with Short URLs

### Changes
- Share image URLs now use short hash-based URLs (`/cal-image/a3f8b2`) instead of fragile base64/query string URLs
- Event data stored in `share_images` DB table (max 100 rows, auto-pruned)
- Past events render an "event has passed" image with stored event name/date
- Facebook controller and Site `fb()` generate share hashes automatically
- OG image tags use short URLs for reliable social media previews
- Old query string URLs (`/cal-image?start_date=...`) still work for backwards compatibility
- Old files (`gcal/cal_image.php`, `gcal/fb_image.php`) untouched — cached links still work

### Files Modified
| File | Change |
|------|--------|
| `application/models/Share_image_model.php` | New — find_or_create with hash, get_by_hash, auto-prune |
| `application/models/Template_model.php` | Added `get_active_with_assets()` |
| `application/libraries/Cal_image_renderer.php` | Added `render_expired()` for past events |
| `application/controllers/Site.php` | `cal_image()` accepts hash param, expired logic |
| `application/controllers/Facebook.php` | Generates share hash, uses `og.php` partial |
| `application/config/routes.php` | Added `cal-image/(:any)` route |
| `application/views/partials/og.php` | OG image uses short URL |
| `application/views/fb.php` | Inline image uses short URL |
| `application/views/fb1.php` | Inline image uses short URL |
| `docs/share_images.sql` | New — table definition for production |

## 2026-03-11 v1 — "How to Listen" Section & Song List Styling

### Changes
- Replaced "Want to hear more?" CTA section on homepage with a two-card side-by-side layout:
  - **Left card:** Original player CTA with app screenshot beside text (horizontal layout), "Launch the Player" button, Add to Home Screen note
  - **Right card:** "The Best Way to Hear It" teaser with "How to Listen" button that opens a modal overlay
- Added full-page modal with the "How to Actually Listen to Milestones" article (article content, setup instructions, quick note about Bluetooth)
- Modal features: fade + slide animation, click-outside-to-close, Escape key support, responsive mobile layout
- Added Lora and Source Sans 3 Google Fonts for the listen section and modal
- Increased song play/pause icons from 30px to 38px (font 14px → 18px)
- Increased song title font size from 13px to 18px
- Increased song image max-width from 17% to 27% at 767px+ breakpoint
- Cards stack vertically on mobile (under 700px)

### Bug Fixes
- Removed `error_log("Hello, errors!")` from `gcal/gcal-upcoming.php` and `gcal/gcal-gigs.php` — missed in v1/v2 cleanup on 2026-03-10, never deployed. Firing on every page/AJAX load.
- Fixed `vids.php` crash: `$videos` undefined when YouTube API fails (HTTP 406, rate limit). Added null guard in controller (`@file_get_contents`, null-check on response) and fallback message in view.
- Fixed `$vid_title` and `$vid_sub_title` undefined notices — only initialized in `case 3` but referenced for all vids routes. Now initialized with defaults at top of method.
- Fixed `Music_model::get_featured()` crash when remote SQLite DB lacks `s.featured` column — query now suppresses error and returns null gracefully instead of fatal.

### Files Modified
| File | Change |
|------|--------|
| `application/views/home.php` | Replaced CTA with two-card listen section + modal; added Lora/Source Sans 3 fonts; bumped music.css to `?v=5` |
| `demos/music/music.css` | Larger play/pause icons (38px), larger song titles (18px), wider song images (27%) |
| `gcal/gcal-upcoming.php` | Removed test `error_log()` call |
| `gcal/gcal-gigs.php` | Removed test `error_log()` call |
| `application/controllers/Site.php` | Initialized `$vid_title`/`$vid_sub_title` defaults; added YouTube API error handling |
| `application/models/Music_model.php` | Added null check on failed `featured` query |
| `application/views/vids.php` | Added empty `$videos` guard with user-friendly message |

### Podcast & Directory Security
- Uploaded `songs/podcast/episode_1.mp3` — normalized podcast episode (m4a → mp3, -24.9 LUFS → -16.2 LUFS, 192kbps)
- Added `songs/podcast/cover.jpg` — podcast cover art (280KB)
- Added empty `index.html` to `songs/`, `songs/_staging/`, `songs/imgs/`, and `songs/podcast/` to prevent directory listing

### Server Cleanup
| File | Size | Action |
|------|------|--------|
| `php-error.log` | 3.2 KB | Deleted |
| `gcal/php-error.log` | 406 bytes | Deleted |
| `application/logs/log-*.php` | ~28 MB (143 files, Sept 2025–Feb 2026) | Deleted (backed up in zip) |

### Backups
Remote backups saved to `backups/2026-03-11-v1/`

---

## 2026-03-10 v2 — Remove gcal error_log & Fix Weather Null Check

### Changes
- Removed test `error_log("Hello, errors!")` from `gcal/gcal-core.php` (same issue as ical fix in v1)
- Fixed "array offset on null" error in `gcal/libs/weather_warnings.php` — weather API response now null-checked before accessing data

### Files Modified
| File | Change |
|------|--------|
| `gcal/gcal-core.php` | Removed test `error_log()` call |
| `gcal/libs/weather_warnings.php` | Added null check on API response before accessing `$data['days']` |

### Backups
Remote backups saved to `backups/2026-03-10-v2/`

---

## 2026-03-10 v1 — Remove Test error_log, Fix DTEND Notices, Remove Dead Code

### Changes
- Removed test `error_log("Hello, errors!")` from `ical/gcal-core.php` line 7 that fired on every calendar load
- Fixed "Undefined index: DTEND" PHP notices in `gcal/libs/gcal_reader.php` for calendar events missing an end time (e.g., all-day events)
- Removed dead `vids_old()` method from `Site.php` that referenced missing `rs-plugin` YouTube class (replaced by new `vids()` using YouTube Data API directly)
- Deleted three bloated `php-error.log` files from server (`ical/`, `gcal/`, and root)

### Files Modified
| File | Change |
|------|--------|
| `ical/gcal-core.php` | Removed test `error_log()` call |
| `gcal/libs/gcal_reader.php` | Added `isset()` guards for missing `DTEND` on lines 176 and 219 |
| `application/controllers/Site.php` | Removed unused `vids_old()` method with broken `rs-plugin` dependency |

### Server Cleanup
| File | Size | Action |
|------|------|--------|
| `ical/php-error.log` | small | Deleted |
| `php-error.log` | 2.2 MB | Deleted |
| `gcal/php-error.log` | 426 KB | Deleted |

### Backups
Remote backups saved to `backups/2026-03-10-v1/`

---

## 2026-03-07 v6 — SMS Encoding Fix

### Changes
- Fixed SMS share links showing `+` instead of spaces by switching from `urlencode()` to `rawurlencode()` for `sms:` links

### Files Modified
| File | Change |
|------|--------|
| `application/views/home.php` | Changed SMS link to use `rawurlencode()` |
| `gcal/gcal-gigs.php` | Changed SMS link to use `rawurlencode()` |

### Backups
Remote backups saved to `backups/2026-03-07-v1/`

---

## 2026-03-07 v5 — Larger OG Image Text

### Changes
- Increased OG image body text to 48px, button to 42px, URL to 26px for better readability when shared
- Bumped cache-busting parameter to `?v=4`

### Files Modified
| File | Change |
|------|--------|
| `imgs/og-image.jpg` | Regenerated with larger body text and button |
| `application/views/home.php` | Cache-bust bumped to `?v=4` |

### Backups
Remote backups saved to `backups/2026-03-07-v1/`

---

## 2026-03-07 v4 — Final OG Image with Montserrat Bold

### Changes
- Regenerated OG image using brewery performance photo (640-480.jpg) with Montserrat Bold font at 100px
- Headline: "Glenn L. Bennett", subtitle: "Music & Live Performances"
- Added text shadows and stronger gradient for readability
- Added `?v=3` cache-busting parameter to og:image and twitter:image meta tags to force Facebook cache refresh

### Files Modified
| File | Change |
|------|--------|
| `imgs/og-image.jpg` | Final 1200x630 OG image — Montserrat Bold, large text, right-aligned with shadow |
| `application/views/home.php` | Added `?v=3` cache-busting to og:image and twitter:image URLs |

### Backups
Remote backups saved to `backups/2026-03-07-v1/`

---

## 2026-03-07 v3 — Updated OG Image

### Changes
- Regenerated OG image using brewery live performance photo (640-480.jpg)
- Updated headline to "Glenn L. Bennett", subtitle to "Music & Live Performances"
- Text content positioned top-right with gradient overlay

### Files Modified
| File | Change |
|------|--------|
| `imgs/og-image.jpg` | Regenerated 1200x630 OG image from brewery performance photo |

### Backups
No backup needed — image was newly created in v2

---

## 2026-03-07 v2 — OG Image & Cleaner Share Text

### Changes
- Created 1200x630 OG image with headline, CTA, and URL (replaces 640x480)
- Updated OG and Twitter Card meta tags to use new image
- Cleaned up SMS pre-filled text for homepage and event shares

### Files Modified
| File | Change |
|------|--------|
| `imgs/og-image.jpg` | New 1200x630 Open Graph image |
| `application/views/home.php` | Updated og:image/twitter:image meta tags; shorter SMS share text |
| `gcal/gcal-gigs.php` | Cleaner event share text for SMS/social |

### Backups
Remote backups saved to `backups/2026-03-07-v1/`

---

## 2026-03-07 v1 — Share Buttons & Weather Fix

### Changes
- Added social/messaging share buttons to calendar events (Facebook, Twitter, WhatsApp, Text, Email, Copy Link, Print)
- Added site-wide share buttons to homepage footer (Facebook, Twitter, WhatsApp, Text, Email, Copy Link)
- Added Instagram follow button to homepage footer
- Fixed PHP warning in weather_warnings.php when preciptype is null

### Files Modified
| File | Change |
|------|--------|
| `gcal/gcal-gigs.php` | Added share buttons row (Twitter, WhatsApp, SMS, Email, Copy Link) alongside existing Facebook/Print |
| `application/views/home.php` | Moved share buttons into footer right side; added Instagram follow link |
| `gcal/libs/weather_warnings.php` | Added null/array check on `$hour['preciptype']` to prevent warning |

### Backups
Remote backups saved to `backups/2026-03-07-v1/`

---

## 2026-03-01 v1 — Footer Link Updates

### Change
- Added Booking link to footer nav in site layout (all pages except home)
- Removed Home link from home page footer

### Files Modified
| File | Change |
|------|--------|
| `application/views/layouts/canvas-white.php` | Added Booking link to footer nav |
| `application/views/home.php` | Removed Home link from footer |

### Backups
Remote backups saved to `backups/2026-03-01-v1/`

---

## 2026-02-28 v4 — Inline Time Validation Error

### Change
- Replaced `alert()` on invalid Start Time with an inline `<small class="text-danger">` error message beneath the field, matching the server-side validation style
- Error clears automatically when the user edits the field or on next valid submit

### Files Modified
| File | Change |
|------|--------|
| `application/views/booking_form.php` | Replaced `alert()` with inline error via `showTimeError()`/`clearTimeError()` helpers; added `input` listener to clear error on typing |

### Backups
Remote backups saved to `backups/2026-02-28-v4/`

---

## 2026-02-28 v3 — Booking Form Tweaks

### Change
- Swapped Duration and Event Type field positions so Duration sits next to Start Time
- Added time format validation on Start Time field — both client-side and server-side (accepts 7:00 PM, 7pm, 19:00, etc.)

### Files Modified
| File | Change |
|------|--------|
| `application/views/booking_form.php` | Reordered Row 2; added client-side time validation before reCAPTCHA |
| `application/controllers/Booking.php` | Added `callback_valid_time` rule and `valid_time()` method |

### Backups
Remote backups saved to `backups/2026-02-28-v3/`

---

## 2026-02-28 v2 — Booking Page Overhaul

### Change
- Rewrote booking page to use site template (nav/footer) instead of standalone HTML
- Added reCAPTCHA v3, form validation, XSS filtering, duplicate submission prevention
- Compact multi-column form layout matching site styling
- Proper email handling (site email as from, user email as reply-to, HTML template, error logging)

### Files Modified
| File | Change |
|------|--------|
| `application/controllers/Booking.php` | Full rewrite — validation, reCAPTCHA, HTML email, layout_view |
| `application/views/booking_form.php` | Full rewrite — site template structure, compact form layout |

### Backups
Remote backups saved to `backups/2026-02-28-v2/`

---

## 2026-02-28 v1 — Footer Page Links

### Change
- Added page links (Home, Albums, Calendar, About, Newsletter, Contact) to the footer on all pages

### Files Modified
| File | Change |
|------|--------|
| `application/views/home.php` | Added footer page links to homepage inline footer |
| `application/views/layouts/canvas-white.php` | Added footer page links to shared layout footer |

### Backups
Remote backups saved to `backups/2026-02-28-v1/`

---

## 2026-02-27 v7 — Limit Hero Events to 2

### Change
- **Site.php**: Reduced max upcoming events in hero section from 3 to 2

### Files Modified
| File | Change |
|------|--------|
| `application/controllers/Site.php` | Changed hero event limit from 3 to 2 |

### Backups
Remote backups saved to `backups/2026-02-27-v5/`

---

## 2026-02-27 v6 — Mobile City Display

### Mobile Events
- **home.php**: Show city name (extracted from location) on mobile under each event title
- Desktop still shows full split address; mobile shows only city

### Files Modified
| File | Change |
|------|--------|
| `application/views/home.php` | Mobile city display for hero events |

### Backups
Remote backups saved to `backups/2026-02-27-v6/`

---

## 2026-02-27 v5 — Mobile Hero & Retina Logo

### Mobile Hero Events
- **home.php**: Restructured hero to use flexbox layout (table-cell behavior) so featured song and events sit side by side without overlapping
- **home.php**: On mobile, events show only titles and "View All Shows" button (date, location hidden)

### Retina Logo Fix
- **home.php**: Added `retina-logo` element — Canvas theme hides `standard-logo` on retina/iPhone displays
- **logo-dark@2x.png**: Created 2x retina logo (276x200)

### Files Modified
| File | Change |
|------|--------|
| `application/views/home.php` | Flexbox hero layout, mobile event titles, retina logo |
| `imgs/logo-dark@2x.png` | New retina logo image |

### Backups
Remote backups saved to `backups/2026-02-27-v5/`

---

## 2026-02-27 v4 — Hero Upcoming Shows

### Upcoming Shows in Hero Section
- **home.php**: Moved upcoming events from below hero to lower-right of hero section (absolute positioned inside swiper-slide)
- **home.php**: Removed separate "Upcoming Events" section below the hero
- **home.php**: Address split across two lines at first comma (venue name / city-state-zip)
- **home.php**: Increased font sizes for event name (19px), date/time (16px), location (14px), heading (18px)
- **home.php**: Shows now visible on mobile (removed `d-none d-md-block`)
- **Site.php**: Fixed hero event filter — was excluding all events without explicit "Status: Active" in calendar description; now only excludes explicitly canceled events

### Files Modified
| File | Change |
|------|--------|
| `application/views/home.php` | Hero events positioned lower-right, larger text, address split, mobile visible, removed separate events section |
| `application/controllers/Site.php` | Fixed event status filter for hero |

### Backups
Remote backups saved to `backups/2026-02-27-v4/`

---

## 2026-02-27 v3 — Album Player CTA Update

### "Want to Hear More?" Section
- **home.php**: Added description mentioning it's a full album player app with tracks and playlists
- Button text changed to "Open Album Player"

### Files Modified
| File | Change |
|------|--------|
| `application/views/home.php` | Album player CTA description |

### Backups
Remote backups saved to `backups/2026-02-27-v3/`

---

## 2026-02-27 v2 — Popular Songs Spacing & Dynamic Album Title

### Popular Songs Mobile Fix
- **home.php**: Replaced unsupported `g-4` grid gutter with `mb-4` on each column for proper spacing on mobile

### Dynamic Album Title
- **Site.php**: Added `get_album_data()` method that fetches album title + songs from API in one call
- **Site.php**: `build_data()` now uses `get_album_data()` and passes `album_title` to view
- **home.php**: Album heading now uses `$album_title` from API instead of hardcoded "3stones"

### Files Modified
| File | Change |
|------|--------|
| `application/views/home.php` | Popular songs spacing, dynamic album title |
| `application/controllers/Site.php` | `get_album_data()` method, pass album title to view |

### Backups
Remote backups saved to `backups/2026-02-27-v2/`

---

## 2026-02-27 v1 — Menu Alignment & Homepage Improvements

### Menu Restructure
- **home.php** & **nav.php**: Aligned menus across homepage and shared nav partial
- New structure: Home | Albums | Calendar | About (dropdown)
- About dropdown contains: About, Newsletter, Contact
- Albums links to music.glennbennett.com (opens in new tab)

### Homepage Nav Styling
- **home.php**: Increased nav font size (15px) and weight (600) via inline style
- **music.css**: Updated selector to `.primary-menu .menu-container > .menu-item > .menu-link`, kept `text-transform: none`
- Hover color set to light blue (#79b8f3), active "Home" link set to white — both with `!important` to override Canvas theme green (#1ABC9C)

### Featured Song
- **Site.php**: Changed default featured song from hardcoded ID 8 to title match for "I've Got To Find Out For Myself"
- **home.php**: Added blurb: "Featured on GarageBand.com | Reached #22 on the American Idol Underground Folk Music Chart"

### "Want to Hear More?" CTA
- **home.php**: Added call-to-action section above footer linking to music.glennbennett.com

### Footer & Layout Fixes
- **home.php**: Copyright updated to "2000-current year" (dynamic)
- **home.php**: Added `padding-bottom: 50px` and `background-color: #131722` on `#wrapper` to prevent audio player overlap and white gap
- **bootstrap.css**: Removed `sourceMappingURL` reference that caused console errors
- **canvas-white.php**: Added `?v=2` cache buster to bootstrap.css link

### Calendar Mobile Fix
- **gcal-gigs.php**: Portrait/square event images now use responsive class `.cal-portrait-frame` — shrinks from 170px to 120px on screens under 768px

### Files Modified
| File | Change |
|------|--------|
| `application/views/home.php` | Menu, styling, featured blurb, CTA, footer, layout |
| `application/views/partials/nav.php` | Menu restructure |
| `application/controllers/Site.php` | Featured song selection logic |
| `css/bootstrap.css` | Removed source map reference |
| `demos/music/music.css` | Updated nav selector |
| `gcal/gcal-gigs.php` | Responsive calendar images |
| `application/views/layouts/canvas-white.php` | Cache buster on bootstrap |

### Backups
Remote backups saved to `backups/2026-02-27/`
