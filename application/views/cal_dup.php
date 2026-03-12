
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<script>
$(document).ready(function(){

    $("#gcal").load("/gcal/gcal-gigs-dup.php"+location.search);

});

</script>

<div class="content-wrap">
				<div class="container clearfix">

					<!-- Posts
					============================================= -->
					<div id="posts" class="post-grid row grid-container gutter-50 has-init-isotope" style="position: relative; height: 3061.53px;">
<div class="entry col-sm-6 col-12" style="position: absolute; left: 50%; top: 0px;">
<h2>Create Duplicate Performances</h2>



<div id="spinner" class="container">
    <img src="/gcal/images/gmail.gif" class="img-fluid center-block d-block mx-auto" alt="gmail">
</div>

<div id="gcal">
</div>

<hr>
<a href='/cal'>
Upcoming Performances
</a>

</div>


<div class="entry col-sm-6 col-12" style="position: absolute; left: 50%; top: 0px;">


  <h2>Join My Mailing List</h2>
 
  
<p>Keep up to date. If my schedule changes unexpectedly, I usually send out a notice.</p>
<!-- Send in Blue-->
<a target="_blank" href="<?php echo $contact_link; ?>"><button type="button" class="btn btn-primary">Subscribe to my newsletter</button></a>
<!-- Send in Blue End -->
<hr>
<h2>Local Weather</h2>


<a class="weatherwidget-io" href="https://forecast7.com/en/34d29n118d88/moorpark/?unit=us" data-label_1="MOORPARK" data-label_2="WEATHER" data-theme="original" >MOORPARK WEATHER</a>
<script>
!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src='https://weatherwidget.io/js/widget.min.js';fjs.parentNode.insertBefore(js,fjs);}}(document,'script','weatherwidget-io-js');
</script>
If I'm playing outside it might caneled due to weather. So make sure the sun is shining before you head out on those days.

</div>


					</div><!-- #posts end -->





				</div>
			</div>
