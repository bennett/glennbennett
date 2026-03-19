<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<?php include VIEWPATH . 'admin/includes/header.php'; ?>

<section class="content-header">
    <h1>Template Photos <small>Manage PNG cutouts for share templates</small></h1>
</section>

<section class="content">

    <!-- Upload Form -->
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-upload"></i> Upload New Photo</h3>
        </div>
        <div class="box-body">
            <?php echo form_open_multipart('admin/upload_template_photo'); ?>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="image_file">Select PNG (transparent cutout, max 5MB)</label>
                            <input type="file" name="image_file" id="image_file" class="form-control" accept=".png" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>&nbsp;</label><br>
                            <button type="submit" class="btn btn-primary"><i class="fa fa-upload"></i> Upload</button>
                        </div>
                    </div>
                </div>
            <?php echo form_close(); ?>
        </div>
    </div>

    <!-- Grid -->
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-th"></i> Photos (<?php echo count($photos) ?>)</h3>
        </div>
        <div class="box-body">
            <?php if (empty($photos)): ?>
                <p class="text-muted">No photos uploaded yet.</p>
            <?php else: ?>
                <div class="row">
                    <?php foreach ($photos as $photo): ?>
                        <div class="col-md-3 col-sm-4 col-xs-6" style="margin-bottom: 20px;">
                            <div class="thumbnail" style="position: relative;">
                                <div style="background: repeating-conic-gradient(#ccc 0% 25%, #fff 0% 50%) 50% / 20px 20px; min-height: 150px; display: flex; align-items: center; justify-content: center;">
                                    <img src="<?php echo base_url('imgs/template-photos/' . $photo->filename) ?>"
                                         alt="<?php echo htmlspecialchars($photo->original_name) ?>"
                                         style="max-width: 100%; max-height: 200px;">
                                </div>

                                <div class="caption">
                                    <p style="margin-bottom: 5px;">
                                        <strong><?php echo htmlspecialchars($photo->original_name) ?></strong><br>
                                        <span class="text-muted" style="font-size: 11px;"><?php echo $photo->width ?>x<?php echo $photo->height ?></span>
                                    </p>

                                    <div style="margin-top: 10px;">
                                        <a href="<?php echo site_url('admin/template_photo_defaults/' . $photo->id) ?>"
                                           class="btn btn-xs btn-info" title="Edit Position Defaults">
                                            <i class="fa fa-arrows"></i> Defaults
                                        </a>

                                        <button class="btn btn-xs toggle-btn <?php echo $photo->is_active ? 'btn-success' : 'btn-default' ?>"
                                                data-id="<?php echo $photo->id ?>"
                                                title="Toggle Active">
                                            <i class="fa <?php echo $photo->is_active ? 'fa-check-circle' : 'fa-circle-o' ?>"></i>
                                            <?php echo $photo->is_active ? 'Active' : 'Inactive' ?>
                                        </button>

                                        <a href="<?php echo site_url('admin/delete_template_photo/' . $photo->id) ?>"
                                           class="btn btn-xs btn-danger"
                                           onclick="return confirm('Delete this photo? All templates using it will also be deleted.')"
                                           title="Delete">
                                            <i class="fa fa-trash"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

</section>

<script>
$(document).ready(function() {
    $('.toggle-btn').click(function() {
        var $btn = $(this);
        var id = $btn.data('id');

        $.ajax({
            url: '<?php echo site_url("admin/toggle_template_photo/") ?>' + id,
            type: 'POST',
            dataType: 'json',
            success: function(resp) {
                if (resp.status === 'ok') {
                    if (resp.is_active) {
                        $btn.removeClass('btn-default').addClass('btn-success');
                        $btn.html('<i class="fa fa-check-circle"></i> Active');
                    } else {
                        $btn.removeClass('btn-success').addClass('btn-default');
                        $btn.html('<i class="fa fa-circle-o"></i> Inactive');
                    }
                }
            }
        });
    });
});
</script>

<?php include VIEWPATH . 'admin/includes/footer.php'; ?>
