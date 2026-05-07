<script>
    $(document).ready(function () {
        $('.datepicker').datepicker({"changeMonth": true, "changeYear": true, "dateFormat": "dd-mm-yy", "yearRange": '1995:2030'});
    });
</script>
<div class="panel panel-primary">
    <div class="panel-heading">
        <h3 style="text-align: center">Add Expense All</h3>
    </div>
    <div class="panel-body">
        <?php
        if ($this->session->userdata('success') != '') {
            ?>
            <div class="alert alert-success">
                <strong>Success!</strong>Data has been saved successfully.
            </div>
            <?php
            $sdata['success'] = '';
            $this->session->set_userdata($sdata);
        }
        ?>
        <form class="form-horizontal" method="post" action="<?php echo site_url('ExpenseController/expense_all_save') ?>" enctype='multipart/form-data'>

            <table style="width: 80%;margin:0 auto" class="table table-bordered table-condensed table-responsive table-striped">
                <tr>
                    <td></td>
                    <td style="text-align: right">Date</td>

                    <td >
                        <input name="date" value="<?php echo date('d-m-Y') ?>" class="datepicker form-control" name="date" id="date datepicker" type="text">
                    </td>
                    <td></td>
                </tr>
                <tr>
                    <td>Sl</td>
                    
                    <td>Expense Head</td>
                    <td>Voucher No</td>
                    <td>Amount</td>
                </tr>
                <?php
                $sl = 1;
                $expense_head = $this->db->select('*')->get('expense_head')->result();
                foreach ($expense_head as $expense_head_value) {
                    ?>
                    <tr>
                        <td><?php echo $sl++ ?></td>
                  
                        <td><?php echo $expense_head_value->expense_head_name ?></td>
                            <td>
                            <input placeholder="Voucher No.." name="voucher_no[]"  class="form-control" type="text">

                        </td>
                        <td>
                            <input  name="expense_head_id[]" value="<?php echo $expense_head_value->expense_head_id ?>" class="form-control" type="hidden">
                            <input placeholder="Amount.." name="amount[]" class="form-control" type="text">
                        </td>
                    </tr>
                    <?php
                }
                ?>
                <tr>
                    <td></td>
                    <td>
                        <input type="submit" value="Submit" class="btn btn-primary">
                    </td>
                    <td></td>
                </tr>
            </table>
        </form>

    </div>
</div>
