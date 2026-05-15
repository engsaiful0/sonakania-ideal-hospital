<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Mobile Version Report</title>

    <!-- Bootstrap 5 -->
    
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
   
    $company = $this->db->where('company_id', '1')->get('company')->row();
    ?>

    <div class="container" style="margin-top: 10px;">
        <div class="row">
            <!-- <div class="col-md-4 col-sm-12 col-xs-12">
                <img style="width: 100%; height: 60%;" src="<?php echo base_url(); ?>assets/images/<?php echo $company->logo; ?>">
            </div> -->
            <div class="col-md-12 col-sm-12 col-xs-12">
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
        <form method="post" action="<?php echo site_url('mobile'); ?>" class="row g-2 align-items-end">
            <div class="col-12">
                <p class="text-center text-muted small mb-2">
                    Summary for <strong><?php echo html_escape($display_from); ?></strong>
                    <?php if ($display_from !== $display_to) { ?>
                        to <strong><?php echo html_escape($display_to); ?></strong>
                    <?php } ?>
                </p>
            </div>
            <div class="col-md-5">
                <div class="form-group">
                    <label for="datepicker1">From Date</label>
                    <input type="date" name="from_date" value="<?php echo html_escape($input_date_from); ?>" id="datepicker1" class="form-control" autocomplete="off">
                </div>
            </div>

            <div class="col-md-5">
                <div class="form-group">
                    <label for="datepicker2">To Date</label>
                    <input type="date" name="to_date" value="<?php echo html_escape($input_date_to); ?>" id="datepicker2" class="form-control" autocomplete="off">
                </div>
            </div>

            <div class="col-md-2">
                <div class="form-group">
                    <label class="d-block">&nbsp;</label>
                    <button type="submit" class="btn btn-primary w-100">Search</button>
                </div>
            </div>
        </form>
    </div>
    <div class="container" style="margin-top: 10px;">

        <div class="row g-4 mb-5">
            <div class="col-md-3">
                <div class="card pricing-card text-center p-4 border border-primary">
                    <span class="badge bg-primary mb-2">Total Income</span>

                    <h4><?php echo number_format($total_income); ?></h4>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card pricing-card text-center p-4 border border-warning">
                    <h4 class="badge bg-warning mb-2">Total Return</h4>
                    <h4><?php echo number_format($total_return); ?></h4>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card pricing-card text-center p-4 border border-danger">
                    <h4 class="badge bg-danger mb-2">Total Expense</h4>
                    <h4><?php echo number_format($total_expense); ?></h4>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card pricing-card text-center p-4 border border-success">
                    <h4 class="badge bg-success mb-2">Total Balance</h4>
                    <h4><?php echo number_format($total_balance); ?></h4>
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


    <div class="container" style="margin-top: 10px;height: 10vh;">
        <div class="row">
        </div>
    </div>

    <script type="text/javascript" src="<?php echo base_url() ?>js/bootstrap.bundle.min.js"></script>

</body>

</html>