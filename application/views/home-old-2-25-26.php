<!DOCTYPE html>
<html dir="ltr" lang="en-US">
<head>

<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-76452576-1"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-76452576-1');
</script>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>



	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<meta name="author" content="SemiColonWeb" />

	<!-- Stylesheets
	============================================= -->
	<link href="https://fonts.googleapis.com/css?family=Poppins:200,300,400,400i,600,700|Montserrat:300,400,700|Caveat+Brush&display=swap" rel="stylesheet" type="text/css" />
	<link rel="stylesheet" href="/css/bootstrap.css" type="text/css" />
	<link rel="stylesheet" href="/style.css" type="text/css" />
	<link rel="stylesheet" href="/css/dark.css" type="text/css" />
	<link rel="stylesheet" href="/css/swiper.css" type="text/css" />

	<!-- Music Specific Stylesheet -->
	<link rel="stylesheet" href="/demos/music/music.css" type="text/css" />
	<!-- / -->

	<link rel="stylesheet" href="/css/font-icons.css" type="text/css" />
	<link rel="stylesheet" href="/one-page/css/et-line.css" type="text/css" />
	<link rel="stylesheet" href="/css/animate.css" type="text/css" />
	<link rel="stylesheet" href="/css/magnific-popup.css" type="text/css" />

	<link rel="stylesheet" href="/demos/music/css/fonts.css" type="text/css" />

	<!-- Bootstrap Switch CSS -->
	<link rel="stylesheet" href="/css/components/bs-switches.css" type="text/css" />

	<link rel="stylesheet" href="/css/custom.css" type="text/css" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />

	<!-- Theme color -->
	<link rel="stylesheet" href="/css/colors.php?color=0665a2" type="text/css" />

	<!-- Audio Player Plugin CSS -->
	<link rel="stylesheet" href="/demos/music/css/mediaelement/mediaelementplayer.css">

	<!-- Document Title
	============================================= -->
	<title><?php echo $site_title . " | " . $site_sub_title . " | " . $featured->title; ?></title>

	<style>
		.css3-spinner { background-color: #131722; }
	</style>


<?php
    if($featured->cover)
    {
        $song_type = "Cover song by Glenn Bennett.";
    }
    else
    {
        $song_type =  "Original song by Glenn Bennett.";

    }
//    var_dump($featured);
?>

<meta property="og:url"                content="<?php echo site_url('/site/song/') . $featured->id; ?>" />
<meta property="og:type"               content="article" />
<meta property="og:title"              content="<?php echo $featured->title; ?>" />
<meta property="og:description"        content="<?php echo $song_type . " | " . htmlspecialchars($featured->featured_track->blurb); ?>" />
<meta property="og:image"              content="<?php echo $image_url . $featured->art; ?>" />

</head>

<body class="stretched bg-color2" data-loader="4" data-loader-color="theme">

	<!-- Document Wrapper
	============================================= -->
	<div id="wrapper" class="clearfix" style="margin-bottom: 40px">

		<!-- Header
		============================================= -->
		<header id="header" class="full-header transparent-header dark no-sticky header-size-md">
			<div id="header-wrap">
				<div class="container">
					<div class="header-row">

						<!-- Logo
						============================================= -->
						<div id="logo">
							<a href="/" class="standard-logo" data-dark-logo="/imgs/logo-dark.png"><img src="/imgs/logo.png" alt="GlennBennett.com"></a>
							<a href="/" class="retina-logo" data-dark-logo="/imgs/logo-dark@2x.png"><img src="/imgs/logo@2x.png" alt="GlennBennett.com"></a>
						</div><!-- #logo end -->

						<div class="header-misc">

							<!-- Top Search
							============================================= -->
							<div id="top-search" class="header-misc-icon">
								<a href="#" id="top-search-trigger"><i class="icon-line-search"></i><i class="icon-line-cross"></i></a>
							</div><!-- #top-search end -->

						</div>

						<div id="primary-menu-trigger">
							<svg class="svg-trigger" viewBox="0 0 100 100"><path d="m 30,33 h 40 c 3.722839,0 7.5,3.126468 7.5,8.578427 0,5.451959 -2.727029,8.421573 -7.5,8.421573 h -20"></path><path d="m 30,50 h 40"></path><path d="m 70,67 h -40 c 0,0 -7.5,-0.802118 -7.5,-8.365747 0,-7.563629 7.5,-8.634253 7.5,-8.634253 h 20"></path></svg>
						</div>

						<!-- Primary Navigation
						============================================= -->
						<nav class="primary-menu not-dark">

							<ul class="menu-container">
								<li class="menu-item active"><a class="menu-link" href="#"><div>Home</div></a></li>
                                <li class="menu-item"><a class="menu-link" href="#"><div>Albums</div></a>
									<ul class="sub-menu-container">
										<li class="menu-item"><a class="menu-link" target="_blank" href="https://music.glennbennett.com"><div>Album Player</div></a></li>


									</ul>
								</li>
                                <li class="menu-item"><a class="menu-link" href="/site/mlist"><div>Newsletter</div></a></li-->
								<li class="menu-item"><a class="menu-link" href="/cal"><div>Calendar</div></a>
                                <li class="menu-item"><a class="menu-link" href="#"><div>About</div></a>
									<ul class="sub-menu-container">
										<li class="menu-item"><a class="menu-link" href="/site/mlist"><div>Newsletter</div></a></li>
					                    <li class="menu-item"><a class="menu-link" href="/contact"><div>Contact</div></a></li>
									</ul>
								</li>

								<!--li class="menu-item"><a class="menu-link" href="#"><div>About</div></a></li>
								<li class="menu-item"><a class="menu-link" href="#"><div>Discover</div></a></li>
								<li class="menu-item"><a class="menu-link" href="#"><div>Artists</div></a>
									<ul class="sub-menu-container">
										<li class="menu-item"><a class="menu-link" href="#"><div>A-Z Artists</div></a></li>
										<li class="menu-item"><a class="menu-link" href="#"><div>New Artists</div></a></li>
										<li class="menu-item"><a class="menu-link" href="#"><div>Popular Artists</div></a></li>
									</ul>
								</li>
								<li class="menu-item"><a class="menu-link" href="#"><div>Videos</div></a></li>
								<li class="menu-item"><a class="menu-link" href="#"><div>Contact</div></a></li-->
							</ul>

						</nav><!-- #primary-menu end -->

						<form class="top-search-form" action="search.html" method="get">
							<input type="text" name="q" class="form-control" value="" placeholder="Type &amp; Hit Enter.." autocomplete="off">
						</form>

					</div>
				</div>
			</div>
			<div class="header-wrap-clone"></div>
		</header><!-- #header end -->


		<!-- Slider
		============================================= -->
		<section id="slider" class="slider-element swiper_wrapper min-vh-60 min-vh-md-100 include-header" style="background: #131722;" data-effect="fade" data-loop="true" data-speed="1000">
			<div class="swiper-container swiper-parent">
				<div class="swiper-wrapper">
					<div class="swiper-slide dark">
						<div class="container">
							<div class="slider-caption justify-content-end">
								<div>
                                
									<h2 class="font-primary nott"><?php echo wordwrap($featured->title, 11, '<br />'); ?></h2>
									<p class="d-none d-md-block">
                    <?php echo $song_type; ?>
                   </p>
                   <p class="d-none d-md-block">
                    <?php 
                    // Show blurb if available, or show award for the featured song
                    if (!empty($featured->featured_track->blurb)) {
                        echo $featured->featured_track->blurb;
                    } elseif (stripos($featured->title, "Find Out For Myself") !== false) {
                        echo 'Reaches #22 on the American Idol Underground Folk Music Chart | May 8, 2006';
                    }
                    ?>
                   </p>
									<a class="button button-rounded font-weight-normal ls1 track-list mt-3 clearfix" data-track="<?php echo $audio_url . $featured->featured_track->audio_file; ?>" data-poster="<?php echo $image_url . $featured->art; ?>" data-title="<?php echo $featured->title; ?>" data-singer="United Album"><i class="icon-play"></i>Play Now</a>
									<!--a href="#" class="button d-none d-md-inline-block button-rounded mt-3 px-3" style="background-color: #1f2330;"><i class="icon-line-ribbon mr-0"></i></a>
									<a href="#" class="button d-none d-md-inline-block button-rounded mt-3 px-3" style="background-color: #1f2330;"><i class="icon-line-heart mr-0"></i></a>
									<a href="#" class="button d-none d-md-inline-block bg-transparent font-weight-light nott mt-3 px-3"><i class="icon-line-share color"></i>Share</a>
									<a href="#" class="button d-none d-md-inline-block bg-transparent font-weight-light nott mt-3 px-3 ml-0"><i class="icon-line-plus color"></i>Add</a-->
								</div>
							</div>
						</div>
						<div class="swiper-slide-bg" style="background-image: url('/demos/music/images/slider/me.jpg'); background-position: bottom center;"></div>
					</div>
					<!-- div class="swiper-slide dark">
						<div class="container">
							<div class="slider-caption justify-content-end">
								<div>
									<h2 class="font-primary nott">sgt. peppers heart club band Edition</h2>
									<p class="d-none d-md-block">The Beatles Club</p>
									<a href="#" class="button button-rounded font-weight-normal ls1 track-list mt-3 clearfix" data-track="demos/music/tracks/tammy-stan-devereaux.mp3" data-poster="demos/music/tracks/poster-images/tammy-stan-devereaux.jpg" data-title="sgt. peppers heart club band Edition" data-singer="the bettles 2021"><i class="icon-play"></i>Play Now</a>
									<a href="#" class="button d-none d-md-inline-block button-rounded mt-3 px-3" style="background-color: #1f2330;"><i class="icon-line-ribbon mr-0"></i></a>
									<a href="#" class="button d-none d-md-inline-block button-rounded mt-3 px-3" style="background-color: #1f2330;"><i class="icon-line-heart mr-0"></i></a>
									<a href="#" class="button d-none d-md-inline-block mt-3 px-3 bg-transparent font-weight-light nott" style="background-color: #1f2330;"><i class="icon-line-share color"></i>Share</a>
									<a href="#" class="button d-none d-md-inline-block mt-3 px-3 bg-transparent font-weight-light nott ml-0" style="background-color: #1f2330;"><i class="icon-line-plus color"></i>Add</a>
								</div>
							</div>
						</div>
						<div class="swiper-slide-bg" style="background-image: url('demos/music/images/slider/2.jpg');"></div>
					</div>
					<div class="swiper-slide dark">
						<div class="container">
							<div class="slider-caption justify-content-end">
								<div>
									<h2 class="font-primary nott">The End Of The Beginning</h2>
									<p class="d-none d-md-block">The Unplugged Editions</p>
									<a href="#" class="button button-rounded font-weight-normal ls1 track-list mt-3 clearfix" data-track="demos/music/tracks/the-end-of-the-beginning.mp3" data-poster="demos/music/tracks/poster-images/the-end-of-the-beginning.jpg" data-title="The End Of The Beginning" data-singer="The Unplugged Editions" style="margin-top: 15px"><i class="icon-play"></i>Play Now</a>
									<a href="#" class="button d-none d-md-inline-block button-rounded mt-3 px-3" style="background-color: #1f2330;"><i class="icon-line-ribbon mr-0"></i></a>
									<a href="#" class="button d-none d-md-inline-block button-rounded mt-3 px-3" style="background-color: #1f2330;"><i class="icon-line-heart mr-0"></i></a>
									<a href="#" class="button d-none d-md-inline-block mt-3 px-3 bg-transparent font-weight-light nott"><i class="icon-line-share color"></i>Share</a>
									<a href="#" class="button d-none d-md-inline-block mt-3 px-3 bg-transparent font-weight-light nott ml-0"><i class="icon-line-plus color"></i>Add</a>
								</div>
							</div>
						</div>
						<div class="swiper-slide-bg" style="background-image: url('demos/music/images/slider/3.jpg'); background-position: bottom left;"></div>
					</div -->
				</div>
				<!--div class="slider-arrow-left bg-transparent"><i class="icon-line-arrow-left"></i></div>
				<div class="slider-arrow-right bg-transparent"><i class="icon-line-arrow-right"></i></div-->
			</div>
		</section>

		<!-- Content
		============================================= -->
		<section id="content" class="bg-color2">
			<div class="content-wrap py-0" style="overflow: visible;">
				<div class="container clearfix" style="z-index: 7;">

<?php echo $gcal; ?>


					<div class="heading-block topmargin bottommargin-sm border-0 dark">
						<h3>Popular Songs of the Week</h3>
						<span>Original Songs.</span>
					</div>

					<!-- Carousel
					============================================= -->
					<div id="oc-popular-songs" class="owl-carousel image-carousel carousel-widget" data-margin="20" data-nav="true" data-pagi="false" data-items-xs="2" data-items-sm="3" data-items-md="4" data-items-lg="6" data-items-xl="6">

						<!-- Carousel Items
						============================================= -->
                        <?php
                       // var_dump($shuffled_songs);
                        foreach($popular as $track)
                        {
                        ?>
                        
    						<div class="oc-item" data-animate="fadeInDown">
    							<img src="<?php echo $image_url . $track->art; ?>" alt="Image 1">
    							<div class="bg-overlay">
    								<div class="bg-overlay-content text-overlay-mask dark desc-sm align-items-center justify-content-between">
    									<div class="portfolio-desc py-0">
    										<h3><a href="#"><?php echo $track->title; ?></a></h3>
    										<span><a href="#"></a></span>
    									</div>
    									<div data-hover-animate="fadeIn" data-hover-speed="400">
    										<a href="#" class="text-light mx-0 track-list" data-track="<?php echo $audio_url . $track->featured_track->audio_file; ?>" data-poster="<?php echo $image_url . $track->art; ?>" data-title="<?php echo $track->title; ?>" data-singer=""><i class="icon-play"></i></a>
    									</div>
    								</div>
    							</div>
    						</div>                        
                        <?php    
                        }
                        ?>                        


					</div>

					<div class="row topmargin-lg clearfix">
						<div class="col-lg-6">
							<div class="heading-block border-0 dark" style="margin-bottom: 15px;">
								<h3>Milestones Album</h3>
								<span>Songs from the Milestones album.</span>
							</div>

							<!-- Song Lists Items
							============================================= -->
							<div class="songs-lists-wrap">
                                <?php
                                $i = 1;
                                foreach($originals as $original)
                                {
                                ?>
                                    <!-- List Items
    								============================================= -->
    								<div class="songs-list">
    									<div class="songs-number"><?php echo sprintf('%02d', $i++);; ?></div>
    									<div class="songs-image track-image">
    										<a href="#" class="track-list"  data-track="<?php echo $audio_url . $original->featured_track->audio_file; ?>" data-poster="<?php echo $image_url . $original->art; ?>" data-title="<?php echo $original->title; ?>" data-singer="">
    											<img src="<?php echo $image_url . $original->art; ?>" alt="Image 1"><span><i class="icon-play"></i></span>
    										</a>
    									</div>
    									<div class="songs-name track-name"><a href="#"><?php echo $original->title; ?><br><span></span></a></div>
    									<div class="songs-time"><?php echo substr($original->featured_track->duration, -5); ?></div>
    									<div class="songs-button"><a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="icon-line-ellipsis"></i></a>
    										<ul class="dropdown-menu dropdown-menu-right">
    											<li>
                            <a class="dropdown-item" target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode(site_url('/site/song/') . $original->id); ?>"><span class="icon-line-plus"></span> Share to FaceBook</a>
                            <a class="dropdown-item" target="_blank" href="https://twitter.com/intent/tweet?url=<?php echo urlencode(site_url('/site/song/') . $original->id); ?>&text=<?php echo urlencode($original->title . " | " . $song_type); ?>"><span class="icon-line-plus"></span> Share to Twitter</a>
    												<!--a class="dropdown-item" href="#"><span class="icon-line-plus"></span> Add to Queue</a>
    												<a class="dropdown-item" href="#"><span class="icon-music"></span> Add to Playlist</a>
    												<a class="dropdown-item" href="#"><span class="icon-line-cloud-download"></span> Download Offline</a>
    												<a class="dropdown-item" href="#"><span class="icon-line-heart"></span> Love</a>
    												<div class="dropdown-divider"></div>
    												<a class="dropdown-item" href="#"><span class="icon-line-share"></span> Share</a-->
    											</li>
    										</ul>
    									</div>
    								</div>
                                <?php    
                                }
                                ?>


								<!-- List Items
								============================================= -->
								<!--div class="songs-list">
									<div class="songs-number">01</div>
									<div class="songs-image track-image">
										<a href="#" class="track-list"  data-track="demos/music/tracks/ibelieveinyou.mp3" data-poster="demos/music/tracks/poster-images/ibelieveinyou.jpg" data-title="i Believe In You" data-singer="Lost European">
											<img src="/demos/music/tracks/poster-images/ibelieveinyou.jpg" alt="Image 1"><span><i class="icon-play"></i></span>
										</a>
									</div>
									<div class="songs-name track-name"><a href="#">i Believe In You<br><span>Lost European</span></a></div>
									<div class="songs-time">03:28</div>
									<div class="songs-button"><a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="icon-line-ellipsis"></i></a>
										<ul class="dropdown-menu dropdown-menu-right">
											<li>
												<a class="dropdown-item" href="#"><span class="icon-line-plus"></span> Add to Queue</a>
												<a class="dropdown-item" href="#"><span class="icon-music"></span> Add to Playlist</a>
												<a class="dropdown-item" href="#"><span class="icon-line-cloud-download"></span> Download Offline</a>
												<a class="dropdown-item" href="#"><span class="icon-line-heart"></span> Love</a>
												<div class="dropdown-divider"></div>
												<a class="dropdown-item" href="#"><span class="icon-line-share"></span> Share</a>
											</li>
										</ul>
									</div>
								</div-->

							</div>
							<!-- a href="#" class="button bg-transparent font-weight-light nott float-right ml-0" style="color: #AAA; padding: 0 16px;">See More..</a-->
						</div>

						<div class="w-100 d-block d-md-block d-lg-none topmargin-lg clear"></div>

						<div class="col-lg-6">
							<div class="heading-block border-0 dark" style="margin-bottom: 15px;">
								<h3>More Originals</h3>
								<span>More original songs by Glenn Bennett.</span>
							</div>
							<div class="songs-lists-wrap">
                             <?php
                                $i = 1;
                                foreach($covers as $track)
                                {
                                ?>
                                    <!-- List Items
    								============================================= -->
    								<div class="songs-list">
    									<div class="songs-number"><?php echo sprintf('%02d', $i++);; ?></div>
    									<div class="songs-image track-image">
    										<a href="#" class="track-list"  data-track="<?php echo $audio_url . $track->featured_track->audio_file; ?>" data-poster="<?php echo $image_url . $track->art; ?>" data-title="<?php echo $track->title; ?>" data-singer="">
    											<img src="<?php echo $image_url . $track->art; ?>" alt="Image 1"><span><i class="icon-play"></i></span>
    										</a>
    									</div>
    									<div class="songs-name track-name"><a href="#"><?php echo $track->title; ?><br><span></span></a></div>
    									<div class="songs-time"><?php echo substr($track->featured_track->duration,-5); ?></div>
    									<div class="songs-button"><a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="icon-line-ellipsis"></i></a>
    										<ul class="dropdown-menu dropdown-menu-right">
    											<li>
                            <a class="dropdown-item" target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode(site_url('/site/song/') . $track->id); ?>"><span class="icon-line-plus"></span> Share to FaceBook</a>
                            <a class="dropdown-item" target="_blank" href="https://twitter.com/intent/tweet?url=<?php echo urlencode(site_url('/site/song/') . $track->id); ?>&text=<?php echo urlencode($track->title . " | " . $song_type); ?>"><span class="icon-line-plus"></span> Share to Twitter</a>
    							
    												<!--a class="dropdown-item" href="#"><span class="icon-line-plus"></span> Add to Queue</a>
    												<a class="dropdown-item" href="#"><span class="icon-music"></span> Add to Playlist</a>
    												<a class="dropdown-item" href="#"><span class="icon-line-cloud-download"></span> Download Offline</a>
    												<a class="dropdown-item" href="#"><span class="icon-line-heart"></span> Love</a>
    												<div class="dropdown-divider"></div>
    												<a class="dropdown-item" href="#"><span class="icon-line-share"></span> Share</a-->
    											</li>
    										</ul>
    									</div>
    								</div>
                                <?php    
                                }
                                ?>

							</div>

							<!--a href="#" class="button bg-transparent font-weight-light nott float-right ml-0" style="color: #AAA; padding: 0 16px;">See More..</a-->
						</div>
					</div>


                    <div class="heading-block border-0 dark mb-3 mt-4">
                        <h3>Reviews</h3>
                        <span>What people have to say.</span>
                    </div>
                
               		<div id="oc-testi" class="owl-carousel mt-4 testimonials-carousel carousel-widget" data-margin="20" data-items-sm="1" data-items-md="2" data-items-xl="3">

                   <?php 
                   foreach($quotes as $quote)
                   {
                   ?>
						<div class="oc-item">
							<div class="testimonial">
								<div>
									<p><?php echo $quote; ?></p>
									
								</div>
							</div>
						</div>                   
                   <?php
                   }
                   ?>




						<!--div class="oc-item">
							<div class="testimonial">
								<div class="testi-image">
									<a href="#"><img src="images/testimonials/2.jpg" alt="Customer Testimonails"></a>
								</div>
								<div class="testi-content">
									<p>Natus voluptatum enim quod necessitatibus quis expedita harum provident eos obcaecati id culpa corporis molestias.</p>
									<div class="testi-meta">
										Collis Ta'eed
										<span>Envato Inc.</span>
									</div>
								</div>
							</div>
						</div>

						<div class="oc-item">
							<div class="testimonial">
								<div class="testi-image">
									<a href="#"><img src="images/testimonials/7.jpg" alt="Customer Testimonails"></a>
								</div>
								<div class="testi-content">
									<p>Fugit officia dolor sed harum excepturi ex iusto magnam asperiores molestiae qui natus obcaecati facere sint amet.</p>
									<div class="testi-meta">
										Mary Jane
										<span>Google Inc.</span>
									</div>
								</div>
							</div>
						</div>

						<div class="oc-item">
							<div class="testimonial">
								<div class="testi-image">
									<a href="#"><img src="images/testimonials/3.jpg" alt="Customer Testimonails"></a>
								</div>
								<div class="testi-content">
									<p>Similique fugit repellendus expedita excepturi iure perferendis provident quia eaque. Repellendus, vero numquam?</p>
									<div class="testi-meta">
										Steve Jobs
										<span>Apple Inc.</span>
									</div>
								</div>
							</div>
						</div>

						<div class="oc-item">
							<div class="testimonial">
								<div class="testi-image">
									<a href="#"><img src="images/testimonials/4.jpg" alt="Customer Testimonails"></a>
								</div>
								<div class="testi-content">
									<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Minus, perspiciatis illum totam dolore deleniti labore.</p>
									<div class="testi-meta">
										Jamie Morrison
										<span>Adobe Inc.</span>
									</div>
								</div>
							</div>
						</div-->

					</div>



				</div>
			</div>


		</section><!-- #content end -->

		<!-- Footer
		============================================= -->
		<footer id="footer" class="border-0 dark" style="background-color: #111;">
			<!-- Copyrights
			============================================= -->
			<div id="copyrights" style="color: #444;">
				<div class="container clearfix">

					<div class="row justify-content-between col-mb-30">
						<div class="col-12 col-lg-auto text-center text-lg-left">
							Copyrights &copy; 2004-<?php echo date("Y"); ?> All Rights Reserved by Glenn L. Bennett.
						</div>

						<div class="col-12 col-lg-auto text-center text-lg-right">
							<a target="_blank" href="https://www.facebook.com/glennlbennett" class="social-icon inline-block si-small si-borderless si-facebook">
								<i class="icon-facebook"></i>
								<i class="icon-facebook"></i>
							</a>

							<a target="_blank" href="https://twitter.com/glennbennett" class="social-icon inline-block si-small si-borderless si-twitter">
								<i class="icon-twitter"></i>
								<i class="icon-twitter"></i>
							</a>
                            
                            
                            <a target="_blank" href="https://instagram.com/glennlbennett" class="social-icon inline-block si-small si-borderless si-twitter">
								<i class="icon-instagram"></i>
								<i class="icon-instagram"></i>
							</a>

							<!--a href="#" class="social-icon inline-block si-small si-borderless si-gplus">
								<i class="icon-gplus"></i>
								<i class="icon-gplus"></i>
							</a>

							<a href="#" class="social-icon inline-block si-small si-borderless si-pinterest">
								<i class="icon-pinterest"></i>
								<i class="icon-pinterest"></i>
							</a>

							<a href="#" class="social-icon inline-block si-small si-borderless si-vimeo">
								<i class="icon-vimeo"></i>
								<i class="icon-vimeo"></i>
							</a>

							<a href="#" class="social-icon inline-block si-small si-borderless si-github">
								<i class="icon-github"></i>
								<i class="icon-github"></i>
							</a>

							<a href="#" class="social-icon inline-block si-small si-borderless si-yahoo">
								<i class="icon-yahoo"></i>
								<i class="icon-yahoo"></i>
							</a>

							<a href="#" class="social-icon inline-block si-small si-borderless si-linkedin">
								<i class="icon-linkedin"></i>
								<i class="icon-linkedin"></i>
							</a-->
						</div>
					</div>

				</div>
			</div><!-- #copyrights end -->


		</footer><!-- #footer end -->

	</div><!-- #wrapper end -->

	<!-- Audio Player
	============================================= -->
	<audio id="audio-player" preload="none" class="mejs__player" controls data-mejsoptions='{"defaultAudioHeight": "50", "alwaysShowControls": "true"}' style="max-width:100%;">
		<source src="<?php echo $audio_url . $featured->featured_track->audio_file; ?>">
	</audio>

	<!-- Default Track - onLoad
	============================================= -->
	<div id="track-onload" data-track="<?php echo $audio_url . $featured->featured_track->audio_file; ?>" data-poster="<?php echo $image_url . $featured->art; ?>" data-title="<?php echo $featured->title; ?>" data-singer="United Album"></div>

	<!-- Go To Top
	============================================= -->
	<div id="gotoTop" class="icon-angle-up" style="bottom: 70px;"></div>

	<!-- JavaScripts
	============================================= -->
	<script src="/js/jquery.js"></script>
	<script src="/js/plugins.min.js"></script>

	<!-- Audio player Plugin
	============================================= -->
	<script src="/demos/music/js/mediaelement/mediaelement-and-player.js"></script>





	<!-- Footer Scripts
	============================================= -->
	<script src="/js/functions.js"></script>

	<script>

		// Custom Tab jQuery
		// jQuery( '.tabs' ).on( 'tabsactivate', function( event, ui ) {
		// 	var gridContainerAvailable = jQuery( ui.newPanel ).find('.grid-container');

		// 	if( gridContainerAvailable.length > 0 ) {
		// 		gridContainerAvailable.each( function(){
		// 			var portfolioGrid = jQuery(this);

		// 			if( !portfolioGrid.hasClass('tabs-enabled-grid-container') ) {
		// 				portfolioGrid.isotope('layout');
		// 				jQuery(window).trigger('resize');
		// 				portfolioGrid.addClass('tabs-enabled-grid-container');
		// 			}
		// 		});
		// 	}
		// });

		// Music playing Scripts
		var trackPlaying = '',
			audioPlayer = document.getElementById('audio-player');

		audioPlayer.addEventListener("ended", function(){
			jQuery('.track-list').find('i').removeClass('icon-pause').addClass('icon-play');
		});

		audioPlayer.addEventListener("pause", function(){
			jQuery('.track-list').find('i').removeClass('icon-pause').addClass('icon-play');
		});

		function changeAudio( sourceUrl, posterUrl, trackTitle, trackSinger, playAudio = true ) {
			var audio = $("#audio-player"),
				clickEl = jQuery('[data-track="'+sourceUrl+'"]'),
				playerId = audio.closest('.mejs__container').attr('id'),
				playerObject = mejs.players[playerId];

			jQuery('.track-list').find('i').removeClass('icon-pause').addClass('icon-play');

			if( sourceUrl == trackPlaying ) {
				if (playerObject.node.paused) {
					playerObject.play();
					clickEl.find('i').removeClass('icon-play').addClass('icon-pause');
				} else {
					playerObject.pause();
					clickEl.find('i').removeClass('icon-pause').addClass('icon-play');
				}
				return true;
			}

			trackPlaying = sourceUrl;

			audio.attr( 'poster', posterUrl );
			audio.attr( 'title', trackTitle );

			jQuery('.mejs__layers').html('').html('<div class="mejs-track-artwork"><img src="'+ posterUrl +'" alt="Track Poster" /></div><div class="mejs-track-details"><h3>'+ trackTitle +'<br><span>'+ trackSinger +'</span></h3></div>');

			if( sourceUrl != '' ) {
				playerObject.setSrc( sourceUrl );
			}
			playerObject.pause();
			playerObject.load();

			if( playAudio == true ) {
				playerObject.play();
				jQuery(clickEl).find('i').removeClass('icon-play').addClass('icon-pause');
			}
		}

		jQuery('.track-list').on( 'click', function(){
			var audioTrack = jQuery(this).attr('data-track'), // Track url
				posterUrl = jQuery(this).attr('data-poster'), // Track Poster Image
				trackTitle = jQuery(this).attr('data-title'); // Track Title
				trackSinger = jQuery(this).attr('data-singer'); // Track Singer Name

			changeAudio( audioTrack, posterUrl, trackTitle, trackSinger );
			return false;
		});

		jQuery(window).on( 'load', function(){
			var trackOnload = jQuery('#track-onload');

			if( trackOnload.length > 0 ) {
				var audioTrack = trackOnload.attr('data-track'), // Track url
					posterUrl = trackOnload.attr('data-poster'), // Track Poster Image
					trackTitle = trackOnload.attr('data-title'); // Track Title
					trackSinger = trackOnload.attr('data-singer'); // Track Singer Name

				setTimeout( function(){
					changeAudio( audioTrack, posterUrl, trackTitle, trackSinger, false );
				}, 500);
			}
		});

	</script>
    


</body>
</html>
