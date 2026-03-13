<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<?php include VIEWPATH . 'admin/includes/header.php'; ?>

<section class="content-header">
    <h1><?php echo $venue ? 'Edit' : 'Add' ?> Venue</h1>
</section>

<section class="content">

    <div class="row">
        <div class="col-md-8">
            <?php echo form_open_multipart('admin/venue_save'); ?>

            <?php if ($venue): ?>
                <input type="hidden" name="id" value="<?php echo $venue->id ?>">
            <?php endif; ?>

            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Venue Details</h3>
                </div>
                <div class="box-body">
                    <div class="form-group">
                        <label for="name">Venue Name</label>
                        <input type="text" class="form-control" id="name" name="name"
                               value="<?php echo set_value('name', $venue ? $venue->name : '') ?>" required>
                        <?php echo form_error('name', '<span class="text-danger">', '</span>'); ?>
                    </div>

                    <div class="form-group">
                        <label for="match_pattern">Match Pattern</label>
                        <input type="text" class="form-control" id="match_pattern" name="match_pattern"
                               value="<?php echo set_value('match_pattern', $venue ? $venue->match_pattern : '') ?>" required>
                        <span class="help-block">The text to match against calendar event summaries.</span>
                        <?php echo form_error('match_pattern', '<span class="text-danger">', '</span>'); ?>
                    </div>

                    <div class="form-group">
                        <label for="match_type">Match Type</label>
                        <select class="form-control" id="match_type" name="match_type">
                            <?php
                            $current_type = $venue ? $venue->match_type : 'exact';
                            foreach (['exact', 'contains', 'alpha_only'] as $type):
                            ?>
                                <option value="<?php echo $type ?>" <?php echo ($current_type === $type) ? 'selected' : '' ?>>
                                    <?php echo ucfirst(str_replace('_', ' ', $type)) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <span class="help-block">
                            <strong>Exact:</strong> Event summary must match exactly.<br>
                            <strong>Contains:</strong> Event summary must contain the pattern.<br>
                            <strong>Alpha Only:</strong> Compare alphabetic characters only (ignores spaces, punctuation).
                        </span>
                    </div>

                    <div class="form-group">
                        <label for="venue_logo">Venue Logo</label>
                        <?php if ($venue && $venue->venue_logo): ?>
                            <div style="margin-bottom: 10px;">
                                <img src="<?php echo $venue->venue_logo ?>" style="max-height: 60px;">
                                <small class="text-muted">(current)</small>
                            </div>
                        <?php endif; ?>
                        <input type="file" class="form-control" id="venue_logo" name="venue_logo" accept=".jpg,.jpeg,.png">
                        <span class="help-block">Upload a new logo to replace the current one.</span>
                    </div>

                    <div class="form-group">
                        <label>
                            <input type="checkbox" name="is_active" value="1"
                                   <?php echo (!$venue || $venue->is_active) ? 'checked' : '' ?>>
                            Active
                        </label>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-success btn-lg">
                    <i class="fa fa-save"></i> Save Venue
                </button>
                <a href="<?php echo site_url('admin/venues') ?>" class="btn btn-default btn-lg">Cancel</a>
            </div>

            <?php echo form_close(); ?>
        </div>
    </div>

</section>

<?php include VIEWPATH . 'admin/includes/footer.php'; ?>
