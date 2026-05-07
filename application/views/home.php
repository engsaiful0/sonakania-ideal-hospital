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
        <div id="cols" class="box">
            <div id="content">
                <img src="<?php echo base_url() ?>assets/ajax-loader.gif" id="img" style="display:none;position:fixed;top:100;left:100;background:rgba(255, 255, 255, 0.8);z-index:10001;" />
                <?php
                $this->load->view('main_dashboard');
                ?>
            </div> <!-- /content -->
        </div> <!-- /cols -->
        <hr class="noscreen" />
        <!-- Footer -->
        <div id="footer" class="box">
            <?php
            $this->load->view("common/footer.php");
            ?>
        </div> <!-- /footer -->
    </div> <!-- /main -->
</body>

<script src="<?php echo base_url(); ?>js/jquery-ui.js"></script>
<script src="<?php echo base_url(); ?>js/jquery-ui.min.js"></script>

<script src="<?php echo base_url(); ?>js/jquery.timeselector.js"></script>
<link rel="stylesheet" href="<?php echo base_url(); ?>css/jquery.timeselector.css">

<script src="<?php echo base_url() ?>js/jquery.validate.js"></script>



<script src="<?php echo base_url(); ?>js/autocomplete_js.js"></script>
<script src="<?php echo base_url(); ?>js/common-functions.js"></script>
<script src="<?php echo base_url(); ?>js/select2.js"></script>
<script src="<?php echo base_url(); ?>js/select2.min.js"></script>
<script src="<?php echo base_url(); ?>js/sweetalert2.js"></script>

