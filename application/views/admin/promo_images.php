<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<?php include VIEWPATH . 'admin/includes/header.php'; ?>

<section class="content-header">
    <h1>Promo Image Library <small>Manage images for AI promo generation</small></h1>
</section>

<section class="content">

    <!-- Upload -->
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-upload"></i> Upload Images</h3>
        </div>
        <div class="box-body">
            <div class="form-group">
                <label>Category</label>
                <select id="uploadCategory" class="form-control" style="width: 200px; display: inline-block;">
                    <option value="artist">Artist Photos</option>
                    <option value="venue">Venue Photos</option>
                    <option value="generic" selected>Generic / Theme</option>
                </select>
            </div>
            <div id="dropzone"
                 style="border: 3px dashed #ccc; border-radius: 8px; padding: 40px; text-align: center; cursor: pointer; transition: all 0.2s;">
                <i class="fa fa-cloud-upload" style="font-size: 48px; color: #aaa;"></i>
                <p style="font-size: 16px; color: #888; margin-top: 10px;">
                    Drag & drop images here or <strong>click to browse</strong>
                </p>
                <p class="text-muted" style="font-size: 12px;">JPG, PNG, or WebP, max 10MB</p>
            </div>
            <input type="file" id="fileInput" accept=".jpg,.jpeg,.png,.webp" multiple style="display: none;">
            <div id="uploadProgress" style="margin-top: 15px;"></div>
        </div>
    </div>

    <!-- Category Filter -->
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-th"></i> Images (<span id="imageCount"><?php echo count($images) ?></span>)</h3>
            <div class="box-tools">
                <div class="btn-group" id="categoryFilter">
                    <button class="btn btn-sm btn-default active" data-filter="all">All</button>
                    <button class="btn btn-sm btn-default" data-filter="artist">Artist</button>
                    <button class="btn btn-sm btn-default" data-filter="venue">Venue</button>
                    <button class="btn btn-sm btn-default" data-filter="generic">Generic</button>
                </div>
            </div>
        </div>
        <div class="box-body">
            <div class="row" id="imageGrid">
                <?php if (empty($images)): ?>
                    <div class="col-xs-12" id="emptyMsg">
                        <p class="text-muted">No images uploaded yet.</p>
                    </div>
                <?php endif; ?>
                <?php foreach ($images as $img): ?>
                    <div class="col-md-3 col-sm-4 col-xs-6 image-card" data-category="<?php echo $img->category ?>" style="margin-bottom: 20px;">
                        <div class="thumbnail" style="position: relative;">
                            <div style="background: #f5f5f5; min-height: 180px; display: flex; align-items: center; justify-content: center; padding: 5px;">
                                <img loading="lazy"
                                     src="<?php echo base_url('imgs/promo/' . $img->filename) ?>"
                                     alt="<?php echo htmlspecialchars($img->label ?: $img->original_name) ?>"
                                     style="max-width: 100%; max-height: 200px;">
                            </div>
                            <div class="caption">
                                <p style="margin-bottom: 5px;">
                                    <strong class="editable-label" data-id="<?php echo $img->id ?>" style="cursor: pointer;" title="Click to rename"><?php echo htmlspecialchars($img->label ?: $img->original_name) ?></strong>
                                    <i class="fa fa-pencil text-muted" style="font-size: 10px; cursor: pointer;"></i>
                                </p>
                                <p style="margin-bottom: 5px;">
                                    <select class="form-control input-sm category-select" data-id="<?php echo $img->id ?>" style="width: auto; display: inline-block; font-size: 11px;">
                                        <option value="artist" <?php echo $img->category === 'artist' ? 'selected' : '' ?>>Artist</option>
                                        <option value="venue" <?php echo $img->category === 'venue' ? 'selected' : '' ?>>Venue</option>
                                        <option value="generic" <?php echo $img->category === 'generic' ? 'selected' : '' ?>>Generic</option>
                                    </select>
                                    <span class="text-muted" style="font-size: 11px;"><?php echo $img->width ?>x<?php echo $img->height ?></span>
                                </p>
                                <button class="btn btn-xs btn-danger delete-btn" data-id="<?php echo $img->id ?>"
                                        onclick="return confirm('Delete this image?')">
                                    <i class="fa fa-trash"></i> Delete
                                </button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

</section>

