<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<?php include VIEWPATH . 'admin/includes/header.php'; ?>

<section class="content-header">
    <h1>Share Templates <small>Photo + background combinations</small></h1>
</section>

<section class="content">

    <?php
    $orphaned_count = 0;
    $needs_work_count = 0;
    $ready_count = 0;
    foreach ($templates as $t) {
        if ($t->is_orphaned) $orphaned_count++;
        elseif ( ! $t->is_ready) $needs_work_count++;
        else $ready_count++;
    }
    ?>

    <?php if ($orphaned_count > 0): ?>
        <div class="callout callout-danger">
            <h4><i class="fa fa-chain-broken"></i> <?php echo $orphaned_count ?> Orphaned Template(s)</h4>
            <p>These templates have a deleted background or photo and can no longer be used.</p>
            <a href="<?php echo site_url('admin/delete_orphaned_templates') ?>"
               class="btn btn-danger btn-sm"
               onclick="return confirm('Delete all <?php echo $orphaned_count ?> orphaned template(s)?')">
                <i class="fa fa-trash"></i> Delete All Orphaned
            </a>
        </div>
    <?php endif; ?>

    <?php if ($needs_work_count > 0): ?>
        <div class="callout callout-warning">
            <h4><i class="fa fa-wrench"></i> <?php echo $needs_work_count ?> Template(s) Need Work</h4>
            <p>These templates haven't been customized yet or their assets were updated. Customize them and mark as Ready.</p>
        </div>
    <?php endif; ?>

    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-th"></i> Share Templates (<?php echo count($templates) ?>)</h3>
            <span class="pull-right">
                <span class="label label-success" style="font-size: 13px;"><?php echo $ready_count ?> Ready</span>
                <span class="label label-warning" style="font-size: 13px;"><?php echo $needs_work_count ?> Needs Work</span>
                <?php if ($orphaned_count): ?>
                    <span class="label label-danger" style="font-size: 13px;"><?php echo $orphaned_count ?> Orphaned</span>
                <?php endif; ?>
            </span>
        </div>
        <div class="box-body">
            <?php if (empty($templates)): ?>
                <p class="text-muted">No templates yet. Upload backgrounds and photos first.</p>
            <?php else: ?>
                <div class="row">
                    <?php foreach ($templates as $tpl): ?>
                        <?php
                        if ($tpl->is_orphaned) {
                            $border_color = '#dd4b39';
                            $status_label = '<span class="label label-danger"><i class="fa fa-chain-broken"></i> Orphaned</span>';
                        } elseif ( ! $tpl->is_ready) {
                            $border_color = '#f39c12';
                            $status_label = '<span class="label label-warning"><i class="fa fa-wrench"></i> Needs Work</span>';
                        } else {
                            $border_color = '#00a65a';
                            $status_label = '<span class="label label-success"><i class="fa fa-check"></i> Ready</span>';
                        }
                        ?>
                        <div class="col-md-6 col-sm-6 col-xs-12" style="margin-bottom: 20px;">
                            <div class="thumbnail" style="position: relative; border-left: 4px solid <?php echo $border_color ?>;">

                                <div style="position: absolute; top: 8px; right: 8px; z-index: 1; font-size: 13px; padding: 4px 8px;">
                                    <?php echo $status_label ?>
                                </div>

                                <?php if ($tpl->is_orphaned): ?>
                                    <div style="min-height: 200px; background: #f5f5f5; display: flex; align-items: center; justify-content: center; color: #999;">
                                        <div class="text-center">
                                            <i class="fa fa-chain-broken" style="font-size: 48px;"></i>
                                            <p style="margin-top: 10px;">Missing <?php echo empty($tpl->bg_filename) ? 'background' : '' ?><?php echo empty($tpl->bg_filename) && empty($tpl->photo_filename) ? ' & ' : '' ?><?php echo empty($tpl->photo_filename) ? 'artist photo' : '' ?></p>
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <?php
                                        $cache_file = 'imgs/template-cache/preview-' . $tpl->id . '.png';
                                        $cache_path = FCPATH . $cache_file;
                                        $cache_bust = file_exists($cache_path) ? filemtime($cache_path) : time();
                                        $img_src = file_exists($cache_path)
                                            ? base_url($cache_file) . '?t=' . $cache_bust
                                            : site_url('admin/preview_template/' . $tpl->id);
                                    ?>
                                    <img loading="lazy"
                                         src="<?php echo $img_src ?>"
                                         alt="Template #<?php echo $tpl->id ?>"
                                         style="width: 100%; height: auto; aspect-ratio: 1024/541; background: #e0e0e0;">
                                <?php endif; ?>

                                <div class="caption">
                                    <p style="margin-bottom: 8px;">
                                        <strong class="editable-name" data-id="<?php echo $tpl->id ?>" style="font-size: 14px; cursor: pointer;" title="Click to rename"><?php echo htmlspecialchars($tpl->name ?: $tpl->bg_name . '_' . $tpl->photo_name) ?></strong>
                                        <i class="fa fa-pencil text-muted" style="font-size: 10px; cursor: pointer;"></i>
                                    </p>

                                    <?php if ( ! empty($tpl->venues)): ?>
                                        <div style="margin-bottom: 8px;">
                                            <span class="label label-warning" style="font-size: 13px; padding: 4px 8px;"><?php echo htmlspecialchars($tpl->venues[0]->name) ?></span>
                                        </div>
                                    <?php elseif ( ! empty($tpl->venue_types) && $tpl->venue_types[0]->slug !== 'general'): ?>
                                        <div style="margin-bottom: 8px;">
                                            <span class="label label-primary" style="font-size: 13px; padding: 4px 8px;"><?php echo htmlspecialchars($tpl->venue_types[0]->name) ?></span>
                                        </div>
                                    <?php endif; ?>

                                    <div style="margin-top: 10px;">
                                        <?php if ($tpl->is_orphaned): ?>
                                            <a href="<?php echo site_url('admin/delete_template/' . $tpl->id) ?>"
                                               class="btn btn-sm btn-danger"
                                               onclick="return confirm('Delete this orphaned template?')">
                                                <i class="fa fa-trash"></i> Delete
                                            </a>
                                        <?php else: ?>
                                            <a href="<?php echo site_url('admin/template_editor/' . $tpl->id) ?>"
                                               class="btn btn-sm btn-info">
                                                <i class="fa fa-sliders"></i> Customize
                                            </a>

                                            <button class="btn btn-sm ready-btn <?php echo $tpl->is_ready ? 'btn-success' : 'btn-default' ?>"
                                                    data-id="<?php echo $tpl->id ?>">
                                                <i class="fa <?php echo $tpl->is_ready ? 'fa-check' : 'fa-circle-o' ?>"></i>
                                                <?php echo $tpl->is_ready ? 'Ready' : 'Not Ready' ?>
                                            </button>
                                        <?php endif; ?>
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

