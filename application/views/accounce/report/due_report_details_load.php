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

        #report_block_header {
            background-color: yellow;
            color: black;
        }

        .container_user {
            display: none;
        }
    }
</style>

<div class="row">
    <div class="col-md-12">
        <button onclick="window.print()" class="btn btn-primary">Print</button>
    </div>
</div>
<div id="report" style="width: 90%;margin:0 auto;margin-left:45px;margin-top:40px;color:black;height: 20cm ">
    <?php
    $user_id = $this->session->userdata('user_id');
    $user_type_name = $this->session->userdata('user_type');
    $user = getUserById($this->session->userdata('user_id'));
    $total_grand_due_payment = 0;
    $from_date = date('Y-m-d', strtotime($from_date));
    $to_date = date('Y-m-d', strtotime($to_date));
  
    ?>

    <div style="clear: left;" class="panel panel-primary">
        <div id="report_container">
            <div class="panel-heading" style="font-weight: bold;text-align: center;">
                <?php
                $this->load->view('common/report_header');
                ?>
                <p style="clear:left;text-align: center">
                    Due Report: From Date: <b><?php echo date('Y-m-d', strtotime($from_date)); ?></b>
                    to Date: <b><?php echo date('Y-m-d', strtotime($to_date)); ?></b>
                </p>
            </div>
            <div class="panel-body" style="width: 100%;">
                <div class="row">
                    <div class="col-md-12">
                        <?php
                        $total_due_payment = 0;
                        $this->db->from('patient_test_entry');
                        $this->db->where('paid_or_due_status', 'due');
                        $this->db->where('date>=', $from_date)
                            ->where('date<=', $to_date);
                        $query_patient_test_entry_due = $this->db->get()->result();
                        if (!empty($query_patient_test_entry_due)) {
                        ?>
                            <table border="1" style="width: 100%;border-collapse:collapse;margin:0 auto;color:black;">
                                <thead>
                                    <tr>
                                        <td colspan="8" style="text-align: center;">Due in Test</td>
                                    </tr>
                                    <tr>
                                        <th style="width: 5%">SL</th>
                                        <th style="width: 10%">Patient Name</th>
                                        <th style="width: 10%">Invoice</th>
                                        <th style="width: 10%">Net Total</th>
                                        <th style="width: 10%">Paid Amount</th>
                                        <th style="width: 10%">Due Amount</th>
                                        <th style="width: 10%">Date</th>
                                        <th style="width: 10%">User</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $i = 1;
                                    foreach ($query_patient_test_entry_due as $value) {
                                        $user = getUserById($value->user_id);
                                    ?>
                                        <tr class="gradeX">
                                            <td><?php echo $i; ?></td>
                                            <td><?php echo $value->patient_name; ?></td>
                                            <td><?php echo $value->invoice_no; ?></td>
                                            <td><?php echo $value->net_total; ?></td>
                                            <td><?php echo $value->paid; ?></td>
                                            <td><?php echo $value->due; ?></td>
                                            <td><?php echo date('d-m-Y', strtotime($value->date)); ?></td>
                                            <td><?php echo $user->user_name; ?></td>
                                        </tr>
                                    <?php
                                        $total_due_payment += $value->due;
                                    }
                                    ?>
                                    <tr>
                                        <td colspan="5" style="text-align: right;">Total Due</td>
                                        <td><?php echo $total_due_payment;
                                            $total_grand_due_payment += $total_due_payment; ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        <?php
                        }
                        ?>
                        <?php
                        $total_due_payment = 0;
                        $this->db->from('emergency');
                        $this->db->where('paid_or_due_status', 'due');
                        $this->db->where('date>=', $from_date)
                            ->where('date<=', $to_date);
                        $query_emergency = $this->db->get()->result();
                        if (!empty($query_emergency)) {
                        ?>
                            <table border="1" style="width: 100%;border-collapse:collapse;margin:0 auto;color:black;">
                                <thead>
                                    <tr>
                                        <td colspan="7" style="text-align: center;">Due in Emergency</td>
                                    </tr>
                                    <tr>
                                        <th style="width: 5%">SL</th>
                                        <th style="width: 20%">Patient Name</th>
                                        <th style="width: 20%">Net Total</th>
                                        <th style="width: 20%">Paid Amount</th>
                                        <th style="width: 20%">Due Amount</th>
                                        <th style="width: 20%">Date</th>
                                        <th style="width: 20%">User</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $i = 1;
                                    foreach ($query_emergency as $value) {
                                        $user = getUserById($value->user_id);
                                    ?>
                                        <tr class="gradeX">
                                            <td><?php echo $i; ?></td>
                                            <td><?php echo $value->name; ?></td>
                                            <td><?php echo $value->nettotal; ?></td>
                                            <td><?php echo $value->paid; ?></td>
                                            <td><?php echo $value->due; ?></td>
                                            <td><?php echo date('d-m-Y', strtotime($value->date)); ?></td>
                                            <td><?php echo $user->user_name; ?></td>
                                        </tr>
                                    <?php
                                        $total_due_payment += $value->due;
                                    }
                                    ?>
                                    <tr>
                                        <td colspan="4" style="text-align: right;">Total Due</td>
                                        <td><?php echo $total_due_payment;
                                            $total_grand_due_payment += $total_due_payment; ?></td>
                                    </tr>
                                <?php
                            }
                                ?>
                                </tbody>
                            </table>
                            <?php
                            $total_due_payment = 0;
                            $this->db->from('phygiotherapy');
                            $this->db->where('paid_or_due_status', 'due');
                            $this->db->where('date>=', $from_date)
                                ->where('date<=', $to_date);
                            $query_phygiotherapy = $this->db->get()->result();
                            if (!empty($query_phygiotherapy)) {
                            ?>
                                <table border="1" style="width: 100%;border-collapse:collapse;margin:0 auto;color:black;">
                                    <thead>
                                        <tr>
                                            <td colspan="7" style="text-align: center;">Due in Physiotherapy</td>
                                        </tr>
                                        <tr>
                                            <th style="width: 5%">SL</th>
                                            <th style="width: 20%">Patient Name</th>
                                            <th style="width: 20%">Net Total</th>
                                            <th style="width: 20%">Paid Amount</th>
                                            <th style="width: 20%">Due Amount</th>
                                            <th style="width: 20%">Date</th>
                                            <th style="width: 20%">User</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $i = 1;
                                        foreach ($query_phygiotherapy as $value) {
                                            $user = getUserById($value->user_id);
                                        ?>
                                            <tr class="gradeX">
                                                <td><?php echo $i; ?></td>
                                                <td><?php echo $value->name; ?></td>
                                                <td><?php echo $value->nettotal; ?></td>
                                                <td><?php echo $value->paid; ?></td>
                                                <td><?php echo $value->due; ?></td>
                                                <td><?php echo date('d-m-Y', strtotime($value->date)); ?></td>
                                                <td><?php echo $user->user_name; ?></td>
                                            </tr>
                                        <?php
                                            $total_due_payment += $value->due;
                                        }
                                        ?>
                                        <tr>
                                            <td colspan="4" style="text-align: right;">Total Due</td>
                                            <td><?php echo number_format($total_due_payment);
                                                $total_grand_due_payment += $total_due_payment ?></td>
                                        </tr>
                                    <?php
                                }
                                    ?>
                                    </tbody>
                                </table>
                                <p style="text-align: center;"><b>Final Total Due: <?php echo number_format($total_grand_due_payment); ?></b></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>