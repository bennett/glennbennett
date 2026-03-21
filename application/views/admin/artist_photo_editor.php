<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<?php include VIEWPATH . 'admin/includes/header.php'; ?>

<section class="content-header">
    <h1>Edit Artist Photo <small><?php echo htmlspecialchars($photo->original_name) ?></small></h1>
</section>

<section class="content">

    <div class="row">
        <!-- Canvas -->
        <div class="col-md-8">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-pencil"></i> Editor</h3>
                    <span class="pull-right text-muted" id="dimensions"><?php echo $photo->width ?>x<?php echo $photo->height ?></span>
                </div>
                <div class="box-body" style="text-align: center; overflow: auto;">
                    <div style="display: inline-block; background: repeating-conic-gradient(#ccc 0% 25%, #fff 0% 50%) 50% / 20px 20px; border: 1px solid #ddd;">
                        <canvas id="canvas"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tools -->
        <div class="col-md-4">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-wrench"></i> Tools</h3>
                </div>
                <div class="box-body">

                    <h4>Rotate</h4>
                    <div class="btn-group btn-group-justified" style="margin-bottom: 15px;">
                        <div class="btn-group">
                            <button class="btn btn-default" id="rotateCCW" title="Rotate 90° left">
                                <i class="fa fa-undo"></i> 90° Left
                            </button>
                        </div>
                        <div class="btn-group">
                            <button class="btn btn-default" id="rotateCW" title="Rotate 90° right">
                                <i class="fa fa-repeat"></i> 90° Right
                            </button>
                        </div>
                    </div>

                    <h4>Flip</h4>
                    <div class="btn-group btn-group-justified" style="margin-bottom: 15px;">
                        <div class="btn-group">
                            <button class="btn btn-default" id="flipH" title="Flip horizontal">
                                <i class="fa fa-arrows-h"></i> Horizontal
                            </button>
                        </div>
                        <div class="btn-group">
                            <button class="btn btn-default" id="flipV" title="Flip vertical">
                                <i class="fa fa-arrows-v"></i> Vertical
                            </button>
                        </div>
                    </div>

                    <h4>Trim</h4>
                    <button class="btn btn-default btn-block" id="trimBtn" style="margin-bottom: 15px;">
                        <i class="fa fa-crop"></i> Trim Transparent Pixels
                    </button>

                    <h4>Crop</h4>
                    <p class="text-muted" style="font-size: 12px;">Click and drag on the image to select a crop area.</p>
                    <button class="btn btn-default btn-block" id="cropBtn" disabled style="margin-bottom: 15px;">
                        <i class="fa fa-scissors"></i> Crop Selection
                    </button>
                    <button class="btn btn-default btn-block" id="clearCropBtn" disabled style="margin-bottom: 20px;">
                        <i class="fa fa-times"></i> Clear Selection
                    </button>

                    <hr>

                    <button class="btn btn-success btn-lg btn-block" id="saveBtn">
                        <i class="fa fa-save"></i> Save Changes
                    </button>
                    <div id="saveStatus" style="margin-top: 10px;"></div>

                    <button class="btn btn-warning btn-block" id="revertBtn" style="margin-top: 10px;">
                        <i class="fa fa-undo"></i> Revert to Original
                    </button>

                    <a href="<?php echo site_url('admin/template_photos') ?>" class="btn btn-default btn-block" style="margin-top: 10px;">
                        <i class="fa fa-arrow-left"></i> Back to Artist Photos
                    </a>
                </div>
            </div>

            <!-- Image Adjustments -->
            <div class="box box-default">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-adjust"></i> Image Adjustments</h3>
                </div>
                <div class="box-body">

                    <h4>Color</h4>
                    <div class="form-group">
                        <label>Brightness: <span id="val_brightness"><?php echo $photo->brightness ?></span></label>
                        <input type="range" id="brightness" min="-100" max="100" value="<?php echo $photo->brightness ?>" class="adjust-range" style="width:100%">
                    </div>
                    <div class="form-group">
                        <label>Contrast: <span id="val_contrast"><?php echo $photo->contrast ?></span></label>
                        <input type="range" id="contrast" min="-100" max="100" value="<?php echo $photo->contrast ?>" class="adjust-range" style="width:100%">
                    </div>
                    <div class="form-group">
                        <label>Saturation: <span id="val_saturation"><?php echo $photo->saturation ?></span></label>
                        <input type="range" id="saturation" min="-100" max="100" value="<?php echo $photo->saturation ?>" class="adjust-range" style="width:100%">
                    </div>

                    <hr>
                    <h4>Effects</h4>
                    <div class="row">
                        <div class="col-xs-6">
                            <div class="form-group">
                                <label>Sharpen: <span id="val_sharpen"><?php echo $photo->sharpen ?></span></label>
                                <input type="range" id="sharpen" min="0" max="5" value="<?php echo $photo->sharpen ?>" class="adjust-range" style="width:100%">
                            </div>
                        </div>
                        <div class="col-xs-6">
                            <div class="form-group">
                                <label>Blur: <span id="val_blur"><?php echo $photo->blur ?></span></label>
                                <input type="range" id="blur" min="0" max="10" value="<?php echo $photo->blur ?>" class="adjust-range" style="width:100%">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-6">
                            <label class="checkbox-inline" style="font-size: 14px;">
                                <input type="checkbox" id="sepia" value="1" <?php echo $photo->sepia ? 'checked' : '' ?> class="adjust-check"> Sepia
                            </label>
                        </div>
                        <div class="col-xs-6">
                            <label class="checkbox-inline" style="font-size: 14px;">
                                <input type="checkbox" id="grayscale" value="1" <?php echo $photo->grayscale ? 'checked' : '' ?> class="adjust-check"> Grayscale
                            </label>
                        </div>
                    </div>

                    <hr>
                    <h4>Tint</h4>
                    <div class="form-group">
                        <label>Hue Rotate: <span id="val_hue_rotate"><?php echo $photo->hue_rotate ?></span>&deg;</label>
                        <input type="range" id="hue_rotate" min="-180" max="180" value="<?php echo $photo->hue_rotate ?>" class="adjust-range" style="width:100%">
                    </div>
                    <div class="row">
                        <div class="col-xs-6">
                            <div class="form-group">
                                <label>Tint Amount: <span id="val_tint_amount"><?php echo $photo->tint_amount ?></span>%</label>
                                <input type="range" id="tint_amount" min="0" max="100" value="<?php echo $photo->tint_amount ?>" class="adjust-range" style="width:100%">
                            </div>
                        </div>
                        <div class="col-xs-6">
                            <div class="form-group">
                                <label>Tint Color</label><br>
                                <input type="color" id="tint_color" value="<?php echo $photo->tint_color ?: '#ff0000' ?>" class="adjust-color" style="width: 60px; height: 34px; padding: 2px; cursor: pointer;">
                            </div>
                        </div>
                    </div>

                    <button class="btn btn-primary btn-block" id="saveAdjustments" style="margin-top: 10px;">
                        <i class="fa fa-save"></i> Save Adjustments
                    </button>
                    <div id="adjustStatus" style="margin-top: 10px;"></div>
                </div>
            </div>
        </div>
    </div>

