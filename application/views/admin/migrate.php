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
    $has_pending = false;
    $has_ran = false;
    foreach ($migrations as $m) {
        if ($m['ran']) $has_ran = true;
        else $has_pending = true;
    }
    ?>

    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-database"></i> Migrations</h3>
            <div class="box-tools">
                <form action="<?php echo site_url('migrate/run'); ?>" method="post" style="display: inline-block;" class="mr-2">
                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                    <button type="submit" class="btn btn-primary btn-sm" <?php echo $has_pending ? '' : 'disabled'; ?>>
                        <i class="fa fa-play"></i> Run Pending Migrations
                    </button>
                </form>
                <form action="<?php echo site_url('migrate/rollback'); ?>" method="post" style="display: inline-block;"
                      onsubmit="return confirm('Roll back the last batch?');">
                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                    <button type="submit" class="btn btn-warning btn-sm" <?php echo $has_ran ? '' : 'disabled'; ?>>
                        <i class="fa fa-undo"></i> Rollback Last Batch
                    </button>
                </form>
            </div>
        </div>
        <div class="box-body no-padding">
            <?php if (empty($migrations)): ?>
                <p class="text-muted" style="padding: 15px;">No migration files found in <code>database/migrations/</code>.</p>
            <?php else: ?>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Migration</th>
                            <th style="width: 100px;">Status</th>
                            <th style="width: 80px;">Batch</th>
                            <th style="width: 180px;">Ran At</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($migrations as $m): ?>
                        <tr>
                            <td><code><?php echo htmlspecialchars($m['file']); ?></code></td>
                            <td>
                                <?php if ($m['ran']): ?>
                                    <span class="label label-success">Ran</span>
                                <?php else: ?>
                                    <span class="label label-warning">Pending</span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo $m['batch'] ? $m['batch'] : '—'; ?></td>
                            <td><?php echo $m['ran_at'] ? $m['ran_at'] : '—'; ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
        <div class="box-footer text-muted small">
            Migration files: <code>database/migrations/</code> &middot;
            Tracking table: <code>migrations</code>
        </div>
    </div>

</section>

<?php include VIEWPATH . 'admin/includes/footer.php'; ?>
