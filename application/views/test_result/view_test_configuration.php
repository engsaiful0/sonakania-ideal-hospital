<?php
$permissions = $this->session->userdata('permissions');
?><script>
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

    function deleteTestConfiguration(test_configuration_id, row_id) {
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
                    url: "<?php echo site_url('TestResultController/test_configuration_delete_ajax'); ?>",
                    type: 'POST',
                    data: {
                        test_configuration_id: test_configuration_id
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
<div class="panel panel-primary">
    <div class="panel-heading">
        <h3 style="text-align: center">View Test Configuration</h3>
    </div>
    <div class="panel-body">

        <img src="<?php echo base_url() ?>assets/ajax-loader.gif" id="img" style="display:none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); z-index: 9999;" />
        <?php if (in_array('search_test_configuration', $permissions)) { ?>
            <form method="post" action="<?php echo site_url('TestResultController/view_test_configuration') ?>">
                <table>
                    <tr>
                        <td>Test Group Name</td>

                        <td>Test Name</td>
                        <td>



                        </td>
                    </tr>
                    <tr>

                        <td style="width: 300px;">
                            <select type="text" class="form-control" onchange="test_name_load(this.value)" id="test_group_id" name="test_group_id">
                                <option value="" disabled="" selected="">Select Test Group Name</option>

                                <?php
                                $test_group = $this->db->select('*')->get('test_group')->result();
                                foreach ($test_group as $value) {
                                ?>
                                    <option value="<?php echo $value->test_group_id; ?>"><?php echo $value->test_group_name; ?></option>
                                <?php
                                }
                                ?>
                            </select>
                        </td>

                        <td style="width: 300px;">
                            <select type="text" required="" class="form-control" id="test_id" name="test_id">
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
                <td>Test Group Name</td>
                <td>Test Name</td>
                <!--<td>Parameter Name</td>-->
                <td>Unit</td>
                <td>Normal Range</td>
                <td>Absolute Value</td>
                <?php
                $user_type_name = $this->session->userdata('user_type');
                if ($user_type_name == 'Admin') {
                ?>
                    <td>User</td>
                <?php
                }
                ?>
                <?php if (in_array('edit_test_configuration', $permissions)) { ?>
                    <td>Edit</td>
                <?php } ?>
                <?php if (in_array('delete_test_configuration', $permissions)) { ?>
                    <td>Delete</td>
                <?php } ?>

            </tr>
            <?php
            error_reporting(0);
            $sl = 1;

            for ($i = 0; $i < count($detailsList); ++$i) {
                $test_group = $this->db->where('test_group_id', $detailsList[$i]->test_group_id)
                    ->get('test_group')->row();
                //echo '<pre>';
                //print_r($detailsList[$i]->test_id);
                //die;
                $test = $this->db->where('test_id', $detailsList[$i]->test_id)
                    ->get('test')->row();
                //echo '<pre>';
                //print_r($detailsList[$i]->test_id);
                //die;
                $test_configuration_id = $detailsList[$i]->test_configuration_id;
                $user = getUserById($detailsList[$i]->user_id);
            ?>
                <tr id="test-configuration-row-<?php echo $test_configuration_id; ?>">
                    <td><?php echo $sl++ ?></td>
                    <td><?php echo $test_group->test_group_name ?></td>
                    <td><?php echo $test->test_name ?></td>
                    <!--                    <td><?php
                                                //  echo $detailsList[$i]->test_parameter;
                                                ?></td>-->
                    <td><?php
                        echo $detailsList[$i]->unit;
                        ?></td>
                    <td><?php
                        echo $detailsList[$i]->normal_range;
                        ?></td>
                    <td><?php
                        echo $detailsList[$i]->absolute_value;
                        ?></td>

                    <td><?php echo $user->user_name ?? "" ?> </td>
                    <?php if (in_array('edit_test_configuration', $permissions)) { ?>
                        <td>
                            <a id="biomedicaltest_id_<?php echo $detailsList[$i]->test_configuration_id ?>" onclick="modalLoadEdit(this.id)" class="btn btn-primary" data-target="#globalModalEdit" data-toggle="modal" data-placement="top" data-content="update" href=""><i class="glyphicon glyphicon-edit" aria-hidden="true"></i></a>
                        </td>
                    <?php } ?>
                    <?php if (in_array('delete_test_configuration', $permissions)) { ?>
                        <td><a onclick="deleteTestConfiguration(<?php echo $test_configuration_id; ?>, 'test-configuration-row-<?php echo $test_configuration_id; ?>')" class="btn btn-danger"><i class="glyphicon glyphicon-trash" aria-hidden="true"></i>
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
<div class="modal" id="globalModalEdit" role="dialog" aria-labelledby="esModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" style="">

        <div class="modal-content">

            <div class="modal-body">
                <div class="loader">
                    <div class="es-spinner">
                        <i class="glyphicon glyphicon-spinner fa-pulse fa-5x fa-fw"></i>
                    </div>
                </div>
            </div>

        </div>

    </div>
</div>
<div class="modal" id="globalModalDetails" role="dialog" aria-labelledby="esModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" style="">

        <div class="modal-content">

            <div class="modal-body">
                <div class="loader">
                    <div class="es-spinner">
                        <i class="glyphicon glyphicon-spinner fa-pulse fa-5x fa-fw"></i>
                    </div>
                </div>
            </div>

        </div>

    </div>
</div>
<script>
    $(document).ready(function() {
        // alert();
        $('#test_group_id').select2();
        $('#test_id').select2();

    });

    function modalLoadEdit(rowId) {
        var data = rowId.split('_'); //To get the row id
        //alert(data[2]);
        $_token = "{{ csrf_token() }}";
        $.ajax({
            headers: {
                'X-CSRF-Token': $('meta[name=_token]').attr('content')
            },
            url: "<?php echo site_url('TestResultController/edit_test_configuration') ?>" + '/' + data[2],
            type: 'GET',
            cache: false,
            data: {
                '_token': $_token
            }, //see the $_token
            datatype: 'html',
            beforeSend: function() {},
            success: function(data) {

                // alert(data.length);
                //                    $('.modal-content').html(data);
                if (data.length > 0) {
                    // remove modal body
                    $('.modal-body').remove();
                    // add modal content
                    $('.modal-content').html(data);
                } else {
                    // add modal content
                    $('.modal-content').html('info');
                }
            }
        });
    }
</script>