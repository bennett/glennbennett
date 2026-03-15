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
    <div class="row">
        <div class="col-md-8">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">
                        Events for <?php echo $date->format('l, F j, Y') ?>
                        <small>(Los Angeles Time)</small>
                    </h3>
                </div>
                <div class="box-body">
                    <?php if (empty($events)): ?>
                        <div class="alert alert-info">No events found for <?php echo $date->format('F j, Y') ?>.</div>
                    <?php else: ?>
                        <?php echo form_open('admin/dup_events/generate_csv') ?>

                        <div class="form-group">
                            <label for="newEventDate">New Event Date:</label>
                            <input type="date" class="form-control" id="newEventDate" name="newEventDate"
                                   value="<?php echo $next_same_day ?>" style="width: 200px;">
                        </div>

                        <?php foreach ($events as $i => $event): ?>
                            <div class="panel panel-default">
                                <div class="panel-body">
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="events[<?php echo $i ?>][selected]" value="1" checked>
                                            <strong><?php echo $event['HTML_Subject'] ?></strong>
                                        </label>
                                    </div>
                                    <p><?php echo $event['HTML_Description'] ?></p>
                                    <p class="text-muted">
                                        <i class="fa fa-calendar"></i>
                                        <?php echo $event['Start_DateTime']->format('l - F j, Y') ?> |
                                        <?php echo $event['Start_DateTime']->format('g:i a') ?> - <?php echo $event['End_DateTime']->format('g:i a') ?>
                                    </p>
                                    <?php if ($event['HTML_Location']): ?>
                                        <p class="text-muted"><i class="fa fa-map-marker"></i> <?php echo $event['HTML_Location'] ?></p>
                                    <?php endif; ?>

                                    <input type="hidden" name="events[<?php echo $i ?>][Subject]" value="<?php echo $event['ASCII_Subject'] ?>">
                                    <input type="hidden" name="events[<?php echo $i ?>][Start_Date]" value="<?php echo $event['Start_DateTime']->format('Y-m-d') ?>">
                                    <input type="hidden" name="events[<?php echo $i ?>][Start_Time]" value="<?php echo $event['Start_DateTime']->format('H:i:s') ?>">
                                    <input type="hidden" name="events[<?php echo $i ?>][End_Date]" value="<?php echo $event['End_DateTime']->format('Y-m-d') ?>">
                                    <input type="hidden" name="events[<?php echo $i ?>][End_Time]" value="<?php echo $event['End_DateTime']->format('H:i:s') ?>">
                                    <input type="hidden" name="events[<?php echo $i ?>][Description]" value="<?php echo $event['GCAL_Description'] ?>">
                                    <input type="hidden" name="events[<?php echo $i ?>][Location]" value="<?php echo $event['ASCII_Location'] ?>">
                                </div>
                            </div>
                        <?php endforeach; ?>

                        <button type="submit" class="btn btn-primary"><i class="fa fa-download"></i> Generate CSV</button>
                        <?php echo form_close() ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php' ?>
