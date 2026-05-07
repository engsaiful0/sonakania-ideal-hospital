<?php
$permissions = $this->session->userdata('permissions');
?>
<div class="panel panel-primary">
    <div class="panel-heading">
        <h3 style="text-align: center">View Salary </h3>
    </div>

    <div class="panel-body" style="width: 100%;">
        <script>
            $(document).ready(function() {
                $("#month_id").select2();
                $('#year_id').select2();
            });



            function deleteSalary(salary_id, row_id) {
                Swal.fire({
                    title: 'Do you want to delete this?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "<?php echo site_url('SalaryController/salary_delete_ajax'); ?>",
                            type: 'POST',
                            data: {
                                salary_id: salary_id
                            },
                            success: function(response) {
                                var res = JSON.parse(response);
                                if (res.status == 'success') {
                                    $.toast({
                                        heading: 'Success',
                                        text: res.message,
                                        showHideTransition: 'slide',
                                        position: 'top-right',
                                        hideAfter: 1000,
                                        icon: 'success'
                                    });

                                    $('#' + row_id).remove();
                                } else {
                                    $.toast({
                                        heading: 'Error',
                                        text: res.message,
                                        showHideTransition: 'slide',
                                        position: 'top-right',
                                        hideAfter: 2000,
                                        icon: 'error'
                                    });
                                }
                            }
                        });
                    }
                });
            }
        </script>

        <?php if (in_array('hrm_employee_salary_view', $permissions)) { ?>
            <form method="post" action="<?php echo base_url() . "index.php/SalaryController/view_salary"; ?>">
                <table class="table table-hover table-bordered table-condensed">
                    <tr>
                        <td>Year</td>
                        <td>Month</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td style="width: 200px;">
                            <select  name="year_id" id="year_id" class="form-control">
                                <option value="">Year</option>
                                <?php
                                $sql = $this->db->select('*')->order_by('name', 'ASC')->get('year')->result();

                                foreach ($sql as $value) {
                                ?>
                                    <option value="<?php echo $value->year_id ?>"><?php echo $value->name ?></option>
                                <?php
                                }
                                ?>
                            </select>
                        </td>
                        <td style="width: 200px;">
                            <select  name="month_id" id="month_id" class="form-control">
                                <option value="">Month</option>
                                <?php
                                $sql = $this->db->select('*')->order_by('name', 'ASC')->get('month')->result();

                                foreach ($sql as $value) {
                                ?>
                                    <option value="<?php echo $value->month_id ?>"><?php echo $value->name ?></option>
                                <?php
                                }
                                ?>
                            </select>
                        </td>

                        <td style="width: 100px;"><input type="submit" class="btn btn-primary" value="Search"></td>
                    </tr>

                </table>
            </form>
        <?php } ?>
        <?php if (isset($detailsList) && is_array($detailsList) && !empty($detailsList)) : ?>
            <table style="width: 100%;" class="table table-hover table-bordered table-condensed">
                <tr>
                    <td>#</td>
                    <td>Month</td>
                    <td>Year</td>

                    <td>Total Salary</td>

                    <td>Date</td>
                    <?php
                    $user_type_name = $this->session->userdata('user_type');
                    if ($user_type_name == 'Admin') {
                    ?>
                        <td>User</td>
                    <?php
                    }
                    ?>
                    <?php if (in_array('hrm_employee_salary_print', $permissions)) { ?>
                        <td>Print</td>
                    <?php } ?>
                    <?php if (in_array('hrm_employee_salary_edit', $permissions)) { ?>
                        <td>Edit</td>
                    <?php } ?>
                    <?php if (in_array('hrm_employee_salary_delete', $permissions)) { ?>
                        <td>Delete</td>
                    <?php } ?>

                </tr>
                <?php
                error_reporting(0);
                $sl = 1;
                for ($i = 0; $i < count($detailsList); ++$i) {
                    $salary_id = $detailsList[$i]->salary_id;
                    $year = getYear($detailsList[$i]->year_id);
                    $month = getMonth($detailsList[$i]->month_id);
                    $user = getUserById($detailsList[$i]->user_id);
                ?>
                    <tr id="salary-row-<?php echo $salary_id; ?>">
                        <td><?php echo $sl++ ?></td>
                        <td>
                            <?= $month->name ?>
                        </td>
                        <td>
                            <?= $year->name ?>
                        </td>
                       
                          <td>
                            <?= $detailsList[$i]->net_payable ?>
                        </td>
                        <td><?php echo date('d-m-Y', strtotime($detailsList[$i]->date)) ?></td>
                        <td><?php echo $user->user_name ?? "" ?> </td>

                        <?php if (in_array('hrm_employee_salary_print', $permissions)) { ?>
                            <td><a class="btn btn-danger" href="<?php echo base_url("print-salary-again/$salary_id") ?>"><i class="glyphicon glyphicon-print" aria-hidden="true"></i>
                                </a></td>
                        <?php } ?>
                        <?php if (in_array('hrm_employee_salary_edit', $permissions)) { ?>
                            <td>
                                <a  class="btn btn-primary" href="<?php echo base_url() ?>salary-edit/<?php echo $detailsList[$i]->salary_id ?>"><i class="glyphicon glyphicon-edit" aria-hidden="true"></i></a>
                            </td>
                        <?php } ?>
                        <?php if (in_array('hrm_employee_salary_delete', $permissions)) { ?>
                            <td><a onclick="deleteSalary(<?php echo $salary_id; ?>, 'salary-row-<?php echo $salary_id; ?>')" class="btn btn-success"><i class="glyphicon glyphicon-trash" aria-hidden="true"></i></a></td>
                        <?php } ?>
                    </tr>
                <?php
                }
                ?>

            </table>

            <div style="width:70%;margin:0 auto;text-align:center">
                <p><?php echo $pagination; ?></p>
            </div>
        <?php else : ?>
            <p style="text-align:center">No records found.</p>
        <?php endif; ?>
    </div>
</div>