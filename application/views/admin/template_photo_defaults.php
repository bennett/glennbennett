<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<?php include VIEWPATH . 'admin/includes/header.php'; ?>

<section class="content-header">
    <h1>Artist Photo Defaults <small><?php echo htmlspecialchars($photo->original_name) ?></small></h1>
    <div style="margin-top: 10px;">
        <a href="<?php echo site_url('admin/template_photos') ?>" class="btn btn-default">
            <i class="fa fa-arrow-left"></i> Back to Artist Photos
        </a>
        <button id="saveDefaults" class="btn btn-success">
            <i class="fa fa-save"></i> Save Defaults
        </button>
        <span id="saveStatus" style="margin-left: 10px;"></span>
    </div>
</section>

<section class="content">

    <?php if ( ! $preview_bg): ?>
        <div class="callout callout-warning">
            <p>Upload a background first to preview photo positioning.</p>
        </div>
    <?php else: ?>

    <div class="row">
        <!-- Preview + Photo Position -->
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

            <!-- Photo Position -->
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-user"></i> Artist Photo Position</h3>
                </div>
                <div class="box-body">
                    <p class="text-muted" style="font-size: 12px;">These defaults are inherited by new templates using this photo.</p>
                    <div class="row">
                        <div class="col-xs-8">
                            <div class="form-group">
                                <label>X Position: <span id="val_photo_x"><?php echo $photo->photo_x ?></span>px</label>
                                <input type="range" id="photo_x" min="-500" max="1500" value="<?php echo $photo->photo_x ?>" class="layout-range" style="width:100%">
                            </div>
                            <div class="form-group">
                                <label>Photo Glow</label>
                                <div style="display: flex; align-items: center; gap: 10px;">
                                    <input type="number" id="photo_glow_radius" min="0" max="40" value="<?php echo $photo->photo_glow_radius ?>" class="form-control layout-range" style="width: 80px;">
                                    <span>px</span>
                                    <input type="color" id="photo_glow_color" value="<?php echo $photo->photo_glow_color ?: '#000000' ?>" class="layout-color" style="width: 40px; height: 30px; padding: 2px; cursor: pointer;">
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Scale</label>
                                <div style="display: flex; align-items: center; gap: 10px;">
                                    <input type="number" id="photo_scale" min="10" max="300" value="<?php echo $photo->photo_scale ?>" class="form-control layout-range" style="width: 80px;">
                                    <span>% of canvas height</span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Opacity: <span id="val_opacity"><?php echo $photo->opacity ?></span>%</label>
                                <input type="range" id="opacity" min="0" max="100" value="<?php echo $photo->opacity ?>" class="layout-range" style="width:100%">
                            </div>
                        </div>
                        <div class="col-xs-4 text-center">
                            <small class="text-muted">Up</small>
                            <div style="display: flex; justify-content: center;">
                                <input type="range" id="photo_y" min="-500" max="1500" value="<?php echo $photo->photo_y ?>" class="layout-range" orient="vertical" style="writing-mode: vertical-lr; height: 300px; width: 30px;">
                            </div>
                            <small class="text-muted">Down</small>
                            <div><span id="val_photo_y"><?php echo $photo->photo_y ?></span>px</div>
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
                                <label>Horizontal Offset: <span id="val_text_offset"><?php echo $photo->text_offset ?></span>px</label>
                                <input type="range" id="text_offset" min="-400" max="400" value="<?php echo $photo->text_offset ?>" class="layout-range" style="width:100%">
                            </div>
                            <div class="form-group">
                                <label>Font Color</label><br>
                                <input type="color" id="font_color" value="<?php echo $photo->font_color ?: '#ffffff' ?>" class="layout-color" style="width: 60px; height: 34px; padding: 2px; cursor: pointer;">
                            </div>
                            <div class="form-group">
                                <label>Glow</label>
                                <div style="display: flex; align-items: center; gap: 10px;">
                                    <input type="number" id="glow_radius" min="0" max="40" value="<?php echo $photo->glow_radius ?>" class="form-control layout-range" style="width: 80px;">
                                    <span>px</span>
                                    <input type="color" id="glow_color" value="<?php echo $photo->glow_color ?: '#ffffff' ?>" class="layout-color" style="width: 40px; height: 30px; padding: 2px; cursor: pointer;">
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Stroke</label>
                                <div style="display: flex; align-items: center; gap: 10px;">
                                    <input type="number" id="stroke_width" min="0" max="6" value="<?php echo $photo->stroke_width ?>" class="form-control layout-range" style="width: 80px;">
                                    <span>px</span>
                                    <input type="color" id="stroke_color" value="<?php echo $photo->stroke_color ?: '#000000' ?>" class="layout-color" style="width: 40px; height: 30px; padding: 2px; cursor: pointer;">
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Shadow</label>
                                <div style="display: flex; align-items: center; gap: 10px;">
                                    <input type="number" id="shadow_offset" min="0" max="8" value="<?php echo $photo->shadow_offset ?>" class="form-control layout-range" style="width: 80px;">
                                    <span>px</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-4 text-center">
                            <small class="text-muted">Up</small>
                            <div style="display: flex; justify-content: center;">
                                <input type="range" id="summary_margin_top" min="20" max="500" value="<?php echo $photo->summary_margin_top ?>" class="layout-range" orient="vertical" style="writing-mode: vertical-lr; height: 300px; width: 30px;">
                            </div>
                            <small class="text-muted">Down</small>
                            <div><span id="val_summary_margin_top"><?php echo $photo->summary_margin_top ?></span>px</div>
                        </div>
                    </div>

                    <hr style="margin: 10px 0;">
                    <h4 style="margin-top: 0;"><i class="fa fa-text-height"></i> Font Sizes & Spacing</h4>
                    <div class="row">
                        <div class="col-xs-6">
                            <div class="form-group">
                                <label>Summary: <span id="val_summary_font_size"><?php echo $photo->summary_font_size ?></span>px</label>
                                <input type="range" id="summary_font_size" min="10" max="80" value="<?php echo $photo->summary_font_size ?>" class="layout-range" style="width:100%">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-4">
                            <div class="form-group">
                                <label>Date size: <span id="val_date_font_size"><?php echo $photo->date_font_size ?></span>px</label>
                                <input type="range" id="date_font_size" min="10" max="80" value="<?php echo $photo->date_font_size ?>" class="layout-range" style="width:100%">
                            </div>
                        </div>
                        <div class="col-xs-4">
                            <div class="form-group">
                                <label>Date gap: <span id="val_date_margin_top"><?php echo $photo->date_margin_top ?></span>px</label>
                                <input type="range" id="date_margin_top" min="0" max="100" value="<?php echo $photo->date_margin_top ?>" class="layout-range" style="width:100%">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-4">
                            <div class="form-group">
                                <label>Time size: <span id="val_time_font_size"><?php echo $photo->time_font_size ?></span>px</label>
                                <input type="range" id="time_font_size" min="10" max="80" value="<?php echo $photo->time_font_size ?>" class="layout-range" style="width:100%">
                            </div>
                        </div>
                        <div class="col-xs-4">
                            <div class="form-group">
                                <label>Time gap: <span id="val_time_margin_top"><?php echo $photo->time_margin_top ?></span>px</label>
                                <input type="range" id="time_margin_top" min="0" max="100" value="<?php echo $photo->time_margin_top ?>" class="layout-range" style="width:100%">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-4">
                            <div class="form-group">
                                <label>Location size: <span id="val_location_font_size"><?php echo $photo->location_font_size ?></span>px</label>
                                <input type="range" id="location_font_size" min="10" max="80" value="<?php echo $photo->location_font_size ?>" class="layout-range" style="width:100%">
                            </div>
                        </div>
                        <div class="col-xs-4">
                            <div class="form-group">
                                <label>Location gap: <span id="val_location_margin_top"><?php echo $photo->location_margin_top ?></span>px</label>
                                <input type="range" id="location_margin_top" min="0" max="100" value="<?php echo $photo->location_margin_top ?>" class="layout-range" style="width:100%">
                            </div>
                        </div>
                    </div>
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
                + '&opacity=' + getVal('opacity')
                + '&text_offset=' + getVal('text_offset')
                + '&summary_margin_top=' + getVal('summary_margin_top')
                + '&summary_font_size=' + getVal('summary_font_size')
                + '&date_font_size=' + getVal('date_font_size')
                + '&date_margin_top=' + getVal('date_margin_top')
                + '&time_font_size=' + getVal('time_font_size')
                + '&time_margin_top=' + getVal('time_margin_top')
                + '&location_font_size=' + getVal('location_font_size')
                + '&location_margin_top=' + getVal('location_margin_top')
                + '&font_color=' + encodeURIComponent($('#font_color').val())
                + '&glow_radius=' + getVal('glow_radius')
                + '&glow_color=' + encodeURIComponent($('#glow_color').val())
                + '&shadow_offset=' + getVal('shadow_offset')
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

    // Dirty tracking
    var isDirty = false;

    $('.layout-range, .layout-color').on('input change', function() {
        isDirty = true;
    });

    $(window).on('beforeunload', function() {
        if (isDirty) {
            return 'You have unsaved changes. Leave anyway?';
        }
    });

    $('#saveDefaults').click(function() {
        var $btn = $(this);
        $btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Saving...');

        var data = {
            photo_id:           <?php echo $photo->id ?>,
            photo_x:            getVal('photo_x'),
            photo_y:            getVal('photo_y'),
            photo_scale:        getVal('photo_scale'),
            photo_glow_radius:  getVal('photo_glow_radius'),
            photo_glow_color:   $('#photo_glow_color').val(),
            opacity:            getVal('opacity'),
            text_offset:        getVal('text_offset'),
            summary_margin_top: getVal('summary_margin_top'),
            summary_font_size:  getVal('summary_font_size'),
            date_font_size:     getVal('date_font_size'),
            date_margin_top:    getVal('date_margin_top'),
            time_font_size:     getVal('time_font_size'),
            time_margin_top:    getVal('time_margin_top'),
            location_font_size: getVal('location_font_size'),
            location_margin_top:getVal('location_margin_top'),
            font_color:         $('#font_color').val(),
            glow_radius:        getVal('glow_radius'),
            glow_color:         $('#glow_color').val(),
            shadow_offset:      getVal('shadow_offset'),
            stroke_width:       getVal('stroke_width'),
            stroke_color:       $('#stroke_color').val()
        };

        $.post('<?php echo site_url("admin/save_template_photo_defaults") ?>', data, function() {
            isDirty = false;
            window.location.href = '<?php echo site_url("admin/template_photos") ?>';
        }).fail(function() {
            $btn.prop('disabled', false).html('<i class="fa fa-save"></i> Save Defaults');
            $('#saveStatus').html('<div class="alert alert-danger">Save failed.</div>');
        });
    });
});
</script>

<?php include VIEWPATH . 'admin/includes/footer.php'; ?>
