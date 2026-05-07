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
    <?php if (in_array('hrm_dashboard', $permissions)) { ?>

        <div class="panel panel-primary" style="width: 100%; margin: 0 auto;">
            <div class="panel-heading">
                <h3 style="text-align: center;">HRM Dashboard</h3>
            </div>
            <div class="panel-body">
                <?php
                $total_directors = $this->db->count_all('director');
                $total_doctors = $this->db->count_all('doctor');
                $total_employees = $this->db->count_all('employee');

                $this->db->select('COUNT(*) as total_nurses');
                $this->db->from('employee');
                $this->db->join('department', 'department.department_id = employee.department_id');
                $this->db->where('department.department_name', 'Nursing');

                // Execute the query and fetch the result
                $query = $this->db->get();
                $result = $query->row();

                // Display the result
                $total_nurses = $result->total_nurses;


                ?>
                <div class="col-xl-3 col-lg-3">
                    <div class="card l-bg-cherry">
                        <div class="card-statistic-3 p-4">
                            <div class="card-icon card-icon-large"><i class="fas fa-shopping-cart"></i></div>
                            <div class="mb-4">
                                <h5 class="card-title mb-0">Total Director</h5>
                            </div>
                            <div class="row align-items-center mb-2 d-flex">
                                <div class="col-8">
                                    <h2 style="color: white;" class="d-flex align-items-center mb-0">
                                        <?php
                                        echo $total_directors;
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
                                <h5 class="card-title mb-0">Total Doctor</h5>
                            </div>
                            <div class="row align-items-center mb-2 d-flex">
                                <div class="col-8">
                                    <h2 style="color: white;" class="d-flex align-items-center mb-0">
                                        <?php
                                        echo $total_doctors;
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
                                <h5 class="card-title mb-0">Total Nurse</h5>
                            </div>
                            <div class="row align-items-center mb-2 d-flex">
                                <div class="col-8">
                                    <h2 style="color: white;" class="d-flex align-items-center mb-0">
                                        <?php
                                        echo $total_nurses;
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
                                <h5 class="card-title mb-0">Total Employee</h5>
                            </div>
                            <div class="row align-items-center mb-2 d-flex">
                                <div class="col-8">
                                    <h2 style="color: white;" class="d-flex align-items-center mb-0">
                                        <?php
                                        echo $total_employees;
                                        ?>
                                    </h2>
                                </div>

                            </div>

                        </div>
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