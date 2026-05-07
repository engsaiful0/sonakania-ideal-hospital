<!DOCTYPE html>
<html lang="en">

<head>
    <title>Hospital ERP</title>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <meta http-equiv="content-language" content="en" />
    <meta name="robots" content="noindex,nofollow" />
    <link rel="stylesheet" media="screen,projection" type="text/css" href="<?php echo base_url() ?>css/bootstrap.min.css" />
    <link rel="stylesheet" media="screen,projection" type="text/css" href="<?php echo base_url() ?>css/reset.css" /> <!-- RESET -->
    <link rel="stylesheet" media="screen,projection" type="text/css" href="<?php echo base_url() ?>css/main.css" /> <!-- MAIN STYLE SHEET -->
    <link rel="stylesheet" media="screen,projection" type="text/css" href="<?php echo base_url() ?>css/2col.css" title="2col" /> <!-- DEFAULT: 2 COLUMNS -->
    <link rel="alternate stylesheet" media="screen,projection" type="text/css" href="<?php echo base_url() ?>css/1col.css" title="1col" /> <!-- ALTERNATE: 1 COLUMN -->
    <!--[if lte IE 6]><link rel="stylesheet" media="screen,projection" type="text/css" href="css/main-ie6.css" /><![endif]--> <!-- MSIE6 -->
    <link rel="stylesheet" media="screen,projection" type="text/css" href="<?php echo base_url() ?>css/style.css" /> <!-- GRAPHIC THEME -->
    <link rel="stylesheet" media="screen,projection" type="text/css" href="<?php echo base_url() ?>css/mystyle.css" /> <!-- WRITE YOUR CSS CODE HERE -->
    <script type="text/javascript" src="<?php echo base_url() ?>js/jquery.js"></script>
    <script type="text/javascript" src="<?php echo base_url() ?>js/switcher.js"></script>
    <script type="text/javascript" src="<?php echo base_url() ?>js/toggle.js"></script>
    <script type="text/javascript" src="<?php echo base_url() ?>js/ui.core.js"></script>
    <script type="text/javascript" src="<?php echo base_url() ?>js/ui.tabs.js"></script>
    <script type="text/javascript" src="<?php echo base_url() ?>js/bootstrap.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url() ?>js/jquery.toast.min.js"></script>
    <link rel="stylesheet" href="<?php echo base_url(); ?>css/datepicker.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>css/jquery.toast.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>css/select2.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>css/bootstrap-multiselect.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>css/sweetalert2.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>fontawesome/css/fontawesome.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>fontawesome/css/brands.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>fontawesome/css/solid.css">


    <link rel="stylesheet" href="<?php echo base_url(); ?>css/select2.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>css/jquery-ui.css">
    <!--<script type="text/javascript" src="<?php echo base_url(); ?>js/jquery-2.1.3.min.js"></script>-->

    <?php
    //  include("common/title.php");
    $this->load->view("common/title.php");
    ?>
</head>

<body>
    <div id="main">
        <!-- Tray -->
        <div id="tray" style="width:100%!important" class="box">
            <?php
            $this->load->view("common/top_bar.php");
            //  include();
            ?>
        </div> <!--  /tray -->
        <hr class="noscreen" />

        <!-- Menu -->
        <div id="menu" class="box" style="width:100%!important">
            <?php
            $this->load->view("common/top_menu.php");
            ?>
        </div> <!-- /header -->

        <hr class="noscreen" />
        <!-- Columns -->