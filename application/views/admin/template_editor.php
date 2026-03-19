<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<?php include VIEWPATH . 'admin/includes/header.php'; ?>

<section class="content-header">
    <h1>Template Editor <small><?php echo htmlspecialchars($template->bg_name) ?> + <?php echo htmlspecialchars($template->photo_name) ?></small></h1>
</section>

<section class="content">

    <!-- Preview + Photo Position below it -->
    <div class="row">
        <div class="col-md-7">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-eye"></i> Preview</h3>
                </div>
                <div class="box-body text-center">
                    <img id="previewImage"
                         src="<?php echo site_url('admin/preview_template/' . $template->id) ?>"
                         style="max-width: 100%; border: 1px solid #ddd;">
                    <div id="loadingOverlay" style="display: none; padding: 10px;">
                        <i class="fa fa-spinner fa-spin"></i> Rendering...
                    </div>
                </div>
            </div>

            <!-- Photo Controls (under preview) -->
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-user"></i> Photo Position</h3>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-xs-8">
                            <div class="form-group">
                                <label>X Position: <span id="val_photo_x"><?php echo $template->photo_x ?></span>px</label>
                                <input type="range" id="photo_x" min="-500" max="1500" value="<?php echo $template->photo_x ?>" class="layout-range" style="width:100%">
                            </div>
                            <div class="form-group">
                                <label>Scale: <span id="val_photo_scale"><?php echo $template->photo_scale ?></span>%</label>
                                <input type="range" id="photo_scale" min="10" max="300" value="<?php echo $template->photo_scale ?>" class="layout-range" style="width:100%">
                            </div>
                        </div>
                        <div class="col-xs-4 text-center">
                            <label>Y: <span id="val_photo_y"><?php echo $template->photo_y ?></span>px</label>
                            <div style="display: flex; justify-content: center;">
                                <input type="range" id="photo_y" min="-500" max="1500" value="<?php echo $template->photo_y ?>" class="layout-range" orient="vertical" style="writing-mode: vertical-lr; height: 150px; width: 30px;">
                            </div>
                        </div>
                    </div>
                    <div class="form-group" style="margin-top: 15px;">
                        <label>Photo Glow: <span id="val_photo_glow_radius"><?php echo $template->photo_glow_radius ?></span>px</label>
                        <div style="display: flex; align-items: center; gap: 10px;">
                            <input type="range" id="photo_glow_radius" min="0" max="30" value="<?php echo $template->photo_glow_radius ?>" class="layout-range" style="flex: 1;">
                            <input type="color" id="photo_glow_color" value="<?php echo $template->photo_glow_color ?: '#000000' ?>" class="layout-color" style="width: 40px; height: 30px; padding: 2px; cursor: pointer;">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Text Controls -->
        <div class="col-md-5">
            <div class="box box-warning">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-font"></i> Text Layout</h3>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-xs-8">
                            <div class="form-group">
                                <label>Horizontal Offset: <span id="val_text_offset"><?php echo $template->text_offset ?></span>px</label>
                                <input type="range" id="text_offset" min="-400" max="400" value="<?php echo $template->text_offset ?>" class="layout-range" style="width:100%">
                            </div>
                            <div class="form-group">
                                <label>Font Color</label><br>
                                <input type="color" id="font_color" value="<?php echo $template->font_color ?: '#ffffff' ?>" class="layout-color" style="width: 60px; height: 34px; padding: 2px; cursor: pointer;">
                            </div>
                            <div class="form-group">
                                <label>Glow: <span id="val_glow_radius"><?php echo $template->glow_radius ?></span>px</label>
                                <div style="display: flex; align-items: center; gap: 10px;">
                                    <input type="range" id="glow_radius" min="0" max="10" value="<?php echo $template->glow_radius ?>" class="layout-range" style="flex: 1;">
                                    <input type="color" id="glow_color" value="<?php echo $template->glow_color ?: '#ffffff' ?>" class="layout-color" style="width: 40px; height: 30px; padding: 2px; cursor: pointer;">
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Stroke: <span id="val_stroke_width"><?php echo $template->stroke_width ?></span>px</label>
                                <div style="display: flex; align-items: center; gap: 10px;">
                                    <input type="range" id="stroke_width" min="0" max="6" value="<?php echo $template->stroke_width ?>" class="layout-range" style="flex: 1;">
                                    <input type="color" id="stroke_color" value="<?php echo $template->stroke_color ?: '#000000' ?>" class="layout-color" style="width: 40px; height: 30px; padding: 2px; cursor: pointer;">
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Shadow: <span id="val_shadow_offset"><?php echo $template->shadow_offset ?></span>px</label>
                                <input type="range" id="shadow_offset" min="0" max="8" value="<?php echo $template->shadow_offset ?>" class="layout-range" style="width:100%">
                            </div>
                        </div>
                        <div class="col-xs-4 text-center">
                            <label>Y: <span id="val_summary_margin_top"><?php echo $template->summary_margin_top ?></span>px</label>
                            <div style="display: flex; justify-content: center;">
                                <input type="range" id="summary_margin_top" min="20" max="500" value="<?php echo $template->summary_margin_top ?>" class="layout-range" orient="vertical" style="writing-mode: vertical-lr; height: 150px; width: 30px;">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Save -->
            <div class="box box-success">
                <div class="box-body">
                    <button id="saveLayout" class="btn btn-success btn-lg btn-block">
                        <i class="fa fa-save"></i> Save & Preview
                    </button>
                    <div id="saveStatus" style="margin-top: 10px;"></div>
                    <hr>
                    <a href="<?php echo site_url('admin/templates') ?>" class="btn btn-default btn-block">
                        <i class="fa fa-arrow-left"></i> Back to Templates
                    </a>
                </div>
            </div>
        </div>
    </div>

