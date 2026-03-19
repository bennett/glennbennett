<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<?php include VIEWPATH . 'admin/includes/header.php'; ?>

<section class="content-header">
    <h1>Photo Position Defaults <small><?php echo htmlspecialchars($photo->original_name) ?></small></h1>
</section>

<section class="content">

    <?php if ( ! $preview_bg): ?>
        <div class="callout callout-warning">
            <p>Upload a background first to preview photo positioning.</p>
        </div>
    <?php else: ?>

    <div class="row">
        <!-- Preview -->
        <div class="col-md-7">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-eye"></i> Preview</h3>
                </div>
                <div class="box-body text-center">
                    <img id="previewImage"
                         src="<?php echo site_url('admin/preview_template_photo/' . $photo->id) ?>"
                         style="max-width: 100%; border: 1px solid #ddd;">
                </div>
            </div>
        </div>

        <!-- Controls -->
        <div class="col-md-5">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-user"></i> Default Position</h3>
                </div>
                <div class="box-body">
                    <p class="text-muted" style="font-size: 12px;">These defaults are applied to new templates using this photo.</p>

                    <div class="form-group">
                        <label>X Position: <span id="val_photo_x"><?php echo $photo->photo_x ?></span>px</label>
                        <input type="range" id="photo_x" min="-500" max="1500" value="<?php echo $photo->photo_x ?>" class="layout-range" style="width:100%">
                    </div>
                    <div class="form-group">
                        <label>Y Position: <span id="val_photo_y"><?php echo $photo->photo_y ?></span>px</label>
                        <input type="range" id="photo_y" min="-500" max="1500" value="<?php echo $photo->photo_y ?>" class="layout-range" style="width:100%">
                    </div>
                    <div class="form-group">
                        <label>Scale: <span id="val_photo_scale"><?php echo $photo->photo_scale ?></span>%</label>
                        <input type="range" id="photo_scale" min="10" max="300" value="<?php echo $photo->photo_scale ?>" class="layout-range" style="width:100%">
                    </div>
                    <div class="form-group">
                        <label>Photo Glow: <span id="val_photo_glow_radius"><?php echo $photo->photo_glow_radius ?></span>px</label>
                        <div style="display: flex; align-items: center; gap: 10px;">
                            <input type="range" id="photo_glow_radius" min="0" max="30" value="<?php echo $photo->photo_glow_radius ?>" class="layout-range" style="flex: 1;">
                            <input type="color" id="photo_glow_color" value="<?php echo $photo->photo_glow_color ?: '#000000' ?>" class="layout-color" style="width: 40px; height: 30px; padding: 2px; cursor: pointer;">
                        </div>
                    </div>

                    <hr>
                    <button id="saveDefaults" class="btn btn-success btn-lg btn-block">
                        <i class="fa fa-save"></i> Save Defaults
                    </button>
                    <div id="saveStatus" style="margin-top: 10px;"></div>
                    <hr>
                    <a href="<?php echo site_url('admin/template_photos') ?>" class="btn btn-default btn-block">
                        <i class="fa fa-arrow-left"></i> Back to Photos
                    </a>
                </div>
            </div>
        </div>
    </div>

    <?php endif; ?>

</section>

<script>
$(document).ready(function() {
    var previewUrl = '<?php echo site_url("admin/preview_template_photo/" . $photo->id) ?>';
    var debounceTimer = null;

    function getVal(id) {
        return parseInt($('#' + id).val());
    }

    function refreshPreview() {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(function() {
            var src = previewUrl
                + '?photo_x=' + getVal('photo_x')
                + '&photo_y=' + getVal('photo_y')
                + '&photo_scale=' + getVal('photo_scale')
                + '&photo_glow_radius=' + getVal('photo_glow_radius')
                + '&photo_glow_color=' + encodeURIComponent($('#photo_glow_color').val())
                + '&t=' + Date.now();
            $('#previewImage').attr('src', src);
        }, 300);
    }

    $('.layout-range').on('input change', function() {
        $('#val_' + $(this).attr('id')).text($(this).val());
        refreshPreview();
    });

    $('.layout-color').on('input change', function() {
        refreshPreview();
    });

    $('#saveDefaults').click(function() {
        var data = {
            photo_id:           <?php echo $photo->id ?>,
            photo_x:            getVal('photo_x'),
            photo_y:            getVal('photo_y'),
            photo_scale:        getVal('photo_scale'),
            photo_glow_radius:  getVal('photo_glow_radius'),
            photo_glow_color:   $('#photo_glow_color').val()
        };

        $.post('<?php echo site_url("admin/save_template_photo_defaults") ?>', data, function() {
            $('#saveStatus').html('<div class="alert alert-success">Defaults saved!</div>');
            setTimeout(function() { $('#saveStatus').html(''); }, 3000);
        }).fail(function() {
            $('#saveStatus').html('<div class="alert alert-danger">Save failed.</div>');
        });
    });
});
</script>

<?php include VIEWPATH . 'admin/includes/footer.php'; ?>
