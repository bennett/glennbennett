<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Database Migrations</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
<div class="container py-5" style="max-width: 900px;">
    <h1 class="mb-4">Database Migrations</h1>

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

    <div class="mb-4">
        <form action="<?php echo site_url('migrate/run'); ?>" method="post" style="display: inline-block;" class="mr-2">
            <?php if (function_exists('form_hidden')) echo form_hidden($this->security->get_csrf_token_name(), $this->security->get_csrf_hash()); ?>
            <button type="submit" class="btn btn-primary" <?php echo $has_pending ? '' : 'disabled'; ?>>
                Run Pending Migrations
            </button>
        </form>
        <form action="<?php echo site_url('migrate/rollback'); ?>" method="post" style="display: inline-block;"
              onsubmit="return confirm('Roll back the last batch?');">
            <?php if (function_exists('form_hidden')) echo form_hidden($this->security->get_csrf_token_name(), $this->security->get_csrf_hash()); ?>
            <button type="submit" class="btn btn-warning" <?php echo $has_ran ? '' : 'disabled'; ?>>
                Rollback Last Batch
            </button>
        </form>
    </div>

    <?php if (empty($migrations)): ?>
        <div class="alert alert-info">No migration files found in <code>database/migrations/</code>.</div>
    <?php else: ?>
        <table class="table table-bordered bg-white">
            <thead class="thead-light">
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
                            <span class="badge badge-success">Ran</span>
                        <?php else: ?>
                            <span class="badge badge-warning">Pending</span>
                        <?php endif; ?>
                    </td>
                    <td><?php echo $m['batch'] ? $m['batch'] : '—'; ?></td>
                    <td><?php echo $m['ran_at'] ? $m['ran_at'] : '—'; ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <p class="text-muted small mt-4">
        Migration files: <code>database/migrations/</code> &middot;
        Tracking table: <code>migrations</code>
    </p>
</div>
</body>
</html>
