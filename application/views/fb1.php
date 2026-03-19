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
                <h2>Performs</h2><hr>
                <div class="text-center">
                    <h1 text-center><?php echo $summary; ?></h1>
                    <strong text-center><?php echo $description; ?></strong><br>
                    <h4 class="mb-0" text-center><?php echo $date; ?></h4>
                    <h3 class="mb-0"><?php echo $time; ?></h3>
                    <h4 class="mb-0"><?php echo $date_diff; ?></h4>
                    
                     <strong text-center><?php echo $location; ?></strong><hr>
                    
                    <img src="https://glennbennett.com/cal-image/<?php echo $share_hash; ?>" alt="Glenn Bennett Performs">

                </div>
            </div>

            <div class="entry col-sm-6 col-12">
                <h2>Join My Mailing List</h2>
                <hr>
                <p>Keep up to date.</p>
                <!-- subscribe_link-->
                <a target="_blank" href="<?php echo isset($subscribe_link) ? $subscribe_link : '#'; ?>">
                    <button type="button" class="btn btn-primary">Subscribe to my newsletter</button>
                </a>
                <!-- subscribe_link End -->
                <hr>
                <h2>Contact Me</h2>
                <hr>
                <!-- Send in contact_link-->
                <p>Need more information.</p>
                <a target="_blank" href="<?php echo isset($contact_link) ? $contact_link : '#'; ?>">
                    <button type="button" class="btn btn-primary">Contact Me</button>
                </a>
                <!-- Send in contact_link End -->
                
                <hr>
                <h2>Upcoming Performances</h2>
                <div id="spinner" class="container">
                    <img src="/gcal/images/gmail.gif" class="img-fluid center-block d-block mx-auto" alt="gmail">
                </div>

                <div id="gcal">
                </div>

                <hr>
                <div class="entry-content">
                    <h2>Social Media</h2>
                    <hr>
                    
                    <a href="https://www.instagram.com/glennlbennett/" class="social-icon si-large si-colored si-instagram" title="Instagram">
                        <i class="icon-instagram"></i>
                        <i class="icon-instagram"></i>
                    </a>
                    
                    <a href="https://www.facebook.com/GlennBennettcom" class="social-icon si-large si-colored si-facebook" title="Facebook">
                        <i class="icon-facebook"></i>
                        <i class="icon-facebook"></i>
                    </a>
                    
                    <a href="https://twitter.com/glennbennett" class="social-icon si-large si-colored si-twitter" title="Twitter">
                        <i class="icon-twitter"></i>
                        <i class="icon-twitter"></i>
                    </a>
                </div>
            </div>
        </div><!-- #posts end -->
    </div>
</div>
