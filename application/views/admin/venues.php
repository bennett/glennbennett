<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<?php include VIEWPATH . 'admin/includes/header.php'; ?>

<section class="content-header">
    <h1>Performance Venues <small>Manage performance venue image assignments</small></h1>
</section>

<section class="content">

    <div class="callout callout-info">
        <h4>How Performance Venues Work</h4>
        <p>When a calendar event is shared on social media, the system matches the event title against each venue's <strong>match pattern</strong> to determine which share image templates to use. Each venue can have its own assigned templates, or fall back to templates assigned to its <strong>venue type</strong> (e.g. Winery, Farmers Market). If no match is found, the default template pool is used.</p>
        <p style="margin-bottom: 0;">Venues can also store a <strong>logo</strong> for display on calendar listings, and optional <strong>logistics</strong> details like drive time, setup time, and address.</p>
    </div>

    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-map-marker"></i> Performance Venues</h3>
            <div class="box-tools">
                <a href="<?php echo site_url('admin/venue_edit') ?>" class="btn btn-primary btn-sm">
                    <i class="fa fa-plus"></i> Add Performance Venue
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
                            <th>Type</th>
                            <th>Logo</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($venues as $venue): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($venue->name) ?></td>
                                <td><?php echo $venue->venue_type_name ?: '<span class="text-muted">—</span>' ?></td>
                                <td>
                                    <?php if ($venue->venue_logo): ?>
                                        <img src="<?php echo $venue->venue_logo ?>" style="max-height: 40px;">
                                    <?php else: ?>
                                        <span class="text-muted">—</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($venue->is_active): ?>
                                        <span class="label label-success" style="font-size: 13px; padding: 4px 8px;">Active</span>
                                    <?php else: ?>
                                        <span class="label label-default" style="font-size: 13px; padding: 4px 8px;">Inactive</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="<?php echo site_url('admin/venue_edit/' . $venue->id) ?>"
                                       class="btn btn-sm btn-info">
                                        <i class="fa fa-edit"></i> Edit
                                    </a>
                                    <a href="<?php echo site_url('admin/venue_delete/' . $venue->id) ?>"
                                       class="btn btn-sm btn-danger"
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
