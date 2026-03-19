<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<?php include VIEWPATH . 'admin/includes/header.php'; ?>

<section class="content-header">
    <h1>Background Text Defaults <small><?php echo htmlspecialchars($background->original_name) ?></small></h1>
</section>

<section class="content">

    <div class="row">
        <!-- Preview -->
        <div class="col-md-7">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-eye"></i> Preview</h3>
                </div>
                <div class="box-body text-center">
                    <img id="previewImage"
                         src="<?php echo site_url('admin/preview_template_background/' . $background->id) ?>"
                         style="max-width: 100%; border: 1px solid #ddd;">
                </div>
            </div>
        </div>

        <!-- Controls -->
        <div class="col-md-5">
            <div class="box box-warning">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-font"></i> Default Text Settings</h3>
                </div>
                <div class="box-body">
                    <p class="text-muted" style="font-size: 12px;">These defaults are applied to new templates using this background.</p>

                    <div class="form-group">
                        <label>Horizontal Offset: <span id="val_text_offset"><?php echo $background->text_offset ?></span>px</label>
                        <input type="range" id="text_offset" min="-400" max="400" value="<?php echo $background->text_offset ?>" class="layout-range" style="width:100%">
                    </div>
                    <div class="form-group">
                        <label>Vertical Position: <span id="val_summary_margin_top"><?php echo $background->summary_margin_top ?></span>px</label>
                        <input type="range" id="summary_margin_top" min="20" max="500" value="<?php echo $background->summary_margin_top ?>" class="layout-range" style="width:100%">
                    </div>
                    <div class="form-group">
                        <label>Font Color</label><br>
                        <input type="color" id="font_color" value="<?php echo $background->font_color ?: '#ffffff' ?>" class="layout-color" style="width: 60px; height: 34px; padding: 2px; cursor: pointer;">
                    </div>
                    <div class="form-group">
                        <label>Glow: <span id="val_glow_radius"><?php echo $background->glow_radius ?></span>px</label>
                        <div style="display: flex; align-items: center; gap: 10px;">
                            <input type="range" id="glow_radius" min="0" max="10" value="<?php echo $background->glow_radius ?>" class="layout-range" style="flex: 1;">
                            <input type="color" id="glow_color" value="<?php echo $background->glow_color ?: '#ffffff' ?>" class="layout-color" style="width: 40px; height: 30px; padding: 2px; cursor: pointer;">
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Stroke: <span id="val_stroke_width"><?php echo $background->stroke_width ?></span>px</label>
                        <div style="display: flex; align-items: center; gap: 10px;">
                            <input type="range" id="stroke_width" min="0" max="6" value="<?php echo $background->stroke_width ?>" class="layout-range" style="flex: 1;">
                            <input type="color" id="stroke_color" value="<?php echo $background->stroke_color ?: '#000000' ?>" class="layout-color" style="width: 40px; height: 30px; padding: 2px; cursor: pointer;">
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Shadow: <span id="val_shadow_offset"><?php echo $background->shadow_offset ?></span>px</label>
                        <input type="range" id="shadow_offset" min="0" max="8" value="<?php echo $background->shadow_offset ?>" class="layout-range" style="width:100%">
                    </div>

                    <hr>
                    <button id="saveDefaults" class="btn btn-success btn-lg btn-block">
                        <i class="fa fa-save"></i> Save Defaults
                    </button>
                    <div id="saveStatus" style="margin-top: 10px;"></div>
                    <hr>
                    <a href="<?php echo site_url('admin/template_backgrounds') ?>" class="btn btn-default btn-block">
                        <i class="fa fa-arrow-left"></i> Back to Backgrounds
                    </a>
                </div>
            </div>
        </div>
    </div>

</section>

<script>
$(document).ready(function() {
    var previewUrl = '<?php echo site_url("admin/preview_template_background/" . $background->id) ?>';
    var debounceTimer = null;

    function getVal(id) {
        return parseInt($('#' + id).val());
    }

    function refreshPreview() {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(function() {
            var src = previewUrl
                + '?text_offset=' + getVal('text_offset')
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

    $('.layout-range').on('input change', function() {
        $('#val_' + $(this).attr('id')).text($(this).val());
        refreshPreview();
    });

    $('.layout-color').on('input change', function() {
        refreshPreview();
    });

    $('#saveDefaults').click(function() {
        var data = {
            background_id:        <?php echo $background->id ?>,
            text_offset:          getVal('text_offset'),
            summary_margin_top:   getVal('summary_margin_top'),
            summary_font_size:    <?php echo (int) $background->summary_font_size ?>,
            date_font_size:       <?php echo (int) $background->date_font_size ?>,
            date_margin_top:      <?php echo (int) $background->date_margin_top ?>,
            time_font_size:       <?php echo (int) $background->time_font_size ?>,
            time_margin_top:      <?php echo (int) $background->time_margin_top ?>,
            location_font_size:   <?php echo (int) $background->location_font_size ?>,
            location_margin_top:  <?php echo (int) $background->location_margin_top ?>,
            font_color:           $('#font_color').val(),
            glow_radius:          getVal('glow_radius'),
            glow_color:           $('#glow_color').val(),
            shadow_offset:        getVal('shadow_offset'),
            stroke_width:         getVal('stroke_width'),
            stroke_color:         $('#stroke_color').val()
        };

        $.post('<?php echo site_url("admin/save_template_background_defaults") ?>', data, function() {
            $('#saveStatus').html('<div class="alert alert-success">Defaults saved!</div>');
            setTimeout(function() { $('#saveStatus').html(''); }, 3000);
        }).fail(function() {
            $('#saveStatus').html('<div class="alert alert-danger">Save failed.</div>');
        });
    });
});
</script>

<?php include VIEWPATH . 'admin/includes/footer.php'; ?>
