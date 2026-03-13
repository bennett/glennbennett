<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<?php include VIEWPATH . 'admin/includes/header.php'; ?>

<section class="content-header">
    <h1>Venues <small>Manage venue image assignments</small></h1>
</section>

<section class="content">

    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-map-marker"></i> Venues</h3>
            <div class="box-tools">
                <a href="<?php echo site_url('admin/venue_edit') ?>" class="btn btn-primary btn-sm">
                    <i class="fa fa-plus"></i> Add Venue
                </a>
            </div>
        </div>
        <div class="box-body">
            <?php if (empty($venues)): ?>
                <p class="text-muted">No venues configured yet.</p>
            <?php else: ?>
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Match Pattern</th>
                            <th>Match Type</th>
                            <th>Logo</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($venues as $venue): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($venue->name) ?></td>
                                <td><code><?php echo htmlspecialchars($venue->match_pattern) ?></code></td>
                                <td>
                                    <span class="label label-info"><?php echo $venue->match_type ?></span>
                                </td>
                                <td>
                                    <?php if ($venue->venue_logo): ?>
                                        <img src="<?php echo $venue->venue_logo ?>" style="max-height: 40px;">
                                    <?php else: ?>
                                        <span class="text-muted">None</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($venue->is_active): ?>
                                        <span class="label label-success">Active</span>
                                    <?php else: ?>
                                        <span class="label label-default">Inactive</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="<?php echo site_url('admin/venue_edit/' . $venue->id) ?>"
                                       class="btn btn-xs btn-info">
                                        <i class="fa fa-edit"></i> Edit
                                    </a>
                                    <a href="<?php echo site_url('admin/venue_delete/' . $venue->id) ?>"
                                       class="btn btn-xs btn-danger"
                                       onclick="return confirm('Delete this venue?')">
                                        <i class="fa fa-trash"></i>
                                    </a>
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