</section>

<script>
$(document).ready(function() {
    var canvas = document.getElementById('canvas');
    var ctx = canvas.getContext('2d');
    var img = new Image();
    var originalImg = new Image();
    var cropStart = null;
    var cropEnd = null;
    var isDragging = false;
    var hasChanges = false;

    var imgSrc = '<?php echo base_url("imgs/template-photos/" . $photo->filename) ?>?t=' + Date.now();

    img.crossOrigin = 'anonymous';
    originalImg.crossOrigin = 'anonymous';

    img.onload = function() {
        drawImage();
    };

    originalImg.onload = function() {};
    originalImg.src = imgSrc;
    img.src = imgSrc;

    function drawImage() {
        canvas.width = img.width;
        canvas.height = img.height;
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        ctx.drawImage(img, 0, 0);
        $('#dimensions').text(img.width + 'x' + img.height);
        drawCropOverlay();
    }

    function drawCropOverlay() {
        if (!cropStart || !cropEnd) return;

        var x = Math.min(cropStart.x, cropEnd.x);
        var y = Math.min(cropStart.y, cropEnd.y);
        var w = Math.abs(cropEnd.x - cropStart.x);
        var h = Math.abs(cropEnd.y - cropStart.y);

        // Dim outside selection
        ctx.fillStyle = 'rgba(0, 0, 0, 0.5)';
        ctx.fillRect(0, 0, canvas.width, y);
        ctx.fillRect(0, y + h, canvas.width, canvas.height - y - h);
        ctx.fillRect(0, y, x, h);
        ctx.fillRect(x + w, y, canvas.width - x - w, h);

        // Selection border
        ctx.strokeStyle = '#fff';
        ctx.lineWidth = 2;
        ctx.setLineDash([5, 5]);
        ctx.strokeRect(x, y, w, h);
        ctx.setLineDash([]);
    }

    function getCanvasCoords(e) {
        var rect = canvas.getBoundingClientRect();
        var scaleX = canvas.width / rect.width;
        var scaleY = canvas.height / rect.height;
        return {
            x: Math.round((e.clientX - rect.left) * scaleX),
            y: Math.round((e.clientY - rect.top) * scaleY)
        };
    }

    $(canvas).on('mousedown', function(e) {
        isDragging = true;
        cropStart = getCanvasCoords(e);
        cropEnd = null;
    });

    $(canvas).on('mousemove', function(e) {
        if (!isDragging) return;
        cropEnd = getCanvasCoords(e);
        drawImage();
    });

    $(canvas).on('mouseup', function(e) {
        if (!isDragging) return;
        isDragging = false;
        cropEnd = getCanvasCoords(e);

        var w = Math.abs(cropEnd.x - cropStart.x);
        var h = Math.abs(cropEnd.y - cropStart.y);

        if (w > 5 && h > 5) {
            $('#cropBtn').prop('disabled', false);
            $('#clearCropBtn').prop('disabled', false);
        } else {
            cropStart = null;
            cropEnd = null;
            drawImage();
        }
    });

    function replaceImage() {
        var dataUrl = canvas.toDataURL('image/png');
        var newImg = new Image();
        newImg.onload = function() {
            img = newImg;
            cropStart = null;
            cropEnd = null;
            $('#cropBtn').prop('disabled', true);
            $('#clearCropBtn').prop('disabled', true);
            hasChanges = true;
            drawImage();
        };
        newImg.src = dataUrl;
    }

    // Rotate CW
    $('#rotateCW').click(function() {
        var tempCanvas = document.createElement('canvas');
        tempCanvas.width = canvas.height;
        tempCanvas.height = canvas.width;
        var tCtx = tempCanvas.getContext('2d');
        tCtx.translate(tempCanvas.width, 0);
        tCtx.rotate(Math.PI / 2);
        tCtx.drawImage(canvas, 0, 0);

        canvas.width = tempCanvas.width;
        canvas.height = tempCanvas.height;
        ctx.drawImage(tempCanvas, 0, 0);
        replaceImage();
    });

    // Rotate CCW
    $('#rotateCCW').click(function() {
        var tempCanvas = document.createElement('canvas');
        tempCanvas.width = canvas.height;
        tempCanvas.height = canvas.width;
        var tCtx = tempCanvas.getContext('2d');
        tCtx.translate(0, tempCanvas.height);
        tCtx.rotate(-Math.PI / 2);
        tCtx.drawImage(canvas, 0, 0);

        canvas.width = tempCanvas.width;
        canvas.height = tempCanvas.height;
        ctx.drawImage(tempCanvas, 0, 0);
        replaceImage();
    });

    // Flip horizontal
    $('#flipH').click(function() {
        var tempCanvas = document.createElement('canvas');
        tempCanvas.width = canvas.width;
        tempCanvas.height = canvas.height;
        var tCtx = tempCanvas.getContext('2d');
        tCtx.translate(tempCanvas.width, 0);
        tCtx.scale(-1, 1);
        tCtx.drawImage(canvas, 0, 0);

        ctx.clearRect(0, 0, canvas.width, canvas.height);
        ctx.drawImage(tempCanvas, 0, 0);
        replaceImage();
    });

    // Flip vertical
    $('#flipV').click(function() {
        var tempCanvas = document.createElement('canvas');
        tempCanvas.width = canvas.width;
        tempCanvas.height = canvas.height;
        var tCtx = tempCanvas.getContext('2d');
        tCtx.translate(0, tempCanvas.height);
        tCtx.scale(1, -1);
        tCtx.drawImage(canvas, 0, 0);

        ctx.clearRect(0, 0, canvas.width, canvas.height);
        ctx.drawImage(tempCanvas, 0, 0);
        replaceImage();
    });

    // Trim
    $('#trimBtn').click(function() {
        var imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
        var data = imageData.data;
        var w = canvas.width;
        var h = canvas.height;

        var top = h, bottom = 0, left = w, right = 0;

        for (var y = 0; y < h; y++) {
            for (var x = 0; x < w; x++) {
                var i = (y * w + x) * 4;
                var r = data[i], g = data[i+1], b = data[i+2], alpha = data[i+3];
                // Keep pixel if not transparent AND not white/near-white
                var isContent = (alpha > 10) && !(r > 240 && g > 240 && b > 240);
                if (isContent) {
                    if (y < top) top = y;
                    if (y > bottom) bottom = y;
                    if (x < left) left = x;
                    if (x > right) right = x;
                }
            }
        }

        if (top > bottom || left > right) return;
        if (top === 0 && left === 0 && right === w - 1 && bottom === h - 1) return;

        var newW = right - left + 1;
        var newH = bottom - top + 1;
        var cropped = ctx.getImageData(left, top, newW, newH);

        canvas.width = newW;
        canvas.height = newH;
        ctx.putImageData(cropped, 0, 0);
        replaceImage();
    });

    // Crop
    $('#cropBtn').click(function() {
        if (!cropStart || !cropEnd) return;

        var x = Math.min(cropStart.x, cropEnd.x);
        var y = Math.min(cropStart.y, cropEnd.y);
        var w = Math.abs(cropEnd.x - cropStart.x);
        var h = Math.abs(cropEnd.y - cropStart.y);

        var cropped = ctx.getImageData(x, y, w, h);
        canvas.width = w;
        canvas.height = h;
        ctx.putImageData(cropped, 0, 0);
        replaceImage();
    });

    // Clear crop selection
    $('#clearCropBtn').click(function() {
        cropStart = null;
        cropEnd = null;
        $('#cropBtn').prop('disabled', true);
        $('#clearCropBtn').prop('disabled', true);
        drawImage();
    });

    // Revert
    $('#revertBtn').click(function() {
        if (!hasChanges || confirm('Revert all changes to the original image?')) {
            img = new Image();
            img.crossOrigin = 'anonymous';
            img.onload = function() {
                hasChanges = false;
                cropStart = null;
                cropEnd = null;
                $('#cropBtn').prop('disabled', true);
                $('#clearCropBtn').prop('disabled', true);
                drawImage();
            };
            img.src = originalImg.src;
        }
    });

    // Save
    $('#saveBtn').click(function() {
        var $btn = $(this);
        $btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Saving...');

        var dataUrl = canvas.toDataURL('image/png');

        $.ajax({
            url: '<?php echo site_url("admin/save_artist_photo/" . $photo->id) ?>',
            type: 'POST',
            data: { image_data: dataUrl },
            dataType: 'json',
            success: function(resp) {
                if (resp.status === 'ok') {
                    hasChanges = false;
                    originalImg.src = canvas.toDataURL('image/png');
                    $('#saveStatus').html('<div class="alert alert-success">Saved! ' + resp.width + 'x' + resp.height + '</div>');
                    $('#dimensions').text(resp.width + 'x' + resp.height);
                } else {
                    $('#saveStatus').html('<div class="alert alert-danger">' + (resp.message || 'Save failed') + '</div>');
                }
                $btn.prop('disabled', false).html('<i class="fa fa-save"></i> Save Changes');
                setTimeout(function() { $('#saveStatus').html(''); }, 3000);
            },
            error: function() {
                $('#saveStatus').html('<div class="alert alert-danger">Save failed.</div>');
                $btn.prop('disabled', false).html('<i class="fa fa-save"></i> Save Changes');
            }
        });
    });

    // Warn before leaving with unsaved changes
    $(window).on('beforeunload', function() {
        if (hasChanges) return 'You have unsaved changes.';
    });

    // Image adjustments
    $('.adjust-range').on('input change', function() {
        $('#val_' + $(this).attr('id')).text($(this).val());
    });

    $('#saveAdjustments').click(function() {
        var data = {
            photo_id:    <?php echo $photo->id ?>,
            brightness:  parseInt($('#brightness').val()),
            contrast:    parseInt($('#contrast').val()),
            saturation:  parseInt($('#saturation').val()),
            sharpen:     parseInt($('#sharpen').val()),
            blur:        parseInt($('#blur').val()),
            sepia:       $('#sepia').is(':checked') ? 1 : 0,
            grayscale:   $('#grayscale').is(':checked') ? 1 : 0,
            hue_rotate:  parseInt($('#hue_rotate').val()),
            tint_color:  $('#tint_color').val(),
            tint_amount: parseInt($('#tint_amount').val())
        };

        $.post('<?php echo site_url("admin/save_template_photo_defaults") ?>', data, function() {
            $('#adjustStatus').html('<div class="alert alert-success">Adjustments saved!</div>');
            setTimeout(function() { $('#adjustStatus').html(''); }, 3000);
        }).fail(function() {
            $('#adjustStatus').html('<div class="alert alert-danger">Save failed.</div>');
        });
    });
});
</script>

<?php include VIEWPATH . 'admin/includes/footer.php'; ?>
