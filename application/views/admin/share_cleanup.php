<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<?php include VIEWPATH . 'admin/includes/header.php'; ?>

<section class="content-header">
    <h1>Share Link Cleanup <small>Retire the old calendar image system</small></h1>
</section>

<section class="content">

    <?php if ($this->session->flashdata('cleanup_success')): ?>
        <div class="alert alert-success"><?php echo $this->session->flashdata('cleanup_success') ?></div>
    <?php endif; ?>

    <?php if ($this->session->flashdata('cleanup_error')): ?>
        <div class="alert alert-danger"><?php echo $this->session->flashdata('cleanup_error') ?></div>
    <?php endif; ?>

    <!-- Prerequisites Check -->
    <div class="callout <?php echo $templates_ready ? 'callout-success' : 'callout-warning' ?>">
        <h4><i class="fa fa-<?php echo $templates_ready ? 'check' : 'warning' ?>"></i> Template System Status</h4>
        <p>
            <?php if ($templates_ready): ?>
                <strong><?php echo $ready_template_count ?> share template(s) are ready.</strong> The new template system can handle image generation.
            <?php else: ?>
                <strong>No share templates are marked as ready.</strong> Complete the template setup before running cleanup, or old share links will show errors.
            <?php endif; ?>
        </p>
    </div>

    <!-- What Will Be Cleaned Up -->
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-list"></i> Cleanup Inventory</h3>
        </div>
        <div class="box-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>Item</th>
                        <th>Status</th>
                        <th>Details</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Cal-Event images -->
                    <tr>
                        <td><i class="fa fa-image text-info"></i> Cal-Event-*.jpg images</td>
                        <td>
                            <?php if ($cal_event_count > 0): ?>
                                <span class="label label-warning"><?php echo $cal_event_count ?> file(s)</span>
                            <?php else: ?>
                                <span class="label label-success">Cleaned</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-muted">Old background images in <code>imgs/</code> used by the legacy system</td>
                    </tr>

                    <!-- cal_images table -->
                    <tr>
                        <td><i class="fa fa-database text-info"></i> cal_images table</td>
                        <td>
                            <?php if ($cal_images_count > 0): ?>
                                <span class="label label-warning"><?php echo $cal_images_count ?> row(s)</span>
                            <?php else: ?>
                                <span class="label label-success">Cleaned</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-muted">Legacy image records and their layout settings</td>
                    </tr>

                    <!-- cal_image_layouts table -->
                    <tr>
                        <td><i class="fa fa-database text-info"></i> cal_image_layouts table</td>
                        <td>
                            <?php if ($cal_layouts_count > 0): ?>
                                <span class="label label-warning"><?php echo $cal_layouts_count ?> row(s)</span>
                            <?php else: ?>
                                <span class="label label-success">Cleaned</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-muted">Text positioning for legacy images</td>
                    </tr>

                    <!-- venue_images table -->
                    <tr>
                        <td><i class="fa fa-database text-info"></i> venue_images table</td>
                        <td>
                            <?php if ($venue_images_count > 0): ?>
                                <span class="label label-warning"><?php echo $venue_images_count ?> row(s)</span>
                            <?php else: ?>
                                <span class="label label-success">Cleaned</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-muted">Venue-to-cal_image associations (replaced by venue templates)</td>
                    </tr>

                    <!-- Old gcal scripts -->
                    <tr>
                        <td><i class="fa fa-file-code-o text-info"></i> Legacy gcal/ scripts</td>
                        <td>
                            <?php if ($gcal_legacy_count > 0): ?>
                                <span class="label label-warning"><?php echo $gcal_legacy_count ?> file(s)</span>
                            <?php else: ?>
                                <span class="label label-success">Cleaned</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-muted">
                            Old image generators, configs, and junk files in <code>gcal/</code>
                            <?php if ( ! empty($gcal_legacy_files)): ?>
                                <br><small><?php echo implode(', ', $gcal_legacy_files) ?></small>
                            <?php endif; ?>
                        </td>
                    </tr>

                    <!-- Old share_images rows -->
                    <tr>
                        <td><i class="fa fa-database text-info"></i> share_images table</td>
                        <td>
                            <?php if ($share_images_count > 0): ?>
                                <span class="label label-info"><?php echo $share_images_count ?> row(s)</span>
                            <?php else: ?>
                                <span class="label label-success">Empty</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-muted">
                            Hash lookups for share links — auto-prunes to 100.
                            <?php if ($expired_share_count > 0): ?>
                                <strong><?php echo $expired_share_count ?> expired</strong> (event already passed).
                            <?php endif; ?>
                        </td>
                    </tr>

                    <!-- Fallback code in Site.php -->
                    <tr>
                        <td><i class="fa fa-code text-info"></i> Fallback rendering code</td>
                        <td><span class="label label-default">Manual</span></td>
                        <td class="text-muted">Tier 2 (cal_images) and Tier 3 (static Cal-Event-N.jpg) fallbacks in <code>Site.php</code> — remove manually after cleanup</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Actions -->
    <div class="box box-danger">
        <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-trash"></i> Cleanup Actions</h3>
        </div>
        <div class="box-body">
            <?php
            $has_work = ($cal_event_count > 0 || $cal_images_count > 0 || $cal_layouts_count > 0 || $venue_images_count > 0 || $gcal_legacy_count > 0);
            $safe_to_run = $templates_ready && $has_work;
            ?>

            <?php if ( ! $templates_ready): ?>
                <div class="alert alert-warning">
                    <i class="fa fa-warning"></i> <strong>Not safe to run yet.</strong>
                    Set up and mark share templates as ready first, or old share links will break.
                </div>
            <?php endif; ?>

            <?php if ( ! $has_work): ?>
                <div class="alert alert-success">
                    <i class="fa fa-check-circle"></i> <strong>All clean!</strong> Nothing to clean up.
                </div>
            <?php endif; ?>

            <form action="<?php echo site_url('admin/run_share_cleanup') ?>" method="post"
                  onsubmit="return confirm('This will permanently delete legacy calendar images, clear legacy database tables, and remove old gcal scripts. This cannot be undone.\n\nAre you sure?');">
                <input type="hidden" name="<?php echo $this->security->get_csrf_token_name() ?>" value="<?php echo $this->security->get_csrf_hash() ?>">

                <button type="submit" class="btn btn-danger btn-lg" <?php echo $safe_to_run ? '' : 'disabled' ?>>
                    <i class="fa fa-trash"></i> Run Full Cleanup
                </button>

                <span class="text-muted" style="margin-left: 15px;">
                    Deletes legacy images, clears old tables, removes old gcal scripts
                </span>
            </form>

            <?php if ($expired_share_count > 0): ?>
                <hr>
                <form action="<?php echo site_url('admin/prune_expired_shares') ?>" method="post"
                      onsubmit="return confirm('Delete <?php echo $expired_share_count ?> expired share image records?');">
                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name() ?>" value="<?php echo $this->security->get_csrf_hash() ?>">

                    <button type="submit" class="btn btn-warning">
                        <i class="fa fa-clock-o"></i> Prune <?php echo $expired_share_count ?> Expired Share Links
                    </button>

                    <span class="text-muted" style="margin-left: 15px;">
                        Remove share_images rows for past events (links will 404)
                    </span>
                </form>
            <?php endif; ?>
        </div>
    </div>

    <!-- How it works -->
    <div class="box box-default collapsed-box">
        <div class="box-header with-border" data-widget="collapse" style="cursor: pointer;">
            <h3 class="box-title"><i class="fa fa-info-circle"></i> How Share Images Work</h3>
            <div class="box-tools"><button type="button" class="btn btn-box-tool"><i class="fa fa-plus"></i></button></div>
        </div>
        <div class="box-body">
            <h4>Current System (Templates)</h4>
            <ol>
                <li>Calendar page generates a share link: <code>/facebook?event_id=...&event_date=...</code></li>
                <li>Facebook controller creates a hash in <code>share_images</code> table</li>
                <li>OG meta tag points to <code>/cal-image/{hash}</code></li>
                <li>Image is rendered dynamically using template backgrounds + artist photos</li>
                <li>Past events show "This event has passed" overlay</li>
            </ol>

            <h4>Legacy System (Being Retired)</h4>
            <ol>
                <li>25 static <code>Cal-Event-N.jpg</code> backgrounds in <code>imgs/</code></li>
                <li>Layout settings in <code>cal_image_layouts</code> table</li>
                <li>Venue-specific image matching via <code>venue_images</code> table</li>
                <li>Old gcal scripts (<code>fb_image.php</code>, <code>cal_image.php</code>) — now redirects</li>
            </ol>

            <h4>After Cleanup</h4>
            <p>Only the template system will be used. If no template matches an event, the image request will show a generic fallback or 404 gracefully.</p>
        </div>
    </div>

</section>

<?php include VIEWPATH . 'admin/includes/footer.php'; ?>
