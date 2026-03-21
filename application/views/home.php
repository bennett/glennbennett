<!DOCTYPE html>
<html dir="ltr" lang="en-US">
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Glenn Bennett | Original Music</title>
    <meta name="description" content="Original music by Glenn L. Bennett — stream full albums, explore playlists, discover upcoming live performances, and install the free album player app." />
    <meta name="author" content="Glenn Bennett" />
    <link rel="canonical" href="https://glennbennett.com/" />

    <!-- Open Graph -->
    <meta property="og:type" content="website" />
    <meta property="og:title" content="Glenn L. Bennett | Original Music, Albums &amp; Live Shows" />
    <meta property="og:description" content="Original music by Glenn L. Bennett — stream full albums, explore playlists, discover upcoming live performances, and install the free album player app." />
    <meta property="og:url" content="https://glennbennett.com/" />
    <meta property="og:image" content="https://glennbennett.com/imgs/og-image.jpg?v=4" />
    <meta property="og:image:width" content="1200" />
    <meta property="og:image:height" content="630" />
    <meta property="og:site_name" content="Glenn Bennett Music" />
    <meta property="og:locale" content="en_US" />

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:title" content="Glenn L. Bennett | Original Music, Albums &amp; Live Shows" />
    <meta name="twitter:description" content="Original music by Glenn L. Bennett — stream full albums, explore playlists, discover upcoming live performances, and install the free album player app." />
    <meta name="twitter:image" content="https://glennbennett.com/imgs/og-image.jpg?v=4" />

    <link href="https://fonts.googleapis.com/css?family=Poppins:200,300,400,400i,600,700|Montserrat:300,400,700|Caveat+Brush&display=swap" rel="stylesheet" type="text/css" />
    <link href="https://fonts.googleapis.com/css2?family=Lora:ital,wght@0,400;0,600;1,400&family=Source+Sans+3:wght@300;400;600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="/css/bootstrap.css" type="text/css" />
    <link rel="stylesheet" href="/style.css" type="text/css" />
    <link rel="stylesheet" href="/css/dark.css" type="text/css" />
    <link rel="stylesheet" href="/css/swiper.css" type="text/css" />
    <link rel="stylesheet" href="/demos/music/music.css?v=8" type="text/css" />
    <link rel="stylesheet" href="/css/font-icons.css" type="text/css" />
    <link rel="stylesheet" href="/demos/music/css/mediaelement/mediaelementplayer.css">
    <link rel="stylesheet" href="/css/custom.css" type="text/css" />

    <style>
        .css3-spinner { background-color: #131722; }
        /* SECTION: COMPONENT STYLES */
        .popular-card { position: relative; overflow: hidden; border-radius: 8px; transition: transform 0.3s ease; }
        .popular-card:hover { transform: scale(1.03); }
        .popular-card img { width: 100%; display: block; }
        .popular-overlay { position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0); transition: background 0.3s ease; display: flex; align-items: center; justify-content: center; }
        .popular-card:hover .popular-overlay { background: rgba(0,0,0,0.4); }
        .popular-overlay i { font-size: 40px; color: #fff; opacity: 0; transition: opacity 0.3s ease; }
        .popular-card:hover .popular-overlay i { opacity: 1; }
        .primary-menu .menu-container > .menu-item > .menu-link { font-size: 15px; font-weight: 600; }
        .dark .menu-item:hover > .menu-link { color: #79b8f3 !important; }
        .dark .menu-item.active > .menu-link { color: #FFF !important; }
        #wrapper { padding-bottom: 50px; background-color: #131722; }
        #slider .slider-caption { bottom: 20px !important; }
        #slider .swiper-slide > .container { position: relative; height: 100%; }
        .hero-bottom { position: absolute; bottom: 20px; left: 15px; right: 15px; z-index: 20; display: flex; align-items: flex-end; justify-content: space-between; }
        .hero-bottom .slider-caption { position: static !important; height: auto !important; bottom: auto !important; flex: 0 1 auto; }
        .hero-events { flex: 0 1 auto; color: #F7F7F7; text-align: right; }
        .hero-events .evt-detail { display: block; }
        .hero-events .evt-city { display: none; }
        .hero-events .evt-btn { display: inline-block; }
        /* Equalizer bars for now-playing indicator */
        .songs-list .songs-time { white-space: nowrap; }
        .eq-bars { display: inline-flex; align-items: flex-end; height: 12px; gap: 2px; margin-left: 6px; }
        .eq-bars span { display: block; width: 2px; background: #79b8f3; border-radius: 1px; animation: eq-bounce 0.8s ease-in-out infinite alternate; }
        .eq-bars span:nth-child(1) { height: 4px; animation-duration: 0.6s; }
        .eq-bars span:nth-child(2) { height: 8px; animation-duration: 0.8s; animation-delay: 0.2s; }
        .eq-bars span:nth-child(3) { height: 5px; animation-duration: 0.7s; animation-delay: 0.4s; }
        @keyframes eq-bounce { 0% { height: 3px; } 100% { height: 12px; } }
        @media (max-width: 767.98px) {
            .hero-events .evt-detail { display: none; }
            .hero-events .evt-city { display: block; }
            .hero-events h4 { font-size: 14px !important; margin-bottom: 8px !important; }
            .hero-events .evt-title { font-size: 14px !important; }
            .hero-events .mb-3 { margin-bottom: 6px !important; }
        }
    </style>
</head>

<body class="stretched bg-color2" data-loader="4" data-loader-color="theme">

    <div id="wrapper" class="clearfix">

        <header id="header" class="full-header transparent-header dark no-sticky header-size-md">
            <div id="header-wrap">
                <div class="container">
                    <div class="header-row">
                        <div id="logo">
                            <a href="/" class="standard-logo"><img src="/imgs/logo-dark.png" alt="Logo"></a>
                            <a href="/" class="retina-logo"><img src="/imgs/logo-dark@2x.png" alt="Logo"></a>
                        </div>
                        <div id="primary-menu-trigger">
                            <svg class="svg-trigger" viewBox="0 0 100 100"><path d="m 30,33 h 40 c 3.722839,0 7.5,3.126468 7.5,8.578427 0,5.451959 -2.727029,8.421573 -7.5,8.421573 h -20"></path><path d="m 30,50 h 40"></path><path d="m 70,67 h -40 c 0,0 -7.5,-0.802118 -7.5,-8.365747 0,-7.563629 7.5,-8.634253 7.5,-8.634253 h 20"></path></svg>
                        </div>
                        <nav class="primary-menu not-dark">
                            <ul class="menu-container">
                                <li class="menu-item active"><a class="menu-link" href="/"><div>Home</div></a></li>
                                <li class="menu-item"><a class="menu-link" href="https://music.glennbennett.com" target="_blank"><div>Albums</div></a></li>
                                <li class="menu-item"><a class="menu-link" href="/cal"><div>Calendar</div></a></li>
                                <li class="menu-item"><a class="menu-link" href="#"><div>About</div></a>
                                    <ul class="sub-menu-container">
                                        <li class="menu-item"><a class="menu-link" href="/about"><div>About</div></a></li>
                                        <li class="menu-item"><a class="menu-link" href="/newsletter"><div>Newsletter</div></a></li>
                                        <li class="menu-item"><a class="menu-link" href="/contact"><div>Contact</div></a></li>
                                    </ul>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </header>

        <section id="slider" class="slider-element swiper_wrapper min-vh-60 min-vh-md-100 include-header" style="background: #131722;">
            <div class="swiper-container swiper-parent">
                <div class="swiper-wrapper">
                    <div class="swiper-slide dark">
                        <div class="container">
                            <div class="hero-bottom">
                                <div class="slider-caption">
                                    <div>
                                        <h2 class="font-primary nott"><?php echo $featured ? wordwrap($featured->title, 11, '<br />') : 'Welcome'; ?></h2>
                                        <p class="d-none d-md-block">Featured Track by Glenn Bennett</p>
                                        <?php if($featured && stripos($featured->title, "Find Out For Myself") !== false): ?>
                                        <p class="d-none d-md-block mb-0"><small>Featured on GarageBand.com<br>Reached #22 on the American Idol Underground Folk Music Chart</small></p>
                                        <?php endif; ?>
                                        <?php if($featured): ?>
                                        <a href="#" class="button button-rounded font-weight-normal ls1 track-list mt-3 clearfix"
                                           data-track="<?php echo $featured->audio_url; ?>" data-poster="<?php echo $featured->cover_url; ?>"
                                           data-title="<?php echo htmlspecialchars($featured->title); ?>" data-singer="Glenn Bennett">
                                           <i class="icon-play"></i>Play Now
                                        </a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="hero-events">
                                    <?php if(!empty($hero_events)): ?>
                                    <h4 class="text-uppercase ls1 mb-3" style="font-size: 18px;">Upcoming Shows</h4>
                                    <?php foreach($hero_events as $evt): ?>
                                    <div class="mb-3">
                                        <div class="evt-title" style="font-size: 19px; font-weight: 600;"><?php echo htmlspecialchars(strip_tags($evt['summary'])); ?></div>
                                        <div class="evt-detail" style="font-size: 16px; opacity: 0.7;"><?php echo htmlspecialchars($evt['display_date']); ?><?php if(!empty($evt['display_date_time'])): ?> &middot; <?php echo $evt['display_date_time']; ?><?php endif; ?></div>
                                        <?php if(!empty($evt['location'])): ?>
                                        <?php
$loc = htmlspecialchars($evt['location']);
$parts = array_map('trim', explode(',', $loc));
if (count($parts) >= 2) {
    $city = $parts[1];
    echo '<div class="evt-city" style="font-size: 13px; opacity: 0.6;">' . $city . '</div>';
    echo '<div class="evt-detail" style="font-size: 14px; opacity: 0.6;">' . $parts[0] . '</div>';
    echo '<div class="evt-detail" style="font-size: 14px; opacity: 0.6;">' . implode(', ', array_slice($parts, 1)) . '</div>';
} else {
    echo '<div class="evt-city" style="font-size: 13px; opacity: 0.6;">' . $loc . '</div>';
    echo '<div class="evt-detail" style="font-size: 14px; opacity: 0.6;">' . $loc . '</div>';
}
?>
                                        <?php endif; ?>
                                    </div>
                                    <?php endforeach; ?>
                                    <a href="/calendar" class="button button-rounded font-weight-normal ls1 mt-1 evt-btn">View All Shows</a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <div class="swiper-slide-bg" style="background-image: url('/assets/img/me.jpg');"></div>
                    </div>
                </div>
            </div>
        </section>


        <section class="bg-color2" style="padding-top: 3rem; padding-bottom: 0;">
            <div class="container">
                <div class="heading-block border-0 dark text-center mb-3">
                    <h3>Popular Songs of the Week</h3>
                </div>
                <div class="row"> <?php foreach($popular as $track): ?>
                    <div class="col-6 col-md-3 mb-3">
                        <div class="popular-card">
                            <img src="<?php echo $track->cover_url; ?>" alt="Art">
                            <a href="#" class="popular-overlay track-list" 
                               data-track="<?php echo $track->audio_url; ?>" data-poster="<?php echo $track->cover_url; ?>" 
                               data-title="<?php echo htmlspecialchars($track->title); ?>" data-singer="Glenn Bennett">
                                <i class="icon-play"></i>
                            </a>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>

        <section class="bg-color2" style="padding-top: 1.5rem; padding-bottom: 1rem;">
            <div class="container">
                <hr class="border-dark mt-0 mb-3" style="opacity: 0.5;">
                <div class="row">
                    <div class="col-lg-6 mb-2">
                        <div class="heading-block border-0 dark mb-4">
                            <h3><?php echo htmlspecialchars($album_title); ?> Album</h3>
                        </div>
                        <div class="songs-lists-wrap">
                            <?php $i = 1; foreach($originals as $original): ?>
                            <div class="songs-list">
                                <div class="songs-number"><?php echo sprintf('%02d', $i++); ?></div>
                                <div class="songs-image">
                                    <a href="#" class="track-list" data-track="<?php echo $original->audio_url; ?>" data-poster="<?php echo $original->cover_url; ?>" data-title="<?php echo $original->title; ?>" data-singer="Glenn Bennett">
                                        <img src="<?php echo $original->cover_url; ?>" alt="Art"><span><i class="icon-play"></i></span>
                                    </a>
                                </div>
                                <div class="songs-name track-name"><a href="#"><?php echo htmlspecialchars($original->title); ?></a></div>
                                <div class="songs-time"><?php echo gmdate("i:s", $original->duration); ?></div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <div class="col-lg-6 mb-2">
                        <div class="heading-block border-0 dark mb-4">
                            <h3>More Originals</h3>
                        </div>
                        <div class="songs-lists-wrap">
                            <?php $j = 1; foreach($covers as $track): ?>
                            <div class="songs-list">
                                <div class="songs-number"><?php echo sprintf('%02d', $j++); ?></div>
                                <div class="songs-image">
                                    <a href="#" class="track-list" data-track="<?php echo $track->audio_url; ?>" data-poster="<?php echo $track->cover_url; ?>" data-title="<?php echo $track->title; ?>" data-singer="Glenn Bennett">
                                        <img src="<?php echo $track->cover_url; ?>" alt="Art"><span><i class="icon-play"></i></span>
                                    </a>
                                </div>
                                <div class="songs-name track-name"><a href="#"><?php echo htmlspecialchars($track->title); ?></a></div>
                                <div class="songs-time"><?php echo gmdate("i:s", $track->duration); ?></div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <?php if(!empty($new_quotes)): ?>
        <section class="bg-color2 py-5 border-top border-dark">
            <div class="container">
                <div id="oc-testi" class="owl-carousel testimonials-carousel carousel-widget" data-margin="20" data-items-sm="1" data-items-md="2" data-items-xl="3">
                    <?php foreach($new_quotes as $quote): ?>
                    <div class="oc-item">
                        <div class="testimonial dark">
                            <p><?php echo htmlspecialchars($quote); ?></p>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
        <?php endif; ?>

        <!-- Listen Section -->
        <section style="padding:3rem 0;">
            <div class="container">
                <div style="width:60px;height:1px;background:linear-gradient(90deg,transparent,rgba(120,160,200,0.5),transparent);margin:0 auto 2.5rem;"></div>
                <h2 style="font-family:'Lora',serif;font-size:1.5rem;font-weight:400;color:#c8d8e8;margin-bottom:0.75rem;letter-spacing:0.02em;text-align:center;">Give <em>Milestones</em> a Real Listen</h2>
                <p style="font-family:'Source Sans 3',sans-serif;font-size:1rem;font-weight:300;color:#8a9ab0;line-height:1.65;max-width:520px;margin:0 auto 2.5rem;text-align:center;">I built a simple player for my album — no ads, no algorithms, just the music. Two ways to get started:</p>

                <div class="listen-cards" style="display:flex;gap:2rem;max-width:800px;margin:0 auto;align-items:stretch;">
                    <!-- Left: Launch the Player -->
                    <div style="flex:1;background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.08);border-radius:12px;padding:2rem;display:flex;align-items:center;gap:2rem;">
                        <div style="text-align:center;flex-shrink:1;">
                            <h3 style="font-family:'Lora',serif;font-size:1.15rem;font-weight:400;color:#c8d8e8;margin-bottom:0.5rem;">Want to hear more?</h3>
                            <p style="font-family:'Source Sans 3',sans-serif;font-size:0.9rem;font-weight:300;color:#8a9ab0;line-height:1.6;margin-bottom:0.5rem;">Browse albums, explore playlists, and stream every original track in one place.</p>
                            <p style="font-family:'Source Sans 3',sans-serif;font-size:0.85rem;font-weight:300;color:rgba(255,255,255,0.45);margin-bottom:1rem;">All songs by Glenn L. Bennett.</p>
                            <a href="https://music.glennbennett.com" target="_blank" class="listen-btn" style="display:inline-block;font-family:'Source Sans 3',sans-serif;font-size:0.85rem;font-weight:600;letter-spacing:0.12em;text-transform:uppercase;color:#d0dcea;background:transparent;border:1px solid rgba(140,180,220,0.35);padding:0.7rem 1.75rem;border-radius:3px;cursor:pointer;transition:all 0.3s ease;text-decoration:none;">Launch the Player</a>
                            <p style="color:rgba(255,255,255,0.35);font-size:0.8rem;margin-top:0.75rem;margin-bottom:0;">
                                <i class="icon-apple" style="margin-right:3px;font-size:0.8rem;"></i>
                                <i class="icon-android" style="margin-right:5px;font-size:0.8rem;"></i>Add to Home Screen
                            </p>
                        </div>
                        <div style="flex-shrink:0;">
                            <img src="/assets/img/Screenshot.png" alt="Album Player App" style="height:220px;width:auto;border-radius:8px;box-shadow:0 6px 24px rgba(0,0,0,0.5);border:1px solid rgba(255,255,255,0.1);">
                        </div>
                    </div>

                    <!-- Right: How to Listen -->
                    <div style="flex:1;background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.08);border-radius:12px;padding:2rem;text-align:center;display:flex;flex-direction:column;align-items:center;justify-content:center;">
                        <h3 style="font-family:'Lora',serif;font-size:1.15rem;font-weight:400;color:#c8d8e8;margin-bottom:0.5rem;">The Best Way to Hear It</h3>
                        <p style="font-family:'Source Sans 3',sans-serif;font-size:0.9rem;font-weight:300;color:#8a9ab0;line-height:1.6;margin-bottom:0.75rem;">This isn't background music. <em>Milestones</em> is meant to be heard front to back — in the car, on a walk, wherever you can actually listen.</p>
                        <p style="font-family:'Source Sans 3',sans-serif;font-size:0.9rem;font-weight:300;color:#8a9ab0;line-height:1.6;margin-bottom:1.25rem;">A short note on why I built my own player and the best way to give it a spin.</p>
                        <button class="listen-btn" onclick="openListenModal()" style="display:inline-block;font-family:'Source Sans 3',sans-serif;font-size:0.85rem;font-weight:600;letter-spacing:0.12em;text-transform:uppercase;color:#d0dcea;background:transparent;border:1px solid rgba(140,180,220,0.35);padding:0.7rem 1.75rem;border-radius:3px;cursor:pointer;transition:all 0.3s ease;position:relative;overflow:hidden;margin-top:auto;">How to Listen</button>
                    </div>
                </div>

                <div style="width:60px;height:1px;background:linear-gradient(90deg,transparent,rgba(120,160,200,0.5),transparent);margin:2.5rem auto 0;"></div>
            </div>
        </section>

        <!-- Listen Modal -->
        <div class="listen-modal-overlay" id="listenModalOverlay" onclick="closeListenModalOutside(event)">
            <div class="listen-modal">
                <button onclick="closeListenModal()" aria-label="Close" style="position:absolute;top:1.25rem;right:1.25rem;width:32px;height:32px;border:none;background:transparent;color:#999;font-size:1.4rem;cursor:pointer;border-radius:50%;display:flex;align-items:center;justify-content:center;transition:all 0.2s ease;line-height:1;">&times;</button>

                <div class="listen-article">
                    <h1 style="font-family:'Lora',serif;font-size:1.75rem;font-weight:600;color:#2b4a6b;text-align:center;margin-bottom:0.25rem;">How to Actually Listen to <em>Milestones</em></h1>
                    <p style="font-family:'Lora',serif;font-size:0.95rem;font-style:italic;color:#888;text-align:center;margin-bottom:2rem;">A short note from Glenn Bennett</p>

                    <hr style="border:none;height:1px;background:linear-gradient(90deg,transparent,#2b4a6b44,transparent);margin:2rem 0;">

                    <h2 style="font-family:'Lora',serif;font-size:1.25rem;font-weight:600;color:#2b4a6b;margin:2rem 0 0.75rem;">Nobody Listens to Music Anymore</h2>
                    <p style="font-family:'Lora',serif;font-size:0.95rem;color:#444;line-height:1.75;margin-bottom:1rem;">Okay, that's a slight exaggeration. But here's the truth: most people don't <em>really</em> listen to music the way they used to. We all have access to more music than any generation in history, and somehow we listen to less of it — really listen, I mean. Not as background noise while scrolling, not shuffled into a playlist of a thousand songs you half-recognize.</p>
                    <p style="font-family:'Lora',serif;font-size:0.95rem;color:#444;line-height:1.75;margin-bottom:1rem;">The big streaming platforms are designed for skipping, not listening. They're built around algorithms, ads, and endless choices — which, ironically, makes it harder to just sit with anything and take it in.</p>
                    <p style="font-family:'Lora',serif;font-size:0.95rem;color:#444;line-height:1.75;margin-bottom:1rem;">I'm not a professional musician. I'm not chasing a record deal or trying to build a following. I just make music because I enjoy it and I can, and for some reason I can't fully explain, I'd like people to hear it. But that's a hard thing to ask when every platform is designed to pull your attention somewhere else.</p>

                    <h2 style="font-family:'Lora',serif;font-size:1.25rem;font-weight:600;color:#2b4a6b;margin:2rem 0 0.75rem;">A Better Way to Give It a Listen</h2>
                    <p style="font-family:'Lora',serif;font-size:0.95rem;color:#444;line-height:1.75;margin-bottom:1rem;">So here's my idea. Instead of asking you to find me on Spotify or Apple Music — where, honestly, even if you did find me, the experience wouldn't be great — I built something different. Those platforms aren't really designed for <em>listening</em>; they're designed for <em>engagement</em>. Every screen is packed with recommendations, playlists, podcasts, and notifications pulling your attention somewhere else. That's not an accident; their business model depends on keeping you browsing, not on helping you sit with an album. The longer you scroll, the more ads they serve and the more data they collect. The music is almost secondary.</p>
                    <p style="font-family:'Lora',serif;font-size:0.95rem;color:#444;line-height:1.75;margin-bottom:1rem;">So I built a simple player for my album <em>Milestones</em> — nothing on the screen but the songs and a play button. No ads, no recommendations, no rabbit holes. It lives on the web at:</p>
                    <p style="display:block;text-align:center;font-family:'Source Sans 3',sans-serif;font-size:1.1rem;font-weight:600;margin:1.25rem 0;"><a href="https://music.glennbennett.com" target="_blank" style="color:#2b5c8a;text-decoration:none;border-bottom:2px solid rgba(43,92,138,0.3);transition:border-color 0.2s ease;">music.glennbennett.com</a></p>

                    <h2 style="font-family:'Lora',serif;font-size:1.25rem;font-weight:600;color:#2b4a6b;margin:2rem 0 0.75rem;">Take It for a Drive</h2>
                    <p style="font-family:'Lora',serif;font-size:0.95rem;color:#444;line-height:1.75;margin-bottom:1rem;">Here's what I'd love for you to try. Next time you're in the car by yourself — maybe a longer drive, maybe just running errands — open that link and hit play. <em>Milestones</em> plays straight through, one song after another, the way an album is meant to be heard.</p>
                    <p style="font-family:'Lora',serif;font-size:0.95rem;color:#444;line-height:1.75;margin-bottom:1rem;">And here's the nice part: <strong style="color:#333;font-weight:600;">it remembers where you left off.</strong> If you only get through four songs before you arrive somewhere, the next time you open it, it picks up right where you stopped. No hunting around, no re-finding your place. Just press play and keep going.</p>

                    <hr style="border:none;height:1px;background:linear-gradient(90deg,transparent,#2b4a6b44,transparent);margin:2rem 0;">

                    <p style="text-align:center;font-family:'Lora',serif;font-style:italic;color:#777;font-size:0.95rem;line-height:1.8;margin:0.5rem 0;">Thanks for giving it a chance.<br>That really is all I'm asking.</p>

                    <div style="background:#f0eee9;margin:2rem -2.5rem 0;padding:2rem 2.5rem 0.5rem;border-radius:0 0 6px 6px;">
                        <h2 style="font-family:'Lora',serif;font-size:1.1rem;font-weight:600;color:#5a7a96;margin:0 0 0.75rem;">Getting Set Up</h2>
                        <p style="font-family:'Lora',serif;font-size:0.9rem;color:#666;line-height:1.75;margin-bottom:1rem;">The player is what's called a <strong>Progressive Web App</strong> (PWA), which is a fancy way of saying it's a website that can work like an app on your phone. No account to create. No app store download. Just visit the link and you're listening.</p>
                        <p style="font-family:'Lora',serif;font-size:0.9rem;color:#666;line-height:1.75;margin-bottom:1rem;">But here's the part that makes it even better: if you save it to your home screen, it'll sit right there alongside your other apps — ready whenever you are.</p>
                        <p style="font-family:'Lora',serif;font-size:0.9rem;color:#666;line-height:1.75;margin-bottom:1rem;"><strong>On iPhone:</strong> Open the link in Safari, tap the Share button (the square with the arrow), and choose "Add to Home Screen."</p>
                        <p style="font-family:'Lora',serif;font-size:0.9rem;color:#666;line-height:1.75;margin-bottom:1rem;"><strong>On Android:</strong> Open the link in Chrome, tap the three-dot menu, and choose "Add to Home Screen" or "Install App."</p>
                        <p style="font-family:'Lora',serif;font-size:0.9rem;color:#666;line-height:1.75;margin-bottom:1rem;">That's it. Now you've got a little icon on your phone that goes straight to the player.</p>

                        <h2 style="font-family:'Lora',serif;font-size:1.1rem;font-weight:600;color:#5a7a96;margin:2rem 0 0.75rem;">A Quick Note</h2>
                        <p style="font-family:'Lora',serif;font-size:0.9rem;color:#666;line-height:1.75;margin-bottom:1rem;">The player works with your car's Bluetooth and shows controls on your lock screen, so in most cases you can lock your phone and keep listening. Occasionally, if the connection hiccups or your phone goes to sleep for a while, you may need to tap back in — but the player remembers exactly where you were, so you'll pick right back up.</p>

                        <p style="text-align:center;font-family:'Source Sans 3',sans-serif;font-size:0.9rem;margin-top:1.5rem;">
                            <a href="https://music.glennbennett.com" target="_blank" style="color:#2b5c8a;text-decoration:none;border-bottom:1px solid rgba(43,92,138,0.3);margin:0 0.5rem;">music.glennbennett.com</a> &middot;
                            <a href="https://glennbennett.com" style="color:#2b5c8a;text-decoration:none;border-bottom:1px solid rgba(43,92,138,0.3);margin:0 0.5rem;">glennbennett.com</a>
                        </p>

                        <p style="text-align:center;margin-top:1.25rem;margin-bottom:0.5rem;">
                            <a href="javascript:void(0);" onclick="var el=this,url='https://glennbennett.com/#how-to-listen';if(navigator.clipboard&&window.isSecureContext){navigator.clipboard.writeText(url).then(function(){el.textContent='Link copied!';setTimeout(function(){el.textContent='Share this article';},2000);});}else{var t=document.createElement('textarea');t.value=url;t.style.position='fixed';t.style.opacity='0';document.body.appendChild(t);t.select();document.execCommand('copy');document.body.removeChild(t);el.textContent='Link copied!';setTimeout(function(){el.textContent='Share this article';},2000);}" style="font-family:'Source Sans 3',sans-serif;font-size:0.8rem;color:#5a7a96;text-decoration:none;border:1px solid rgba(90,122,150,0.3);padding:0.4rem 1rem;border-radius:3px;transition:all 0.2s ease;">Share this article</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <style>
            .listen-btn:hover {
                border-color: rgba(140,180,220,0.7) !important;
                color: #fff !important;
                background: rgba(140,180,220,0.08) !important;
                box-shadow: 0 0 20px rgba(100,150,200,0.1);
            }
            .listen-modal-overlay {
                display: none;
                position: fixed;
                inset: 0;
                background: rgba(10,10,20,0.85);
                backdrop-filter: blur(8px);
                -webkit-backdrop-filter: blur(8px);
                z-index: 1000;
                justify-content: center;
                align-items: flex-start;
                padding: 2rem 1rem;
                overflow-y: auto;
                opacity: 0;
                transition: opacity 0.3s ease;
            }
            .listen-modal-overlay.active {
                display: flex;
                opacity: 1;
            }
            .listen-modal-overlay.entering {
                opacity: 0;
            }
            .listen-modal {
                background: #f8f6f2;
                color: #333;
                width: 100%;
                max-width: 640px;
                border-radius: 6px;
                padding: 3rem 2.5rem;
                position: relative;
                box-shadow: 0 25px 80px rgba(0,0,0,0.5);
                transform: translateY(20px);
                transition: transform 0.35s ease;
                margin: 2rem 0;
            }
            .listen-modal-overlay.active .listen-modal {
                transform: translateY(0);
            }
            .listen-modal-overlay.entering .listen-modal {
                transform: translateY(20px);
            }
            .listen-article a:hover {
                border-bottom-color: #2b5c8a !important;
            }
            @media (max-width: 700px) {
                .listen-cards {
                    flex-direction: column !important;
                    max-width: 400px !important;
                }
            }
            @media (max-width: 600px) {
                .listen-modal {
                    padding: 2rem 1.5rem !important;
                }
                .listen-modal .listen-article h1 {
                    font-size: 1.4rem !important;
                }
                .listen-modal div[style*="margin:2rem -2.5rem"] {
                    margin-left: -1.5rem !important;
                    margin-right: -1.5rem !important;
                    padding-left: 1.5rem !important;
                    padding-right: 1.5rem !important;
                }
            }
        </style>

        <?php
            $site_url = "https://glennbennett.com";
            $site_text = "Check out Glenn Bennett's original music";
            $site_tw = "https://twitter.com/intent/tweet?url=" . urlencode($site_url) . "&text=" . urlencode($site_text);
            $site_wa = "https://api.whatsapp.com/send?text=" . urlencode($site_text . " " . $site_url);
            $site_sms = "sms:?&body=" . rawurlencode($site_text . " " . $site_url);
            $site_email = "mailto:?subject=" . rawurlencode("Glenn Bennett — Original Music") . "&body=" . rawurlencode($site_text . "\n\n" . $site_url);
            $site_fb = "https://www.facebook.com/sharer.php?u=" . urlencode($site_url);
        ?>

        <footer id="footer" class="border-0 dark bg-black py-4">
            <div class="container">
                <div class="d-flex flex-wrap justify-content-between align-items-center">
                    <div>
                        <div class="mb-2">
                            <a href="https://music.glennbennett.com" target="_blank" class="text-white-50 mr-3">Albums</a>
                            <a href="/cal" class="text-white-50 mr-3">Calendar</a>
                            <a href="/about" class="text-white-50 mr-3">About</a>
                            <a href="/newsletter" class="text-white-50 mr-3">Newsletter</a>
                            <a href="/contact" class="text-white-50">Contact</a>
                        </div>
                        <div class="mt-2">
                            <a target="_blank" href="https://www.instagram.com/glennlbennett/" class="share-btn mr-1" title="Instagram" style="width: 30px; height: 30px; border-radius: 50%; background: rgba(255,255,255,0.08); display: inline-flex; align-items: center; justify-content: center; transition: all 0.2s ease; text-decoration: none;">
                                <i class="icon-instagram" style="color: rgba(255,255,255,0.5); font-size: 13px;"></i>
                            </a>
                        </div>
                        <small class="text-white-50" style="opacity: 0.6;">Copyrights &copy; 2000-<?php echo date("Y"); ?> Glenn L. Bennett.</small>
                    </div>
                    <div class="text-right mt-3 mt-md-0">
                        <small class="d-block text-white-50 mb-2" style="opacity: 0.6; text-transform: uppercase; letter-spacing: 1px; font-size: 0.7rem;">Share</small>
                        <div class="d-flex" style="gap: 8px;">
                            <a target="_blank" href="<?php echo $site_fb; ?>" class="share-btn" title="Facebook" style="width: 36px; height: 36px; border-radius: 50%; background: rgba(255,255,255,0.08); display: flex; align-items: center; justify-content: center; transition: all 0.2s ease; text-decoration: none;">
                                <i class="icon-facebook" style="color: rgba(255,255,255,0.5); font-size: 14px;"></i>
                            </a>
                            <a target="_blank" href="<?php echo $site_tw; ?>" class="share-btn" title="Twitter" style="width: 36px; height: 36px; border-radius: 50%; background: rgba(255,255,255,0.08); display: flex; align-items: center; justify-content: center; transition: all 0.2s ease; text-decoration: none;">
                                <i class="icon-twitter" style="color: rgba(255,255,255,0.5); font-size: 14px;"></i>
                            </a>
                            <a target="_blank" href="<?php echo $site_wa; ?>" class="share-btn" title="WhatsApp" style="width: 36px; height: 36px; border-radius: 50%; background: rgba(255,255,255,0.08); display: flex; align-items: center; justify-content: center; transition: all 0.2s ease; text-decoration: none;">
                                <i class="icon-whatsapp" style="color: rgba(255,255,255,0.5); font-size: 14px;"></i>
                            </a>
                            <a href="<?php echo $site_sms; ?>" class="share-btn" title="Text Message" style="width: 36px; height: 36px; border-radius: 50%; background: rgba(255,255,255,0.08); display: flex; align-items: center; justify-content: center; transition: all 0.2s ease; text-decoration: none;">
                                <i class="icon-phone" style="color: rgba(255,255,255,0.5); font-size: 14px;"></i>
                            </a>
                            <a href="<?php echo $site_email; ?>" class="share-btn" title="Email" style="width: 36px; height: 36px; border-radius: 50%; background: rgba(255,255,255,0.08); display: flex; align-items: center; justify-content: center; transition: all 0.2s ease; text-decoration: none;">
                                <i class="icon-envelope" style="color: rgba(255,255,255,0.5); font-size: 14px;"></i>
                            </a>
                            <a href="javascript:void(0);" onclick="var i=this.querySelector('i');navigator.clipboard.writeText('https://glennbennett.com').then(function(){i.className='icon-check';setTimeout(function(){i.className='icon-link';},2000);});" class="share-btn" title="Copy Link" style="width: 36px; height: 36px; border-radius: 50%; background: rgba(255,255,255,0.08); display: flex; align-items: center; justify-content: center; transition: all 0.2s ease; text-decoration: none;">
                                <i class="icon-link" style="color: rgba(255,255,255,0.5); font-size: 14px;"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
        <style>
            .share-btn:hover { background: rgba(255,255,255,0.18) !important; transform: translateY(-2px); }
            .share-btn:hover i { color: #fff !important; }
        </style>

    </div>

    <audio id="audio-player" preload="none" class="mejs__player" controls style="max-width:100%;">
        <source src="<?php echo $featured ? $featured->audio_url : ''; ?>">
    </audio>

    <script src="/js/jquery.js"></script>
    <script src="/js/plugins.min.js"></script>
    <script src="/demos/music/js/mediaelement/mediaelement-and-player.js"></script>
    <script src="/js/functions.js"></script>
    <script>
        var trackPlaying = '';
        var activeTrack = null;
        var eqHtml = '<span class="eq-bars"><span></span><span></span><span></span></span>';
        jQuery(document).ready(function($) {
            $('.track-list').on('click', function(e) {
                e.preventDefault();
                var $this = $(this);
                var source = $this.attr('data-track'), poster = $this.attr('data-poster'), title = $this.attr('data-title'), singer = $this.attr('data-singer');
                var player = mejs.players[$('#audio-player').closest('.mejs__container').attr('id')];
                if (source == trackPlaying) {
                    if (player.node.paused) {
                        player.play();
                        $this.find('i').removeClass('icon-play').addClass('icon-pause');
                        $this.closest('.songs-list').find('.songs-time .eq-bars').remove();
                        $this.closest('.songs-list').find('.songs-time').append(eqHtml);
                    } else {
                        player.pause();
                        $this.find('i').removeClass('icon-pause').addClass('icon-play');
                        $this.closest('.songs-list').find('.songs-time .eq-bars').remove();
                    }
                } else {
                    // Reset previous active track
                    if (activeTrack) {
                        activeTrack.find('i').removeClass('icon-pause').addClass('icon-play');
                        activeTrack.closest('.songs-list').find('.songs-time .eq-bars').remove();
                    }
                    trackPlaying = source;
                    activeTrack = $this;
                    $('.mejs__layers').html('<div class="mejs-track-artwork"><img src="'+ poster +'" /></div><div class="mejs-track-details"><h3>'+ title +'<br><span>'+ singer +'</span></h3></div>');
                    player.setSrc(source); player.load(); player.play();
                    $this.find('i').removeClass('icon-play').addClass('icon-pause');
                    $this.closest('.songs-list').find('.songs-time').append(eqHtml);
                }
                return false;
            });

            // Sync list icons when the bottom player is used directly
            var playerNode = $('#audio-player')[0];
            playerNode.addEventListener('play', function() {
                if (activeTrack) {
                    activeTrack.find('i').removeClass('icon-play').addClass('icon-pause');
                    if (!activeTrack.closest('.songs-list').find('.songs-time .eq-bars').length) {
                        activeTrack.closest('.songs-list').find('.songs-time').append(eqHtml);
                    }
                }
            });
            playerNode.addEventListener('pause', function() {
                if (activeTrack) {
                    activeTrack.find('i').removeClass('icon-pause').addClass('icon-play');
                    activeTrack.closest('.songs-list').find('.songs-time .eq-bars').remove();
                }
            });
        });
    </script>
    <script>
        var listenOverlay = document.getElementById('listenModalOverlay');
        function openListenModal() {
            listenOverlay.classList.add('active', 'entering');
            document.body.style.overflow = 'hidden';
            history.replaceState(null, '', '#how-to-listen');
            requestAnimationFrame(function() {
                requestAnimationFrame(function() {
                    listenOverlay.classList.remove('entering');
                });
            });
        }
        function closeListenModal() {
            listenOverlay.classList.add('entering');
            history.replaceState(null, '', window.location.pathname);
            setTimeout(function() {
                listenOverlay.classList.remove('active', 'entering');
                document.body.style.overflow = '';
            }, 300);
        }
        function closeListenModalOutside(e) {
            if (e.target === listenOverlay) closeListenModal();
        }
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && listenOverlay.classList.contains('active')) closeListenModal();
        });
        if (window.location.hash === '#how-to-listen') {
            openListenModal();
        }
    </script>
</body>
</html>
