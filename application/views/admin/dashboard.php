<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<?php include VIEWPATH . 'admin/includes/header.php'; ?>

<section class="content-header">
    <h1>Dashboard <small><?php echo $page->title ?></small></h1>
</section>

<section class="content">

    <div class="row">
        <div class="col-md-4 col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-aqua"><i class="fa fa-image"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Total Images</span>
                    <span class="info-box-number"><?php echo $image_count ?></span>
                </div>
            </div>
        </div>

        <div class="col-md-4 col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-green"><i class="fa fa-check-circle"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Active Images</span>
                    <span class="info-box-number"><?php echo $active_image_count ?></span>
                </div>
            </div>
        </div>

        <div class="col-md-4 col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-yellow"><i class="fa fa-map-marker"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Venues</span>
                    <span class="info-box-number"><?php echo $venue_count ?></span>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="box box-primary box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-image"></i> Calendar Images</h3>
                </div>
                <div class="box-body">
                    <p>Manage background images for Facebook calendar event shares.</p>
                    <a href="<?php echo site_url('admin/images') ?>" class="btn btn-primary">Manage Images</a>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="box box-warning box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-map-marker"></i> Venues</h3>
                </div>
                <div class="box-body">
                    <p>Manage venue logos and image assignments for calendar listings.</p>
                    <a href="<?php echo site_url('admin/venues') ?>" class="btn btn-warning">Manage Venues</a>
                </div>
            </div>
        </div>
    </div>

</section>

<?php include VIEWPATH . 'admin/includes/footer.php'; ?>
