<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<?php include VIEWPATH . 'admin/includes/header.php'; ?>

<section class="content-header">
    <h1>Text Layout Editor <small><?php echo htmlspecialchars($image->original_name) ?></small></h1>
</section>

<section class="content">

    <div class="row">
        <!-- GD Preview -->
        <div class="col-md-7">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-eye"></i> Preview</h3>
                </div>
                <div class="box-body text-center">
                    <img id="previewImage"
                         src="<?php echo site_url('admin/preview_image/' . $image->id) ?>"
                         style="max-width: 100%; border: 1px solid #ddd;">
                    <div id="loadingOverlay" style="display: none; padding: 10px;">
                        <i class="fa fa-spinner fa-spin"></i> Rendering...
                    </div>
                    <br><br>
                    <a href="<?php echo site_url('admin/images') ?>" class="btn btn-default">
                        <i class="fa fa-arrow-left"></i> Back to Images
                    </a>
                </div>
            </div>
        </div>

        <!-- Slider Controls -->
        <div class="col-md-5">
            <div class="box box-warning">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-sliders"></i> Layout Controls</h3>
                </div>
                <div class="box-body">
                    <div class="form-group">
                        <label>Text Offset (horizontal shift): <span id="val_text_offset"><?php echo $layout->text_offset ?></span>px</label>
                        <input type="range" id="text_offset" min="-400" max="400" value="<?php echo $layout->text_offset ?>" class="form-control slider">
                    </div>

                    <hr>
                    <h4>Summary</h4>
                    <div class="form-group">
                        <label>Font Size: <span id="val_summary_font_size"><?php echo $layout->summary_font_size ?></span>px</label>
                        <input type="range" id="summary_font_size" min="12" max="72" value="<?php echo $layout->summary_font_size ?>" class="form-control slider">
                    </div>
                    <div class="form-group">
                        <label>Margin Top (Y start): <span id="val_summary_margin_top"><?php echo $layout->summary_margin_top ?></span>px</label>
                        <input type="range" id="summary_margin_top" min="20" max="500" value="<?php echo $layout->summary_margin_top ?>" class="form-control slider">
                    </div>

                    <hr>
                    <h4>Date</h4>
                    <div class="form-group">
                        <label>Font Size: <span id="val_date_font_size"><?php echo $layout->date_font_size ?></span>px</label>
                        <input type="range" id="date_font_size" min="12" max="72" value="<?php echo $layout->date_font_size ?>" class="form-control slider">
                    </div>
                    <div class="form-group">
                        <label>Margin Top: <span id="val_date_margin_top"><?php echo $layout->date_margin_top ?></span>px</label>
                        <input type="range" id="date_margin_top" min="0" max="100" value="<?php echo $layout->date_margin_top ?>" class="form-control slider">
                    </div>

                    <hr>
                    <h4>Time</h4>
                    <div class="form-group">
                        <label>Font Size: <span id="val_time_font_size"><?php echo $layout->time_font_size ?></span>px</label>
                        <input type="range" id="time_font_size" min="12" max="72" value="<?php echo $layout->time_font_size ?>" class="form-control slider">
                    </div>
                    <div class="form-group">
                        <label>Margin Top: <span id="val_time_margin_top"><?php echo $layout->time_margin_top ?></span>px</label>
                        <input type="range" id="time_margin_top" min="0" max="100" value="<?php echo $layout->time_margin_top ?>" class="form-control slider">
                    </div>

                    <hr>
                    <h4>Location</h4>
                    <div class="form-group">
                        <label>Font Size: <span id="val_location_font_size"><?php echo $layout->location_font_size ?></span>px</label>
                        <input type="range" id="location_font_size" min="12" max="72" value="<?php echo $layout->location_font_size ?>" class="form-control slider">
                    </div>
                    <div class="form-group">
                        <label>Margin Top: <span id="val_location_margin_top"><?php echo $layout->location_margin_top ?></span>px</label>
                        <input type="range" id="location_margin_top" min="0" max="100" value="<?php echo $layout->location_margin_top ?>" class="form-control slider">
                    </div>

                    <hr>
                    <button id="saveLayout" class="btn btn-success btn-lg btn-block">
                        <i class="fa fa-save"></i> Save & Preview
                    </button>
                    <div id="saveStatus" style="margin-top: 10px;"></div>
                </div>
            </div>
        </div>
    </div>

</section>

<script>
$(document).ready(function() {
    function getVal(id) {
        return parseInt($('#' + id).val());
    }

    // Update label on slider change
    $('.slider').on('input', function() {
        var id = $(this).attr('id');
        $('#val_' + id).text($(this).val());
    });

    // Save via AJAX then reload the GD preview
    $('#saveLayout').click(function() {
        var data = {
            cal_image_id: <?php echo $image->id ?>,
            text_offset: getVal('text_offset'),
            summary_font_size: getVal('summary_font_size'),
            summary_margin_top: getVal('summary_margin_top'),
            date_font_size: getVal('date_font_size'),
            date_margin_top: getVal('date_margin_top'),
            time_font_size: getVal('time_font_size'),
            time_margin_top: getVal('time_margin_top'),
            location_font_size: getVal('location_font_size'),
            location_margin_top: getVal('location_margin_top')
        };

        $('#loadingOverlay').show();

        $.post('<?php echo site_url("admin/save_layout") ?>', data, function(resp) {
            // Reload preview image from server (cache-bust with timestamp)
            $('#previewImage').attr('src', '<?php echo site_url("admin/preview_image/" . $image->id) ?>?t=' + Date.now());
            $('#saveStatus').html('<div class="alert alert-success">Layout saved!</div>');
            setTimeout(function() { $('#saveStatus').html(''); }, 3000);
        }).fail(function() {
            $('#saveStatus').html('<div class="alert alert-danger">Save failed.</div>');
        }).always(function() {
            $('#loadingOverlay').hide();
        });
    });
});
</script>

<?php include VIEWPATH . 'admin/includes/footer.php'; ?>
