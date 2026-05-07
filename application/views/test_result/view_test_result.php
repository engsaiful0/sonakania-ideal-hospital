<?php
$permissions = $this->session->userdata('permissions');
?>
<script>
    function test_name_load(test_group_id) {

        $('#img').show();
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (xhttp.readyState == 4 && xhttp.status == 200) {
                document.getElementById("test_id").innerHTML = xhttp.responseText;
                //                  var newdiv = document.createElement('tr');
                //                newdiv.innerHTML = xhttp.responseText;
                //                document.getElementById('due_history').appendChild(newdiv);
                $('#img').hide();
            }
        }
        //                    alert(xhttp.responseText);
        xhttp.open("POST", "<?php echo site_url('TestResultController/test_name_load'); ?>", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        //            xhttp.send("fname=Henry&lname=Ford");
        xhttp.send("test_group_id=" + test_group_id);
    }
    jQuery(document).ready(function() {
        jQuery('.alert-auto-hide').fadeTo(800, 200, function() {
            $(this).slideUp('slow', function() {
                $(this).remove();
            });
        });
    });
</script>
<script>
    $(document).ready(function() {
        // alert();
        $('#patient_test_entry_id').select2();
        $('#test_group_id').select2();
        $('#test_id').select2();
        $('#patient_unique_id').focus();
    });

    function deleteTestResult(test_result_id, row_id) {
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
                    url: "<?php echo site_url('TestResultController/test_result_delete_ajax'); ?>",
                    type: 'POST',
                    data: {
                        test_result_id: test_result_id
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
    $(document).ready(function() {

        $("#invoice_no").autocomplete({
            source: function(request, response) {
                $.ajax({
                    url: "<?php echo site_url('TestResultController/invoice_no_load'); ?>",
                    data: {
                        parameter: request.term
                    },
                    dataType: "json",
                    type: "POST",
                    success: function(data) {
                        response(data);
                    }
                });
            },
            select: function(event, ui) {
                $('#invoice_no').val(ui.item.label);
                $('form').submit();
                return false;
            }
        });

    });
</script>
<div class="panel panel-primary">
    <div class="panel-heading">
        <h3 style="text-align: center">View Test Result</h3>
    </div>
    <div class="panel-body">
        <?php if (in_array('search_test_result', $permissions)) { ?>
            <form method="post" action="<?php echo site_url('TestResultController/view_test_result') ?>">
                <table>
                    <tr>
                        <td>Invoice No</td>
                        <td>Test Group Name</td>
                        <td>Test Name</td>
                        <td></td>
                    </tr>
                    <tr>

                        <td>
                            <input focus class="form-control" placeholder="Enter Invoice No" id="invoice_no" name="invoice_no">
                        </td>

                        <td>
                            <select type="text" class="form-control" onchange="test_name_load(this.value)" id="test_group_id" name="test_group_id">
                                <option value="" disabled="" selected="">Select Test Group Name</option>

                                <?php
                                $test_group = $this->db->select('*')->order_by('test_group_name')->get('test_group')->result();
                                foreach ($test_group as $value) {
                                ?>
                                    <option value="<?php echo $value->test_group_id; ?>"><?php echo $value->test_group_name; ?></option>
                                <?php
                                }
                                ?>
                            </select>
                        </td>
                        <td>
                            <select type="text" class="form-control" id="test_id" name="test_id">
                                <option value="" disabled="" selected="">Select Test Name</option>



                            </select>
                        </td>
                        <td><input type="submit" class="btn btn-primary" value="Search"></td>
                    </tr>
                </table>
            </form>
        <?php } ?>
        <table class="table table-bordered table-hover table-condensed">
            <caption style="text-align: center"></caption>
            <tr>
                <td>Sl</td>
                <td>Patient Name</td>
                <td>Invoice No</td>
                <td>Test No</td>
                <td>Test Group Name</td>
                <td>Manual Report</td>
                <?php
                    $user_type_name = $this->session->userdata('user_type');
                    if ($user_type_name == 'Admin') {
                    ?>
                        <td>User</td>
                    <?php
                    }
                    ?>
                <?php if (in_array('print_test_result', $permissions)) { ?>
                    <td>Print</td>
                <?php } ?>
                <?php if (in_array('edit_test_result', $permissions)) { ?>
                    <td>Edit</td>
                <?php } ?>
                <?php if (in_array('delete_test_result', $permissions)) { ?>
                    <td>Delete</td>
                <?php } ?>

            </tr>
            <?php
            error_reporting(0);
            $sl = 1;

            for ($i = 0; $i < count($detailsList); ++$i) {
                $patient_test_entry = $this->db->where('patient_test_entry_id', $detailsList[$i]->patient_test_entry_id)
                    ->get('patient_test_entry')->row();

                $test_group = $this->db->where('test_group_id', $detailsList[$i]->test_group_id)
                    ->get('test_group')->row();

                $test = $this->db->where('test_id', $detailsList[$i]->test_id)
                    ->get('test')->row();
                $test_result_id = $detailsList[$i]->test_result_id;
                $user = getUserById($detailsList[$i]->user_id);
            ?>
                <tr id="test-result-row-<?php echo $test_result_id; ?>">
                    <td><?php echo $sl++ ?></td>
                    <td><?php echo $patient_test_entry->patient_name ?></td>
                    <td><?php echo $detailsList[$i]->invoice_no ?></td>
                    <td><?php echo $detailsList[$i]->test_result_no ?></td>
                    <td><?php echo $test_group->test_group_name ?></td>
                    <td>
                    <a target="_blank" href="<?php echo base_url() ?>assets/manual_report/<?php echo $detailsList[$i]->manual_report ?>" class="btn btn-primary">View</a>
                        
                    </td>
                    <td><?php echo $user->user_name ?? "" ?> </td>
                    <?php if (in_array('print_test_result', $permissions)) { ?>
                        <td><a class="btn btn-danger" href="<?php echo base_url("TestResultController/test_result_report_print_again/$test_result_id") ?>"><i class="glyphicon glyphicon-print" aria-hidden="true"></i>
                            </a></td>
                    <?php } ?>
                    <?php if (in_array('edit_test_result', $permissions)) { ?>
                        <td>
                            <a href="<?php echo site_url("TestResultController/test_result_edit/$test_result_id") ?>" id="biomedicaltest_id_<?php echo $detailsList[$i]->test_result_id ?>" class="btn btn-primary"><i class="glyphicon glyphicon-edit" aria-hidden="true"></i></a>
                        </td>
                    <?php } ?>
                    <?php if (in_array('delete_test_result', $permissions)) { ?>
                        <td><a onclick="deleteTestResult(<?php echo $test_result_id; ?>, 'test-result-row-<?php echo $test_result_id; ?>')" class="btn btn-success"><i class="glyphicon glyphicon-trash" aria-hidden="true"></i>
                            </a></td>
                    <?php } ?>
                </tr>
            <?php
            }
            ?>

        </table>
        <div style="width:70%;margin:0 auto;text-align:center">
            <p><?php echo $pagination; ?></p>
        </div>
    </div>

</div>