
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<script>
$(document).ready(function(){

    $("#gcal").load("/gcal/gcal-gigs.php"+location.search);

});

</script>

<div class="content-wrap">
				<div class="container clearfix">

					<!-- Posts
					============================================= -->
					<div id="posts" class="post-grid row grid-container gutter-50 has-init-isotope" style="position: relative; height: 3061.53px;">
<div class="entry col-sm-6 col-12">
<h2>Performances</h2>



<div id="spinner" class="container">
    <img src="/gcal/images/gmail.gif" class="img-fluid center-block d-block mx-auto" alt="gmail">
</div>

<div id="gcal">
</div>

<hr>
<h2>Previous Performances</h2>
<a href="past">
List of Pervious Performances
</a><hr>

<!-- iframe src="https://calendar.google.com/calendar/embed?height=600&amp;wkst=1&amp;bgcolor=%23ffffff&amp;ctz=America%2FLos_Angeles&amp;src=Y184b3F0OWU3Ym1zMXNlZnNrcjBmbDAxcjd0Z0Bncm91cC5jYWxlbmRhci5nb29nbGUuY29t&amp;color=%23616161&amp;showNav=0&amp;showDate=1&amp;showTabs=0&amp;showCalendars=0&amp;mode=AGENDA" style="border:solid 1px #777" width="800" height="600" frameborder="0" scrolling="no"></iframe-->
<img src="https://s3-media0.fl.yelpcdn.com/bphoto/Wmb8V70TiJ7BA7WgBqOdpw/l.jpg" class="img-fluid" alt="Responsive image">
<p>Performaning at the Moorpark Alley outside of the Enegren Brewing Company</p>
</div>


<div class="entry col-sm-6 col-12">

<!--#include file="../notes/cal/note1.txt"-->

<!--h2>Notes</h2>
<div class="card mb-2">
<div class="card-header">Moorpark Performance - Note: Nov. 24, 2024</div>
<div class="card-body">
<h4 class="card-title">I'm Pausing Sunday Performances at the Kohl's Shopping Center</h4>
<p class="card-text">
    I wanted to let you know about a change in my upcoming Sunday performances. 
    I've decided to take a break from my Sunday performances for a while, 
    at least until the spring. 

</p>
</div>
</div-->

  <h2>Join My Mailing List</h2>
 
  
<p>Keep up to date. If my schedule changes unexpectedly, I usually send out a notice.</p>
<!-- Send in Blue-->
<a href="<?php echo $subscribe_link; ?>"><button type="button" class="btn btn-primary">Subscribe to my newsletter</button></a>
<!-- Send in Blue End -->
<hr>

<h2>Check Local Weather</h2>

<!-- Steart Now Weather>
<div class="weather-widget">
    <div class="widget-content">
        <?php if (!empty($weather_results)): ?>
            <div class="weather-grid">
                <?php foreach ($weather_results as $city_data): ?>
                    <div class="weather-column">
                        <div class="weather-location">
                            <div class="location-name">
                            
                                <?php echo html_escape($city_data['city_name']); ?>
                            </div>
                            <div class="weather-row">
                                <div class="weather-label">
                                    <i class="icon-temperature3"></i>
                                    High
                                </div>
                                <div class="weather-value">
                                    <?php echo $city_data['High_Temp']; ?>
                                </div>
                            </div>
                            <?php if ($city_data['Wind_Speed'] !== 'N/A' && (int)$city_data['Wind_Speed'] >= 10): ?>
                                <div class="weather-row">
                                    <div class="weather-label">
                                        <i class="icon-wind"></i>
                                        Wind Speed
                                    </div>
                                    <div class="weather-value">
                                        <?php echo html_escape($city_data['Wind_Speed']); ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <?php if ($city_data['Rain_Chance'] !== 'N/A' && (int)str_replace('%', '', $city_data['Rain_Chance']) >= 20): ?>
                                <div class="weather-row">
                                    <div class="weather-label">
                                        <i class="icon-rain"></i>
                                        Rain Chance
                                    </div>
                                    <div class="weather-value">
                                        <?php echo html_escape($city_data['Rain_Chance']); ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="alert">
                Weather data currently unavailable.
            </div>
        <?php endif; ?>
    </div>
</div-->



<!-- End Now Weather-->

    


<!--a class="weatherwidget-io" href="https://forecast7.com/en/34d29n118d88/moorpark/?unit=us" data-label_1="MOORPARK" data-label_2="WEATHER" data-theme="original" >MOORPARK WEATHER</a>
<!-- script>
!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src='https://weatherwidget.io/js/widget.min.js';fjs.parentNode.insertBefore(js,fjs);}}(document,'script','weatherwidget-io-js');
</script -->

<!-- Old wheather
        <script>
        (function(d, s, id) {
            if (d.getElementById(id)) {
                if (window.__TOMORROW__) {
                    window.__TOMORROW__.renderWidget();
                }
                return;
            }
            const fjs = d.getElementsByTagName(s)[0];
            const js = d.createElement(s);
            js.id = id;
            js.src = "https://www.tomorrow.io/v1/widget/sdk/sdk.bundle.min.js";

            fjs.parentNode.insertBefore(js, fjs);
        })(document, 'script', 'tomorrow-sdk');
        </script>

        <!-- all cities data-location-id="122213,125920,126445,128175,113242,127609" -->
        <!-- 
        <div class="tomorrow"
           
           data-location-id="122213,125920,126445"
           data-language="EN"
           data-unit-system="IMPERIAL"
           data-skin="light"
           data-widget-type="current6"
           style="padding-bottom:22px;position:relative;"
        >
          <a
            href="https://www.tomorrow.io/weather-api/"
            rel="nofollow noopener noreferrer"
            target="_blank"
            style="position: absolute; bottom: 0; transform: translateX(-50%); left: 50%;"
          >
            <img
              alt="Powered by the Tomorrow.io Weather API"
              src="https://weather-website-client.tomorrow.io/img/powered-by.svg"
              width="250"
              height="18"
            />
          </a>
        </div>
        
 End Old wheather -->        
<p>If I'm playing outside it might caneled due to weather. So make sure the sun is shining and below 95&deg; before you head out on those days.</p>

</div>


					</div><!-- #posts end -->





				</div>
			</div>
