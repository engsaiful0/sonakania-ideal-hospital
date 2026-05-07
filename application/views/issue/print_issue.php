<style>
    @media print {
        body * {
            visibility: hidden;
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

<div class="row">
    <div class="col-md-12">
        <button onclick="window.print()" id="sumbit_button" class="btn btn-primary">Print</button>
    </div>

</div>
<div id="report" style="width: 90%;margin:0 auto;margin-left:45px;;margin-top:50px;">

    <?php
    error_reporting(0);
    $issue_id = $this->session->userdata('print_issue_id');
    $issue = $this->db->where('issue_id', $issue_id)
        ->get('issue')
        ->row();
    $employee = $this->db->where('employee_id', $issue->employee_id)
        ->get('employee')
        ->row();
    $issue_details = $this->db
        ->where('issue_id', $issue->issue_id)
        ->get('issue_details')
        ->result();
    $user = getUserById($issue->user_id);
    $department = $this->db->where('department_id', $issue->department_id)->get('department')->row();
  
    $compnay = $this->db->where('company_id', '1')->get('company')->row();
    ?>

    <div class="customer-copy" style="margin-top: 50px; ">

        <div class="" style="width: 100%;margin-bottom: 10px;">
            <div style="width: 15%;float: left;">
                <img style="width:90%;padding-left: 30px;" src="<?php echo base_url() ?>assets/images/<?php echo $compnay->logo ?>">
            </div>

            <div style="width: 70%;float: left;margin-bottom: 10px;text-align: center">

                <p style="text-align: center"><span style="text-align: center;font-size: 25px;text-align: center "> <?php echo $compnay->company_name ?><br><?php echo $compnay->address ?></span><br>
                    <span style="text-align: center">
                        Email: <?php echo $compnay->email ?>,Web:<?php echo $compnay->web ?>
                    </span>
                </p>
            </div>
            <div style="width: 15%;float: left;">
                <img src="<?php echo base_url('IssueController/set_barcode/' . $issue->issue_no); ?>" alt="Barcode">
            </div>
        </div>
        <div class="name" style="width: 100%;margin-bottom: 10px;">
            <table border="1" style="width: 100%;border-collapse:collapse;margin:0 auto;color:black;">
                <tr>
                    <td>Concern By</td>

                    <td>
                        <b><?php echo $employee->employee_name ?></b> </b>
                    </td>
                    <td>Date</td>
                    <td>
                        <b> <?php echo date('d-m-Y H:i:s', strtotime($issue->created_at)) ?></b>
                    </td>
                    <td>Issue No</td>
                    <td><b> <?php echo $issue->issue_no ?></b></td>
                </tr>
                <tr>
                    <td>Purpose</td>
                    <td>
                        <b><?php echo $issue->purpose ?></b>
                    </td>
                    <td>
                        Department
                    </td>

                    <td>
                        <b><?php echo $department->department_name ?></b>

                    </td>
                </tr>

            </table>
        </div>
        <div class="product" style="height: 300px; ">
            <table border="1" style="width: 100%;border-collapse:collapse;margin:0 auto;color:black">
                <tr>
                    <td>Sl</td>
                    <td>Item Name</td>
                    <td>Issue Quantity</td>
                </tr>
                <?php
                $sl = 1;
                foreach ($issue_details as $issue_detail) {
                    $issue_details_id  = $issue_detail->issue_details_id;
                    $item = $this->db
                        ->where('item_id', $issue_detail->item_id)
                        ->get('item')
                        ->row();
                ?>
                    <tr>
                        <td><?php echo $sl++ ?></td>
                        <td><?php echo $item->item_name ?></td>
                        <td style="text-align:left"><?php echo $issue_detail->issue_quantity ?></td>
                    </tr>
                <?php
                }
                ?>
                <tr>
                    <td colspan="2" style="text-align:right">Total Quantity</td>
                    <td style="text-align:left"><?php echo $issue->total_quantity ?></td>
                </tr>


            </table>
            <div style="margin-top: 100px; ">
                <div style="width: 50%;float:left">
                    <p style="text-align: left; ">___________<br>Receiver</p>
                </div>
                <div style="width: 50%;float:left">
                    <p style="text-align: right;">___________<br>Issuer</p>
                </div>
            </div>
            <div style="margin-top: 100px; ">
                <div style="width: 30%;float:right">
                    <p style="text-align: right;">Entry By: <?php echo  $user->name ?? "" ?></p>
                </div>
            </div>
        </div>
    </div>


</div>