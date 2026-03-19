<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<?php include VIEWPATH . 'admin/includes/header.php'; ?>

<section class="content-header">
    <h1>Share Templates <small>Photo + background combinations</small></h1>
</section>

<section class="content">

    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-th"></i> Templates (<?php echo count($templates) ?>)</h3>
        </div>
        <div class="box-body">
            <?php if (empty($templates)): ?>
                <p class="text-muted">No templates yet. Upload backgrounds and photos first.</p>
            <?php else: ?>
                <div class="row">
                    <?php foreach ($templates as $tpl): ?>
                        <div class="col-md-6 col-sm-6 col-xs-12" style="margin-bottom: 20px;">
                            <div class="thumbnail" style="position: relative;">
                                <img src="<?php echo site_url('admin/preview_template/' . $tpl->id) ?>"
                                     alt="Template #<?php echo $tpl->id ?>"
                                     style="width: 100%; height: auto;">

                                <div class="caption">
                                    <p style="margin-bottom: 5px;">
                                        <strong><?php echo htmlspecialchars($tpl->bg_name) ?></strong> +
                                        <strong><?php echo htmlspecialchars($tpl->photo_name) ?></strong>
                                    </p>

                                    <div style="margin-top: 10px;">
                                        <a href="<?php echo site_url('admin/template_editor/' . $tpl->id) ?>"
                                           class="btn btn-xs btn-info" title="Customize">
                                            <i class="fa fa-sliders"></i> Customize
                                        </a>

                                        <button class="btn btn-xs toggle-btn <?php echo $tpl->is_active ? 'btn-success' : 'btn-default' ?>"
                                                data-id="<?php echo $tpl->id ?>"
                                                title="Toggle Active">
                                            <i class="fa <?php echo $tpl->is_active ? 'fa-check-circle' : 'fa-circle-o' ?>"></i>
                                            <?php echo $tpl->is_active ? 'Active' : 'Inactive' ?>
                                        </button>
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
    $('.toggle-btn').click(function() {
        var $btn = $(this);
        var id = $btn.data('id');

        $.ajax({
            url: '<?php echo site_url("admin/toggle_template/") ?>' + id,
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
});
</script>

<?php include VIEWPATH . 'admin/includes/footer.php'; ?>
