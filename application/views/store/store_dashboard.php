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
    <?php if (in_array('account_dashboard', $permissions)) { ?>
        <div class="panel panel-primary" style="width: 100%; margin: 0 auto;">
            <div class="panel-heading">
                <h3 style="text-align: center;">Store Dashboard</h3>
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

                    // Fetch the sum of the amount from the debit_voucher table for the current account for today
                    $total_purchase_quantity = $this->db->select_sum('quantity')
                        // ->where('date', $today) // Filter for today's date
                        ->get('purchase_goods_details')
                        ->row()
                        ->quantity;

                    $total_issue_quantity = $this->db->select_sum('issue_quantity')
                        // ->where('date', $today) // Filter for today's date
                        ->get('issue_details')
                        ->row()
                        ->issue_quantity;



                    ?>
                    <div class="col-xl-4 col-lg-4">
                        <div class="card l-bg-cherry">
                            <div class="card-statistic-3 p-4">
                                <div class="card-icon card-icon-large"><i class="fas fa-shopping-cart"></i></div>
                                <div class="mb-4">
                                    <h5 class="card-title mb-0">Total Purchase Quantity</h5>
                                </div>
                                <div class="row align-items-center mb-2 d-flex">
                                    <div class="col-8">
                                        <h2 class="d-flex align-items-center mb-0">
                                            <?php
                                            echo number_format($total_purchase_quantity);
                                            ?>
                                        </h2>
                                    </div>

                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-4">
                        <div class="card l-bg-blue-dark">
                            <div class="card-statistic-3 p-4">
                                <div class="card-icon card-icon-large"><i class="fas fa-users"></i></div>
                                <div class="mb-4">
                                    <h5 class="card-title mb-0">Total Issue Quantity</h5>
                                </div>
                                <div class="row align-items-center mb-2 d-flex">
                                    <div class="col-8">
                                        <h2 class="d-flex align-items-center mb-0">
                                            <?php
                                            echo number_format($total_issue_quantity);
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
                                    <h5 class="card-title mb-0">Total Stock Quantity</h5>
                                </div>
                                <div class="row align-items-center mb-2 d-flex">
                                    <div class="col-8">
                                        <h2 class="d-flex align-items-center mb-0">
                                            <?php
                                            echo number_format($$total_purchase_quantity-$total_issue_quantity);
                                            ?>
                                        </h2>
                                    </div>

                                </div>

                            </div>
                        </div>
                    </div>


                </fieldset>




            </div>
        </div>
    <?php } ?>
</div>
