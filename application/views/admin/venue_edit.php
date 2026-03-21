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
                        <label for="venue_type_id">Venue Type</label>
                        <select class="form-control" id="venue_type_id" name="venue_type_id">
                            <option value="">-- None --</option>
                            <?php foreach ($venue_types as $vt): ?>
                                <option value="<?php echo $vt->id ?>"
                                    <?php echo ($venue && $venue->venue_type_id == $vt->id) ? 'selected' : '' ?>>
                                    <?php echo htmlspecialchars($vt->name) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <span class="help-block">Used for template selection when no venue-specific templates are assigned.</span>
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

            <!-- Logistics -->
            <div class="box box-warning collapsed-box">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-truck"></i> Logistics</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
                    </div>
                </div>
                <div class="box-body">
                    <?php $d = $venue_details; ?>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="drive_time_mins">Drive Time (mins)</label>
                                <input type="number" class="form-control" id="drive_time_mins" name="drive_time_mins"
                                       value="<?php echo $d ? $d->drive_time_mins : '' ?>" min="0">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="setup_time_mins">Setup Time (mins)</label>
                                <input type="number" class="form-control" id="setup_time_mins" name="setup_time_mins"
                                       value="<?php echo $d ? $d->setup_time_mins : '' ?>" min="0">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="default_start_time">Default Start Time</label>
                                <input type="time" class="form-control" id="default_start_time" name="default_start_time"
                                       value="<?php echo $d ? $d->default_start_time : '' ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="default_length_mins">Default Length (mins)</label>
                                <input type="number" class="form-control" id="default_length_mins" name="default_length_mins"
                                       value="<?php echo $d ? $d->default_length_mins : '' ?>" min="0">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="address">Address</label>
                        <input type="text" class="form-control" id="address" name="address"
                               value="<?php echo $d ? htmlspecialchars($d->address) : '' ?>">
                    </div>
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="city">City</label>
                                <input type="text" class="form-control" id="city" name="city"
                                       value="<?php echo $d ? htmlspecialchars($d->city) : '' ?>">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="state">State</label>
                                <input type="text" class="form-control" id="state" name="state" maxlength="2"
                                       value="<?php echo $d ? htmlspecialchars($d->state) : '' ?>" placeholder="CA">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="special_requirements">Special Requirements</label>
                        <textarea class="form-control" id="special_requirements" name="special_requirements" rows="3"><?php echo $d ? htmlspecialchars($d->special_requirements) : '' ?></textarea>
                    </div>
                </div>
            </div>

            <!-- Template Assignment -->
            <?php if ( ! empty($templates)): ?>
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-th-large"></i> Template Assignment</h3>
                    <p class="text-muted" style="margin-top: 5px; margin-bottom: 0;">Overrides venue type templates for this venue.</p>
                </div>
                <div class="box-body">
                    <div class="row">
                        <?php foreach ($templates as $tpl): ?>
                            <div class="col-md-4 col-sm-6" style="margin-bottom: 15px;">
                                <label style="cursor: pointer; display: block;">
                                    <div class="thumbnail" style="margin-bottom: 5px; <?php echo in_array($tpl->id, $venue_template_ids) ? 'border: 3px solid #3c8dbc;' : '' ?>">
                                        <img loading="lazy"
                                             src="<?php echo site_url('admin/preview_template/' . $tpl->id) ?>"
                                             alt="Template #<?php echo $tpl->id ?>"
                                             style="width: 100%; height: auto; min-height: 120px; background: #f5f5f5;">
                                    </div>
                                    <input type="checkbox" name="template_ids[]" value="<?php echo $tpl->id ?>"
                                           <?php echo in_array($tpl->id, $venue_template_ids) ? 'checked' : '' ?>>
                                    <?php echo htmlspecialchars($tpl->bg_name) ?> + <?php echo htmlspecialchars($tpl->photo_name) ?>
                                </label>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>

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
