<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php include 'includes/header.php' ?>

<section class="content-header">
    <h1>Change Password</h1>
</section>

<section class="content">
    <div class="row">
        <div class="col-md-6">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Update your password</h3>
                </div>
                <?php echo form_open('admin/change_password') ?>
                <div class="box-body">
                    <?php if (validation_errors()): ?>
                        <div class="alert alert-danger"><?php echo validation_errors() ?></div>
                    <?php endif; ?>

                    <div class="form-group">
                        <label for="current_password">Current Password</label>
                        <input type="password" class="form-control" id="current_password" name="current_password" required>
                    </div>
                    <div class="form-group">
                        <label for="new_password">New Password</label>
                        <input type="password" class="form-control" id="new_password" name="new_password" required minlength="4">
                        <p class="help-block">Minimum 4 characters.</p>
                    </div>
                    <div class="form-group">
                        <label for="confirm_password">Confirm New Password</label>
                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                    </div>
                </div>
                <div class="box-footer">
                    <button type="submit" class="btn btn-primary">Change Password</button>
                    <a href="<?php echo site_url('admin') ?>" class="btn btn-default">Cancel</a>
                </div>
                <?php echo form_close() ?>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php' ?>