<script>
$(document).ready(function() {
    var $dropzone = $('#dropzone');
    var $fileInput = $('#fileInput');
    var uploadUrl = '<?php echo site_url("admin/promo/upload_image") ?>';

    // Click to browse
    $dropzone.on('click', function() { $fileInput.trigger('click'); });

    $fileInput.on('change', function() {
        handleFiles(this.files);
        this.value = '';
    });

    // Drag and drop
    $dropzone.on('dragover dragenter', function(e) {
        e.preventDefault();
        e.stopPropagation();
        $(this).css({ borderColor: '#3c8dbc', background: '#f0f8ff' });
    });
    $dropzone.on('dragleave drop', function(e) {
        e.preventDefault();
        e.stopPropagation();
        $(this).css({ borderColor: '#ccc', background: '' });
    });
    $dropzone.on('drop', function(e) {
        handleFiles(e.originalEvent.dataTransfer.files);
    });

    function handleFiles(files) {
        for (var i = 0; i < files.length; i++) {
            uploadFile(files[i]);
        }
    }

    function formatSize(bytes) {
        if (bytes < 1024) return bytes + ' B';
        if (bytes < 1048576) return (bytes / 1024).toFixed(1) + ' KB';
        return (bytes / 1048576).toFixed(1) + ' MB';
    }

    function uploadFile(file) {
        var $item = $(
            '<div class="upload-item" style="background: #f9f9f9; border: 1px solid #ddd; border-radius: 4px; padding: 12px; margin-bottom: 10px;">' +
                '<div style="display: flex; align-items: center; gap: 12px;">' +
                    '<div class="upload-thumb" style="width: 60px; height: 60px; background: #eee; border-radius: 3px; overflow: hidden; flex-shrink: 0; display: flex; align-items: center; justify-content: center;">' +
                        '<img style="max-width: 100%; max-height: 100%; object-fit: contain;">' +
                    '</div>' +
                    '<div style="flex: 1; min-width: 0;">' +
                        '<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 4px;">' +
                            '<strong style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">' + file.name + '</strong>' +
                            '<span class="upload-status text-muted" style="flex-shrink: 0; margin-left: 10px;">' +
                                '<i class="fa fa-spinner fa-spin"></i> Uploading...' +
                            '</span>' +
                        '</div>' +
                        '<div class="progress" style="margin: 0; height: 20px; border-radius: 3px;">' +
                            '<div class="progress-bar progress-bar-striped active" style="width: 0%; min-width: 30px; line-height: 20px; font-size: 11px;">0%</div>' +
                        '</div>' +
                        '<div class="upload-details text-muted" style="font-size: 11px; margin-top: 4px;">' + formatSize(file.size) + '</div>' +
                    '</div>' +
                '</div>' +
            '</div>'
        );

        var reader = new FileReader();
        reader.onload = function(e) { $item.find('.upload-thumb img').attr('src', e.target.result); };
        reader.readAsDataURL(file);

        $('#uploadProgress').append($item);

        var formData = new FormData();
        formData.append('image_file', file);
        formData.append('category', $('#uploadCategory').val());

        $.ajax({
            url: uploadUrl,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            xhr: function() {
                var xhr = new XMLHttpRequest();
                xhr.upload.addEventListener('progress', function(e) {
                    if (e.lengthComputable) {
                        var pct = Math.round(e.loaded / e.total * 100);
                        $item.find('.progress-bar').css('width', pct + '%').text(pct + '%');
                    }
                });
                return xhr;
            },
            success: function(resp) {
                if (resp.status === 'ok') {
                    $item.css({ background: '#f0fff0', borderColor: '#d6e9c6' });
                    $item.find('.upload-status').html('<i class="fa fa-check-circle text-success"></i> <span class="text-success">Done</span>');
                    $item.find('.progress-bar').removeClass('progress-bar-striped active').addClass('progress-bar-success').css('width', '100%').text('100%');
                    $item.find('.upload-details').text(resp.width + 'x' + resp.height);
                    addImageCard(resp);
                } else {
                    $item.css({ background: '#fdf7f7', borderColor: '#ebccd1' });
                    $item.find('.upload-status').html('<i class="fa fa-times-circle text-danger"></i> <span class="text-danger">Failed</span>');
                    $item.find('.progress-bar').removeClass('progress-bar-striped active').addClass('progress-bar-danger').css('width', '100%').text('Error');
                    $item.find('.upload-details').addClass('text-danger').text(resp.message || 'Upload failed');
                }
            },
            error: function() {
                $item.css({ background: '#fdf7f7', borderColor: '#ebccd1' });
                $item.find('.upload-status').html('<i class="fa fa-times-circle text-danger"></i> <span class="text-danger">Failed</span>');
                $item.find('.progress-bar').removeClass('progress-bar-striped active').addClass('progress-bar-danger').css('width', '100%').text('Error');
                $item.find('.upload-details').addClass('text-danger').text('Connection error');
            }
        });
    }

    function addImageCard(data) {
        $('#emptyMsg').remove();
        var card = '<div class="col-md-3 col-sm-4 col-xs-6 image-card" data-category="' + data.category + '" style="margin-bottom: 20px;">' +
            '<div class="thumbnail" style="position: relative;">' +
                '<div style="background: #f5f5f5; min-height: 180px; display: flex; align-items: center; justify-content: center; padding: 5px;">' +
                    '<img src="<?php echo base_url("imgs/promo/") ?>' + data.filename + '" style="max-width: 100%; max-height: 200px;">' +
                '</div>' +
                '<div class="caption">' +
                    '<p style="margin-bottom: 5px;"><strong class="editable-label" data-id="' + data.id + '" style="cursor: pointer;" title="Click to rename">' + data.label + '</strong> <i class="fa fa-pencil text-muted" style="font-size: 10px; cursor: pointer;"></i></p>' +
                    '<p style="margin-bottom: 5px;">' +
                        '<select class="form-control input-sm category-select" data-id="' + data.id + '" style="width: auto; display: inline-block; font-size: 11px;">' +
                            '<option value="artist"' + (data.category === 'artist' ? ' selected' : '') + '>Artist</option>' +
                            '<option value="venue"' + (data.category === 'venue' ? ' selected' : '') + '>Venue</option>' +
                            '<option value="generic"' + (data.category === 'generic' ? ' selected' : '') + '>Generic</option>' +
                        '</select> ' +
                        '<span class="text-muted" style="font-size: 11px;">' + data.width + 'x' + data.height + '</span>' +
                    '</p>' +
                    '<button class="btn btn-xs btn-danger delete-btn" data-id="' + data.id + '" onclick="return confirm(\'Delete this image?\')"><i class="fa fa-trash"></i> Delete</button>' +
                '</div>' +
            '</div></div>';
        $('#imageGrid').append(card);
        $('#imageCount').text($('.image-card').length);
    }

    // Category filter
    $('#categoryFilter').on('click', 'button', function() {
        $('#categoryFilter button').removeClass('active');
        $(this).addClass('active');
        var filter = $(this).data('filter');
        if (filter === 'all') {
            $('.image-card').show();
        } else {
            $('.image-card').hide().filter('[data-category="' + filter + '"]').show();
        }
    });

    // Inline category change
    $(document).on('change', '.category-select', function() {
        var id = $(this).data('id');
        var category = $(this).val();
        var $card = $(this).closest('.image-card');
        $.post('<?php echo site_url("admin/promo/update_image/") ?>' + id, { category: category }, function(resp) {
            if (resp.status === 'ok') {
                $card.attr('data-category', resp.category);
            }
        }, 'json');
    });

    // Inline label edit
    $(document).on('click', '.editable-label, .editable-label + .fa-pencil', function() {
        var $label = $(this).hasClass('editable-label') ? $(this) : $(this).prev('.editable-label');
        var id = $label.data('id');
        var current = $label.text();

        var $input = $('<input type="text" class="form-control input-sm" style="display: inline-block; width: 160px;">').val(current);
        $label.replaceWith($input);
        $input.next('.fa-pencil').hide();
        $input.focus().select();

        function save() {
            var newLabel = $.trim($input.val()) || current;
            $.post('<?php echo site_url("admin/promo/update_image/") ?>' + id, { label: newLabel }, function(resp) {
                if (resp.status === 'ok') {
                    var $new = $('<strong class="editable-label" data-id="' + id + '" style="cursor: pointer;" title="Click to rename"></strong>').text(resp.label);
                    $input.replaceWith($new);
                    $new.after(' <i class="fa fa-pencil text-muted" style="font-size: 10px; cursor: pointer;"></i>');
                }
            }, 'json');
        }

        $input.on('blur', save).on('keydown', function(e) {
            if (e.which === 13) save();
            if (e.which === 27) {
                var $new = $('<strong class="editable-label" data-id="' + id + '" style="cursor: pointer;" title="Click to rename"></strong>').text(current);
                $input.replaceWith($new);
                $new.after(' <i class="fa fa-pencil text-muted" style="font-size: 10px; cursor: pointer;"></i>');
            }
        });
    });

    // Delete
    $(document).on('click', '.delete-btn', function() {
        var $btn = $(this);
        var id = $btn.data('id');
        $.ajax({
            url: '<?php echo site_url("admin/promo/delete_image/") ?>' + id,
            type: 'POST',
            dataType: 'json',
            success: function(resp) {
                if (resp.status === 'ok') {
                    $btn.closest('.image-card').fadeOut(300, function() {
                        $(this).remove();
                        $('#imageCount').text($('.image-card').length);
                        if ($('.image-card').length === 0) {
                            $('#imageGrid').html('<div class="col-xs-12" id="emptyMsg"><p class="text-muted">No images uploaded yet.</p></div>');
                        }
                    });
                }
            }
        });
        return false;
    });
});
</script>

<?php include VIEWPATH . 'admin/includes/footer.php'; ?>
