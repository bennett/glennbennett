<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<?php include VIEWPATH . 'admin/includes/header.php'; ?>

<section class="content-header">
    <h1><?php echo $venue_type ? 'Edit' : 'Add' ?> Venue Type</h1>
</section>

<section class="content">

    <div class="row">
        <div class="col-md-8">
            <?php echo form_open('admin/venue_type_save'); ?>

            <?php if ($venue_type): ?>
                <input type="hidden" name="id" value="<?php echo $venue_type->id ?>">
            <?php endif; ?>

            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Type Details</h3>
                </div>
                <div class="box-body">
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" class="form-control" id="name" name="name"
                               value="<?php echo set_value('name', $venue_type ? $venue_type->name : '') ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="sort_order">Sort Order</label>
                        <input type="number" class="form-control" id="sort_order" name="sort_order"
                               value="<?php echo set_value('sort_order', $venue_type ? $venue_type->sort_order : 0) ?>" min="0">
                    </div>

                    <div class="form-group">
                        <label>
                            <input type="checkbox" name="is_active" value="1"
                                   <?php echo (!$venue_type || $venue_type->is_active) ? 'checked' : '' ?>>
                            Active
                        </label>
                    </div>
                </div>
            </div>

            <?php if ( ! empty($templates)): ?>
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-th-large"></i> Assign Templates</h3>
                    <p class="box-subtitle text-muted" style="margin-top: 5px;">Events at venues of this type will use these templates for share images.</p>
                </div>
                <div class="box-body">
                    <div class="row">
                        <?php foreach ($templates as $tpl): ?>
                            <div class="col-md-4 col-sm-6" style="margin-bottom: 15px;">
                                <label style="cursor: pointer; display: block;">
                                    <div class="thumbnail" style="margin-bottom: 5px; <?php echo in_array($tpl->id, $assigned_template_ids) ? 'border: 3px solid #3c8dbc;' : '' ?>">
                                        <img loading="lazy"
                                             src="<?php echo site_url('admin/preview_template/' . $tpl->id) ?>"
                                             alt="Template #<?php echo $tpl->id ?>"
                                             style="width: 100%; height: auto; min-height: 120px; background: #f5f5f5;">
                                    </div>
                                    <input type="checkbox" name="template_ids[]" value="<?php echo $tpl->id ?>"
                                           <?php echo in_array($tpl->id, $assigned_template_ids) ? 'checked' : '' ?>>
                                    <?php echo htmlspecialchars($tpl->bg_name) ?> + <?php echo htmlspecialchars($tpl->photo_name) ?>
                                </label>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <div class="form-group">
                <button type="submit" class="btn btn-success btn-lg">
                    <i class="fa fa-save"></i> Save Venue Type
                </button>
                <a href="<?php echo site_url('admin/venue_types') ?>" class="btn btn-default btn-lg">Cancel</a>
            </div>

            <?php echo form_close(); ?>
        </div>
    </div>

</section>

<?php include VIEWPATH . 'admin/includes/footer.php'; ?>
