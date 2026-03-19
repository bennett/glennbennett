# Glenn Bennett Website — Sitemap

## Site Map Diagram

```
glennbennett.com
│
├── PUBLIC PAGES (navigation)
│   │
│   ├── /                        Homepage
│   │   ├── Featured track audio player (MediaElement.js)
│   │   ├── Hero section
│   │   └── Upcoming gig carousel (Swiper.js)
│   │
│   ├── /calendar                Live performance calendar
│   │   ├── Google Calendar iCal feed
│   │   ├── Venue details + weather widgets
│   │   ├── Social share buttons (FB, Twitter, WhatsApp, SMS, email)
│   │   └── Add to calendar (Google, Apple, Outlook)
│   │
│   ├── /past                    Past performances
│   │
│   ├── /contact                 Contact form
│   │   └── reCAPTCHA v3 + SES email notification
│   │
│   ├── /booking                 Booking inquiry form
│   │   └── Multi-field (date, venue, audience, style, budget)
│   │
│   ├── /about                   About page
│   │
│   ├── /follow                  Mailing list signup
│   │
│   ├── /samples                 Performance samples (YouTube embeds)
│   │
│   ├── /vids                    YouTube playlists
│   │   ├── Originals
│   │   ├── Covers
│   │   └── Samples
│   │
│   ├── /tip                     Online tip jar
│   │
│   ├── /links                   Quick links dashboard
│   │
│   ├── /album                   2016 Live Album player
│   │   └── /album/{id}          Album by ID
│   │
│   ├── /qr                      QR code landing page
│   │
│   ├── /request                 Audience song request form
│   │   └── /r                   Shorthand redirect
│   │
│   └── /song/{id}               Individual song page
│
├── CALENDAR & SHARING (not in nav)
│   │
│   ├── /facebook                Open Graph metadata for event sharing
│   ├── /fb                      Facebook sharing page
│   ├── /cal-image               Generate 1200x630 OG image (GD)
│   │
│   └── /gcal/                   AJAX endpoints
│       ├── gcal-gigs.php        Upcoming performances
│       ├── gcal-upcoming.php    Next 14 days
│       ├── gcal-gigs-past.php   Past performances
│       ├── gcal-gigs-dup.php    Duplicate events
│       └── fb_image.php         Redirect → /cal-image
│
├── ADMIN PANEL (/admin — login required)
│   │
│   ├── /admin                   Dashboard
│   ├── /admin/login             Login page
│   │
│   ├── /admin/images            Sharing image library
│   │   ├── Upload / toggle / delete images
│   │   └── /admin/image_layout/{id}   Text overlay layout editor
│   │       └── /admin/preview_image/{id}  GD preview render
│   │
│   ├── /admin/venues            Venue image management
│   │   └── /admin/venue_edit/{id}     Edit venue details
│   │
│   ├── /admin/dup_events        Duplicate events tool
│   │   ├── /admin/dup_events/day?date=     Pick events from a date
│   │   └── /admin/dup_events/generate_csv  Download Google Calendar CSV
│   │
│   ├── /admin/test_email        SES email test sender
│   │
│   └── /admin/change_password   Change admin password
│
└── LEGACY / INTERNAL (on production, not actively used)
    │
    ├── /cortez                  Internal todo/task list
    ├── /cortez_edit             Task CRUD (Grocery CRUD)
    ├── /examples/*              Grocery CRUD demo pages
    ├── /raw.php                 TinyFileManager (file browser)
    ├── /caltest/                Calendar testing
    ├── /wtest/                  Weather widget testing
    ├── /gcal/test.php           Calendar debug page
    ├── /ical/                   Legacy iCal viewer
    ├── /hap/fix_encoding.php    Fix HTML emoji encoding
    └── /css/colors.php          Dynamic CSS colors
```

## External Integrations

```
glennbennett.com
│
├──→ Google Calendar (2 iCal feeds)     Calendar data source
├──→ Amazon SES (us-west-1)            Email delivery
├──→ Bunny CDN (glb-songs.b-cdn.net)   Audio streaming
├──→ music.glennbennett.com (SQLite)    Shared song database
├──→ reCAPTCHA v3                       Form spam protection
├──→ YouTube                            Embedded video players
└──→ Weather API                        Venue weather widgets
```

## Data Flow

```
┌─────────────┐     iCal feed      ┌──────────────────┐
│   Google     │───────────────────→│  /calendar        │
│   Calendar   │                    │  /past            │
└─────────────┘                    │  /facebook, /fb   │
                                   └──────────────────┘
                                            │
                                   ┌────────▼─────────┐
                                   │  /cal-image       │
                                   │  GD image gen     │
                                   │  1200x630 OG      │
                                   └──────────────────┘

┌─────────────┐     SQLite DB       ┌──────────────────┐
│   music.     │───────────────────→│  / (homepage)     │
│   glennbennett│                   │  Featured track   │
└─────────────┘                    └──────────────────┘
       │                                    │
       │  audio files                       │
       ▼                                    ▼
┌─────────────┐                    ┌──────────────────┐
│  Bunny CDN  │───────────────────→│  Audio player     │
│  glb-songs  │                    │  (MediaElement.js)│
└─────────────┘                    └──────────────────┘

┌─────────────┐                    ┌──────────────────┐
│  /booking   │──── SES email ────→│  gbennett@        │
│  /contact   │                    │  tsgdev.com       │
└─────────────┘                    └──────────────────┘
```
