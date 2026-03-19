<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<ul class="sidebar-menu" data-widget="tree">
    <li class="header">NAVIGATION</li>

    <li <?php echo ($page->menu == 'dashboard') ? 'class="active"' : '' ?>>
        <a href="<?php echo site_url('admin') ?>">
            <i class="fa fa-dashboard"></i> <span>Dashboard</span>
        </a>
    </li>

    <li class="treeview <?php echo in_array($page->menu, ['images', 'venues', 'dup_events']) ? 'active menu-open' : '' ?>">
        <a href="#">
            <i class="fa fa-calendar"></i> <span>Calendar Tools</span>
            <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
        </a>
        <ul class="treeview-menu">
            <li <?php echo ($page->menu == 'images') ? 'class="active"' : '' ?>>
                <a href="<?php echo site_url('admin/images') ?>">
                    <i class="fa fa-image"></i> <span>Sharing Images</span>
                </a>
            </li>
            <li <?php echo ($page->menu == 'venues') ? 'class="active"' : '' ?>>
                <a href="<?php echo site_url('admin/venues') ?>">
                    <i class="fa fa-map-marker"></i> <span>Venue Images</span>
                </a>
            </li>
            <li <?php echo ($page->menu == 'dup_events') ? 'class="active"' : '' ?>>
                <a href="<?php echo site_url('admin/dup_events') ?>">
                    <i class="fa fa-copy"></i> <span>Duplicate Events</span>
                </a>
            </li>
        </ul>
    </li>

    <li class="treeview <?php echo in_array($page->menu, ['template_backgrounds', 'template_photos', 'templates']) ? 'active menu-open' : '' ?>">
        <a href="#">
            <i class="fa fa-object-group"></i> <span>Share Templates</span>
            <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
        </a>
        <ul class="treeview-menu">
            <li <?php echo ($page->menu == 'template_backgrounds') ? 'class="active"' : '' ?>>
                <a href="<?php echo site_url('admin/template_backgrounds') ?>">
                    <i class="fa fa-image"></i> <span>Backgrounds</span>
                </a>
            </li>
            <li <?php echo ($page->menu == 'template_photos') ? 'class="active"' : '' ?>>
                <a href="<?php echo site_url('admin/template_photos') ?>">
                    <i class="fa fa-user"></i> <span>Photos</span>
                </a>
            </li>
            <li <?php echo ($page->menu == 'templates') ? 'class="active"' : '' ?>>
                <a href="<?php echo site_url('admin/templates') ?>">
                    <i class="fa fa-th-large"></i> <span>Templates</span>
                </a>
            </li>
        </ul>
    </li>

    <li <?php echo ($page->menu == 'test_email') ? 'class="active"' : '' ?>>
        <a href="<?php echo site_url('admin/test_email') ?>">
            <i class="fa fa-envelope"></i> <span>Test Email</span>
        </a>
    </li>

    <li class="header">ACCOUNT</li>

    <li <?php echo ($page->menu == 'password') ? 'class="active"' : '' ?>>
        <a href="<?php echo site_url('admin/change_password') ?>">
            <i class="fa fa-key"></i> <span>Change Password</span>
        </a>
    </li>

    <li class="header">LINKS</li>

    <li>
        <a href="<?php echo base_url() ?>" target="_blank">
            <i class="fa fa-globe"></i> <span>View Site</span>
        </a>
    </li>

    <li>
        <a href="<?php echo site_url('admin/logout') ?>">
            <i class="fa fa-sign-out"></i> <span>Logout</span>
        </a>
    </li>
</ul>
