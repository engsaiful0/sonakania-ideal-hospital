<!DOCTYPE html>
<html lang="en">

<head>
    <title>Mirzakhil General Hospital and Diagnostic Center Ltd</title>
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
    <style>
        .box_top {
            list-style: none;
            padding: 0;
            margin: 0;
            font-family: Arial, sans-serif;
            height: 50px;
        }
    </style>
</head>

<body>

    <div id="main">
        <!-- Tray -->
        <div id="tray" style="width:100%!important" class="box_top">
            <?php
            $this->load->view("common_customer/top_bar.php");
            //  include();
            ?>
        </div> <!--  /tray -->

        <!-- Menu -->
        <div id="menu" class="box_top" style="width:100%!important">
            <?php
            $this->load->view("common_customer/top_menu.php");
            ?>
        </div> <!-- /header -->
        <!-- Columns -->
        <div class="container-fluid">
            <?php

            $this->load->view($page_name);
            ?>
        </div> <!-- /cols -->
        <hr class="noscreen" />
        <!-- Footer -->
        <div id="footer" class="box">
            <?php
            $this->load->view("common_customer/footer.php");
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
    function validateNumberInput(e) {
        // Allow control keys: Backspace, Delete, Arrow keys, Home, End, Tab
        if (
            ["Backspace", "Delete", "ArrowLeft", "ArrowRight", "Home", "End", "Tab"].includes(e.key)
        ) {
            return; // Allow the event to proceed for these keys
        }

        // Allow only numbers and a single decimal point
        if (isNaN(e.key) && e.key !== ".") {
            e.preventDefault(); // Block non-numeric keys except for '.'
        } else if (e.key === ".") {
            // Prevent multiple decimal points
            if (e.target.value.includes(".")) {
                e.preventDefault();
            }
        }
    }
    validateInputFloatingPointWithPercentage

    function validateNumberInputWithPercentage(e) {
        // Allow control keys: Backspace, Delete, Arrow keys, Home, End, Tab
        if (
            ["Backspace", "Delete", "ArrowLeft", "ArrowRight", "Home", "End", "Tab"].includes(e.key)
        ) {
            return; // Allow the event to proceed for these keys
        }

        // Allow only numbers, a single decimal point, and the percentage symbol
        if (isNaN(e.key) && e.key !== "." && e.key !== "%") {
            e.preventDefault(); // Block non-numeric keys except for '.' and '%'
        } else if (e.key === ".") {
            // Prevent multiple decimal points
            if (e.target.value.includes(".")) {
                e.preventDefault();
            }
        } else if (e.key === "%") {
            // Prevent multiple '%' symbols
            if (e.target.value.includes("%")) {
                e.preventDefault();
            }
        }
    }

    function validateInputFloatingPointWithPercentage(input) {
        // Remove any non-numeric characters except for dot (.) and percentage (%)
        input.value = input.value.replace(/[^0-9.%]/g, '');

        // Ensure only one dot (.) is allowed
        const dotCount = (input.value.match(/\./g) || []).length;
        if (dotCount > 1) {
            $.toast({
                heading: 'Error',
                text: 'Only one decimal point is allowed.',
                showHideTransition: 'slide',
                position: 'top-right',
                hideAfter: 1000,
                icon: 'error'
            });
            // Remove the last character (which is the extra dot) and return
            input.value = input.value.slice(0, -1);
            return;
        }

        // Ensure only one percentage symbol (%) is allowed and it must be at the end
        const percentageCount = (input.value.match(/%/g) || []).length;
        if (percentageCount > 1 || (input.value.includes('%') && !input.value.endsWith('%'))) {
            $.toast({
                heading: 'Error',
                text: 'Percentage symbol (%) can only appear at the end.',
                showHideTransition: 'slide',
                position: 'top-right',
                hideAfter: 1000,
                icon: 'error'
            });
            // Remove invalid % placement
            input.value = input.value.replace(/%/g, '') + '%';
            return;
        }

        // If the input is a single dot (.), allow it temporarily while typing
        if (input.value === '.') {
            return;
        }

        // Regular expression to validate floating point numbers with an optional percentage
        const regex = /^-?\d+(\.\d+)?%?$/;

        // If the input is not a valid floating-point number or percentage, show an error
        if (!regex.test(input.value)) {
            $.toast({
                heading: 'Error',
                text: 'Insert a valid floating point number or percentage (e.g., 15.25 or 15.25%).',
                showHideTransition: 'slide',
                position: 'top-right',
                hideAfter: 1000,
                icon: 'error'
            });
        }
    }



    function validateInputFloatingPoint(input) {
        // Remove any non-numeric characters except for the dot
        input.value = input.value.replace(/[^0-9.]/g, '');

        // Check for multiple dots and immediately return if found
        const dotCount = (input.value.match(/\./g) || []).length;
        if (dotCount > 1) {
            $.toast({
                heading: 'Error',
                text: 'Only one decimal point is allowed.',
                showHideTransition: 'slide',
                position: 'top-right',
                hideAfter: 1000,
                icon: 'error' // Show error icon
            });
            // Remove the last character (which is the extra dot) and return
            input.value = input.value.slice(0, -1);
            return;
        }

        // If the input is a single dot (.), allow it temporarily without showing error
        if (input.value === '.') {
            return; // Just a single dot is allowed temporarily while typing
        }

        // Regular expression to validate floating point numbers (e.g., 123.45, 0.5, -0.5)
        const regex = /^-?\d+(\.\d+)?$/;

        // If the input is not a valid floating-point number, show the error toast
        if (!regex.test(input.value) && dotCount != 1) {
            $.toast({
                heading: 'Error',
                text: 'Insert a valid floating point number.',
                showHideTransition: 'slide',
                position: 'top-right',
                hideAfter: 1000,
                icon: 'error' // Show error icon
            });
        }
    }

    function validateIntegerInput(input) {
        // Trim the input value to remove any extra whitespace
        const trimmedValue = input.value.trim();

        // Check if the input value is a valid integer
        if (!/^\d+$/.test(trimmedValue)) {
            // Show an error toast if not valid
            $.toast({
                heading: 'Error',
                text: 'Insert a valid integer number.',
                showHideTransition: 'slide',
                position: 'top-right',
                hideAfter: 1000,
                icon: 'error' // 'error' indicates a warning
            });

            // Optionally, clear the invalid input
            input.value = '';
        } else {
            // If valid, set the cleaned value back to the input field
            input.value = trimmedValue;
        }
    }

    function validatePhoneNumberInput(input) {
        // Trim the input value to remove any extra whitespace
        const trimmedValue = input.value.trim();

        // Regex for validating phone numbers with optional country code
        const phoneNumberRegex = /^\+?[0-9]*$/; // Allows an optional "+" at the start and only digits

        // Set minimum and maximum lengths for the phone number
        const minLength = 8; // Minimum phone number length
        const maxLength = 15; // Maximum phone number length

        // Check if the input matches the regex pattern (valid characters only)
        if (!phoneNumberRegex.test(trimmedValue)) {
            // Show an error toast if invalid characters are entered
            $.toast({
                heading: 'Error',
                text: 'Phone number can only contain digits and an optional "+" at the start.',
                showHideTransition: 'slide',
                position: 'top-right',
                hideAfter: 1500,
                icon: 'error'
            });

            // Remove the invalid character
            input.value = input.value.slice(0, -1);
            return;
        }

        // Check if the phone number length exceeds the maximum limit
        if (trimmedValue.length > maxLength) {
            $.toast({
                heading: 'Error',
                text: `Phone number cannot exceed ${maxLength} digits.`,
                showHideTransition: 'slide',
                position: 'top-right',
                hideAfter: 1500,
                icon: 'error'
            });

            // Trim the input to the maximum allowed length
            input.value = trimmedValue.slice(0, maxLength);
            return;
        }
    }
</script>

</html>