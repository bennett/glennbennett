<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<?php include VIEWPATH . 'admin/includes/header.php'; ?>

<section class="content-header">
    <h1>Venue Types <small>Categorize venues and assign templates</small></h1>
</section>

<section class="content">

    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-tags"></i> Venue Types</h3>
            <div class="box-tools">
                <a href="<?php echo site_url('admin/venue_type_edit') ?>" class="btn btn-primary btn-sm">
                    <i class="fa fa-plus"></i> Add Venue Type
                </a>
            </div>
        </div>
        <div class="box-body">
            <?php if (empty($venue_types)): ?>
                <p class="text-muted">No venue types configured yet.</p>
            <?php else: ?>
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Slug</th>
                            <th># Templates</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($venue_types as $vt): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($vt->name) ?></td>
                                <td><code><?php echo htmlspecialchars($vt->slug) ?></code></td>
                                <td><?php echo $vt->template_count ?></td>
                                <td>
                                    <?php if ($vt->is_active): ?>
                                        <span class="label label-success">Active</span>
                                    <?php else: ?>
                                        <span class="label label-default">Inactive</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="<?php echo site_url('admin/venue_type_edit/' . $vt->id) ?>"
                                       class="btn btn-xs btn-info">
                                        <i class="fa fa-edit"></i> Edit
                                    </a>
                                    <?php if ($vt->slug !== 'general'): ?>
                                        <a href="<?php echo site_url('admin/venue_type_delete/' . $vt->id) ?>"
                                           class="btn btn-xs btn-danger"
                                           onclick="return confirm('Delete this venue type?')">
                                            <i class="fa fa-trash"></i>
                                        </a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>

</section>

<?php include VIEWPATH . 'admin/includes/footer.php'; ?>
