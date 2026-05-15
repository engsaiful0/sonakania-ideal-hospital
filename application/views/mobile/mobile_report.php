<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bootstrap Various Cards</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" media="screen,projection" type="text/css" href="<?php echo base_url() ?>css/bootstrap5.3.min.css" />

    <!-- Bootstrap Icons -->

    <link rel="stylesheet" media="screen,projection" type="text/css" href="<?php echo base_url() ?>css/bootstrap-icons.min.css" />
    <style>
        body {
            background: #f5f7fb;
        }

        .card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
            transition: 0.3s;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .gradient-card {
            background: linear-gradient(135deg, #4e54c8, #8f94fb);
            color: #fff;
        }

        .profile-img {
            width: 90px;
            height: 90px;
            border-radius: 50%;
            object-fit: cover;
        }

        .pricing-card h1 {
            font-size: 48px;
            font-weight: bold;
        }

        .stats-icon {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: #fff;
        }

        .news-img {
            height: 220px;
            object-fit: cover;
        }
    </style>
</head>

<body>

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
    $company = $this->db->where('company_id', '1')->get('company')->row();
    ?>

    <div class="container" style="margin-top: 10px;">
        <div class="row">
            <div class="col-md-4">
                <img style="width: 150px; height: 150px;" src="<?php echo base_url(); ?>assets/images/<?php echo $company->logo; ?>">
            </div>
            <div class="col-md-8">
                <p style="text-align: center;">
                    <span style="text-align: center;"><?php echo $company->company_name; ?></span><br>
                    <span style="text-align: center;"><?php echo $company->address; ?></span><br>
                    <span style="text-align: center;">Email: <?php echo $company->email; ?>,</span>
                    <span style="text-align: center;">Web: <?php echo $company->web; ?></span>
                </p>
            </div>
        </div>
    </div>
    <div class="container" style="margin-top: 10px;">
        <div class="row">
            <div class="col-md-5">
                <div class="form-group">
                    <label>From Date</label>
                    <input name="from_date" value="<?php echo date('d-m-Y'); ?>" id="datepicker1" class="form-control">
                </div>
            </div>

            <div class="col-md-5">
                <div class="form-group">
                    <label>To Date</label>
                    <input name="to_date" value="<?php echo date('d-m-Y'); ?>" id="datepicker2" class="form-control">
                </div>
            </div>

            <div class="col-md-2">
                <div class="form-group">
                    <label>&nbsp;</label>
                    <input type="submit" class="btn btn-primary btn-block" onclick="load_date_wise_summary_report()" value="Search">
                </div>
            </div>
        </div>
    </div>
    <div class="container" style="margin-top: 10px;">
        <!-- Basic Cards -->
        <div class="row g-4 mb-5">

            <div class="col-md-3">
                <div class="card p-3">
                    <div class="card-body">
                        <h5 class="card-title text-center">Total OPD Paid</h5>
                        <p class="card-text btn btn-primary btn-block" style="width: 100%;">
                            <?php echo number_format($total_opd_payable_today); ?>
                        </p>

                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card p-3 text-center">
                    <div class="card-body">
                        <h5 class="card-title text-center">Total Test</h5>
                        <p class="card-text btn btn-primary btn-block" style="width: 100%;">
                            <?php echo number_format($total_test_entry_today); ?>
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card p-3">
                    <div class="card-body">
                        <h5 class="card-title">Total IPD Paid</h5>
                        <p class="card-text btn btn-primary btn-block" style="width: 100%;">
                            <?php echo number_format($total_ipd_paid_amount_today); ?>
                        </p>
                    </div>
                </div>
            </div>


            <div class="col-md-3">
                <div class="card p-3 text-center">
                    <div class="card-body">
                        <h5 class="card-title text-center">Total Discharge</h5>
                        <p class="card-text btn btn-primary btn-block" style="width: 100%;">
                            <?php echo number_format($total_discharge_value_today); ?>
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card p-3 text-center">
                    <div class="card-body">
                        <h5 class="card-title text-center">Total Emergency</h5>
                        <p class="card-text btn btn-primary btn-block" style="width: 100%;">
                            <?php echo number_format($total_emergency_today); ?>
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card p-3 text-center">
                    <div class="card-body">
                        <h5 class="card-title text-center">Total Physiotherapy</h5>
                        <p class="card-text btn btn-primary btn-block" style="width: 100%;">
                            <?php echo number_format($total_phygiotherapy_today); ?>
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card p-3 text-center">
                    <div class="card-body">
                        <h5 class="card-title text-center">Total Medicine Sales</h5>
                        <p class="card-text btn btn-primary btn-block" style="width: 100%;">
                            <?php echo number_format($total_medicine_sells_today); ?>
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card p-3 text-center">
                    <div class="card-body">
                        <h5 class="card-title text-center">Total Credit Voucher</h5>
                        <p class="card-text btn btn-primary btn-block" style="width: 100%;">
                            <?php echo number_format($total_credit_voucher_today); ?>
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card p-3 text-center">
                    <div class="card-body">
                        <h5 class="card-title text-center">Total Debit Voucher</h5>
                        <p class="card-text btn btn-primary btn-block" style="width: 100%;">
                            <?php echo number_format($total_debit_voucher_today); ?>
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card p-3 text-center">
                    <div class="card-body">
                        <h5 class="card-title text-center">Total OT Service Paid</h5>
                        <p class="card-text btn btn-primary btn-block" style="width: 100%;">
                            <?php echo number_format($total_ot_service_paid_today); ?>
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card p-3 text-center">
                    <div class="card-body">
                        <h5 class="card-title text-center">Total Test Due Collection</h5>
                        <p class="card-text btn btn-primary btn-block" style="width: 100%;">
                            <?php echo number_format($total_test_due_collection_today); ?>
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card p-3 text-center">
                    <div class="card-body">
                        <h5 class="card-title text-center">Total Test Return</h5>
                        <p class="card-text btn btn-primary btn-block" style="width: 100%;">
                            <?php echo number_format($total_test_return_today); ?>
                        </p>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Pricing Cards -->
    <div class="container" style="margin-top: 10px;">

        <div class="row g-4 mb-5">
            <div class="col-md-3">
                <div class="card pricing-card text-center p-4 border border-primary">
                    <span class="badge bg-primary mb-2">Total Income</span>

                    <h4><?php echo number_format($total_income); ?></h4>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card pricing-card text-center p-4">
                    <h4>Total Return</h4>
                    <h4><?php echo number_format($total_return); ?></h4>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card pricing-card text-center p-4">
                    <h4>Total Expense</h4>
                    <h4><?php echo number_format($total_expense); ?></h4>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card pricing-card text-center p-4">
                    <h4>Total Balance</h4>
                    <h4><?php echo number_format($total_balance); ?></h4>
                </div>
            </div>

        </div>
    </div>
    <div class="container" style="margin-top: 10px;height: 10vh;">
        <div class="row">
        </div>
    </div>

    <script type="text/javascript" src="<?php echo base_url() ?>js/bootstrap.bundle.min.js"></script>

</body>

</html>