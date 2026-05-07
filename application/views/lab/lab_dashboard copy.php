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
    <?php if (in_array('lab_dashboard', $permissions)) { ?>

        <div class="panel panel-primary" style="width: 100%; margin: 0 auto;">
            <div class="panel-heading">
                <h3 style="text-align: center;">Lab Dashboard</h3>
            </div>
            <div class="panel-body">

                <fieldset>
                    <legend>
                        Test Result
                    </legend>
                    <?php
                    error_reporting(0);
                    // Get today's date in the 'Y-m-d' format
                    $today = date('Y-m-d'); // Get today's date
                    $this_week_start = date('Y-m-d', strtotime('monday this week')); // Get the start of the week
                    $this_week_end = date('Y-m-d', strtotime('sunday this week')); // Get the end of the week
                    $this_month_start = date('Y-m-01'); // Get the start of the month
                    $this_month_end = date('Y-m-t'); // Get the end of the month
                    $this_year_start = date('Y-01-01'); // Get the start of the year
                    $this_year_end = date('Y-12-31'); // Get the end of the year

                    // Count the total test results for today
                    $total_test_result_today = $this->db->where('date', $today)
                        ->count_all_results('test_result');

                    // Count the total test results for this week
                    $total_test_result_this_week = $this->db->where('date >=', $this_week_start)
                        ->where('date <=', $this_week_end)
                        ->count_all_results('test_result');

                    // Count the total test results for this month
                    $total_test_result_this_month = $this->db->where('date >=', $this_month_start)
                        ->where('date <=', $this_month_end)
                        ->count_all_results('test_result');

                    // Count the total test results for this year
                    $total_test_result_this_year = $this->db->where('date >=', $this_year_start)
                        ->where('date <=', $this_year_end)
                        ->count_all_results('test_result');


                    ?>
                    <div class="col-xl-3 col-lg-3">
                        <div class="card l-bg-cherry">
                            <div class="card-statistic-3 p-4">
                                <div class="card-icon card-icon-large"><i class="fas fa-shopping-cart"></i></div>
                                <div class="mb-4">
                                    <h4 class="card-title mb-0">Today</h4>
                                    <h5 class="card-title mb-0">Total result</h5>
                                </div>
                                <div class="row align-items-center mb-2 d-flex">
                                    <div class="col-8">
                                        <h2 style="color: white;" class="d-flex align-items-center mb-0">
                                            <?php
                                            echo number_format($total_test_result_today);
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
                                    <h4 class="card-title mb-0">This week</h4>
                                    <h5 class="card-title mb-0">Total result</h5>
                                </div>
                                <div class="row align-items-center mb-2 d-flex">
                                    <div class="col-8">
                                        <h2 style="color: white;" class="d-flex align-items-center mb-0">
                                            <?php
                                            echo number_format($total_test_result_this_week);
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
                                    <h4 class="card-title mb-0">This month</h4>
                                    <h5 class="card-title mb-0">Total result</h5>
                                </div>
                                <div class="row align-items-center mb-2 d-flex">
                                    <div class="col-8">
                                        <h2 style="color: white;" class="d-flex align-items-center mb-0">
                                            <?php
                                            echo number_format($total_test_result_this_month);
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
                                    <h4 class="card-title mb-0">This year</h4>
                                    <h5 class="card-title mb-0">Total result</h5>
                                </div>
                                <div class="row align-items-center mb-2 d-flex">
                                    <div class="col-8">
                                        <h2 style="color: white;" class="d-flex align-items-center mb-0">
                                            <?php
                                            echo number_format($total_test_result_this_year);
                                            ?>
                                        </h2>
                                    </div>

                                </div>

                            </div>
                        </div>
                    </div>

                </fieldset>

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