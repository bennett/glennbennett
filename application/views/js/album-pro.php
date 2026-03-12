<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/jquery-ui.min.js"></script>
<script src="/player/js/jquery.mousewheel.min.js" type="text/javascript"></script>
<script src="/player/js/jquery.touchSwipe.min.js" type="text/javascript"></script>
<script src="/player/js/audio2_html5.js" type="text/javascript"></script>
<!-- must have -->





	<script>
		jQuery(function() {
				setTimeout(function(){
							jQuery('#audio2_html5_black').audio2_html5({
								skin: 'blackControllers',
                                
                facebookAppID:'501470923386393',  
                facebookShareTitle: 'Glenn Bennett - Live at The Guitar Merchant',
                facebookShareDescription: 'January 22, 2016. Listen to the full live concert for FREE!',              
                                
								autoPlay:false,
								initialVolume:0.5,
								showRewindBut:true,
								showShuffleBut:false,
								showDownloadBut:true,
								showFacebookBut:true,
								showTwitterBut:true,
								responsive:true,
								shuffle:false,
								playerBg: '#FFFFFF',
								bufferEmptyColor: '#737373',
								bufferFullColor: '#bababa',
								seekbarColor: '#000000',
								volumeOffColor: '#bababa',
								volumeOnColor: '#000000',
								timerColor: '#000000',
								songTitleColor: '#000000',
								songAuthorColor: '#888888',
								bordersDivColor: '#cccccc',
								playlistTopPos:0,
								playlistBgColor:'#FFFFFF',
								playlistRecordBgOffColor:'#FFFFFF',
								playlistRecordBgOnColor:'#FFFFFF',
								playlistRecordBottomBorderOffColor:'#cccccc',
								playlistRecordBottomBorderOnColor:'#8d8d8d',
								playlistRecordTextOffColor:'#4286f4',
								playlistRecordTextOnColor:'#000000',
                                
								categoryRecordBgOffColor:'#111111',
								categoryRecordBgOnColor:'#111111',
								categoryRecordBottomBorderOffColor:'#2f2f2f',
								categoryRecordBottomBorderOnColor:'#2f2f2f',
								categoryRecordTextOffColor:'#b4cdf7',
								categoryRecordTextOnColor:'#000000',
                                
								selectedCategBg: '#c7c7c7',
								selectedCategOffColor: '#333333',
								selectedCategOnColor: '#f90000',
								selectedCategMarginBottom:12,
                                
								searchAreaBg: '#c7c7c7',
								searchInputBg:'#FFFFFF',
								searchInputBorderColor:'#c7c7c7',
								searchInputTextColor:'#333333'
							});
					}, 500);
		});
	</script>