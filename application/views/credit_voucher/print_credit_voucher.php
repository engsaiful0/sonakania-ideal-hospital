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
    $print_credit_voucher_id = $this->session->userdata('print_credit_voucher_id');
    $credit_voucher = $this->db->where('credit_voucher_id', $print_credit_voucher_id)
        ->get('credit_voucher')
        ->row();
    $credit_account = getCreditAccount($credit_voucher->credit_account_id);
    $bank = getBankName($credit_voucher->bank_name_id);
    $bank_account = getBankAccountNumber($credit_voucher->bank_account_id);

    $user = getUserById($credit_voucher->user_id);
    $compnay = $this->db->where('company_id', '1')->get('company')->row();
    ?>

    <div class="customer-copy" style="margin-top: 50px; ">

        <div class="" style="width: 100%;margin-bottom: 10px;">
            <div style="width: 15%;float: left;margin-top:20px">
                <img style="width:90%;padding-left: 30px;" src="<?php echo base_url() ?>assets/images/<?php echo $compnay->logo ?>">
            </div>
            <div style="width: 70%;float: left;text-align: center">
                <p style="text-align: center"><span style="text-align: center;font-size: 20px;text-align: center "> <?php echo $compnay->company_name ?></span><br><?php echo $compnay->address ?></span><br>
                    <span style="text-align: center">
                        Email: <?php echo $compnay->email ?>,Web:<?php echo $compnay->web ?>
                    </span>
                </p>
            </div>
            <div style="width: 15%;float: left;margin-top:20px">
                <img src="<?php echo base_url('CreditVoucherController/set_barcode/' . $credit_voucher->credit_voucher_no); ?>" alt="Barcode">
            </div>
        </div>
        <div class="name" style="width: 100%;margin-bottom: 10px;">
            <table border="0" class="table table-bordered table-hover" style="width: 100%;border-collapse:collapse;margin:0 auto;color:black;">
                <tr>
                    <td colspan="4" style="text-align:center;font-weight:bold;font-size:20px;"><b><u>Credit Voucher</u></b></td>
                </tr>
                <tr>
                    <td>
                        <b>Received From: <?php echo $credit_voucher->received_from ?></b>
                    </td>
                    <td>
                        <b>Date :<?php echo date('d-m-Y', strtotime($credit_voucher->date)) ?></b>
                    </td>

                </tr>
                <tr>
                    <td>
                        <b>Purpose: <?php echo $credit_account->account_name ?></b>
                    </td>
                    <td>
                        <b>Voucher No: <?php echo $credit_voucher->credit_voucher_no ?></b>
                    </td>
                </tr>
            </table>
            <table border="1" class="table table-bordered table-hover" style="width: 100%;border-collapse:collapse;margin:0 auto;color:black;margin-top:10px;">
                <tr>
                    <td>NO.</td>
                    <td>DESCRIPTION</td>
                    <td>AMOUNT</td>
                </tr>
                <tr>
                    <td>1</td>
                    <td><?php echo $credit_voucher->purpose ?></td>
                    <td><?php echo number_format($credit_voucher->total_amount) ?></td>
                </tr>
                <tr>

                    <td colspan="2" style="text-align:right"><b>TOTAL</b></td>
                    <td><?php echo number_format($credit_voucher->total_amount) ?></td>
                </tr>
            </table>
            <table border="0" class="table table-bordered table-hover" style="width: 100%;border-collapse:collapse;margin:0 auto;color:black;margin-top:10px;">
                <tr>
                    <td colspan=" 4">
                        <b>Amount In word:<?php echo convertNumberToWord($credit_voucher->total_amount) ?> Taka only</b>
                    </td>
                </tr>
            </table>
        </div>
        <div style="margin-top: 100px; ">
            <div style="width: 20%;float:left">
                <p style="text-align: left; ">___________<br>Accounts</p>
            </div>
            <div style="width: 20%;float:left">
                <p style="text-align: left; ">___________<br>Manager</p>
            </div>
            <div style="width: 20%;float:left">
                <p style="text-align: left; ">________________<br>Finance Director</p>
            </div>
            <div style="width: 20%;float:left">
                <p style="text-align: left; ">________________<br>Managing Director</p>
            </div>
            <div style="width: 20%;float:left">
                <p style="text-align: right;">___________<br>Approved By</p>
            </div>

        </div>
        <div id="entry_by">
            <div style="width: 50%;float:left">
                <p style="text-align: left;">Software By:<span style="font-weight:bold"> Bijoylab, www.bijoylab.com</span></p>
            </div>
            <div style="width: 30%;float:right">
                <p style="text-align: right;font-weight: bold;">Entry By: <?php echo  $user->name ?? "" ?></p>
            </div>
        </div>
    </div>


</div>