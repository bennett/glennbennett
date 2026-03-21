<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<?php include VIEWPATH . 'admin/includes/header.php'; ?>

<section class="content-header">
    <h1>Gig Share Template Editor <small><?php echo htmlspecialchars($template->name ?: $template->bg_name . '_' . $template->photo_name) ?></small></h1>
    <div style="margin-top: 10px;">
        <a href="<?php echo site_url('admin/templates') ?>" class="btn btn-default">
            <i class="fa fa-arrow-left"></i> Back to Templates
        </a>
        <button id="saveLayout" class="btn btn-success">
            <i class="fa fa-save"></i> Save & Preview
        </button>
        <span id="saveStatus" style="margin-left: 10px;"></span>
    </div>
</section>

<section class="content">

    <!-- Preview + Photo Position below it -->
    <div class="row">
        <div class="col-md-7">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-eye"></i> Preview</h3>
                    <div class="box-tools pull-right">
                        <button class="btn btn-default" id="resetPhotoDefaults" style="font-size: 14px; padding: 6px 12px;">
                            <i class="fa fa-undo"></i> Reset Photo
                        </button>
                    </div>
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
                    <h3 class="box-title"><i class="fa fa-user"></i> Artist Photo Position</h3>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-xs-8">
                            <div class="form-group">
                                <label>X Position: <span id="val_photo_x"><?php echo $template->photo_x ?></span>px</label>
                                <input type="range" id="photo_x" min="-500" max="1500" value="<?php echo $template->photo_x ?>" class="layout-range" style="width:100%">
                            </div>
                            <div class="form-group">
                                <label>Photo Glow</label>
                                <div style="display: flex; align-items: center; gap: 10px;">
                                    <input type="number" id="photo_glow_radius" min="0" max="40" value="<?php echo $template->photo_glow_radius ?>" class="form-control layout-range" style="width: 80px;">
                                    <span>px</span>
                                    <input type="color" id="photo_glow_color" value="<?php echo $template->photo_glow_color ?: '#000000' ?>" class="layout-color" style="width: 40px; height: 30px; padding: 2px; cursor: pointer;">
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Scale</label>
                                <div style="display: flex; align-items: center; gap: 10px;">
                                    <input type="number" id="photo_scale" min="10" max="300" value="<?php echo $template->photo_scale ?>" class="form-control layout-range" style="width: 80px;">
                                    <span>% of canvas height</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-4 text-center">
                            <small class="text-muted">Up</small>
                            <div style="display: flex; justify-content: center;">
                                <input type="range" id="photo_y" min="-500" max="1500" value="<?php echo $template->photo_y ?>" class="layout-range" orient="vertical" style="writing-mode: vertical-lr; height: 300px; width: 30px;">
                            </div>
                            <small class="text-muted">Down</small>
                            <div><span id="val_photo_y"><?php echo $template->photo_y ?></span>px</div>
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
                    <div class="box-tools pull-right">
                        <button class="btn btn-default" id="loadBgPreset" style="font-size: 14px; padding: 6px 12px;" title="Load text settings saved for this background">
                            <i class="fa fa-download"></i> Load BG Preset
                        </button>
                        <button class="btn btn-info" id="saveBgPreset" style="font-size: 14px; padding: 6px 12px;" title="Save current text settings as preset for this background">
                            <i class="fa fa-upload"></i> Save as BG Preset
                        </button>
                        <button class="btn btn-default" id="resetTextDefaults" style="font-size: 14px; padding: 6px 12px;">
                            <i class="fa fa-undo"></i> Reset to Defaults
                        </button>
                    </div>
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
                                <label>Glow</label>
                                <div style="display: flex; align-items: center; gap: 10px;">
                                    <input type="number" id="glow_radius" min="0" max="40" value="<?php echo $template->glow_radius ?>" class="form-control layout-range" style="width: 80px;">
                                    <span>px</span>
                                    <input type="color" id="glow_color" value="<?php echo $template->glow_color ?: '#ffffff' ?>" class="layout-color" style="width: 40px; height: 30px; padding: 2px; cursor: pointer;">
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Stroke</label>
                                <div style="display: flex; align-items: center; gap: 10px;">
                                    <input type="number" id="stroke_width" min="0" max="6" value="<?php echo $template->stroke_width ?>" class="form-control layout-range" style="width: 80px;">
                                    <span>px</span>
                                    <input type="color" id="stroke_color" value="<?php echo $template->stroke_color ?: '#000000' ?>" class="layout-color" style="width: 40px; height: 30px; padding: 2px; cursor: pointer;">
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Shadow</label>
                                <div style="display: flex; align-items: center; gap: 10px;">
                                    <input type="number" id="shadow_offset" min="0" max="8" value="<?php echo $template->shadow_offset ?>" class="form-control layout-range" style="width: 80px;">
                                    <span>px</span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Text Background</label>
                                <div style="display: flex; align-items: center; gap: 10px;">
                                    <input type="number" id="text_bg_opacity" min="0" max="100" value="<?php echo $template->text_bg_opacity ?>" class="form-control layout-range" style="width: 80px;">
                                    <span>%</span>
                                    <input type="color" id="text_bg_color" value="<?php echo $template->text_bg_color ?: '#ffffff' ?>" class="layout-color" style="width: 40px; height: 30px; padding: 2px; cursor: pointer;">
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-4 text-center">
                            <small class="text-muted">Up</small>
                            <div style="display: flex; justify-content: center;">
                                <input type="range" id="summary_margin_top" min="20" max="500" value="<?php echo $template->summary_margin_top ?>" class="layout-range" orient="vertical" style="writing-mode: vertical-lr; height: 300px; width: 30px;">
                            </div>
                            <small class="text-muted">Down</small>
                            <div><span id="val_summary_margin_top"><?php echo $template->summary_margin_top ?></span>px</div>
                        </div>
                    </div>

                    <hr style="margin: 10px 0;">
                    <h4 style="margin-top: 0;"><i class="fa fa-text-height"></i> Font Sizes & Spacing</h4>
                    <div class="row">
                        <div class="col-xs-6">
                            <div class="form-group">
                                <label>Summary: <span id="val_summary_font_size"><?php echo $template->summary_font_size ?></span>px</label>
                                <input type="range" id="summary_font_size" min="10" max="80" value="<?php echo $template->summary_font_size ?>" class="layout-range" style="width:100%">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-4">
                            <div class="form-group">
                                <label>Date size: <span id="val_date_font_size"><?php echo $template->date_font_size ?></span>px</label>
                                <input type="range" id="date_font_size" min="10" max="80" value="<?php echo $template->date_font_size ?>" class="layout-range" style="width:100%">
                            </div>
                        </div>
                        <div class="col-xs-4">
                            <div class="form-group">
                                <label>Date gap: <span id="val_date_margin_top"><?php echo $template->date_margin_top ?></span>px</label>
                                <input type="range" id="date_margin_top" min="0" max="100" value="<?php echo $template->date_margin_top ?>" class="layout-range" style="width:100%">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-4">
                            <div class="form-group">
                                <label>Time size: <span id="val_time_font_size"><?php echo $template->time_font_size ?></span>px</label>
                                <input type="range" id="time_font_size" min="10" max="80" value="<?php echo $template->time_font_size ?>" class="layout-range" style="width:100%">
                            </div>
                        </div>
                        <div class="col-xs-4">
                            <div class="form-group">
                                <label>Time gap: <span id="val_time_margin_top"><?php echo $template->time_margin_top ?></span>px</label>
                                <input type="range" id="time_margin_top" min="0" max="100" value="<?php echo $template->time_margin_top ?>" class="layout-range" style="width:100%">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-4">
                            <div class="form-group">
                                <label>Location size: <span id="val_location_font_size"><?php echo $template->location_font_size ?></span>px</label>
                                <input type="range" id="location_font_size" min="10" max="80" value="<?php echo $template->location_font_size ?>" class="layout-range" style="width:100%">
                            </div>
                        </div>
                        <div class="col-xs-4">
                            <div class="form-group">
                                <label>Location gap: <span id="val_location_margin_top"><?php echo $template->location_margin_top ?></span>px</label>
                                <input type="range" id="location_margin_top" min="0" max="100" value="<?php echo $template->location_margin_top ?>" class="layout-range" style="width:100%">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Venue Assignment -->
            <div class="box box-default">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-tags"></i> Venue Assignment</h3>
                </div>
                <div class="box-body">
                    <?php echo form_open('admin/save_template_assignments'); ?>
                    <input type="hidden" name="template_id" value="<?php echo $template->id ?>">

                    <div class="form-group">
                        <label>Venue Type</label>
                        <select class="form-control" name="venue_type_id">
                            <?php foreach ($venue_types as $vt): ?>
                                <option value="<?php echo $vt->id ?>"
                                    <?php echo in_array($vt->id, $assigned_type_ids) ? 'selected' : '' ?>>
                                    <?php echo htmlspecialchars($vt->name) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Venue <small class="text-muted">(overrides type)</small></label>
                        <select class="form-control" name="venue_id">
                            <option value="">-- None --</option>
                            <?php foreach ($venues as $v): ?>
                                <option value="<?php echo $v->id ?>"
                                    <?php echo in_array($v->id, $assigned_venue_ids) ? 'selected' : '' ?>>
                                    <?php echo htmlspecialchars($v->name) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary btn-block">
                        <i class="fa fa-save"></i> Save Assignment
                    </button>
                    <?php echo form_close(); ?>
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
                + '&summary_font_size=' + getVal('summary_font_size')
                + '&date_font_size=' + getVal('date_font_size')
                + '&date_margin_top=' + getVal('date_margin_top')
                + '&time_font_size=' + getVal('time_font_size')
                + '&time_margin_top=' + getVal('time_margin_top')
                + '&location_font_size=' + getVal('location_font_size')
                + '&location_margin_top=' + getVal('location_margin_top')
                + '&glow_radius=' + getVal('glow_radius')
                + '&shadow_offset=' + getVal('shadow_offset')
                + '&font_color=' + encodeURIComponent($('#font_color').val())
                + '&glow_color=' + encodeURIComponent($('#glow_color').val())
                + '&stroke_width=' + getVal('stroke_width')
                + '&stroke_color=' + encodeURIComponent($('#stroke_color').val())
                + '&text_bg_opacity=' + getVal('text_bg_opacity')
                + '&text_bg_color=' + encodeURIComponent($('#text_bg_color').val())
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

    // Reset photo position to defaults
    var photoDefaults = {
        photo_x: <?php echo (int) $photo_defaults->photo_x ?>,
        photo_y: <?php echo (int) $photo_defaults->photo_y ?>,
        photo_scale: <?php echo (int) $photo_defaults->photo_scale ?>,
        photo_glow_radius: <?php echo (int) $photo_defaults->photo_glow_radius ?>,
        photo_glow_color: '<?php echo $photo_defaults->photo_glow_color ?: '#000000' ?>'
    };

    var textDefaults = {
        text_offset: <?php echo (int) $photo_defaults->text_offset ?>,
        summary_margin_top: <?php echo (int) $photo_defaults->summary_margin_top ?>,
        summary_font_size: <?php echo (int) $photo_defaults->summary_font_size ?>,
        date_font_size: <?php echo (int) $photo_defaults->date_font_size ?>,
        date_margin_top: <?php echo (int) $photo_defaults->date_margin_top ?>,
        time_font_size: <?php echo (int) $photo_defaults->time_font_size ?>,
        time_margin_top: <?php echo (int) $photo_defaults->time_margin_top ?>,
        location_font_size: <?php echo (int) $photo_defaults->location_font_size ?>,
        location_margin_top: <?php echo (int) $photo_defaults->location_margin_top ?>,
        font_color: '<?php echo $photo_defaults->font_color ?: '#ffffff' ?>',
        glow_radius: <?php echo (int) $photo_defaults->glow_radius ?>,
        glow_color: '<?php echo $photo_defaults->glow_color ?: '#ffffff' ?>',
        shadow_offset: <?php echo (int) $photo_defaults->shadow_offset ?>,
        stroke_width: <?php echo (int) $photo_defaults->stroke_width ?>,
        stroke_color: '<?php echo $photo_defaults->stroke_color ?: '#000000' ?>',
        text_bg_opacity: <?php echo (int) $photo_defaults->text_bg_opacity ?>,
        text_bg_color: '<?php echo $photo_defaults->text_bg_color ?: '#ffffff' ?>'
    };

    function setVal(id, val) {
        var $el = $('#' + id);
        $el.val(val).trigger('input');
        $('#val_' + id).text(val);
    }

    $('#resetPhotoDefaults').click(function() {
        setVal('photo_x', photoDefaults.photo_x);
        setVal('photo_y', photoDefaults.photo_y);
        setVal('photo_scale', photoDefaults.photo_scale);
        setVal('photo_glow_radius', photoDefaults.photo_glow_radius);
        $('#photo_glow_color').val(photoDefaults.photo_glow_color);
        refreshPreview();
    });

    $('#resetTextDefaults').click(function() {
        setVal('text_offset', textDefaults.text_offset);
        setVal('summary_margin_top', textDefaults.summary_margin_top);
        setVal('summary_font_size', textDefaults.summary_font_size);
        setVal('date_font_size', textDefaults.date_font_size);
        setVal('date_margin_top', textDefaults.date_margin_top);
        setVal('time_font_size', textDefaults.time_font_size);
        setVal('time_margin_top', textDefaults.time_margin_top);
        setVal('location_font_size', textDefaults.location_font_size);
        setVal('location_margin_top', textDefaults.location_margin_top);
        setVal('font_color', textDefaults.font_color);
        setVal('glow_radius', textDefaults.glow_radius);
        $('#glow_color').val(textDefaults.glow_color);
        setVal('shadow_offset', textDefaults.shadow_offset);
        setVal('stroke_width', textDefaults.stroke_width);
        $('#stroke_color').val(textDefaults.stroke_color);
        setVal('text_bg_opacity', textDefaults.text_bg_opacity);
        $('#text_bg_color').val(textDefaults.text_bg_color);
        refreshPreview();
    });

    // Load BG text preset
    $('#loadBgPreset').click(function() {
        $.getJSON('<?php echo site_url("admin/get_bg_text_preset/" . $template->background_id) ?>', function(data) {
            if (data.status === 'ok') {
                setVal('text_offset', data.text_offset);
                setVal('summary_margin_top', data.summary_margin_top);
                setVal('summary_font_size', data.summary_font_size);
                setVal('date_font_size', data.date_font_size);
                setVal('date_margin_top', data.date_margin_top);
                setVal('time_font_size', data.time_font_size);
                setVal('time_margin_top', data.time_margin_top);
                setVal('location_font_size', data.location_font_size);
                setVal('location_margin_top', data.location_margin_top);
                setVal('font_color', data.font_color);
                setVal('glow_radius', data.glow_radius);
                $('#glow_color').val(data.glow_color);
                setVal('shadow_offset', data.shadow_offset);
                setVal('stroke_width', data.stroke_width);
                $('#stroke_color').val(data.stroke_color);
                if (data.text_bg_opacity !== undefined) setVal('text_bg_opacity', data.text_bg_opacity);
                if (data.text_bg_color) $('#text_bg_color').val(data.text_bg_color);
                refreshPreview();
            }
        });
    });

    // Save current text settings as BG preset
    $('#saveBgPreset').click(function() {
        var $btn = $(this);
        $btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Saving...');

        $.post('<?php echo site_url("admin/save_bg_text_preset") ?>', {
            background_id:      <?php echo $template->background_id ?>,
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
            stroke_color:       $('#stroke_color').val(),
            text_bg_opacity:    getVal('text_bg_opacity'),
            text_bg_color:      $('#text_bg_color').val()
        }, function() {
            $btn.prop('disabled', false).html('<i class="fa fa-check"></i> Saved!');
            setTimeout(function() { $btn.html('<i class="fa fa-upload"></i> Save as BG Preset'); }, 2000);
        }, 'json').fail(function() {
            $btn.prop('disabled', false).html('<i class="fa fa-upload"></i> Save as BG Preset');
        });
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

    // Save to database
    $('#saveLayout').click(function() {
        var $btn = $(this);
        $btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Saving...');

        var data = {
            template_id:          <?php echo $template->id ?>,
            photo_x:              getVal('photo_x'),
            photo_y:              getVal('photo_y'),
            photo_scale:          getVal('photo_scale'),
            photo_glow_radius:    getVal('photo_glow_radius'),
            photo_glow_color:     $('#photo_glow_color').val(),
            text_offset:          getVal('text_offset'),
            summary_margin_top:   getVal('summary_margin_top'),
            summary_font_size:    getVal('summary_font_size'),
            date_font_size:       getVal('date_font_size'),
            date_margin_top:      getVal('date_margin_top'),
            time_font_size:       getVal('time_font_size'),
            time_margin_top:      getVal('time_margin_top'),
            location_font_size:   getVal('location_font_size'),
            location_margin_top:  getVal('location_margin_top'),
            font_color:           $('#font_color').val(),
            glow_radius:          getVal('glow_radius'),
            glow_color:           $('#glow_color').val(),
            shadow_offset:        getVal('shadow_offset'),
            stroke_width:         getVal('stroke_width'),
            stroke_color:         $('#stroke_color').val(),
            text_bg_opacity:      getVal('text_bg_opacity'),
            text_bg_color:        $('#text_bg_color').val()
        };

        $.post('<?php echo site_url("admin/save_template_layout") ?>', data, function() {
            isDirty = false;
            window.location.href = '<?php echo site_url("admin/templates") ?>';
        }).fail(function() {
            $btn.prop('disabled', false).html('<i class="fa fa-save"></i> Save & Preview');
            $('#saveStatus').html('<div class="alert alert-danger">Save failed.</div>');
        });
    });
});
</script>

<?php include VIEWPATH . 'admin/includes/footer.php'; ?>
