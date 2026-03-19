<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<?php include VIEWPATH . 'admin/includes/header.php'; ?>

<section class="content-header">
    <h1>Template Backgrounds <small>Manage background images for share templates</small></h1>
</section>

<section class="content">

    <!-- Upload Form -->
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-upload"></i> Upload New Background</h3>
        </div>
        <div class="box-body">
            <?php echo form_open_multipart('admin/upload_template_background'); ?>
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

    <!-- Grid -->
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-th"></i> Backgrounds (<?php echo count($backgrounds) ?>)</h3>
        </div>
        <div class="box-body">
            <?php if (empty($backgrounds)): ?>
                <p class="text-muted">No backgrounds uploaded yet.</p>
            <?php else: ?>
                <div class="row">
                    <?php foreach ($backgrounds as $bg): ?>
                        <div class="col-md-4 col-sm-6 col-xs-12" style="margin-bottom: 20px;">
                            <div class="thumbnail" style="position: relative;">
                                <img src="<?php echo base_url('imgs/template-backgrounds/' . $bg->filename) ?>"
                                     alt="<?php echo htmlspecialchars($bg->original_name) ?>"
                                     style="width: 100%; height: auto;">

                                <div class="caption">
                                    <p style="margin-bottom: 5px;">
                                        <strong><?php echo htmlspecialchars($bg->original_name) ?></strong><br>
                                        <span class="text-muted" style="font-size: 11px;"><?php echo $bg->width ?>x<?php echo $bg->height ?></span>
                                    </p>

                                    <div style="margin-top: 10px;">
                                        <a href="<?php echo site_url('admin/template_background_defaults/' . $bg->id) ?>"
                                           class="btn btn-xs btn-info" title="Edit Text Defaults">
                                            <i class="fa fa-font"></i> Defaults
                                        </a>

                                        <button class="btn btn-xs toggle-btn <?php echo $bg->is_active ? 'btn-success' : 'btn-default' ?>"
                                                data-id="<?php echo $bg->id ?>"
                                                title="Toggle Active">
                                            <i class="fa <?php echo $bg->is_active ? 'fa-check-circle' : 'fa-circle-o' ?>"></i>
                                            <?php echo $bg->is_active ? 'Active' : 'Inactive' ?>
                                        </button>

                                        <a href="<?php echo site_url('admin/delete_template_background/' . $bg->id) ?>"
                                           class="btn btn-xs btn-danger"
                                           onclick="return confirm('Delete this background? All templates using it will also be deleted.')"
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
            url: '<?php echo site_url("admin/toggle_template_background/") ?>' + id,
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
