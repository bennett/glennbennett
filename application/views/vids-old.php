

		<!-- Slider
		============================================= -->
        
		<section id="slider" class="slider-element revoslider-wrap mt-5">
        

			<div class="container">
        <?php echo $page_partial ?>
                <!--div class="heading-block border-0 mb-3 mt-4">
                    <h3><?php echo $title; ?></h3>
                    <span><?php echo $sub_title; ?></span>
                </div-->                   
            

				<div id="rev_slider_wrapper" class="rev_slider_wrapper fullwidthbanner-container" style="margin:0px auto;background-color:#000000;padding:0px;margin-top:0px;margin-bottom:0px;">
					<!-- START REVOLUTION SLIDER 5.0.4.1 auto mode -->
					<div id="rev_slider" class="rev_slider fullwidthabanner" style="display:none;" data-version="5.0.4.1">
						
                        
                        <ul>
						<?php foreach ($videos as $video) :
							
						?>
							<!-- SLIDE  -->
							<li data-index="rs-<?php echo $video->snippet->resourceId->videoId; ?>" data-transition="scaledownfrombottom" data-slotamount="7"  data-easein="Power3.easeInOut" data-easeout="Power3.easeInOut" data-masterspeed="1500"  data-thumb="<?php echo $video->snippet->thumbnails->medium->url; ?>"  data-rotate="0"  data-fstransition="fade" data-fsmasterspeed="1500" data-fsslotamount="7" data-saveperformance="off"  data-title="<?php echo $video->snippet->title; ?>" data-param1="<?php echo $video->snippet->publishedAt; ?>" data-description="">
								<!-- MAIN IMAGE -->
                                <?php
                                if(isset($video->snippet->thumbnails->maxres))
                                {
                                ?>
								<img src="<?php echo $video->snippet->thumbnails->maxres->url; ?>"  alt="Image"  data-bgposition="center center" data-bgfit="100% 0%" data-bgrepeat="no-repeat" class="rev-slidebg" data-no-retina>
								<?php
                                }
                                ?>
                                <!-- LAYERS -->

								<!-- LAYER NR. 1 -->
								<div class="tp-caption   tp-resizeme tp-videolayer"
									id="slide-<?php echo $video->snippet->resourceId->videoId; ?>-layer-2"
									data-x="center"
									data-hoffset=""
									data-y="center"
									data-voffset=""
									data-width="['auto']"
									data-height="['auto']"
									data-transform_idle="o:1;"

									data-transform_in="opacity:0;s:300;e:Power2.easeInOut;"
									data-transform_out="opacity:0;s:300;s:300;"
									data-start="500"
									data-responsive_offset="on"

									data-ytid="<?php echo $video->snippet->resourceId->videoId; ?>"
									data-videoattributes="version=3&amp;enablejsapi=1&amp;html5=1&amp;volume=100&hd=1&amp;wmode=opaque&amp;showinfo=0&amp;ref=0;;origin=<?php echo $origin; ?>;"
									data-videorate="1"
									data-videowidth="1230px"
									data-videoheight="692px"
									data-videocontrols="controls"
									data-videoloop="none"
									data-autoplay="<?php echo $autoplay; $autoplay="off"; //force off from second video on ?>"
									data-nextslideatend="true"
									data-volume="100" data-forcerewind="on"
									style="z-index: 5;padding:0 0 0 0;border-radius:0 0 0 0;">
								</div>
							</li>
						<?php endforeach; ?>
						</ul>
						<div class="tp-bannertimer tp-bottom" style="visibility: hidden !important;"></div>
					</div>
				</div><!-- ENF OF SLIDER WRAPPER -->



			<div class="content-wrap">
				<div class="container clearfix">

					<a href="/" class="btn btn-secondary btn-lg btn-block mx-auto" style="max-width: 20rem;"><i class="icon-line-arrow-left mr-2" style="position: relative; top: 1px;"></i> Back to Home Page</a>

				</div>
			</div>
            
			</div>
		</section>

		<!-- Content
		============================================= -->
		

	
