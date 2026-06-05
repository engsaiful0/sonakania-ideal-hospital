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
<div class="container">
   

        <div class="row">
            <div class="col-md-12">
                <button onclick="window.print()" class="btn btn-primary">Print</button>
            </div>
        </div>

        <div id="report" style="width: 90%; margin: 0 auto; margin-left: 45px; margin-top: 10px;" class="panel panel-primary">
            <div class="panel-heading"></div>

            <div class="panel-body">
                <?php
                $compnay = $this->db->where('company_id', '1')->get('company')->row();
                $user_type_name = $this->session->userdata('user_type');

                if ($user_type_name == 'Admin') {
                ?>
                    <div class="container_user">
                        <div class="filter-box">
                            <div class="row">
                                <div class="col-sm-5">
                                    <div class="form-group">
                                        <label>From Date</label>
                                        <input name="from_date" value="<?php echo date('d-m-Y'); ?>" id="datepicker1" class="form-control">
                                    </div>
                                </div>

                                <div class="col-sm-5">
                                    <div class="form-group">
                                        <label>To Date</label>
                                        <input name="to_date" value="<?php echo date('d-m-Y'); ?>" id="datepicker2" class="form-control">
                                    </div>
                                </div>

                                <div class="col-sm-2">
                                    <div class="form-group">
                                        <label>&nbsp;</label>
                                        <input type="submit" class="btn btn-primary btn-block" onclick="load_date_wise_summary_report()" value="Search">
                                    </div>
                                </div>
                            </div>

                            <img
                                src="<?php echo base_url(); ?>images/ajax-loader.gif"
                                id="loadingSpinner"
                                style="display:none; position:fixed; top:50%; left:50%; transform:translate(-50%, -50%); z-index:9999;"
                                alt="Loading...">
                        </div>
                    </div>
                <?php } ?>

                <div id="report_container_header">
                    <div class="company-header">
                        <div class="company-logo">
                            <img src="<?php echo base_url(); ?>assets/images/<?php echo $compnay->logo; ?>">
                        </div>

                        <div class="company-info">
                            <p>
                                <span class="company-name"><?php echo $compnay->company_name; ?></span><br>
                                <?php echo $compnay->address; ?><br>
                                Email: <?php echo $compnay->email; ?>,
                                Web: <?php echo $compnay->web; ?>
                            </p>
                        </div>
                    </div>

                    <div id="report_container">
                        <div class="row">
                            <div class="col-md-12">

                                <?php
                                $this->db->select_sum('nettotal');
                                $this->db->from('medicine_sales');
                                $this->db->where('date', date('Y-m-d'));
                                $query = $this->db->get();
                                $total_medicine_sells_today = $query->row()->nettotal ?? 0;

                                $this->db->select_sum('payable');
                                $this->db->from('opd_patient');
                                $this->db->where('entry_date', date('Y-m-d'));
                                $query = $this->db->get();
                                $total_opd_payable_today = $query->row()->payable ?? 0;

                                $this->db->select_sum('paid_amount');
                                $this->db->from('ipd_patient');
                                $this->db->where('date', date('Y-m-d'));
                                $query = $this->db->get();
                                $total_ipd_paid_amount_today = $query->row()->paid_amount ?? 0;

                                $this->db->select_sum('paid');
                                $this->db->from('emergency');
                                $this->db->where('date', date('Y-m-d'));
                                $query = $this->db->get();
                                $total_emergency_today = $query->row()->paid ?? 0;

                                $this->db->select_sum('paid');
                                $this->db->from('phygiotherapy');
                                $this->db->where('date', date('Y-m-d'));
                                $query = $this->db->get();
                                $total_phygiotherapy_today = $query->row()->paid ?? 0;

                                $this->db->select_sum('total_amount');
                                $this->db->from('debit_voucher');
                                $this->db->where('date', date('Y-m-d'));
                                $query = $this->db->get();
                                $total_debit_voucher_today = $query->row()->total_amount ?? 0;

                                $this->db->select_sum('total_amount');
                                $this->db->from('credit_voucher');
                                $this->db->where('date', date('Y-m-d'));
                                $query = $this->db->get();
                                $total_credit_voucher_today = $query->row()->total_amount ?? 0;

                                $this->db->select_sum('paid');
                                $this->db->from('ot_services');
                                $this->db->where('date', date('Y-m-d'));
                                $query = $this->db->get();
                                $total_ot_service_paid_today = $query->row()->paid ?? 0;

                                $this->db->select_sum('paid');
                                $this->db->from('test_collection');
                                $this->db->where('payment_type', 'from_direct_sales');
                                $this->db->where('date', date('Y-m-d'));
                                $query = $this->db->get();
                                $total_test_entry_today = $query->row()->paid ?? 0;

                                $this->db->select_sum('paid');
                                $this->db->from('test_collection');
                                $this->db->where('payment_type', 'from_due_collection');
                                $this->db->where('date', date('Y-m-d'));
                                $query = $this->db->get();
                                $total_test_due_collection_today = $query->row()->paid ?? 0;

                                $this->db->select_sum('total_return');
                                $this->db->from('patient_test_entry_details');
                                $this->db->where('date', date('Y-m-d'));
                                $query = $this->db->get();
                                $total_test_return_today = $query->row()->total_return ?? 0;

                                $this->db->select_sum('paid');
                                $this->db->from('discharge');
                                $this->db->where('discharge_date', date('Y-m-d'));
                                $query = $this->db->get();
                                $total_discharge_value_today = $query->row()->paid ?? 0;

                                $this->db->select_sum('paid');
                                $this->db->from('medicine_sales');
                                $this->db->where('bill_date', date('Y-m-d'));
                                $query = $this->db->get();
                                $total_medicine_sells_today = $query->row()->paid ?? 0;

                                $this->db->select_sum('due_payment');
                                $this->db->from('medicine_sales');
                                $this->db->where('due_payment_date', date('Y-m-d'));
                                $query = $this->db->get();
                                $total_medicine_sells_due_payment = $query->row()->due_payment ?? 0;

                                $this->db->select_sum('returnable_amount');
                                $this->db->from('emergency');
                                $this->db->where(['date' => date('Y-m-d')]);
                                $query = $this->db->get();
                                $emergency_return_amount = $query->row()->returnable_amount ?? 0;

                                $this->db->select_sum('returnable_amount');
                                $this->db->from('phygiotherapy');
                                $this->db->where(['date' => date('Y-m-d')]);
                                $query = $this->db->get();
                                $phygiotherapy_return_amount = $query->row()->returnable_amount ?? 0;

                                $this->db->select_sum('returnable_amount');
                                $this->db->from('opd_patient');
                                $this->db->where(['entry_date' => date('Y-m-d')]);
                                $query = $this->db->get();
                                $opd_returnable_amount = $query->row()->returnable_amount ?? 0;

                                $this->db->select_sum('paid');
                                $this->db->from('medicine_sale_return');
                                $this->db->where(['date' => date('Y-m-d')]);
                                $query = $this->db->get();
                                $medicine_sales_return_amount = $query->row()->paid ?? 0;

                                $total_income =
                                    $total_ot_service_paid_today +
                                    $total_discharge_value_today +
                                    $total_test_entry_today +
                                    $total_test_due_collection_today +
                                    $total_opd_payable_today +
                                    $total_ipd_paid_amount_today +
                                    $total_emergency_today +
                                    $total_phygiotherapy_today +
                                    $total_medicine_sells_today +
                                    $total_medicine_sells_due_payment +
                                    $total_credit_voucher_today;

                                $total_return =
                                    $medicine_sales_return_amount +
                                    $opd_returnable_amount +
                                    $total_test_return_today +
                                    $emergency_return_amount +
                                    $phygiotherapy_return_amount;

                                $total_expense = $total_debit_voucher_today;
                                $total_balance = $total_income - $total_return - $total_expense;

                                $summary_items = [
                                    ['sl' => 1, 'title' => 'OPD', 'income' => $total_opd_payable_today, 'return' => $opd_returnable_amount, 'expense' => 0],
                                    ['sl' => 2, 'title' => 'IPD', 'income' => $total_ipd_paid_amount_today, 'return' => 0, 'expense' => 0],
                                    ['sl' => 3, 'title' => 'Discharge', 'income' => $total_discharge_value_today, 'return' => 0, 'expense' => 0],
                                    ['sl' => 4, 'title' => 'Emergency', 'income' => $total_emergency_today, 'return' => $emergency_return_amount, 'expense' => 0],
                                    ['sl' => 5, 'title' => 'Physiotherapy', 'income' => $total_phygiotherapy_today, 'return' => $phygiotherapy_return_amount, 'expense' => 0],
                                    ['sl' => 6, 'title' => 'Test Entry', 'income' => $total_test_entry_today, 'return' => $total_test_return_today, 'expense' => 0],
                                    ['sl' => 7, 'title' => 'Test Due Collection', 'income' => $total_test_due_collection_today, 'return' => 0, 'expense' => 0],
                                    ['sl' => 8, 'title' => 'Pharmacy', 'income' => $total_medicine_sells_today + $total_medicine_sells_due_payment, 'return' => $medicine_sales_return_amount, 'expense' => 0],
                                    ['sl' => 9, 'title' => 'Credit Voucher', 'income' => $total_credit_voucher_today, 'return' => 0, 'expense' => 0],
                                    ['sl' => 10, 'title' => 'OT', 'income' => $total_ot_service_paid_today, 'return' => 0, 'expense' => 0],
                                    ['sl' => 11, 'title' => 'Debit Voucher', 'income' => 0, 'return' => 0, 'expense' => $total_debit_voucher_today],
                                ];
                                ?>

                                <div class="summary-page">
                                    <h3 class="summary-main-title">Today Summary</h3>

                                    <div class="summary-date">
                                        Summary report of date:
                                        <b><?php echo date('d-m-Y'); ?></b>
                                    </div>

                                    <div class="row">
                                        <?php foreach ($summary_items as $item) { ?>
                                            <div class="col-xs-12 col-sm-6 col-md-4">
                                                <div class="summary-card">
                                                    <div class="summary-card-header">
                                                        <h4 class="summary-card-title">
                                                            <?php echo $item['title']; ?>
                                                        </h4>
                                                       
                                                    </div>

                                                    <div class="summary-card-body">
                                                        <div class="summary-row">
                                                            <span class="summary-label">Income</span>
                                                            <span class="summary-value income-text">
                                                                <?php echo number_format($item['income']); ?>
                                                            </span>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        <?php } ?>
                                    </div>

                                    <div class="summary-card total-summary-card">
                                        <div class="summary-card-header">
                                            <h4 class="summary-card-title">Final Summary</h4>
                                            <span class="summary-badge">৳</span>
                                        </div>

                                        <div class="summary-card-body">
                                            <div class="summary-row">
                                                <span class="summary-label">Total Income</span>
                                                <span class="summary-value income-text">
                                                    <?php echo number_format($total_income); ?>
                                                </span>
                                            </div>

                                            <div class="summary-row">
                                                <span class="summary-label">Total Return</span>
                                                <span class="summary-value return-text">
                                                    <?php echo number_format($total_return); ?>
                                                </span>
                                            </div>

                                            <div class="summary-row">
                                                <span class="summary-label">Total Expense</span>
                                                <span class="summary-value expense-text">
                                                    <?php echo number_format($total_expense); ?>
                                                </span>
                                            </div>

                                            <div class="summary-row">
                                                <span class="summary-label">Total Balance</span>
                                                <span class="summary-value balance-text">
                                                    <?php echo number_format($total_balance); ?>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
  </div>
</body>
</html>
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
      $('#discharge_date').datepicker({
           "changeMonth": true,
           "changeYear": true,
           "dateFormat": "dd-mm-yy",
           "yearRange": '1995:2030'
       });
       $('#admission_date').datepicker({
           "changeMonth": true,
           "changeYear": true,
           "dateFormat": "dd-mm-yy",
           "yearRange": '1995:2030'
       });
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