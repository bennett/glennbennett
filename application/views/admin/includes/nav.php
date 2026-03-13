<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<ul class="sidebar-menu" data-widget="tree">
    <li class="header">NAVIGATION</li>

    <li <?php echo ($page->menu == 'dashboard') ? 'class="active"' : '' ?>>
        <a href="<?php echo site_url('admin') ?>">
            <i class="fa fa-dashboard"></i> <span>Dashboard</span>
        </a>
    </li>

    <li <?php echo ($page->menu == 'images') ? 'class="active"' : '' ?>>
        <a href="<?php echo site_url('admin/images') ?>">
            <i class="fa fa-image"></i> <span>Calendar Images</span>
        </a>
    </li>

    <li <?php echo ($page->menu == 'venues') ? 'class="active"' : '' ?>>
        <a href="<?php echo site_url('admin/venues') ?>">
            <i class="fa fa-map-marker"></i> <span>Venues</span>
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
