<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php include 'includes/header.php' ?>

<section class="content-header">
    <h1>CSV Preview</h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo site_url('admin') ?>"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li><a href="<?php echo site_url('admin/dup_events') ?>">Duplicate Events</a></li>
        <li class="active">CSV Preview</li>
    </ol>
</section>

<section class="content">
    <div class="row">
        <div class="col-md-10">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">
                        New Date: <?php echo $new_date->format('l, F jS, Y') ?>
                    </h3>
                </div>
                <div class="box-body">
                    <div class="row">
                        <?php
                        $timezone = new DateTimeZone('America/Los_Angeles');
                        for ($i = 1; $i < count($csv_data); $i++):
                            $row = $csv_data[$i];
                            $startTime = DateTime::createFromFormat('H:i:s', $row[2], $timezone);
                            $endTime = DateTime::createFromFormat('H:i:s', $row[4], $timezone);
                        ?>
                        <div class="col-md-4">
                            <div class="box box-widget">
                                <div class="box-header with-border" style="background-color: #3492eb; color: #fff;">
                                    <h3 class="box-title"><?php echo htmlspecialchars($row[0]) ?></h3>
                                </div>
                                <div class="box-body">
                                    <p><strong>Subject:</strong> <?php echo htmlspecialchars($row[0]) ?></p>
                                    <?php if ($startTime && $endTime): ?>
                                        <p><strong>Time:</strong> <?php echo $startTime->format('g:i a') ?> - <?php echo $endTime->format('g:i a') ?></p>
                                    <?php endif; ?>
                                    <?php if ( ! empty($row[5])): ?>
                                        <p><strong>Description:</strong> <?php echo htmlspecialchars($row[5]) ?></p>
                                    <?php endif; ?>
                                    <?php if ( ! empty($row[6])): ?>
                                        <p><strong>Location:</strong> <?php echo htmlspecialchars($row[6]) ?></p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <?php endfor; ?>
                    </div>
                </div>
                <div class="box-footer">
                    <a href="<?php echo base_url($csv_filename) ?>" class="btn btn-success" download>
                        <i class="fa fa-download"></i> Download CSV
                    </a>
                    <a href="<?php echo site_url('admin/dup_events') ?>" class="btn btn-default">
                        <i class="fa fa-arrow-left"></i> Back
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php' ?>
