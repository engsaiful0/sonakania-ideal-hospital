<link href="<?php echo base_url() ?>css/card_boostrap.css" rel="stylesheet">
<style type="text/css">
    .card {
        background-color: #fff;
        border-radius: 10px;
        border: none;
        position: relative;
        margin-bottom: 30px;
        box-shadow: 0 0.46875rem 2.1875rem rgba(90, 97, 105, 0.1), 0 0.9375rem 1.40625rem rgba(90, 97, 105, 0.1), 0 0.25rem 0.53125rem rgba(90, 97, 105, 0.12), 0 0.125rem 0.1875rem rgba(90, 97, 105, 0.1);
    }

    /* Modern dashboard theme */
    .dashboard-hero {
        background: linear-gradient(135deg, #2b5876 0%, #4e4376 100%);
        border-radius: 12px;
        padding: 18px 20px;
        color: #fff;
        margin: 10px 0 20px 0;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .dashboard-hero .title {
        font-size: 20px;
        font-weight: 600;
        margin: 0;
    }
    .dashboard-hero .subtitle {
        opacity: 0.9;
        font-size: 13px;
        margin-top: 4px;
    }
    .dashboard-hero .chip {
        background: rgba(255,255,255,0.16);
        padding: 6px 12px;
        border-radius: 999px;
        font-size: 12px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    .kpi-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 16px;
        width: 100%;
        margin: 0 0 10px 0;
    }
    @media (max-width: 1200px) {
        .kpi-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
    }
    @media (max-width: 768px) {
        .kpi-grid { grid-template-columns: 1fr; }
    }
    .kpi-card {
        border-radius: 12px;
        overflow: hidden;
        position: relative;
    }
    .card, .kpi-card { transition: transform .2s ease, box-shadow .2s ease; }
    .card:hover, .kpi-card:hover { transform: translateY(-2px); box-shadow: 0 10px 26px rgba(0,0,0,.18); }
    .kpi-inner {
        display: flex;
        align-items: center;
        gap: 14px;
    }
    .kpi-icon {
        width: 46px;
        height: 46px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: #eef2ff;
        color: #2563eb;
        font-size: 18px;
    }
    .kpi-meta { color: #111827; }
    .kpi-label { font-size: 12px; color: #6b7280; margin: 0; }
    .kpi-value { font-size: 22px; font-weight: 700; margin: 2px 0 0 0; color: #111827; }
    .progress-soft {
        background: #e5e7eb;
        width: 100%;
        height: 8px;
        border-radius: 999px;
        overflow: hidden;
    }
    .progress-soft > span {
        display: block;
        height: 100%;
        background: linear-gradient(90deg, #2563eb, #60a5fa);
    }
    .charts-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 16px;
        width: 100%;
        margin: 10px 0 0 0;
    }
    @media (max-width: 1200px) {
        .charts-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
    }
    @media (max-width: 768px) {
        .charts-grid { grid-template-columns: 1fr; }
    }

    /* Neutral card style for lists on colored backgrounds */
    .card-plain {
        background: #ffffff;
        border-radius: 12px;
        box-shadow: 0 6px 18px rgba(0,0,0,0.06);
    }
    .card .card-statistic-3 h5.card-title { color: #111827; }
    .card .card-statistic-3 h2 { color: #111827 !important; }
    .section-title {
        font-size: 16px;
        font-weight: 600;
        margin: 0 0 10px 0;
        color: #333;
    }
    .table-modern {
        width: 100%;
        border-collapse: collapse;
    }
    .table-modern th, .table-modern td {
        padding: 8px 10px;
        border-bottom: 1px solid #eee;
        font-size: 13px;
        color: #333;
    }
    .table-modern th { color: #555; text-transform: uppercase; letter-spacing: .02em; font-size: 12px; }
    .mini-muted { color: rgba(255,255,255,.9); font-size: 12px; }

    /* Professional, unified card scheme: neutral surfaces with accent top borders */
    .l-bg-cherry,
    .l-bg-blue-dark,
    .l-bg-green-dark,
    .l-bg-orange-dark,
    .l-bg-cyan,
    .l-bg-green,
    .l-bg-orange {
        background: #ffffff !important;
        color: #111827;
        border-top: 3px solid #e5e7eb; /* default light border */
    }
    .l-bg-blue-dark { border-top-color: #2563eb; }
    .l-bg-green-dark { border-top-color: #059669; }
    .l-bg-orange-dark { border-top-color: #d97706; }
    .l-bg-cyan { border-top-color: #0ea5a4; }
    .l-bg-green { border-top-color: #10b981; }
    .l-bg-orange { border-top-color: #f59e0b; }



    .card .card-statistic-3 .card-icon {
        text-align: center;
        line-height: 50px;
        margin-left: 15px;
        color: #111827;
        position: absolute;
        right: -5px;
        top: 20px;
        opacity: 0.06;
    }

    .l-bg-cyan {
        background: #ffffff !important;
        color: #111827;
    }

    .l-bg-green {
        background: #ffffff !important;
        color: #111827;
    }

    .l-bg-orange {
        background: #ffffff !important;
        color: #111827;
    }

    .l-bg-cyan {
        background: #ffffff !important;
        color: #111827;
    }
</style>
<style>
    fieldset {
        background-color: #eeeeee;
    }

    legend {
        background-color: gray;
        color: white;
        padding: 5px 10px;
    }

    input {
        margin: 5px;
    }
</style>

<div class="container-fluid" style="background-color: white; width: 100%;">
    <div class="panel panel-primary" style="width: 100%; margin: 0 auto;">
        <div class="panel-heading">
            <?php 
             $company = $this->db->where('company_id', '1')->get('company')->row();
             if (!$company) {
                 return false; // Early return if company not found
             }
     
             $company_name = $company->company_name;
            ?>
            <h3 style="text-align: center;"><?php echo $company_name; ?></h3>
        </div>
        <div class="panel-body">


            <?php
            error_reporting(0);
            // Get today's date in the 'Y-m-d' format
            $today = date('Y-m-d');

            $today_date = date('Y-m-d'); // Format: YYYY-MM-DD
            $month_start = date('Y-m-01');
            $year_start = date('Y-01-01');

            // OPD Patients
            $this->db->where('entry_date', $today_date);
            $this->db->from('opd_patient');
            $total_opd_patients_today = $this->db->count_all_results();

            $total_opd_patients_amount_today = $this->db->select_sum('payable')
                ->where('entry_date', $today) // Filter for today's date
                ->get('opd_patient')
                ->row()
                ->payable;

            // IPD Patients
            $this->db->where('date', $today_date);
            $this->db->from('ipd_patient');
            $total_ipd_patients_today = $this->db->count_all_results();

            $total_ipd_patients_amount_today = $this->db->select_sum('paid_amount')
                ->where('date', $today) // Filter for today's date
                ->get('ipd_patient')
                ->row()
                ->paid_amount;

            // Emergency 
            $this->db->where('date', $today_date);
            $this->db->from('emergency');
            $total_emergency_today = $this->db->count_all_results();

            $total_emergency_amount_today = $this->db->select_sum('paid')
                ->where('date', $today) // Filter for today's date
                ->get('emergency')
                ->row()
                ->paid;

            // Phygiotherapy 
            $this->db->where('date', $today_date);
            $this->db->from('phygiotherapy');
            $total_phygiotherapy_today = $this->db->count_all_results();

            $total_phygiotherapy_amount_today = $this->db->select_sum('paid')
                ->where('date', $today) // Filter for today's date
                ->get('phygiotherapy')
                ->row()
                ->paid;

            // Fetch the sum of the amount from the medicine_sales table for the current account for today
            $total_medicine_sale_amount_today = $this->db->select_sum('nettotal')
                ->where('bill_date', $today) // Filter for today's date
                ->get('medicine_sales')
                ->row()
                ->nettotal;

            $total_medicine_due_amount_today = $this->db->select_sum('due')
                ->where('bill_date', $today) // Filter for today's date
                ->get('medicine_sales')
                ->row()
                ->due;



            $total_medicine_purchase_amount_today = $this->db->select_sum('nettotal')
                ->where('date', $today) // Filter for today's date
                ->get('medicine_purchase')
                ->row()
                ->nettotal;

            $total_medicine_purchase_due_amount_today = $this->db->select_sum('due')
                ->where('date', $today) // Filter for today's date
                ->get('medicine_purchase')
                ->row()
                ->due;


            $total_patient_test_entry_net_total_today = $this->db->select_sum('net_total')
                ->where('date', date('Y-m-d')) // Filter for today's date
                ->get('patient_test_entry')
                ->row()
                ->net_total;

            $total_patient_test_entry_amount_today = $this->db->select_sum('due')
                ->where('date', date('Y-m-d')) // Filter for today's date
                ->get('patient_test_entry')
                ->row()
                ->due;

            $this->db->select_sum('paid');
            $this->db->from('test_collection');
            $this->db->where('payment_type', 'from_due_collection');
            $this->db->where('date', date('Y-m-d')); // Filter by today's date
            $query = $this->db->get();
            $total_test_due_collection_today = $query->row()->paid ?? 0; // Return 0 if no income found

            $this->db->select_sum('paid');
            $this->db->from('test_collection');
            $this->db->where('payment_type', 'from_direct_sales');
            $this->db->where('date', date('Y-m-d')); // Filter by today's date
            $query = $this->db->get();
            $total_test_sell_collection_today = $query->row()->paid ?? 0; // Return 0 if no income found

            $this->db->select_sum('total_amount');
            $this->db->from('debit_voucher');
            $this->db->where('date', date('Y-m-d')); // Filter by today's date
            $query = $this->db->get();
            $total_debit_amount_today = $query->row()->total_amount ?? 0; // Return 0 if no income found


            $this->db->select_sum('total_amount');
            $this->db->from('credit_voucher');
            $this->db->where('date', date('Y-m-d')); // Filter by today's date
            $query = $this->db->get();
            $total_credit_amount_today = $query->row()->total_amount ?? 0; // Return 0 if no income found
            
            // Derived overall today stats
            $today_total_revenue =
                (float)$total_patient_test_entry_net_total_today +
                (float)$total_test_sell_collection_today +
                (float)$total_test_due_collection_today +
                (float)$total_medicine_sale_amount_today +
                (float)$total_opd_patients_amount_today +
                (float)$total_ipd_patients_amount_today +
                (float)$total_emergency_amount_today +
                (float)$total_phygiotherapy_amount_today;

            $today_total_patients =
                (int)$total_opd_patients_today +
                (int)$total_ipd_patients_today +
                (int)$total_emergency_today +
                (int)$total_phygiotherapy_today;

            $today_net_cashflow = (float)$total_credit_amount_today - (float)$total_debit_amount_today;

            // Month-to-date (MTD) stats
            $mtd_opd_amount = $this->db->select_sum('payable')->where('entry_date >=', $month_start)->where('entry_date <=', $today)->get('opd_patient')->row()->payable;
            $mtd_ipd_amount = $this->db->select_sum('paid_amount')->where('date >=', $month_start)->where('date <=', $today)->get('ipd_patient')->row()->paid_amount;
            $mtd_emergency_amount = $this->db->select_sum('paid')->where('date >=', $month_start)->where('date <=', $today)->get('emergency')->row()->paid;
            $mtd_physio_amount = $this->db->select_sum('paid')->where('date >=', $month_start)->where('date <=', $today)->get('phygiotherapy')->row()->paid;
            $mtd_test_sale = $this->db->select_sum('net_total')->where('date >=', $month_start)->where('date <=', $today)->get('patient_test_entry')->row()->net_total;
            $mtd_test_collection = $this->db->select_sum('paid')->where('payment_type', 'from_direct_sales')->where('date >=', $month_start)->where('date <=', $today)->get('test_collection')->row()->paid;
            $mtd_test_due_collection = $this->db->select_sum('paid')->where('payment_type', 'from_due_collection')->where('date >=', $month_start)->where('date <=', $today)->get('test_collection')->row()->paid;
            $mtd_pharmacy_sale = $this->db->select_sum('nettotal')->where('bill_date >=', $month_start)->where('bill_date <=', $today)->get('medicine_sales')->row()->nettotal;
            $mtd_debit = $this->db->select_sum('total_amount')->where('date >=', $month_start)->where('date <=', $today)->get('debit_voucher')->row()->total_amount;
            $mtd_credit = $this->db->select_sum('total_amount')->where('date >=', $month_start)->where('date <=', $today)->get('credit_voucher')->row()->total_amount;
            $mtd_revenue = (float)$mtd_opd_amount + (float)$mtd_ipd_amount + (float)$mtd_emergency_amount + (float)$mtd_physio_amount + (float)$mtd_test_sale + (float)$mtd_test_collection + (float)$mtd_test_due_collection + (float)$mtd_pharmacy_sale;
            $mtd_net_cashflow = (float)$mtd_credit - (float)$mtd_debit;

            // Year-to-date (YTD) stats
            $ytd_opd_amount = $this->db->select_sum('payable')->where('entry_date >=', $year_start)->where('entry_date <=', $today)->get('opd_patient')->row()->payable;
            $ytd_ipd_amount = $this->db->select_sum('paid_amount')->where('date >=', $year_start)->where('date <=', $today)->get('ipd_patient')->row()->paid_amount;
            $ytd_emergency_amount = $this->db->select_sum('paid')->where('date >=', $year_start)->where('date <=', $today)->get('emergency')->row()->paid;
            $ytd_physio_amount = $this->db->select_sum('paid')->where('date >=', $year_start)->where('date <=', $today)->get('phygiotherapy')->row()->paid;
            $ytd_test_sale = $this->db->select_sum('net_total')->where('date >=', $year_start)->where('date <=', $today)->get('patient_test_entry')->row()->net_total;
            $ytd_test_collection = $this->db->select_sum('paid')->where('payment_type', 'from_direct_sales')->where('date >=', $year_start)->where('date <=', $today)->get('test_collection')->row()->paid;
            $ytd_test_due_collection = $this->db->select_sum('paid')->where('payment_type', 'from_due_collection')->where('date >=', $year_start)->where('date <=', $today)->get('test_collection')->row()->paid;
            $ytd_pharmacy_sale = $this->db->select_sum('nettotal')->where('bill_date >=', $year_start)->where('bill_date <=', $today)->get('medicine_sales')->row()->nettotal;
            $ytd_debit = $this->db->select_sum('total_amount')->where('date >=', $year_start)->where('date <=', $today)->get('debit_voucher')->row()->total_amount;
            $ytd_credit = $this->db->select_sum('total_amount')->where('date >=', $year_start)->where('date <=', $today)->get('credit_voucher')->row()->total_amount;
            $ytd_revenue = (float)$ytd_opd_amount + (float)$ytd_ipd_amount + (float)$ytd_emergency_amount + (float)$ytd_physio_amount + (float)$ytd_test_sale + (float)$ytd_test_collection + (float)$ytd_test_due_collection + (float)$ytd_pharmacy_sale;
            $ytd_net_cashflow = (float)$ytd_credit - (float)$ytd_debit;
            ?>
            <?php $permissions = $this->session->userdata('permissions'); ?>

            <!-- Hero -->
            <div class="dashboard-hero">
                <div>
                    <div class="title"><i class="fas fa-tachometer-alt"></i> Dashboard Overview</div>
                    <div class="subtitle">At a glance summary of hospital performance</div>
                </div>
                <div class="chip"><i class="far fa-calendar"></i> <?php echo date('d M Y, D'); ?></div>
            </div>

            <!-- At a Glance KPI Grid -->
            <div class="kpi-grid">
                <div class="card l-bg-green kpi-card">
                    <div class="card-statistic-3 p-4 kpi-inner">
                        <div class="kpi-icon"><i class="fas fa-chart-line"></i></div>
                        <div class="kpi-meta">
                            <p class="kpi-label">Total Revenue (Today)</p>
                            <div class="kpi-value"><?php echo number_format($today_total_revenue); ?></div>
                        </div>
                    </div>
                </div>
                <div class="card l-bg-orange kpi-card">
                    <div class="card-statistic-3 p-4 kpi-inner">
                        <div class="kpi-icon"><i class="fas fa-file-invoice-dollar"></i></div>
                        <div class="kpi-meta">
                            <p class="kpi-label">Expenses (Debit Today)</p>
                            <div class="kpi-value"><?php echo number_format($total_debit_amount_today); ?></div>
                        </div>
                    </div>
                </div>
                <div class="card l-bg-cyan kpi-card">
                    <div class="card-statistic-3 p-4 kpi-inner">
                        <div class="kpi-icon"><i class="fas fa-balance-scale"></i></div>
                        <div class="kpi-meta">
                            <p class="kpi-label">Net Cashflow (Today)</p>
                            <div class="kpi-value"><?php echo number_format($today_net_cashflow); ?></div>
                        </div>
                    </div>
                </div>
                <div class="card l-bg-blue-dark kpi-card">
                    <div class="card-statistic-3 p-4 kpi-inner">
                        <div class="kpi-icon"><i class="fas fa-user-friends"></i></div>
                        <div class="kpi-meta">
                            <p class="kpi-label">Total Patients (Today)</p>
                            <div class="kpi-value"><?php echo number_format($today_total_patients); ?></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Overall Summary (Today) -->
            <fieldset class="custom-fieldset">
                <legend>Overall Summary (Today)</legend>
                <div class="col-xl-3 col-lg-3">
                    <div class="card l-bg-green">
                        <div class="card-statistic-3 p-4">
                            <div class="card-icon card-icon-large"><i class="fas fa-chart-line"></i></div>
                            <div class="mb-2"><h5 class="card-title mb-0">Total Revenue</h5></div>
                            <div><h2 style="color: white;" class="d-flex align-items-center mb-0"><?php echo number_format($today_total_revenue); ?></h2></div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-3">
                    <div class="card l-bg-orange">
                        <div class="card-statistic-3 p-4">
                            <div class="card-icon card-icon-large"><i class="fas fa-file-invoice-dollar"></i></div>
                            <div class="mb-2"><h5 class="card-title mb-0">Total Expense (Debit)</h5></div>
                            <div><h2 style="color: white;" class="d-flex align-items-center mb-0"><?php echo number_format($total_debit_amount_today); ?></h2></div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-3">
                    <div class="card l-bg-cyan">
                        <div class="card-statistic-3 p-4">
                            <div class="card-icon card-icon-large"><i class="fas fa-balance-scale"></i></div>
                            <div class="mb-2"><h5 class="card-title mb-0">Net Cashflow</h5></div>
                            <div><h2 style="color: white;" class="d-flex align-items-center mb-0"><?php echo number_format($today_net_cashflow); ?></h2></div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-3">
                    <div class="card l-bg-blue-dark">
                        <div class="card-statistic-3 p-4">
                            <div class="card-icon card-icon-large"><i class="fas fa-user-friends"></i></div>
                            <div class="mb-2"><h5 class="card-title mb-0">Total Patients</h5></div>
                            <div><h2 style="color: white;" class="d-flex align-items-center mb-0"><?php echo number_format($today_total_patients); ?></h2></div>
                        </div>
                    </div>
                </div>
            </fieldset>
            <?php if (in_array('home_patient_dashboard', $permissions)) { ?>
                <fieldset class="custom-fieldset">
                    <legend>Patient (Today)</legend>

                    <div class="col-xl-3 col-lg-3">
                        <div class="card l-bg-cherry">
                            <div class="card-statistic-3 p-4">
                                <div class="card-icon card-icon-large"><i class="fas fa-shopping-cart"></i></div>
                                <div class="mb-4">
                                    <h5 class="card-title mb-0">Total </h5>
                                    <button style="width: 100%;" class="btn btn-success">OPD Patients:
                                        <?php echo number_format($total_opd_patients_today); ?></button>
                                </div>
                                <div class="row align-items-center mb-2 d-flex">
                                    <div class="col-12">
                                        <button style="width: 100%;" class="btn btn-primary">
                                            OPD Patient Amount:
                                            <?php echo number_format($total_opd_patients_amount_today); ?></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-3">
                        <div class="card l-bg-blue-dark">
                            <div class="card-statistic-3 p-4">
                                <div class="card-icon card-icon-large"><i class="fas fa-shopping-cart"></i></div>
                                <div class="mb-4">
                                    <h5 class="card-title mb-0">Total </h5>
                                    <button style="width: 100%;" class="btn btn-success">IPD Patients:
                                        <?php echo number_format($total_ipd_patients_today); ?></button>
                                </div>
                                <div class="row align-items-center mb-2 d-flex">
                                    <div class="col-12">
                                        <button style="width: 100%;" class="btn btn-primary">
                                            IPD Patient Paid:
                                            <?php echo number_format($total_ipd_patients_amount_today); ?></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-3">
                        <div class="card l-bg-green-dark">
                            <div class="card-statistic-3 p-4">

                                <div class="card-icon card-icon-large"><i class="fas fa-shopping-cart"></i></div>
                                <div class="mb-4">
                                    <h5 class="card-title mb-0">Total </h5>
                                    <button style="width: 100%;" class="btn btn-success">Emergency:
                                        <?php echo number_format($total_emergency_today); ?></button>
                                </div>
                                <div class="row align-items-center mb-2 d-flex">
                                    <div class="col-12">
                                        <button style="width: 100%;" class="btn btn-primary">
                                            Emergency Amount:
                                            <?php echo number_format($total_emergency_amount_today); ?></button>
                                    </div>
                                </div>


                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-3">
                        <div class="card l-bg-orange-dark">
                            <div class="card-statistic-3 p-4">
                                <div class="card-icon card-icon-large"><i class="fas fa-dollar-sign"></i></div>
                                <div class="mb-4">
                                    <h5 class="card-title mb-0">Total </h5>
                                    <button style="width: 100%;" class="btn btn-success">Physiotherapy:
                                        <?php echo number_format($total_phygiotherapy_today); ?></button>
                                </div>
                                <div class="row align-items-center mb-2 d-flex">
                                    <div class="col-12">
                                        <button style="width: 100%;" class="btn btn-primary">
                                            Phygiotherapy Amount:
                                            <?php echo number_format($total_phygiotherapy_amount_today); ?></button>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                </fieldset>
            <?php } ?>


            <?php if (in_array('home_test_dashboard', $permissions)) { ?>
                <fieldset class="custom-fieldset">
                    <legend>Test (Today)</legend>
                    <div class="col-xl-4 col-lg-4">
                        <div class="card l-bg-cherry">
                            <div class="card-statistic-3 p-4">
                                <div class="card-icon card-icon-large"><i class="fas fa-shopping-cart"></i></div>
                                <div class="mb-4">
                                    <h5 class="card-title mb-0">Total Sale </h5>
                                </div>
                                <div class="row align-items-center mb-2 d-flex">
                                    <div class="col-8">
                                        <h2 style="color: white;" class="d-flex align-items-center mb-0">
                                            <?php
                                            echo number_format($total_patient_test_entry_net_total_today);
                                            ?>
                                        </h2>
                                    </div>

                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-3" style="display:none">
                        <div class="card l-bg-blue-dark">
                            <div class="card-statistic-3 p-4">
                                <div class="card-icon card-icon-large"><i class="fas fa-users"></i></div>
                                <div class="mb-4">
                                    <h5 class="card-title mb-0">Total Due</h5>
                                </div>
                                <div class="row align-items-center mb-2 d-flex">
                                    <div class="col-8">
                                        <h2 style="color: white;" class="d-flex align-items-center mb-0">
                                            <?php
                                            echo number_format($total_patient_test_entry_amount_today);
                                            ?>
                                        </h2>
                                    </div>

                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-4">
                        <div class="card l-bg-green-dark">
                            <div class="card-statistic-3 p-4">
                                <div class="card-icon card-icon-large"><i class="fas fa-ticket-alt"></i></div>
                                <div class="mb-4">
                                    <h5 class="card-title mb-0">Total Paid</h5>
                                </div>
                                <div class="row align-items-center mb-2 d-flex">
                                    <div class="col-8">
                                        <h2 style="color: white;" class="d-flex align-items-center mb-0">
                                            <?php
                                            echo number_format($total_test_sell_collection_today);
                                            ?>
                                        </h2>
                                    </div>

                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-4">
                        <div class="card l-bg-orange-dark">
                            <div class="card-statistic-3 p-4">
                                <div class="card-icon card-icon-large"><i class="fas fa-dollar-sign"></i></div>
                                <div class="mb-4">
                                    <h5 class="card-title mb-0">Total Due Collection</h5>
                                </div>
                                <div class="row align-items-center mb-2 d-flex">
                                    <div class="col-8">
                                        <h2 style="color: white;" class="d-flex align-items-center mb-0">
                                            <?php
                                            echo number_format($total_test_due_collection_today);
                                            ?>
                                        </h2>
                                    </div>

                                </div>

                            </div>
                        </div>
                    </div>

                </fieldset>
            <?php } ?>

            <?php if (in_array('home_lab_dashboard', $permissions)) { ?>
                <fieldset class="custom-fieldset" style="display: none;">
                    <legend>Lab</legend>
                    <div class="col-xl-3 col-lg-3">
                        <div class="card l-bg-cherry">
                            <div class="card-statistic-3 p-4">
                                <div class="card-icon card-icon-large"><i class="fas fa-shopping-cart"></i></div>
                                <div class="mb-4">
                                    <h5 class="card-title mb-0">Total </h5>
                                </div>
                                <div class="row align-items-center mb-2 d-flex">
                                    <div class="col-8">
                                        <h2 style="color: white;" class="d-flex align-items-center mb-0">
                                            <?php
                                            echo number_format($total_medicine_sale_amount_today);
                                            ?>
                                        </h2>
                                    </div>

                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-3">
                        <div class="card l-bg-blue-dark">
                            <div class="card-statistic-3 p-4">
                                <div class="card-icon card-icon-large"><i class="fas fa-users"></i></div>
                                <div class="mb-4">
                                    <h5 class="card-title mb-0">Total Purchase Amount</h5>
                                </div>
                                <div class="row align-items-center mb-2 d-flex">
                                    <div class="col-8">
                                        <h2 style="color: white;" class="d-flex align-items-center mb-0">
                                            <?php
                                            echo number_format($total_medicine_purchase_amount_today);
                                            ?>
                                        </h2>
                                    </div>

                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-3">
                        <div class="card l-bg-green-dark">
                            <div class="card-statistic-3 p-4">
                                <div class="card-icon card-icon-large"><i class="fas fa-ticket-alt"></i></div>
                                <div class="mb-4">
                                    <h5 class="card-title mb-0">Total Sale Due</h5>
                                </div>
                                <div class="row align-items-center mb-2 d-flex">
                                    <div class="col-8">
                                        <h2 style="color: white;" class="d-flex align-items-center mb-0">
                                            <?php
                                            echo number_format($total_medicine_due_amount_today);
                                            ?>
                                        </h2>
                                    </div>

                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-3">
                        <div class="card l-bg-orange-dark">
                            <div class="card-statistic-3 p-4">
                                <div class="card-icon card-icon-large"><i class="fas fa-dollar-sign"></i></div>
                                <div class="mb-4">
                                    <h5 class="card-title mb-0">Total Purchase Due</h5>
                                </div>
                                <div class="row align-items-center mb-2 d-flex">
                                    <div class="col-8">
                                        <h2 style="color: white;" class="d-flex align-items-center mb-0">
                                            <?php
                                            echo number_format($total_medicine_purchase_due_amount_today);
                                            ?>
                                        </h2>
                                    </div>

                                </div>

                            </div>
                        </div>
                    </div>

                </fieldset>
            <?php } ?>

            <?php if (in_array('home_pharmacy_dashboard', $permissions)) { ?>
                <fieldset class="custom-fieldset">
                    <legend>Pharmacy (Today</legend>
                    <div class="col-xl-3 col-lg-3">
                        <div class="card l-bg-cherry">
                            <div class="card-statistic-3 p-4">
                                <div class="card-icon card-icon-large"><i class="fas fa-shopping-cart"></i></div>
                                <div class="mb-4">
                                    <h5 class="card-title mb-0">Total Sale</h5>
                                </div>
                                <div class="row align-items-center mb-2 d-flex">
                                    <div class="col-8">
                                        <h2 style="color: white;" class="d-flex align-items-center mb-0">
                                            <?php
                                            echo number_format($total_medicine_sale_amount_today);
                                            ?>
                                        </h2>
                                    </div>

                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-3">
                        <div class="card l-bg-blue-dark">
                            <div class="card-statistic-3 p-4">
                                <div class="card-icon card-icon-large"><i class="fas fa-users"></i></div>
                                <div class="mb-4">
                                    <h5 class="card-title mb-0">Total Purchase Amount</h5>
                                </div>
                                <div class="row align-items-center mb-2 d-flex">
                                    <div class="col-8">
                                        <h2 style="color: white;" class="d-flex align-items-center mb-0">
                                            <?php
                                            echo number_format($total_medicine_purchase_amount_today);
                                            ?>
                                        </h2>
                                    </div>

                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-3">
                        <div class="card l-bg-green-dark">
                            <div class="card-statistic-3 p-4">
                                <div class="card-icon card-icon-large"><i class="fas fa-ticket-alt"></i></div>
                                <div class="mb-4">
                                    <h5 class="card-title mb-0">Total Sale Due</h5>
                                </div>
                                <div class="row align-items-center mb-2 d-flex">
                                    <div class="col-8">
                                        <h2 style="color: white;" class="d-flex align-items-center mb-0">
                                            <?php
                                            echo number_format($total_medicine_due_amount_today);
                                            ?>
                                        </h2>
                                    </div>

                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-3">
                        <div class="card l-bg-orange-dark">
                            <div class="card-statistic-3 p-4">
                                <div class="card-icon card-icon-large"><i class="fas fa-dollar-sign"></i></div>
                                <div class="mb-4">
                                    <h5 class="card-title mb-0">Total Purchase Due</h5>
                                </div>
                                <div class="row align-items-center mb-2 d-flex">
                                    <div class="col-8">
                                        <h2 style="color: white;" class="d-flex align-items-center mb-0">
                                            <?php
                                            echo number_format($total_medicine_purchase_due_amount_today);
                                            ?>
                                        </h2>
                                    </div>

                                </div>

                            </div>
                        </div>
                    </div>
                </fieldset>
            <?php } ?>

            <?php if (in_array('home_canteen_dashboard', $permissions)) { ?>
                <fieldset style="display: none;" class="custom-fieldset">
                    <legend>Canteen</legend>
                    <div class="col-xl-3 col-lg-3">
                        <div class="card l-bg-cherry">
                            <div class="card-statistic-3 p-4">
                                <div class="card-icon card-icon-large"><i class="fas fa-shopping-cart"></i></div>
                                <div class="mb-4">
                                    <h5 class="card-title mb-0">Total </h5>
                                </div>
                                <div class="row align-items-center mb-2 d-flex">
                                    <div class="col-8">
                                        <h2 style="color: white;" class="d-flex align-items-center mb-0">
                                            <?php
                                            echo number_format($total_medicine_sale_amount_today);
                                            ?>
                                        </h2>
                                    </div>

                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-3">
                        <div class="card l-bg-blue-dark">
                            <div class="card-statistic-3 p-4">
                                <div class="card-icon card-icon-large"><i class="fas fa-users"></i></div>
                                <div class="mb-4">
                                    <h5 class="card-title mb-0">Total Purchase Amount</h5>
                                </div>
                                <div class="row align-items-center mb-2 d-flex">
                                    <div class="col-8">
                                        <h2 style="color: white;" class="d-flex align-items-center mb-0">
                                            <?php
                                            echo number_format($total_medicine_purchase_amount_today);
                                            ?>
                                        </h2>
                                    </div>

                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-3">
                        <div class="card l-bg-green-dark">
                            <div class="card-statistic-3 p-4">
                                <div class="card-icon card-icon-large"><i class="fas fa-ticket-alt"></i></div>
                                <div class="mb-4">
                                    <h5 class="card-title mb-0">Total Sale Due</h5>
                                </div>
                                <div class="row align-items-center mb-2 d-flex">
                                    <div class="col-8">
                                        <h2 style="color: white;" class="d-flex align-items-center mb-0">
                                            <?php
                                            echo number_format($total_medicine_due_amount_today);
                                            ?>
                                        </h2>
                                    </div>

                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-3">
                        <div class="card l-bg-orange-dark">
                            <div class="card-statistic-3 p-4">
                                <div class="card-icon card-icon-large"><i class="fas fa-dollar-sign"></i></div>
                                <div class="mb-4">
                                    <h5 class="card-title mb-0">Total Purchase Due</h5>
                                </div>
                                <div class="row align-items-center mb-2 d-flex">
                                    <div class="col-8">
                                        <h2 style="color: white;" class="d-flex align-items-center mb-0">
                                            <?php
                                            echo number_format($total_medicine_purchase_due_amount_today);
                                            ?>
                                        </h2>
                                    </div>

                                </div>

                            </div>
                        </div>
                    </div>

                </fieldset>
            <?php } ?>

            <?php if (in_array('home_account_dashboard', $permissions)) { ?>
                <fieldset class="custom-fieldset">
                    <legend>Account (Today)</legend>
                    <div class="col-xl-3 col-lg-3">
                        <div class="card l-bg-cherry">
                            <div class="card-statistic-3 p-4">
                                <div class="card-icon card-icon-large"><i class="fas fa-shopping-cart"></i></div>
                                <div class="mb-4">
                                    <h5 class="card-title mb-0">Total Debit </h5>
                                </div>
                                <div class="row align-items-center mb-2 d-flex">
                                    <div class="col-8">
                                        <h2 style="color: white;" class="d-flex align-items-center mb-0">
                                            <?php
                                            echo number_format($total_debit_amount_today);
                                            ?>
                                        </h2>
                                    </div>

                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-3">
                        <div class="card l-bg-blue-dark">
                            <div class="card-statistic-3 p-4">
                                <div class="card-icon card-icon-large"><i class="fas fa-users"></i></div>
                                <div class="mb-4">
                                    <h5 class="card-title mb-0">Total Credit</h5>
                                </div>
                                <div class="row align-items-center mb-2 d-flex">
                                    <div class="col-8">
                                        <h2 style="color: white;" class="d-flex align-items-center mb-0">
                                            <?php
                                            echo number_format($total_credit_amount_today);
                                            ?>
                                        </h2>
                                    </div>

                                </div>
                                <?php
                                $credit_plus_debit = (float)$total_credit_amount_today + (float)$total_debit_amount_today;
                                $credit_ratio = $credit_plus_debit > 0 ? round(((float)$total_credit_amount_today / $credit_plus_debit) * 100) : 0;
                                ?>
                                <div class="progress-soft"><span style="width: <?php echo $credit_ratio; ?>%"></span></div>
                                <div style="color:#fff;opacity:0.9;font-size:12px;margin-top:6px;">Credit share today: <?php echo $credit_ratio; ?>%</div>

                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-3">
                        <div class="card l-bg-green-dark" style="display: none;">
                            <div class="card-statistic-3 p-4">
                                <div class="card-icon card-icon-large"><i class="fas fa-ticket-alt"></i></div>
                                <div class="mb-4">
                                    <h5 class="card-title mb-0">Total Sale Due</h5>
                                </div>
                                <div class="row align-items-center mb-2 d-flex">
                                    <div class="col-8">
                                        <h2 style="color: white;" class="d-flex align-items-center mb-0">
                                            <?php
                                            echo number_format($total_medicine_due_amount_today);
                                            ?>
                                        </h2>
                                    </div>

                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-3">
                        <div class="card l-bg-orange-dark" style="display: none;">
                            <div class="card-statistic-3 p-4">
                                <div class="card-icon card-icon-large"><i class="fas fa-dollar-sign"></i></div>
                                <div class="mb-4">
                                    <h5 class="card-title mb-0">Total Purchase Due</h5>
                                </div>
                                <div class="row align-items-center mb-2 d-flex">
                                    <div class="col-8">
                                        <h2 style="color: white;" class="d-flex align-items-center mb-0">
                                            <?php
                                            echo number_format($total_medicine_purchase_due_amount_today);
                                            ?>
                                        </h2>
                                    </div>

                                </div>

                            </div>
                        </div>
                    </div>

                </fieldset>
            <?php } ?>

            <!-- MTD & YTD Overview -->
            <fieldset class="custom-fieldset">
                <legend>Overview (MTD / YTD)</legend>
                <div class="col-xl-3 col-lg-3">
                    <div class="card l-bg-green-dark">
                        <div class="card-statistic-3 p-4">
                            <div class="card-icon card-icon-large"><i class="fas fa-calendar-alt"></i></div>
                            <div class="mb-2"><h5 class="card-title mb-0">MTD Revenue</h5></div>
                            <div><h2 style="color: white;" class="d-flex align-items-center mb-0"><?php echo number_format($mtd_revenue); ?></h2></div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-3">
                    <div class="card l-bg-orange-dark">
                        <div class="card-statistic-3 p-4">
                            <div class="card-icon card-icon-large"><i class="fas fa-wallet"></i></div>
                            <div class="mb-2"><h5 class="card-title mb-0">MTD Net Cashflow</h5></div>
                            <div><h2 style="color: white;" class="d-flex align-items-center mb-0"><?php echo number_format($mtd_net_cashflow); ?></h2></div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-3">
                    <div class="card l-bg-cyan">
                        <div class="card-statistic-3 p-4">
                            <div class="card-icon card-icon-large"><i class="fas fa-calendar"></i></div>
                            <div class="mb-2"><h5 class="card-title mb-0">YTD Revenue</h5></div>
                            <div><h2 style="color: white;" class="d-flex align-items-center mb-0"><?php echo number_format($ytd_revenue); ?></h2></div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-3">
                    <div class="card l-bg-blue-dark">
                        <div class="card-statistic-3 p-4">
                            <div class="card-icon card-icon-large"><i class="fas fa-balance-scale-right"></i></div>
                            <div class="mb-2"><h5 class="card-title mb-0">YTD Net Cashflow</h5></div>
                            <div><h2 style="color: white;" class="d-flex align-items-center mb-0"><?php echo number_format($ytd_net_cashflow); ?></h2></div>
                        </div>
                    </div>
                </div>
            </fieldset>


            <!-- Insights & Charts (Today) -->
            <fieldset class="custom-fieldset">
                <legend>Insights & Charts (Today)</legend>
                <div class="charts-grid">
                    <div class="card l-bg-cherry">
                        <div class="card-statistic-3 p-4">
                            <div class="card-icon card-icon-large"><i class="fas fa-chart-pie"></i></div>
                            <div class="mb-2"><h5 class="card-title mb-0">Revenue Composition</h5></div>
                            <canvas id="revenueDoughnut" height="160"></canvas>
                        </div>
                    </div>
                    <div class="card l-bg-green-dark">
                        <div class="card-statistic-3 p-4">
                            <div class="card-icon card-icon-large"><i class="fas fa-user-friends"></i></div>
                            <div class="mb-2"><h5 class="card-title mb-0">Patients by Type</h5></div>
                            <canvas id="patientsBar" height="160"></canvas>
                        </div>
                    </div>
                    <div class="card l-bg-blue-dark">
                        <div class="card-statistic-3 p-4">
                            <div class="card-icon card-icon-large"><i class="fas fa-balance-scale"></i></div>
                            <div class="mb-2"><h5 class="card-title mb-0">Credit vs Debit</h5></div>
                            <canvas id="accountBar" height="160"></canvas>
                        </div>
                    </div>
                </div>
            </fieldset>

            <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1"></script>
            <script>
                $(document).ready(function() {
                    $('[data-toggle="tooltip"]').tooltip();
                });
                // Chart.js setups using existing PHP data
                const revenueLabels = [
                    'OPD','IPD','Emergency','Physiotherapy','Test Sale','Test Direct','Test Due','Pharmacy'
                ];
                const revenueValues = [
                    <?php echo (float)$total_opd_patients_amount_today; ?>,
                    <?php echo (float)$total_ipd_patients_amount_today; ?>,
                    <?php echo (float)$total_emergency_amount_today; ?>,
                    <?php echo (float)$total_phygiotherapy_amount_today; ?>,
                    <?php echo (float)$total_patient_test_entry_net_total_today; ?>,
                    <?php echo (float)$total_test_sell_collection_today; ?>,
                    <?php echo (float)$total_test_due_collection_today; ?>,
                    <?php echo (float)$total_medicine_sale_amount_today; ?>
                ];
                // Professional, muted palette (blue/teal/slate)
                const palette = ['#2563eb','#0ea5a4','#64748b','#1d4ed8','#0f766e','#94a3b8','#60a5fa','#14b8a6'];
                const ctxRev = document.getElementById('revenueDoughnut');
                if (ctxRev) {
                    new Chart(ctxRev, {
                        type: 'doughnut',
                        data: { labels: revenueLabels, datasets: [{ data: revenueValues, backgroundColor: palette, borderWidth: 0 }] },
                        options: { plugins: { legend: { labels: { color: '#333333' } } }, cutout: '60%' }
                    });
                }

                const patientsLabels = ['OPD','IPD','Emergency','Physiotherapy'];
                const patientsValues = [
                    <?php echo (int)$total_opd_patients_today; ?>,
                    <?php echo (int)$total_ipd_patients_today; ?>,
                    <?php echo (int)$total_emergency_today; ?>,
                    <?php echo (int)$total_phygiotherapy_today; ?>
                ];
                const ctxPat = document.getElementById('patientsBar');
                if (ctxPat) {
                    new Chart(ctxPat, {
                        type: 'bar',
                        data: { labels: patientsLabels, datasets: [{
                            label: 'Count', data: patientsValues, backgroundColor: '#38ef7d'
                        }]},
                        options: { plugins: { legend: { display: false } }, scales: { x: { ticks: { color: '#333333' } }, y: { ticks: { color: '#333333' } } } }
                    });
                }

                const accountLabels = ['Credit','Debit'];
                const accountValues = [
                    <?php echo (float)$total_credit_amount_today; ?>,
                    <?php echo (float)$total_debit_amount_today; ?>
                ];
                const ctxAcc = document.getElementById('accountBar');
                if (ctxAcc) {
                    new Chart(ctxAcc, {
                        type: 'bar',
                        data: { labels: accountLabels, datasets: [{
                            label: 'Amount', data: accountValues, backgroundColor: ['#2563eb','#94a3b8']
                        }]},
                        options: { plugins: { legend: { display: false } }, scales: { x: { ticks: { color: '#333333' } }, y: { ticks: { color: '#333333' } } } }
                    });
                }
            </script>

            <!-- Additional CSS for bar styling -->
            <style>
                .bar-container {
                    height: 300px;
                    border-left: 2px solid #ccc;
                    border-bottom: 2px solid #ccc;
                    margin-top: 20px;
                    display: flex;
                    align-items: flex-end;
                    /* Align bars to the bottom */
                }

                .bar {
                    width: 40px;
                    background-color: #007bff;
                    text-align: center;
                    color: white;
                    border-radius: 5px 5px 0 0;
                    position: relative;
                    transition: all 0.3s;
                }

                .bar-wrapper {
                    display: flex;
                    flex-direction: column;
                    align-items: center;
                    margin: 0 10px;
                }



                .bar:hover {
                    background-color: #0056b3;
                }

                .bar-label {
                    position: absolute;
                    top: -25px;
                    left: 50%;
                    transform: translateX(-50%);
                    font-weight: bold;
                    color: #333;
                }

                .x-axis-label {
                    font-weight: bold;
                    font-size: 12px;
                    margin-top: 5px;
                }
            </style>





        </div>
    </div>
</div>
        
        <!-- Recent Activity: Last 5 Transactions -->
        <fieldset class="custom-fieldset">
            <legend>Recent Activity (Last 5)</legend>
            <?php
            // Prepare recent lists (normalize to date + amount)
            $recent_test = $this->db->select('date, net_total as amount')->order_by('patient_test_entry_id','DESC')->limit(5)->get('patient_test_entry')->result();
            $recent_emergency = $this->db->select('date, paid as amount')->order_by('emergency_id','DESC')->limit(5)->get('emergency')->result();
            $recent_opd = $this->db->select('entry_date as date, payable as amount')->order_by('opd_patient_id','DESC')->limit(5)->get('opd_patient')->result();
            $recent_physio = $this->db->select('date, paid as amount')->order_by('phygiotherapy_id','DESC')->limit(5)->get('phygiotherapy')->result();
            $recent_debit_vouchers = $this->db
                ->select('debit_voucher.date, debit_voucher.total_amount as amount, debit_account.account_name')
                ->from('debit_voucher')
                ->join('debit_account', 'debit_account.debit_account_id = debit_voucher.debit_account_id', 'left')
                ->order_by('debit_voucher_id','DESC')
                ->limit(5)
                ->get()
                ->result();
            ?>
            <div class="charts-grid">
                <div class="card-plain">
                    <div class="p-4">
                        <div class="section-title"><i class="fas fa-vial"></i> Test</div>
                        <table class="table-modern table-striped table-bordered table-hover">
                            <thead><tr><th>Date</th><th style="text-align:right">Amount</th></tr></thead>
                            <tbody>
                            <?php foreach ($recent_test as $row) { ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row->date); ?></td>
                                    <td style="text-align:right"><?php echo number_format((float)$row->amount); ?></td>
                                </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-plain">
                    <div class="p-4">
                        <div class="section-title"><i class="fas fa-ambulance"></i> Emergency</div>
                        <table class="table-modern table-striped table-bordered table-hover">
                            <thead><tr><th>Date</th><th style="text-align:right">Amount</th></tr></thead>
                            <tbody>
                            <?php foreach ($recent_emergency as $row) { ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row->date); ?></td>
                                    <td style="text-align:right"><?php echo number_format((float)$row->amount); ?></td>
                                </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-plain">
                    <div class="p-4">
                        <div class="section-title"><i class="fas fa-user-md"></i> OPD</div>
                        <table class="table-modern table-striped table-bordered table-hover">
                            <thead><tr><th>Date</th><th style="text-align:right">Amount</th></tr></thead>
                            <tbody>
                            <?php foreach ($recent_opd as $row) { ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row->date); ?></td>
                                    <td style="text-align:right"><?php echo number_format((float)$row->amount); ?></td>
                                </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-plain">
                    <div class="p-4">
                        <div class="section-title"><i class="fas fa-dumbbell"></i> Physiotherapy</div>
                        <table class="table-modern table-striped table-bordered table-hover">
                            <thead><tr><th>Date</th><th style="text-align:right">Amount</th></tr></thead>
                            <tbody>
                            <?php foreach ($recent_physio as $row) { ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row->date); ?></td>
                                    <td style="text-align:right"><?php echo number_format((float)$row->amount); ?></td>
                                </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-plain">
                    <div class="p-4">
                        <div class="section-title"><i class="fas fa-receipt"></i> Debit Vouchers</div>
                        <table class="table-modern table-striped table-bordered table-hover">
                            <thead><tr><th>Debit Account</th><th>Date</th><th style="text-align:right">Amount</th></tr></thead>
                            <tbody>
                            <?php foreach ($recent_debit_vouchers as $row) { ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row->account_name ?? ''); ?></td>
                                    <td><?php echo htmlspecialchars($row->date); ?></td>
                                    <td style="text-align:right"><?php echo number_format((float)$row->amount); ?></td>
                                </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </fieldset>