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

    .l-bg-cherry {
        background: linear-gradient(to right, #493240, #f09) !important;
        color: #fff;
    }

    .l-bg-blue-dark {
        background: linear-gradient(to right, #373b44, #4286f4) !important;
        color: #fff;
    }

    .l-bg-green-dark {
        background: linear-gradient(to right, #0a504a, #38ef7d) !important;
        color: #fff;
    }

    .l-bg-orange-dark {
        background: linear-gradient(to right, #a86008, #ffba56) !important;
        color: #fff;
    }

    .l-bg-blue-dark {
        background: linear-gradient(to right, #0a3d62, #3c8dbc) !important;
        color: #fff;
    }

    .l-bg-red-dark {
        background: linear-gradient(to right, #7f0000, #ff4b5c) !important;
        color: #fff;
    }


    .card .card-statistic-3 .card-icon {
        text-align: center;
        line-height: 50px;
        margin-left: 15px;
        color: #000;
        position: absolute;
        right: -5px;
        top: 20px;
        opacity: 0.1;
    }

    .l-bg-cyan {
        background: linear-gradient(135deg, #289cf5, #84c0ec) !important;
        color: #fff;
    }

    .l-bg-green {
        background: linear-gradient(135deg, #23bdb8 0%, #43e794 100%) !important;
        color: #fff;
    }

    .l-bg-orange {
        background: linear-gradient(to right, #f9900e, #ffba56) !important;
        color: #fff;
    }

    .l-bg-cyan {
        background: linear-gradient(135deg, #289cf5, #84c0ec) !important;
        color: #fff;
    }
</style>

<div class="container-fluid" style="background-color: white; width: 100%;">
    <?php $permissions = $this->session->userdata('permissions'); ?>
    <?php if (in_array('patient_dashboard', $permissions)) { ?>


        <div class="panel panel-primary" style="width: 100%; margin: 0 auto;">
            <div class="panel-heading">
                <h3 style="text-align: center;">Patient Dashboard</h3>
            </div>
            <div class="panel-body">

                <fieldset>
                    <legend>
                        Today
                    </legend>
                    <?php
                    error_reporting(0);
                    // Get today's date in the 'Y-m-d' format
                    $today = date('Y-m-d');


                    // Fetch the sum of the amount from the opd_patient table for the current account for today
                    $total_opd_patient_today = $this->db->select('COUNT(*) as total')
                        ->where('entry_date', $today)
                        ->get('opd_patient')
                        ->row()
                        ->total; // Access the 'total' property directly here


                    $total_ipd_patient_today = $this->db->select('COUNT(*) as total')
                        ->where('date', $today) // Filter for today's date
                        ->get('ipd_patient')
                        ->row()
                        ->total;

                    $total_emergency_today =  $this->db->select('COUNT(*) as total')
                        ->where('date', $today) // Filter for today's date
                        ->get('emergency')
                        ->row()
                        ->total;

                    $total_phygiotherapy_today =  $this->db->select('COUNT(*) as total')
                        ->where('date', $today) // Filter for today's date
                        ->get('phygiotherapy')
                        ->row()
                        ->total;

                    $total_ot_services_today =  $this->db->select('COUNT(*) as total')
                        ->where('date', $today) // Filter for today's date
                        ->get('ot_services')
                        ->row()
                        ->total;
                    $total_discharge_date_today =  $this->db->select('COUNT(*) as total')
                        ->where('discharge_date', $today) // Filter for today's date
                        ->get('discharge')
                        ->row()
                        ->total;

                    ?>
                    <div class="col-xl-2 col-lg-2">
                        <div class="card l-bg-cherry">
                            <div class="card-statistic-3 p-4">
                                <div class="card-icon card-icon-large"><i class="fas fa-shopping-cart"></i></div>
                                <div class="mb-4">
                                    <h5 class="card-title mb-0">No. of OPD</h5>
                                </div>
                                <div class="row align-items-center mb-2 d-flex">
                                    <div class="col-8">
                                        <h2 style="color: white;" class="d-flex align-items-center mb-0">
                                            <?php
                                            echo number_format($total_opd_patient_today);
                                            ?>
                                        </h2>
                                    </div>

                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="col-xl-2 col-lg-2">
                        <div class="card l-bg-blue-dark">
                            <div class="card-statistic-3 p-4">
                                <div class="card-icon card-icon-large"><i class="fas fa-users"></i></div>
                                <div class="mb-4">
                                    <h5 class="card-title mb-0">No. of IPD</h5>
                                </div>
                                <div class="row align-items-center mb-2 d-flex">
                                    <div class="col-8">
                                        <h2 style="color: white;" class="d-flex align-items-center mb-0">
                                            <?php
                                            echo number_format($total_ipd_patient_today);
                                            ?>
                                        </h2>
                                    </div>

                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="col-xl-2 col-lg-2">
                        <div class="card l-bg-green-dark">
                            <div class="card-statistic-3 p-4">
                                <div class="card-icon card-icon-large"><i class="fas fa-ticket-alt"></i></div>
                                <div class="mb-4">
                                    <h5 class="card-title mb-0">No. of Emergency</h5>
                                </div>
                                <div class="row align-items-center mb-2 d-flex">
                                    <div class="col-8">
                                        <h2 style="color: white;" class="d-flex align-items-center mb-0">
                                            <?php
                                            echo number_format($total_emergency_today);
                                            ?>
                                        </h2>
                                    </div>

                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="col-xl-2 col-lg-2">
                        <div class="card l-bg-orange-dark">
                            <div class="card-statistic-3 p-4">
                                <div class="card-icon card-icon-large"><i class="fas fa-dollar-sign"></i></div>
                                <div class="mb-4">
                                    <h5 class="card-title mb-0">No. of Phygiotherapy</h5>
                                </div>
                                <div class="row align-items-center mb-2 d-flex">
                                    <div class="col-8">
                                        <h2 style="color: white;" class="d-flex align-items-center mb-0">
                                            <?php
                                            echo number_format($total_phygiotherapy_today);
                                            ?>
                                        </h2>
                                    </div>

                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="col-xl-2 col-lg-2">
                        <div class="card l-bg-blue-dark">
                            <div class="card-statistic-3 p-4">
                                <div class="card-icon card-icon-large"><i class="fas fa-dollar-sign"></i></div>
                                <div class="mb-4">
                                    <h5 class="card-title mb-0">No. of OT</h5>
                                </div>
                                <div class="row align-items-center mb-2 d-flex">
                                    <div class="col-8">
                                        <h2 style="color: white;" class="d-flex align-items-center mb-0">
                                            <?php
                                            echo number_format($total_ot_services_today);
                                            ?>
                                        </h2>
                                    </div>

                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="col-xl-2 col-lg-2">
                        <div class="card l-bg-red-dark">
                            <div class="card-statistic-3 p-4">
                                <div class="card-icon card-icon-large"><i class="fas fa-dollar-sign"></i></div>
                                <div class="mb-4">
                                    <h5 class="card-title mb-0">No. of Discharge</h5>
                                </div>
                                <div class="row align-items-center mb-2 d-flex">
                                    <div class="col-8">
                                        <h2 style="color: white;" class="d-flex align-items-center mb-0">
                                            <?php
                                            echo number_format($total_discharge_date_today);
                                            ?>
                                        </h2>
                                    </div>

                                </div>

                            </div>
                        </div>
                    </div>
                </fieldset>
                <fieldset>
                    <legend>
                        This Month
                    </legend>
                    <?php
                    error_reporting(0);
                    // Get the first and last days of the current month in 'Y-m-d' format
                    $first_day_of_month = date('Y-m-01'); // First day of the current month
                    $last_day_of_month = date('Y-m-t');   // Last day of the current month


                    // Fetch the sum of the amount from the opd_patient table for the current account for today
                    $total_opd_patient_month = $this->db->select('COUNT(*) as total')
                        ->where('entry_date >=', $first_day_of_month)
                        ->where('entry_date <=', $last_day_of_month)
                        ->get('opd_patient')
                        ->row()
                        ->total; // Access the 'total' property directly here


                    $total_ipd_patient_month = $this->db->select('COUNT(*) as total')
                        ->where('date >=', $first_day_of_month)
                        ->where('date <=', $last_day_of_month)
                        ->get('ipd_patient')
                        ->row()
                        ->total;

                    $total_emergency_month =  $this->db->select('COUNT(*) as total')
                        ->where('date >=', $first_day_of_month)
                        ->where('date <=', $last_day_of_month)
                        ->get('emergency')
                        ->row()
                        ->total;

                    $total_phygiotherapy_month =  $this->db->select('COUNT(*) as total')
                        ->where('date >=', $first_day_of_month)
                        ->where('date <=', $last_day_of_month)
                        ->get('phygiotherapy')
                        ->row()
                        ->total;

                    $total_ot_services_month =  $this->db->select('COUNT(*) as total')
                        ->where('date >=', $first_day_of_month)
                        ->where('date <=', $last_day_of_month)
                        ->get('ot_services')
                        ->row()
                        ->total;
                    $total_discharge_date_month =  $this->db->select('COUNT(*) as total')
                        ->where('discharge_date >=', $first_day_of_month)
                        ->where('discharge_date <=', $last_day_of_month)
                        ->get('discharge')
                        ->row()
                        ->total;
                    ?>

                    <div class="col-xl-2 col-lg-2">
                        <div class="card l-bg-cherry">
                            <div class="card-statistic-3 p-4">
                                <div class="card-icon card-icon-large"><i class="fas fa-shopping-cart"></i></div>
                                <div class="mb-4">
                                    <h5 class="card-title mb-0">No. of OPD</h5>
                                </div>
                                <div class="row align-items-center mb-2 d-flex">
                                    <div class="col-8">
                                        <h2 style="color: white;" class="d-flex align-items-center mb-0">
                                            <?php
                                            echo number_format($total_opd_patient_month);
                                            ?>
                                        </h2>
                                    </div>

                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="col-xl-2 col-lg-2">
                        <div class="card l-bg-blue-dark">
                            <div class="card-statistic-3 p-4">
                                <div class="card-icon card-icon-large"><i class="fas fa-users"></i></div>
                                <div class="mb-4">
                                    <h5 class="card-title mb-0">No. of IPD</h5>
                                </div>
                                <div class="row align-items-center mb-2 d-flex">
                                    <div class="col-8">
                                        <h2 style="color: white;" class="d-flex align-items-center mb-0">
                                            <?php
                                            echo number_format($total_ipd_patient_month);
                                            ?>
                                        </h2>
                                    </div>

                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="col-xl-2 col-lg-2">
                        <div class="card l-bg-green-dark">
                            <div class="card-statistic-3 p-4">
                                <div class="card-icon card-icon-large"><i class="fas fa-ticket-alt"></i></div>
                                <div class="mb-4">
                                    <h5 class="card-title mb-0">No. of Emergency</h5>
                                </div>
                                <div class="row align-items-center mb-2 d-flex">
                                    <div class="col-8">
                                        <h2 style="color: white;" class="d-flex align-items-center mb-0">
                                            <?php
                                            echo number_format($total_emergency_month);
                                            ?>
                                        </h2>
                                    </div>

                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="col-xl-2 col-lg-2">
                        <div class="card l-bg-orange-dark">
                            <div class="card-statistic-3 p-4">
                                <div class="card-icon card-icon-large"><i class="fas fa-dollar-sign"></i></div>
                                <div class="mb-4">
                                    <h5 class="card-title mb-0">No. of Phygiotherapy</h5>
                                </div>
                                <div class="row align-items-center mb-2 d-flex">
                                    <div class="col-8">
                                        <h2 style="color: white;" class="d-flex align-items-center mb-0">
                                            <?php
                                            echo number_format($total_phygiotherapy_month);
                                            ?>
                                        </h2>
                                    </div>

                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="col-xl-2 col-lg-2">
                        <div class="card l-bg-blue-dark">
                            <div class="card-statistic-3 p-4">
                                <div class="card-icon card-icon-large"><i class="fas fa-dollar-sign"></i></div>
                                <div class="mb-4">
                                    <h5 class="card-title mb-0">No. of OT</h5>
                                </div>
                                <div class="row align-items-center mb-2 d-flex">
                                    <div class="col-8">
                                        <h2 style="color: white;" class="d-flex align-items-center mb-0">
                                            <?php
                                            echo number_format($total_ot_services_today);
                                            ?>
                                        </h2>
                                    </div>

                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="col-xl-2 col-lg-2">
                        <div class="card l-bg-red-dark">
                            <div class="card-statistic-3 p-4">
                                <div class="card-icon card-icon-large"><i class="fas fa-dollar-sign"></i></div>
                                <div class="mb-4">
                                    <h5 class="card-title mb-0">No. of Discharge</h5>
                                </div>
                                <div class="row align-items-center mb-2 d-flex">
                                    <div class="col-8">
                                        <h2 style="color: white;" class="d-flex align-items-center mb-0">
                                            <?php
                                            echo number_format($total_discharge_date_month);
                                            ?>
                                        </h2>
                                    </div>

                                </div>

                            </div>
                        </div>
                    </div>
                </fieldset>
                <fieldset>
                    <legend>
                        This Year
                    </legend>
                    <?php
                    error_reporting(0);

                    // Get the first and last days of the current year in 'Y-m-d' format
                    $first_day_of_year = date('Y-01-01'); // First day of the current year
                    $last_day_of_year = date('Y-12-31');  // Last day of the current year

                    // Fetch the sum of the amount from the medicine_sales table for the current year
                    $total_opd_patient_year = $this->db->select('COUNT(*) as total')
                        ->where('entry_date >=', $first_day_of_year)
                        ->where('entry_date <=', $last_day_of_year)
                        ->get('opd_patient')
                        ->row()
                        ->total;

                    // Fetch the sum of the amount from the medicine_purchase table for the current year
                    $total_ipd_patient_year =  $this->db->select('COUNT(*) as total')
                        ->where('date >=', $first_day_of_year)
                        ->where('date <=', $last_day_of_year)
                        ->get('ipd_patient')
                        ->row()
                        ->total;

                    // Fetch the sum of the due amount from the medicine_sales table for the current year
                    $total_emergency_year =  $this->db->select('COUNT(*) as total')
                        ->where('date >=', $first_day_of_year)
                        ->where('date <=', $last_day_of_year)
                        ->get('emergency')
                        ->row()
                        ->total;

                    // Fetch the sum of the due amount from the medicine_purchase table for the current year
                    $total_phygiotherapy_year = $this->db->select('COUNT(*) as total')
                        ->where('date >=', $first_day_of_year)
                        ->where('date <=', $last_day_of_year)
                        ->get('phygiotherapy')
                        ->row()
                        ->total;

                    $total_ot_services_year =  $this->db->select('COUNT(*) as total')
                        ->where('date >=', $first_day_of_year)
                        ->where('date <=', $last_day_of_year)
                        ->get('ot_services')
                        ->row()
                        ->total;

                    $total_discharge_date_year =  $this->db->select('COUNT(*) as total')
                        ->where('discharge_date >=', $first_day_of_year)
                        ->where('discharge_date <=', $last_day_of_year)
                        ->get('discharge')
                        ->row()
                        ->total;
                    ?>

                    <div class="col-xl-2 col-lg-2">
                        <div class="card l-bg-cherry">
                            <div class="card-statistic-3 p-4">
                                <div class="card-icon card-icon-large"><i class="fas fa-shopping-cart"></i></div>
                                <div class="mb-4">
                                    <h5 class="card-title mb-0">No. of OPD</h5>
                                </div>
                                <div class="row align-items-center mb-2 d-flex">
                                    <div class="col-8">
                                        <h2 style="color: white;" class="d-flex align-items-center mb-0">
                                            <?php
                                            echo number_format($total_opd_patient_year);
                                            ?>
                                        </h2>
                                    </div>

                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="col-xl-2 col-lg-2">
                        <div class="card l-bg-blue-dark">
                            <div class="card-statistic-3 p-4">
                                <div class="card-icon card-icon-large"><i class="fas fa-users"></i></div>
                                <div class="mb-4">
                                    <h5 class="card-title mb-0">No. of IPD</h5>
                                </div>
                                <div class="row align-items-center mb-2 d-flex">
                                    <div class="col-8">
                                        <h2 style="color: white;" class="d-flex align-items-center mb-0">
                                            <?php
                                            echo number_format($total_ipd_patient_year);
                                            ?>
                                        </h2>
                                    </div>

                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="col-xl-2 col-lg-2">
                        <div class="card l-bg-green-dark">
                            <div class="card-statistic-3 p-4">
                                <div class="card-icon card-icon-large"><i class="fas fa-ticket-alt"></i></div>
                                <div class="mb-4">
                                    <h5 class="card-title mb-0">No. of Emergency</h5>
                                </div>
                                <div class="row align-items-center mb-2 d-flex">
                                    <div class="col-8">
                                        <h2 style="color: white;" class="d-flex align-items-center mb-0">
                                            <?php
                                            echo number_format($total_emergency_year);
                                            ?>
                                        </h2>
                                    </div>

                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="col-xl-2 col-lg-2">
                        <div class="card l-bg-orange-dark">
                            <div class="card-statistic-3 p-4">
                                <div class="card-icon card-icon-large"><i class="fas fa-dollar-sign"></i></div>
                                <div class="mb-4">
                                    <h5 class="card-title mb-0">No. of Phygiotherapy</h5>
                                </div>
                                <div class="row align-items-center mb-2 d-flex">
                                    <div class="col-8">
                                        <h2 style="color: white;" class="d-flex align-items-center mb-0">
                                            <?php
                                            echo number_format($total_phygiotherapy_year);
                                            ?>
                                        </h2>
                                    </div>

                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="col-xl-2 col-lg-2">
                        <div class="card l-bg-blue-dark">
                            <div class="card-statistic-3 p-4">
                                <div class="card-icon card-icon-large"><i class="fas fa-dollar-sign"></i></div>
                                <div class="mb-4">
                                    <h5 class="card-title mb-0">No. of OT</h5>
                                </div>
                                <div class="row align-items-center mb-2 d-flex">
                                    <div class="col-8">
                                        <h2 style="color: white;" class="d-flex align-items-center mb-0">
                                            <?php
                                            echo number_format($total_ot_services_year);
                                            ?>
                                        </h2>
                                    </div>

                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="col-xl-2 col-lg-2">
                        <div class="card l-bg-red-dark">
                            <div class="card-statistic-3 p-4">
                                <div class="card-icon card-icon-large"><i class="fas fa-dollar-sign"></i></div>
                                <div class="mb-4">
                                    <h5 class="card-title mb-0">No. of Discharge</h5>
                                </div>
                                <div class="row align-items-center mb-2 d-flex">
                                    <div class="col-8">
                                        <h2 style="color: white;" class="d-flex align-items-center mb-0">
                                            <?php
                                            echo number_format($total_discharge_date_year);
                                            ?>
                                        </h2>
                                    </div>

                                </div>

                            </div>
                        </div>
                    </div>


                </fieldset>
                <div class="container" style="display:none">
                    <h2 class="text-center mt-4">Bar Chart of All Costs(Debit) for This Month</h2>
                    <div class="bar-container d-flex align-items-end justify-content-around">
                        <?php
                        // Fetch all debit accounts ordered by account_name
                        $debit_accounts = $this->db->select('*')->order_by('account_name')->get('debit_account')->result();

                        foreach ($debit_accounts as $debit_account) {
                            // Get the first and last day of the current month
                            $first_day_of_month = date('Y-m-01');
                            $last_day_of_month = date('Y-m-t');

                            // Fetch the sum of amount from the debit_voucher table for the current account within the current month
                            $total_amount = $this->db->select_sum('total_amount')
                                ->where('debit_account_id', $debit_account->debit_account_id)
                                ->where('date >=', $first_day_of_month)
                                ->where('date <=', $last_day_of_month)
                                ->get('debit_voucher')
                                ->row()
                                ->total_amount;

                            if ($total_amount == 0) {
                                continue;
                            }

                            $max_amount = 100000; // Adjust this to match your data range
                            $bar_height = ($total_amount / $max_amount) * 100;
                            $bar_height = min($bar_height, 100);
                        ?>
                            <div data-toggle="tooltip" title="<?= $debit_account->account_name . ': ' . $total_amount ?>" id="bar_<?php echo $debit_account->debit_account_id ?>" class="bar" style="height: <?= $bar_height ?>%;">
                                <span class="bar-label"><?= $total_amount ?></span>
                            </div>

                        <?php
                        }
                        ?>
                    </div>
                </div>
                <script>
                    $(document).ready(function() {
                        $('[data-toggle="tooltip"]').tooltip();
                    });
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
    <?php } ?>
</div>