</section>

<script>
$(document).ready(function() {
    var previewUrl = '<?php echo site_url("admin/preview_template/" . $template->id) ?>';
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
                + '&text_offset=' + getVal('text_offset')
                + '&summary_margin_top=' + getVal('summary_margin_top')
                + '&glow_radius=' + getVal('glow_radius')
                + '&shadow_offset=' + getVal('shadow_offset')
                + '&font_color=' + encodeURIComponent($('#font_color').val())
                + '&glow_color=' + encodeURIComponent($('#glow_color').val())
                + '&stroke_width=' + getVal('stroke_width')
                + '&stroke_color=' + encodeURIComponent($('#stroke_color').val())
                + '&t=' + Date.now();
            $('#previewImage').attr('src', src);
        }, 300);
    }

    // Live preview on slider move
    $('.layout-range').on('input change', function() {
        $('#val_' + $(this).attr('id')).text($(this).val());
        refreshPreview();
    });

    // Live preview on color change
    $('.layout-color').on('input change', function() {
        refreshPreview();
    });

    // Save to database
    $('#saveLayout').click(function() {
        var data = {
            template_id:          <?php echo $template->id ?>,
            photo_x:              getVal('photo_x'),
            photo_y:              getVal('photo_y'),
            photo_scale:          getVal('photo_scale'),
            photo_glow_radius:    getVal('photo_glow_radius'),
            photo_glow_color:     $('#photo_glow_color').val(),
            text_offset:          getVal('text_offset'),
            summary_margin_top:   getVal('summary_margin_top'),
            summary_font_size:    <?php echo (int) $template->summary_font_size ?>,
            date_font_size:       <?php echo (int) $template->date_font_size ?>,
            date_margin_top:      <?php echo (int) $template->date_margin_top ?>,
            time_font_size:       <?php echo (int) $template->time_font_size ?>,
            time_margin_top:      <?php echo (int) $template->time_margin_top ?>,
            location_font_size:   <?php echo (int) $template->location_font_size ?>,
            location_margin_top:  <?php echo (int) $template->location_margin_top ?>,
            font_color:           $('#font_color').val(),
            glow_radius:          getVal('glow_radius'),
            glow_color:           $('#glow_color').val(),
            shadow_offset:        getVal('shadow_offset'),
            stroke_width:         getVal('stroke_width'),
            stroke_color:         $('#stroke_color').val()
        };

        $.post('<?php echo site_url("admin/save_template_layout") ?>', data, function() {
            $('#saveStatus').html('<div class="alert alert-success">Layout saved!</div>');
            setTimeout(function() { $('#saveStatus').html(''); }, 3000);
        }).fail(function() {
            $('#saveStatus').html('<div class="alert alert-danger">Save failed.</div>');
        });
    });
});
</script>

<?php include VIEWPATH . 'admin/includes/footer.php'; ?>
