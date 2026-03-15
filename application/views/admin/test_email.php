<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<?php include VIEWPATH . 'admin/includes/header.php'; ?>

<section class="content-header">
    <h1>Test Email <small>Send a test via Amazon SES</small></h1>
</section>

<section class="content">

    <?php include VIEWPATH . 'admin/includes/notifications.php'; ?>

    <div class="row">
        <div class="col-md-6">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-envelope"></i> Send Test Email</h3>
                </div>
                <div class="box-body">
                    <?php if ($ses_available): ?>
                        <p class="text-success"><i class="fa fa-check-circle"></i> SES is configured and ready.</p>
                        <?php echo form_open('admin/send_test_email'); ?>
                            <div class="form-group">
                                <label for="to_email">Send to:</label>
                                <input type="email" name="to_email" id="to_email" class="form-control"
                                       value="gbennett@tsgdev.com" placeholder="you@example.com" required>
                                <p class="help-block">The test email will be sent from <strong>gbennett@tsgdev.com</strong></p>
                            </div>
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fa fa-paper-plane"></i> Send Test Email
                            </button>
                        <?php echo form_close(); ?>
                    <?php else: ?>
                        <div class="callout callout-danger">
                            <h4><i class="fa fa-times-circle"></i> SES Not Configured</h4>
                            <p>AWS credentials are missing from <code>.env</code>. Add <code>AWS_ACCESS_KEY_ID</code>,
                               <code>AWS_SECRET_ACCESS_KEY</code>, and <code>AWS_DEFAULT_REGION</code> to enable SES.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-info-circle"></i> What to Check</h3>
                </div>
                <div class="box-body">
                    <p>After sending, open the received email and check the headers for:</p>
                    <table class="table table-condensed">
                        <tr>
                            <td><strong>SPF</strong></td>
                            <td>Should say <code>pass</code></td>
                        </tr>
                        <tr>
                            <td><strong>DKIM</strong></td>
                            <td>Should say <code>pass</code></td>
                        </tr>
                        <tr>
                            <td><strong>DMARC</strong></td>
                            <td>Should say <code>pass</code></td>
                        </tr>
                    </table>
                    <p class="text-muted" style="font-size: 13px;">
                        In Gmail: open the email, click the three dots menu, choose "Show original" to see authentication results.
                    </p>
                </div>
            </div>
        </div>
    </div>

</section>

<?php include VIEWPATH . 'admin/includes/footer.php'; ?>
