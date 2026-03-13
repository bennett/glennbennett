<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<?php include VIEWPATH . 'admin/includes/header.php'; ?>

<section class="content-header">
    <h1>Calendar Images <small>Manage background images</small></h1>
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
                                    <p class="text-muted" style="font-size: 11px; margin-bottom: 5px;">
                                        <?php echo htmlspecialchars($image->original_name) ?><br>
                                        <?php echo $image->width ?>x<?php echo $image->height ?>
                                    </p>

                                    <?php if ($image->is_active): ?>
                                        <span class="label label-success">Active</span>
                                    <?php else: ?>
                                        <span class="label label-default">Inactive</span>
                                    <?php endif; ?>

                                    <div style="margin-top: 10px;">
                                        <a href="<?php echo site_url('admin/image_layout/' . $image->id) ?>"
                                           class="btn btn-xs btn-info" title="Edit Layout">
                                            <i class="fa fa-sliders"></i> Layout
                                        </a>

                                        <a href="<?php echo site_url('admin/toggle_image/' . $image->id) ?>"
                                           class="btn btn-xs btn-warning" title="Toggle Active">
                                            <i class="fa fa-power-off"></i>
                                        </a>

                                        <a href="<?php echo site_url('admin/delete_image/' . $image->id) ?>"
                                           class="btn btn-xs btn-danger"
                                           onclick="return confirm('Delete this image?')"
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

<?php include VIEWPATH . 'admin/includes/footer.php'; ?>
