	<!-- SLIDER REVOLUTION 5.x SCRIPTS  -->
	<script src="/include/rs-plugin/js/jquery.themepunch.tools.min.js"></script>
	<script src="/include/rs-plugin/js/jquery.themepunch.revolution.min.js"></script>
	<script>
		var revapi21;
		var $ = jQuery.noConflict();

		jQuery(document).ready(function() {
			if(jQuery("#rev_slider").revolution == undefined){
				revslider_showDoubleJqueryError("#rev_slider");
			}else{
				revapi21 = jQuery("#rev_slider").show().revolution({
					sliderType:"standard",
					sliderLayout:"auto",
					dottedOverlay:"none",
					delay:9000,
					navigation: {
						keyboardNavigation:"off",
						keyboard_direction: "horizontal",
						mouseScrollNavigation:"off",
						onHoverStop:"off",
						arrows: {
							style:"uranus",
							enable:true,
							hide_onmobile:true,
							hide_under:778,
							hide_onleave:true,
							hide_delay:200,
							hide_delay_mobile:1200,
							tmp:'',
							left: {
								h_align:"left",
								v_align:"center",
								h_offset:20,
								v_offset:0
							},
							right: {
								h_align:"right",
								v_align:"center",
								h_offset:20,
								v_offset:0
							}
						}
						,
						thumbnails: {
							style:"erinyen",
							enable:true,
							width:200,
							height:113,
							min_width:170,
							wrapper_padding:30,
							wrapper_color:"#333333",
							wrapper_opacity:"1",
							tmp:'<span class="tp-thumb-over"></span><span class="tp-thumb-image"></span><span class="tp-thumb-title">{{title}}</span><span class="tp-thumb-more"></span>',
							visibleAmount:10,
							hide_onmobile:false,
							hide_onleave:false,
							direction:"horizontal",
							span:true,
							position:"outer-top",
							space:20,
							h_align:"center",
							v_align:"top",
							h_offset:0,
							v_offset:0
						}
					},
					gridwidth:1230,
					gridheight:692,
					lazyType:"none",
					shadow:0,
					spinner:"spinner2",
					stopLoop:"on",
					stopAfterLoops:0,
					stopAtSlide:1,
					shuffle:"off",
					autoHeight:"off",
					disableProgressBar:"on",
					hideThumbsOnMobile:"off",
					hideSliderAtLimit:0,
					hideCaptionAtLimit:0,
					hideAllCaptionAtLilmit:0,
					startWithSlide:0,
					debugMode:false,
					fallbacks: {
						simplifyAll:"off",
						nextSlideOnWindowFocus:"off",
						disableFocusListener:"off",
					}
				});
			}
		});	/*ready*/
	</script>