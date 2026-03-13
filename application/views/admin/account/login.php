<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<?php include 'includes/header.php' ?>

<div class="login-box">
    <div class="login-box-body">
        <div class="login-logo">
            <a href="<?php echo site_url('admin') ?>"><b>Glenn Bennett</b> Admin</a>
        </div>
        <h3 class="text-center">Log in</h3>

        <?php if ( ! empty($this->session->flashdata('alert'))): ?>
            <div class="alert alert-<?php echo $this->session->flashdata('alert-type'); ?>">
                <p><?php echo $this->session->flashdata('alert') ?></p>
            </div>
        <?php endif; ?>

        <?php echo form_open('admin/login/check', ['method' => 'POST', 'autocomplete' => 'off']); ?>

            <div class="form-group has-feedback">
                <input type="text" class="form-control" placeholder="Username"
                       value="<?php echo set_value('username') ?>" name="username" autofocus />
                <span class="fa fa-user form-control-feedback"></span>
                <?php echo form_error('username', '<div class="text-danger">', '</div>'); ?>
            </div>

            <div class="form-group has-feedback">
                <input type="password" class="form-control" placeholder="Password" name="password">
                <span class="fa fa-lock form-control-feedback"></span>
                <?php echo form_error('password', '<div class="text-danger">', '</div>'); ?>
            </div>

            <div class="row">
                <div class="col-xs-8"></div>
                <div class="col-xs-4">
                    <button type="submit" class="btn btn-primary btn-block btn-flat">Sign In</button>
                </div>
            </div>

        <?php echo form_close(); ?>
    </div>
</div>

<?php include 'includes/footer.php' ?>