<script>
$(document).ready(function() {
    $('.ready-btn').click(function() {
        var $btn = $(this);
        var id = $btn.data('id');

        $.ajax({
            url: '<?php echo site_url("admin/toggle_template_ready/") ?>' + id,
            type: 'POST',
            dataType: 'json',
            success: function(resp) {
                if (resp.status === 'ok') {
                    if (resp.is_ready) {
                        $btn.removeClass('btn-default').addClass('btn-success');
                        $btn.html('<i class="fa fa-check"></i> Ready');
                    } else {
                        $btn.removeClass('btn-success').addClass('btn-default');
                        $btn.html('<i class="fa fa-circle-o"></i> Not Ready');
                    }
                }
            }
        });
    });

    // Inline rename
    $(document).on('click', '.editable-name, .editable-name + .fa-pencil', function() {
        var $name = $(this).hasClass('editable-name') ? $(this) : $(this).prev('.editable-name');
        var id = $name.data('id');
        var current = $name.text();

        var $input = $('<input type="text" class="form-control input-sm" style="display: inline-block; width: 250px; font-size: 14px;">').val(current);
        $name.replaceWith($input);
        $input.next('.fa-pencil').hide();
        $input.focus().select();

        function save() {
            var newName = $.trim($input.val()) || current;
            $.post('<?php echo site_url("admin/rename_template") ?>', { id: id, name: newName }, function(resp) {
                if (resp.status === 'ok') {
                    var $new = $('<strong class="editable-name" data-id="' + id + '" style="font-size: 14px; cursor: pointer;" title="Click to rename"></strong>').text(resp.name);
                    $input.replaceWith($new);
                    $new.after(' <i class="fa fa-pencil text-muted" style="font-size: 10px; cursor: pointer;"></i>');
                }
            }, 'json');
        }

        $input.on('blur', save).on('keydown', function(e) {
            if (e.which === 13) save();
            if (e.which === 27) {
                var $new = $('<strong class="editable-name" data-id="' + id + '" style="font-size: 14px; cursor: pointer;" title="Click to rename"></strong>').text(current);
                $input.replaceWith($new);
                $new.after(' <i class="fa fa-pencil text-muted" style="font-size: 10px; cursor: pointer;"></i>');
            }
        });
    });
});
</script>

<?php include VIEWPATH . 'admin/includes/footer.php'; ?>
