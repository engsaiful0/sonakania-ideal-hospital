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
    $print_journal_voucher_id = $this->session->userdata('print_journal_voucher_id');
    $journal_voucher = $this->db->where('journal_voucher_id', $print_journal_voucher_id)
        ->get('journal_vouchers')
        ->row();
    $credit_account = getCreditAccount($journal_voucher->credit_account_id);
    $debit_account = getDebitAccount($journal_voucher->debit_account_id);

    $user = getUserById($journal_voucher->user_id);
    $compnay = $this->db->where('company_id', '1')->get('company')->row();
    ?>

    <div class="customer-copy" style="margin-top: 50px; ">

        <div class="" style="width: 100%;margin-bottom: 10px;">
            <div style="width: 15%;float: left;margin-top:20px">
                <img style="width:90%;padding-left: 30px;" src="<?php echo base_url() ?>assets/images/<?php echo $compnay->logo ?>">
            </div>
            <div style="width: 70%;float: left;text-align: center">
                <p style="text-align: center"><span style="text-align: center;font-size: 20px;text-align: center "> <?php echo $compnay->company_name ?></span><br><?php echo $compnay->address ?></span>
                    <span style="text-align: center">
                        Email: <?php echo $compnay->email ?>,Web:<?php echo $compnay->web ?>
                    </span>
                </p>
            </div>
            <div style="width: 15%;float: left;margin-top:20px">
                <img src="<?php echo base_url('JournalVoucherController/set_barcode/' . $journal_voucher->journal_voucher_no); ?>" alt="Barcode">
            </div>
        </div>
        <div class="name" style="width: 100%;margin-bottom: 10px;">
            <table border="0" class="table table-bordered table-hover" style="width: 100%;border-collapse:collapse;margin:0 auto;color:black;">
                <tr>
                    <td colspan="2" style="text-align:center;font-weight:bold""><u><b>Journal Voucher</b></u></td>
                </tr>
                <tr>

                  <td><b>Date: <?php echo date('d-m-Y', strtotime($journal_voucher->date)) ?></b>
                  </td>
                  <td>
                    <b>Voucher No: <?php echo $journal_voucher->journal_voucher_no ?></b>
                  </td>
              </tr>
            </table>
              <table border="1" class="table table-bordered table-hover" style="width: 100%;border-collapse:collapse;margin:0 auto;color:black;margin-top:10px;">
                <tr>
                    <td>NO.</td>
                    <td>DESCRIPTION</td>
                    <td>Debit Account</td>
                    <td>Credit Account</td>
                    <td>AMOUNT</td>
              </tr>
                <tr>
                  <td>1</td>

                  <td>
                      <b><?php echo $journal_voucher->description ?></b>
                  </td>

                  <td>
                      <b><?php echo $debit_account->account_name . '-' . $debit_account->account_number ?></b>
                  </td>

                  <td>
                      <b><?php echo $credit_account->account_name . '-' . $credit_account->account_number ?></b>
                  </td>

                    <td>
                        <b> <?php echo number_format($journal_voucher->total_amount) ?></b>
                    </td>

                </tr>


              </table>
            <table border="0" class="table table-bordered table-hover" style="width: 100%;border-collapse:collapse;margin:0 auto;color:black;margin-top:10px;">
                <tr>
                    <td colspan=" 4">
                        <b>Amount In word:</b> <?php echo convertNumberToWord($journal_voucher->total_amount) ?> Taka Only
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
        <div style="margin-top: 100px; ">
          <div style="width: 30%;float:right">
              <p style="text-align: right;">Entry By: <?php echo  $user->name ?? "" ?></p>
          </div>
        </div>
    </div>


</div>
