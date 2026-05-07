<style>
    @media print {
        body * {
            visibility: hidden;
        }
        #sumbit_button
        {
            display: none;
        }
        #report,
        #report * {
            visibility: visible;
            overflow: visible;
        }

        #report {
            position: absolute;
            left: 0;
            top: 0;
        }

        .p1 {
            line-height: 80% !important;
        }
    }

    .p1 {
        line-height: 80% !important;
    }
</style>
<script type="text/javascript">
    function load_user_based_sell_report_for_user() {

        var from_date = document.getElementById('datepicker1').value;
        var to_date = document.getElementById('datepicker2').value;
        $('#loadingSpinner').show();
        $.ajax({
            url: "<?php echo base_url('ReportController/load_user_based_sell_report_for_user'); ?>",
            method: "POST",
            data: {
                from_date: from_date,
                to_date: to_date,
            },
            dataType: "json",
            success: function(response) {
                $('#loadingSpinner').hide();
                if (response.status === 'success') {
                    $('#report_container').html(response.data);
                } else if (response.status === 'error') {
                    $('#report_container').html(response.data);
                }
            },
            error: function(xhr, status, error) {

            }
        });
    }

   
</script>
<div class="row">
    <div class="col-md-12">
        <button onclick="window.print()" class="btn btn-primary">Print</button>
    </div>
</div>
<div id="report" style="width: 90%;margin:0 auto;margin-left:45px;margin-top:40px;color:black;height: 20cm ">

    <div class="container_user">
        <div class="row">
            <div class="col-md-12">
                <div class="panel-heading" style="font-weight: bold;text-align: center;">
                    <?php
                    $this->load->view('common/report_header');
                    ?>
                    <p style="clear:left;text-align: center">Department Wise Issue Report. Date:<b><?php echo date('d-m-Y') ?></b> <br>
                        <span style="text-align: center;font-weight:bold"></span>
                    </p>
                </div>
                <div class="form-group">
                    <?php
                    $this->db->select('department.department_name as department_name, item.item_name as item_name, SUM(issue_details.issue_quantity) as total_issued_quantity');
                    $this->db->from('issue_details');
                    $this->db->join('department', 'issue_details.department_id = department.department_id');
                    $this->db->join('item', 'issue_details.item_id = item.item_id');
                    $this->db->group_by(['issue_details.department_id', 'issue_details.item_id']);
                    $this->db->order_by('department.department_name');

                    $query = $this->db->get();
                    $department_wise_issue = $query->result();
                    ?>
                    <table border="1" style="width: 100%;border-collapse:collapse;margin:0 auto;color:black;margin-top:20px;">
                        <tr style="background-color: yellow;color: black;font-weight: bold;text-align: center;" id="report_block_header">
                            <td colspan="4" style="text-align:center;font-weight:bold;background-color:yellow;color:black">Department Issue Report</td>
                        </tr>
                        <tr>
                            <td style="width:10%">Sl</td>
                            <td style="width:30%">Department</td>
                            <td style="width:30%">Item</td>
                            <th style="width:30%">Total Issued Quantity</th>
                        </tr>
                        <?php
                        $k = 1;
                        $grand_issue_quantity = 0;

                        foreach ($department_wise_issue as $row): ?>
                            <tr>
                                <td><?= $k++ ?></td>
                                <td><?= $row->department_name ?></td>
                                <td><?= $row->item_name ?></td>
                                <td><?= $row->total_issued_quantity;
                                    $grand_issue_quantity += $row->total_issued_quantity; ?></td>
                            </tr>
                        <?php endforeach; ?>
                        <tr>
                            <td colspan="3" style="text-align:right">Total</td>
                            <td> <?php echo number_format($grand_issue_quantity) ?></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>