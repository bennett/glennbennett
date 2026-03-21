<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<?php include VIEWPATH . 'admin/includes/header.php'; ?>

<section class="content-header">
    <h1>Legacy Calendar Images <small>Old background images (kept for existing links)</small></h1>
</section>

<section class="content">

    <!-- Upload Form -->
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-upload"></i> Upload New Image</h3>
        </div>
        <div class="box-body">
            <?php echo form_open_multipart('admin/upload_image'); ?>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="image_file">Select Image (JPG/PNG, max 5MB)</label>
                            <input type="file" name="image_file" id="image_file" class="form-control" accept=".jpg,.jpeg,.png" required>
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

    <!-- Image Grid -->
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-th"></i> Image Library (<?php echo count($images) ?>)</h3>
        </div>
        <div class="box-body">
            <?php if (empty($images)): ?>
                <p class="text-muted">No images uploaded yet.</p>
            <?php else: ?>
                <div class="row">
                    <?php foreach ($images as $image): ?>
                        <div class="col-md-6 col-sm-6 col-xs-12" style="margin-bottom: 20px;">
                            <div class="thumbnail" style="position: relative;">
                                <img src="<?php echo site_url('admin/preview_image/' . $image->id) ?>"
                                     alt="<?php echo htmlspecialchars($image->original_name) ?>"
                                     style="width: 100%; height: auto;">

                                <div class="caption">
                                    <p style="margin-bottom: 5px;">
                                        <strong style="font-size: 14px;"><?php echo htmlspecialchars($image->original_name) ?></strong><br>
                                        <span class="text-muted" style="font-size: 11px;"><?php echo $image->width ?>x<?php echo $image->height ?></span>
                                    </p>

                                    <div style="margin-top: 10px;">
                                        <a href="<?php echo site_url('admin/image_layout/' . $image->id) ?>"
                                           class="btn btn-sm btn-info">
                                            <i class="fa fa-sliders"></i> Customize
                                        </a>

                                        <button class="btn btn-sm toggle-btn <?php echo $image->is_active ? 'btn-success' : 'btn-default' ?>"
                                                data-id="<?php echo $image->id ?>">
                                            <i class="fa <?php echo $image->is_active ? 'fa-check-circle' : 'fa-circle-o' ?>"></i>
                                            <?php echo $image->is_active ? 'Active' : 'Inactive' ?>
                                        </button>

                                        <a href="<?php echo site_url('admin/delete_image/' . $image->id) ?>"
                                           class="btn btn-sm btn-danger"
                                           onclick="return confirm('Delete this image?')">
                                            <i class="fa fa-trash"></i> Delete
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
            url: '<?php echo site_url("admin/toggle_image/") ?>' + id,
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
