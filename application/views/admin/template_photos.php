<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<?php include VIEWPATH . 'admin/includes/header.php'; ?>

<section class="content-header">
    <h1>Artist Photos <small>PNG cutouts for gig share templates</small></h1>
</section>

<section class="content">

    <!-- Dropzone Upload -->
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-upload"></i> Upload Artist Photos</h3>
        </div>
        <div class="box-body">
            <div id="dropzone"
                 style="border: 3px dashed #ccc; border-radius: 8px; padding: 40px; text-align: center; cursor: pointer; transition: all 0.2s;">
                <i class="fa fa-cloud-upload" style="font-size: 48px; color: #aaa;"></i>
                <p style="font-size: 16px; color: #888; margin-top: 10px;">
                    Drag & drop PNG files here or <strong>click to browse</strong>
                </p>
                <p class="text-muted" style="font-size: 12px;">PNG only (transparent cutouts), max 20MB. Auto-trimmed on upload.</p>
            </div>
            <input type="file" id="fileInput" accept=".png" multiple style="display: none;">
            <div id="uploadProgress" style="margin-top: 15px;"></div>
        </div>
    </div>

    <!-- Grid -->
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-th"></i> Artist Photos (<span id="photoCount"><?php echo count($photos) ?></span>)</h3>
        </div>
        <div class="box-body">
            <div class="row" id="photoGrid">
                <?php if (empty($photos)): ?>
                    <div class="col-xs-12" id="emptyMsg">
                        <p class="text-muted">No photos uploaded yet.</p>
                    </div>
                <?php endif; ?>
                <?php foreach ($photos as $photo): ?>
                    <div class="col-md-4 col-sm-6 col-xs-6" style="margin-bottom: 20px;">
                        <div class="thumbnail" style="position: relative;">
                            <div style="background: repeating-conic-gradient(#ccc 0% 25%, #fff 0% 50%) 50% / 20px 20px; min-height: 250px; display: flex; align-items: center; justify-content: center; padding: 10px;">
                                <img loading="lazy"
                                     src="<?php echo base_url('imgs/template-photos/' . $photo->filename) ?>"
                                     alt="<?php echo htmlspecialchars($photo->original_name) ?>"
                                     style="max-width: 100%; max-height: 350px;">
                            </div>

                            <div class="caption">
                                <p style="margin-bottom: 5px;">
                                    <strong class="editable-name" data-id="<?php echo $photo->id ?>" data-type="photo" style="cursor: pointer;" title="Click to rename"><?php echo htmlspecialchars($photo->original_name) ?></strong>
                                    <i class="fa fa-pencil text-muted" style="font-size: 10px; cursor: pointer;"></i><br>
                                    <span class="text-muted" style="font-size: 11px;"><?php echo $photo->width ?>x<?php echo $photo->height ?></span>
                                </p>

                                <div style="margin-top: 10px;">
                                    <a href="<?php echo site_url('admin/artist_photo_editor/' . $photo->id) ?>"
                                       class="btn btn-xs btn-primary" title="Edit Image">
                                        <i class="fa fa-pencil"></i> Edit
                                    </a>

                                    <a href="<?php echo site_url('admin/template_photo_defaults/' . $photo->id) ?>"
                                       class="btn btn-xs <?php echo $photo->has_defaults ? 'btn-info' : 'btn-warning' ?>" title="<?php echo $photo->has_defaults ? 'Edit Position Defaults' : 'Set defaults to generate share templates' ?>">
                                        <i class="fa <?php echo $photo->has_defaults ? 'fa-arrows' : 'fa-exclamation-triangle' ?>"></i>
                                        <?php echo $photo->has_defaults ? 'Defaults' : 'Set Defaults' ?>
                                    </a>

                                    <button class="btn btn-xs toggle-btn <?php echo $photo->is_active ? 'btn-success' : 'btn-default' ?>"
                                            data-id="<?php echo $photo->id ?>"
                                            title="Toggle Active">
                                        <i class="fa <?php echo $photo->is_active ? 'fa-check-circle' : 'fa-circle-o' ?>"></i>
                                        <?php echo $photo->is_active ? 'Active' : 'Inactive' ?>
                                    </button>

                                    <a href="<?php echo site_url('admin/delete_template_photo/' . $photo->id) ?>"
                                       class="btn btn-xs btn-danger"
                                       onclick="return confirm('Delete this artist photo? Templates using it will be marked as orphaned.')"
                                       title="Delete">
                                        <i class="fa fa-trash"></i>
                                    </a>
                                </div>
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
    var uploadUrl = '<?php echo site_url("admin/upload_template_photo") ?>';

    // Click to browse
    $dropzone.on('click', function() {
        $fileInput.trigger('click');
    });

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
        var files = e.originalEvent.dataTransfer.files;
        handleFiles(files);
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
        if (!/\.png$/i.test(file.name)) {
            $('#uploadProgress').append(
                '<div class="upload-item" style="background: #fdf7f7; border: 1px solid #ebccd1; border-radius: 4px; padding: 12px; margin-bottom: 10px;">' +
                '<i class="fa fa-times-circle text-danger"></i> ' +
                '<strong>' + file.name + '</strong> — not a PNG file' +
                '</div>'
            );
            return;
        }

        var $item = $(
            '<div class="upload-item" style="background: #f9f9f9; border: 1px solid #ddd; border-radius: 4px; padding: 12px; margin-bottom: 10px;">' +
                '<div style="display: flex; align-items: center; gap: 12px;">' +
                    '<div class="upload-thumb" style="width: 60px; height: 80px; background: repeating-conic-gradient(#ccc 0% 25%, #fff 0% 50%) 50% / 10px 10px; border-radius: 3px; overflow: hidden; flex-shrink: 0; display: flex; align-items: center; justify-content: center;">' +
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
                        '<div class="upload-details text-muted" style="font-size: 11px; margin-top: 4px;">' +
                            formatSize(file.size) +
                        '</div>' +
                    '</div>' +
                '</div>' +
            '</div>'
        );

        // Show thumbnail preview immediately
        var reader = new FileReader();
        reader.onload = function(e) {
            $item.find('.upload-thumb img').attr('src', e.target.result);
        };
        reader.readAsDataURL(file);

        $('#uploadProgress').append($item);

        var formData = new FormData();
        formData.append('image_file', file);
        var startTime = Date.now();

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

                        var elapsed = (Date.now() - startTime) / 1000;
                        var speed = e.loaded / elapsed;
                        var remaining = (e.total - e.loaded) / speed;

                        var detail = formatSize(e.loaded) + ' / ' + formatSize(e.total);
                        if (pct < 100 && remaining > 0) {
                            detail += ' — ' + (remaining < 60 ? Math.ceil(remaining) + 's left' : Math.ceil(remaining / 60) + 'm left');
                        }
                        $item.find('.upload-details').text(detail);

                        if (pct >= 100) {
                            $item.find('.upload-status').html('<i class="fa fa-cog fa-spin"></i> Trimming...');
                            $item.find('.progress-bar').removeClass('active');
                        }
                    }
                });
                return xhr;
            },
            success: function(resp) {
                var elapsed = ((Date.now() - startTime) / 1000).toFixed(1);
                if (resp.status === 'ok') {
                    $item.css({ background: '#f0fff0', borderColor: '#d6e9c6' });
                    $item.find('.upload-status').html('<i class="fa fa-check-circle text-success"></i> <span class="text-success">Done</span>');
                    $item.find('.progress-bar')
                        .removeClass('progress-bar-primary progress-bar-striped active')
                        .addClass('progress-bar-success')
                        .css('width', '100%').text('100%');
                    $item.find('.upload-details').text(resp.width + 'x' + resp.height + ' — ' + elapsed + 's');
                    addPhotoCard(resp);
                } else {
                    $item.css({ background: '#fdf7f7', borderColor: '#ebccd1' });
                    $item.find('.upload-status').html('<i class="fa fa-times-circle text-danger"></i> <span class="text-danger">Failed</span>');
                    $item.find('.progress-bar')
                        .removeClass('progress-bar-primary progress-bar-striped active')
                        .addClass('progress-bar-danger')
                        .css('width', '100%').text('Error');
                    $item.find('.upload-details').addClass('text-danger').text(resp.message || 'Upload failed');
                }
            },
            error: function() {
                $item.css({ background: '#fdf7f7', borderColor: '#ebccd1' });
                $item.find('.upload-status').html('<i class="fa fa-times-circle text-danger"></i> <span class="text-danger">Failed</span>');
                $item.find('.progress-bar')
                    .removeClass('progress-bar-primary progress-bar-striped active')
                    .addClass('progress-bar-danger')
                    .css('width', '100%').text('Error');
                $item.find('.upload-details').addClass('text-danger').text('Connection error');
            }
        });
    }

    function addPhotoCard(data) {
        $('#emptyMsg').remove();

        var card = '<div class="col-md-4 col-sm-6 col-xs-6" style="margin-bottom: 20px;">' +
            '<div class="thumbnail" style="position: relative;">' +
            '<div style="background: repeating-conic-gradient(#ccc 0% 25%, #fff 0% 50%) 50% / 20px 20px; min-height: 250px; display: flex; align-items: center; justify-content: center; padding: 10px;">' +
            '<img src="<?php echo base_url("imgs/template-photos/") ?>' + data.filename + '"' +
            ' alt="' + data.original_name + '"' +
            ' style="max-width: 100%; max-height: 350px;">' +
            '</div>' +
            '<div class="caption">' +
            '<p style="margin-bottom: 5px;"><strong>' + data.original_name + '</strong><br>' +
            '<span class="text-muted" style="font-size: 11px;">' + data.width + 'x' + data.height + '</span></p>' +
            '<div style="margin-top: 10px;">' +
            '<a href="<?php echo site_url("admin/artist_photo_editor/") ?>' + data.id + '" class="btn btn-xs btn-primary"><i class="fa fa-pencil"></i> Edit</a> ' +
            '<a href="<?php echo site_url("admin/template_photo_defaults/") ?>' + data.id + '" class="btn btn-xs btn-warning" title="Set defaults to generate share templates"><i class="fa fa-exclamation-triangle"></i> Set Defaults</a> ' +
            '<button class="btn btn-xs btn-success toggle-btn" data-id="' + data.id + '"><i class="fa fa-check-circle"></i> Active</button> ' +
            '<a href="<?php echo site_url("admin/delete_template_photo/") ?>' + data.id + '" class="btn btn-xs btn-danger" onclick="return confirm(\'Delete this artist photo? Templates using it will be marked as orphaned.\')"><i class="fa fa-trash"></i></a>' +
            '</div></div></div></div>';

        $('#photoGrid').append(card);

        var count = parseInt($('#photoCount').text()) + 1;
        $('#photoCount').text(count);
    }

    // Toggle active
    $(document).on('click', '.toggle-btn', function() {
        var $btn = $(this);
        var id = $btn.data('id');

        $.ajax({
            url: '<?php echo site_url("admin/toggle_template_photo/") ?>' + id,
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

    // Inline rename
    $(document).on('click', '.editable-name, .editable-name + .fa-pencil', function() {
        var $name = $(this).hasClass('editable-name') ? $(this) : $(this).prev('.editable-name');
        var id = $name.data('id');
        var type = $name.data('type');
        var current = $name.text();

        var $input = $('<input type="text" class="form-control input-sm" style="display: inline-block; width: 200px;">').val(current);
        $name.replaceWith($input);
        $input.next('.fa-pencil').hide();
        $input.focus().select();

        function save() {
            var newName = $.trim($input.val()) || current;
            var url = type === 'photo'
                ? '<?php echo site_url("admin/rename_template_photo") ?>'
                : '<?php echo site_url("admin/rename_template_background") ?>';

            $.post(url, { id: id, name: newName }, function(resp) {
                if (resp.status === 'ok') {
                    var $new = $('<strong class="editable-name" data-id="' + id + '" data-type="' + type + '" style="cursor: pointer;" title="Click to rename"></strong>').text(resp.name);
                    $input.replaceWith($new);
                    $new.after(' <i class="fa fa-pencil text-muted" style="font-size: 10px; cursor: pointer;"></i>');
                }
            }, 'json');
        }

        $input.on('blur', save).on('keydown', function(e) {
            if (e.which === 13) save();
            if (e.which === 27) {
                var $new = $('<strong class="editable-name" data-id="' + id + '" data-type="' + type + '" style="cursor: pointer;" title="Click to rename"></strong>').text(current);
                $input.replaceWith($new);
                $new.after(' <i class="fa fa-pencil text-muted" style="font-size: 10px; cursor: pointer;"></i>');
            }
        });
    });
});
</script>

<?php include VIEWPATH . 'admin/includes/footer.php'; ?>