<script type="text/javascript">
    $(document).ready(function() {
        $(".tabs > ul").tabs();
    });
    $(document).ready(function() {
        $('#datepicker').datepicker({
            "changeMonth": true,
            "changeYear": true,
            "dateFormat": "dd-mm-yy",
            "yearRange": '1995:2030'
        });
        $('#datepicker0').datepicker({
            "changeMonth": true,
            "changeYear": true,
            "dateFormat": "dd-mm-yy",
            "yearRange": '1995:2030'
        });
        $('#datepicker1').datepicker({
            "changeMonth": true,
            "changeYear": true,
            "dateFormat": "dd-mm-yy",
            "yearRange": '1995:2030'
        });
        $('#datepicker2').datepicker({
            "changeMonth": true,
            "changeYear": true,
            "dateFormat": "dd-mm-yy",
            "yearRange": '1995:2030'
        });
        $('#datepicker3').datepicker({
            "changeMonth": true,
            "changeYear": true,
            "dateFormat": "dd-mm-yy",
            "yearRange": '1995:2030'
        });
        $('#datepicker4').datepicker({
            "changeMonth": true,
            "changeYear": true,
            "dateFormat": "dd-mm-yy",
            "yearRange": '1995:2030'
        });
        $('#datepicker2').datepicker({
            "changeMonth": true,
            "changeYear": true,
            "dateFormat": "dd-mm-yy",
            "yearRange": '1995:2030'
        });
        $('#datepicker5').datepicker({
            "changeMonth": true,
            "changeYear": true,
            "dateFormat": "dd-mm-yy",
            "yearRange": '1995:2030'
        });
        $('#datepicker6').datepicker({
            "changeMonth": true,
            "changeYear": true,
            "dateFormat": "dd-mm-yy",
            "yearRange": '1995:2030'
        });
        $('#datepicker7').datepicker({
            "changeMonth": true,
            "changeYear": true,
            "dateFormat": "dd-mm-yy",
            "yearRange": '1995:2030'
        });
        $('#datepicker8').datepicker({
            "changeMonth": true,
            "changeYear": true,
            "dateFormat": "dd-mm-yy",
            "yearRange": '1995:2030'
        });
        $('#datepicker9').datepicker({
            "changeMonth": true,
            "changeYear": true,
            "dateFormat": "dd-mm-yy",
            "yearRange": '1995:2030'
        });
        $('#datepicker10').datepicker({
            "changeMonth": true,
            "changeYear": true,
            "dateFormat": "dd-mm-yy",
            "yearRange": '1995:2030'
        });
        $('#datepicker11').datepicker({
            "changeMonth": true,
            "changeYear": true,
            "dateFormat": "dd-mm-yy",
            "yearRange": '1995:2030'
        });
        $('#mfg_datepicker').datepicker({
            "changeMonth": true,
            "changeYear": true,
            "dateFormat": "dd-mm-yy",
            "yearRange": '1995:2030'
        });
        $('#mfg_datepicker0').datepicker({
            "changeMonth": true,
            "changeYear": true,
            "dateFormat": "dd-mm-yy",
            "yearRange": '1995:2030'
        });
        $('#mfg_datepicker1').datepicker({
            "changeMonth": true,
            "changeYear": true,
            "dateFormat": "dd-mm-yy",
            "yearRange": '1995:2030'
        });
        $('#mfg_datepicker2').datepicker({
            "changeMonth": true,
            "changeYear": true,
            "dateFormat": "dd-mm-yy",
            "yearRange": '1995:2030'
        });
        $('#mfg_datepicker3').datepicker({
            "changeMonth": true,
            "changeYear": true,
            "dateFormat": "dd-mm-yy",
            "yearRange": '1995:2030'
        });
        $('#mfg_datepicker4').datepicker({
            "changeMonth": true,
            "changeYear": true,
            "dateFormat": "dd-mm-yy",
            "yearRange": '1995:2030'
        });

        $('#mfg_datepicker5').datepicker({
            "changeMonth": true,
            "changeYear": true,
            "dateFormat": "dd-mm-yy",
            "yearRange": '1995:2030'
        });
        $('#mfg_datepicker6').datepicker({
            "changeMonth": true,
            "changeYear": true,
            "dateFormat": "dd-mm-yy",
            "yearRange": '1995:2030'
        });
        $('#mfg_datepicker7').datepicker({
            "changeMonth": true,
            "changeYear": true,
            "dateFormat": "dd-mm-yy",
            "yearRange": '1995:2030'
        });
        $('#mfg_datepicker8').datepicker({
            "changeMonth": true,
            "changeYear": true,
            "dateFormat": "dd-mm-yy",
            "yearRange": '1995:2030'
        });
        $('#mfg_datepicker9').datepicker({
            "changeMonth": true,
            "changeYear": true,
            "dateFormat": "dd-mm-yy",
            "yearRange": '1995:2030'
        });
        $('#mfg_datepicker10').datepicker({
            "changeMonth": true,
            "changeYear": true,
            "dateFormat": "dd-mm-yy",
            "yearRange": '1995:2030'
        });
        $('#mfg_datepicker11').datepicker({
            "changeMonth": true,
            "changeYear": true,
            "dateFormat": "dd-mm-yy",
            "yearRange": '1995:2030'
        });

        $('#exp_date').datepicker({
            "changeMonth": true,
            "changeYear": true,
            "dateFormat": "dd-mm-yy",
            "yearRange": '1995:2030'
        });
        $('#exp_date0').datepicker({
            "changeMonth": true,
            "changeYear": true,
            "dateFormat": "dd-mm-yy",
            "yearRange": '1995:2030'
        });
        $('#exp_date1').datepicker({
            "changeMonth": true,
            "changeYear": true,
            "dateFormat": "dd-mm-yy",
            "yearRange": '1995:2030'
        });
        $('#exp_date2').datepicker({
            "changeMonth": true,
            "changeYear": true,
            "dateFormat": "dd-mm-yy",
            "yearRange": '1995:2030'
        });
        $('#exp_date3').datepicker({
            "changeMonth": true,
            "changeYear": true,
            "dateFormat": "dd-mm-yy",
            "yearRange": '1995:2030'
        });
        $('#exp_date4').datepicker({
            "changeMonth": true,
            "changeYear": true,
            "dateFormat": "dd-mm-yy",
            "yearRange": '1995:2030'
        });

        $('#exp_date5').datepicker({
            "changeMonth": true,
            "changeYear": true,
            "dateFormat": "dd-mm-yy",
            "yearRange": '1995:2030'
        });
        $('#exp_date6').datepicker({
            "changeMonth": true,
            "changeYear": true,
            "dateFormat": "dd-mm-yy",
            "yearRange": '1995:2030'
        });
        $('#exp_date7').datepicker({
            "changeMonth": true,
            "changeYear": true,
            "dateFormat": "dd-mm-yy",
            "yearRange": '1995:2030'
        });
        $('#exp_date8').datepicker({
            "changeMonth": true,
            "changeYear": true,
            "dateFormat": "dd-mm-yy",
            "yearRange": '1995:2030'
        });
        $('#exp_date9').datepicker({
            "changeMonth": true,
            "changeYear": true,
            "dateFormat": "dd-mm-yy",
            "yearRange": '1995:2030'
        });
        $('#exp_date10').datepicker({
            "changeMonth": true,
            "changeYear": true,
            "dateFormat": "dd-mm-yy",
            "yearRange": '1995:2030'
        });
        $('#exp_date11').datepicker({
            "changeMonth": true,
            "changeYear": true,
            "dateFormat": "dd-mm-yy",
            "yearRange": '1995:2030'
        });
    });
</script>
<script type="text/javascript">
    $(document).keypress(function(event) {
        var keycode = (event.keyCode ? event.keyCode : event.which);
        if (keycode == '13') {
            $('#sumbit_button').click();
        }
    });
    
</script>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        const dropdowns = document.querySelectorAll('.dropdown');

        dropdowns.forEach(function(dropdown) {
            dropdown.addEventListener('click', function() {
                this.classList.toggle('active');
                const icon = this.querySelector('.icon');
                icon.textContent = this.classList.contains('active') ? '-' : '+';
            });
        });
    });
</script>
<script>
    function validateInputFloatingPoint(input) {
        // Allow only numeric values and floating points
        input.value = input.value.replace(/[^0-9.]/g, '');

        // Ensure that there is only one decimal point
        if ((input.value.match(/\./g) || []).length > 1) {
            input.value = input.value.slice(0, -1);
        }
    }

    function validateIntegerInput(input) {
        // Replace any non-digit characters with an empty string
        input.value = input.value.replace(/[^0-9]/g, '');
    }
</script>

</html>