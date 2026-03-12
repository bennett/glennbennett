<section id="content" class="mt-5">
    <div class="container">

        <?php if ($page_partial): ?>
            <?php echo $page_partial; ?>
        <?php endif; ?>

        <div class="heading-block border-0 mb-4">
            <h3><?php echo $vid_title; ?></h3>
            <span><?php echo $vid_sub_title; ?></span>
        </div>

        <!-- Video Grid -->
        <div class="row">
            <?php if (empty($videos)): ?>
                <div class="col-12"><p class="text-muted">Videos are temporarily unavailable. Please check back later.</p></div>
            <?php endif; ?>
            <?php foreach ($videos as $video):
                $vid_id    = $video->snippet->resourceId->videoId;
                $thumb     = isset($video->snippet->thumbnails->medium) 
                             ? $video->snippet->thumbnails->medium->url 
                             : '';
                $vid_title = htmlspecialchars($video->snippet->title);
            ?>
            <div class="col-12 col-sm-6 col-md-4 mb-4" id="wrap-<?php echo $vid_id; ?>">
                
                <!-- Thumbnail (shown by default) -->
                <div id="thumb-<?php echo $vid_id; ?>" 
                     class="card bg-dark text-white border-0 h-100" 
                     style="cursor:pointer;"
                     onclick="playVideo('<?php echo $vid_id; ?>', '<?php echo addslashes($vid_title); ?>')">
                    <img src="<?php echo $thumb; ?>" 
                         class="card-img" 
                         alt="<?php echo $vid_title; ?>"
                         style="opacity:0.85;">
                    <div class="card-img-overlay d-flex align-items-center justify-content-center">
                        <div style="background:rgba(0,0,0,0.5); border-radius:50%; width:54px; height:54px; 
                                    display:flex; align-items:center; justify-content:center;">
                            <i class="icon-play" style="color:#fff; font-size:22px; margin-left:4px;"></i>
                        </div>
                    </div>
                    <div class="card-footer border-0 bg-dark" style="font-size:0.85rem; padding:6px 8px;">
                        <?php echo $vid_title; ?>
                    </div>
                </div>

                <!-- Player (hidden by default, shown when clicked) -->
                <div id="player-<?php echo $vid_id; ?>" style="display:none;">
                    <div style="position:relative; padding-bottom:56.25%; height:0; overflow:hidden;">
                        <iframe id="iframe-<?php echo $vid_id; ?>"
                                src="" 
                                style="position:absolute; top:0; left:0; width:100%; height:100%;"
                                allowfullscreen 
                                allow="autoplay">
                        </iframe>
                    </div>
                    <div class="bg-dark p-2" style="font-size:0.85rem;">
                        <span class="text-white"><?php echo $vid_title; ?></span>
                        <button class="btn btn-sm btn-outline-secondary float-right" 
                                onclick="closeVideo('<?php echo $vid_id; ?>')">&times; Close</button>
                    </div>
                </div>

            </div>
            <?php endforeach; ?>
        </div>

        <div class="mt-4 mb-4">
            <a href="/" class="btn btn-secondary">
                <i class="icon-line-arrow-left mr-2"></i> Back to Home Page
            </a>
        </div>

    </div>
</section>

<script>
var currentVideo = null;

function playVideo(videoId, title) {
    // Close any currently playing video
    if (currentVideo && currentVideo !== videoId) {
        closeVideo(currentVideo);
    }
    currentVideo = videoId;

    document.getElementById('iframe-' + videoId).src = 
        'https://www.youtube.com/embed/' + videoId + '?autoplay=1&rel=0';
    document.getElementById('thumb-' + videoId).style.display = 'none';
    document.getElementById('player-' + videoId).style.display = 'block';
}

function closeVideo(videoId) {
    document.getElementById('iframe-' + videoId).src = '';
    document.getElementById('player-' + videoId).style.display = 'none';
    document.getElementById('thumb-' + videoId).style.display = 'block';
    currentVideo = null;
}
</script>