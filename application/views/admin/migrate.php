<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<?php include VIEWPATH . 'admin/includes/header.php'; ?>

<section class="content-header">
    <h1>Database Migrations <small>Schema updates for production</small></h1>
</section>

<section class="content">

    <?php if (!empty($success)): ?>
        <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
    <?php endif; ?>

    <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <?php
    $pending = array();
    $completed = array();
    foreach ($migrations as $m) {
        if ($m['ran']) $completed[] = $m;
        else $pending[] = $m;
    }
    ?>

    <!-- Pending Migrations -->
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-clock-o"></i> Pending Migrations</h3>
            <?php if (!empty($pending)): ?>
            <div class="box-tools">
                <form action="<?php echo site_url('migrate/run'); ?>" method="post" style="display: inline-block;">
                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="fa fa-play"></i> Run Pending Migrations
                    </button>
                </form>
            </div>
            <?php endif; ?>
        </div>
        <div class="box-body">
            <?php if (empty($pending)): ?>
                <div class="text-center" style="padding: 30px 15px;">
                    <i class="fa fa-check-circle text-success" style="font-size: 48px;"></i>
                    <h3 class="text-muted" style="margin-top: 15px;">Nothing to run</h3>
                    <p class="text-muted">All migrations have been applied.</p>
                </div>
            <?php else: ?>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Migration</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($pending as $m): ?>
                        <tr>
                            <td><code style="color: inherit; background: transparent;"><?php echo htmlspecialchars($m['file']); ?></code></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>

    <!-- Completed Migrations -->
    <?php if (!empty($completed)): ?>
    <div class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-check"></i> Completed Migrations</h3>
            <div class="box-tools">
                <form action="<?php echo site_url('migrate/rollback'); ?>" method="post" style="display: inline-block;"
                      onsubmit="return confirm('Roll back the last batch?');">
                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                    <button type="submit" class="btn btn-warning btn-sm">
                        <i class="fa fa-undo"></i> Rollback Last Batch
                    </button>
                </form>
            </div>
        </div>
        <div class="box-body no-padding">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Migration</th>
                        <th style="width: 80px;">Batch</th>
                        <th style="width: 180px;">Ran At</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($completed as $m): ?>
                    <tr>
                        <td><code style="color: inherit; background: transparent;"><?php echo htmlspecialchars($m['file']); ?></code></td>
                        <td><?php echo $m['batch']; ?></td>
                        <td><?php echo $m['ran_at']; ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php endif; ?>

    <p class="text-muted small">
        Migration files: <code>database/migrations/</code> &middot;
        Tracking table: <code>migrations</code>
    </p>

</section>

<?php include VIEWPATH . 'admin/includes/footer.php'; ?>
