<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php include 'includes/header.php' ?>

<section class="content-header">
    <h1>Duplicate Events</h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo site_url('admin') ?>"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li class="active">Duplicate Events</li>
    </ol>
</section>

<section class="content">
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Past <?php echo count($events) ?> Performances</h3>
            <small class="text-muted">(last 360 days)</small>
        </div>
        <div class="box-body">
            <?php if (empty($events)): ?>
                <div class="alert alert-info">No past events found.</div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Event</th>
                                <th>Date</th>
                                <th>Time</th>
                                <th>Location</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($events as $event): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($event['summary']) ?></td>
                                <td><?php echo date('l - F d, Y', $event['start_date']) ?></td>
                                <td><?php echo $event['display_date_time'] ?></td>
                                <td><?php echo htmlspecialchars($event['location']) ?></td>
                                <td>
                                    <a href="<?php echo site_url('admin/dup_events/day?date=' . $event['start_date']) ?>"
                                       class="btn btn-primary btn-sm">
                                        <i class="fa fa-copy"></i> Duplicate
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php include 'includes/footer.php' ?>
