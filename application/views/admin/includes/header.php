<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?php echo $page->title ?> | <?php echo $app->site_title ?></title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

    <link rel="stylesheet" href="<?php echo $url->assets ?>bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="//stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="<?php echo $url->assets ?>plugins/Ionicons/css/ionicons.min.css">
    <link rel="stylesheet" href="<?php echo $url->assets ?>css/app.css">
    <link rel="stylesheet" href="<?php echo $url->assets ?>css/skins/skin-blue-light.css">

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700">

    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <script src="<?php echo $url->assets ?>js/jquery/jquery.min.js"></script>
    <script src="<?php echo $url->assets ?>plugins/jqueryUi/jquery-ui.min.js"></script>
    <script>$.widget.bridge('uibutton', $.ui.button);</script>
    <script src="<?php echo $url->assets ?>bootstrap/js/bootstrap.min.js"></script>
    <script src="<?php echo $url->assets ?>plugins/jquery-slimscroll/jquery.slimscroll.min.js"></script>
    <script src="<?php echo $url->assets ?>plugins/fastclick/lib/fastclick.js"></script>
</head>
<body class="hold-transition skin-blue-light sidebar-mini">
<div class="wrapper">

    <!-- Main Header -->
    <header class="main-header">
        <a href="<?php echo site_url('admin') ?>" class="logo">
            <span class="logo-mini"><b>G</b>B</span>
            <span class="logo-lg">Glenn<b>Bennett</b></span>
        </a>

        <nav class="navbar navbar-static-top">
            <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
                <span class="sr-only">Toggle navigation</span>
            </a>

            <div class="navbar-custom-menu">
                <ul class="nav navbar-nav">
                    <li class="dropdown user user-menu">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <span class="hidden-xs"><?php echo admin_logged('username') ?></span>
                        </a>
                        <ul class="dropdown-menu">
                            <li class="user-header">
                                <p><?php echo admin_logged('username') ?></p>
                            </li>
                            <li class="user-body">
                                <div class="pull-right">
                                    <a href="<?php echo site_url('admin/logout') ?>" class="btn btn-default btn-flat">Sign out</a>
                                </div>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </nav>
    </header>

    <!-- Left Sidebar -->
    <aside class="main-sidebar">
        <section class="sidebar">
            <div class="user-panel">
                <div class="pull-left info">
                    <p><?php echo admin_logged('username') ?></p>
                    <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
                </div>
            </div>

            <?php include 'nav.php' ?>
        </section>
    </aside>

    <!-- Content Wrapper -->
    <div class="content-wrapper">
        <?php include 'notifications.php'; ?>
