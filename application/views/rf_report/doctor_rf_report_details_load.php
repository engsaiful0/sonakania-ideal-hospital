<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <style>
        body {
            width: 100%;
            background: rgb(204, 204, 204);
            overflow-x: hidden;


        }

        page[size="A4"] {
            background: white;
            width: 21cm;
            height: 29cm;
            display: block;
            margin: 0 auto;
            margin-bottom: 0.5cm;
            box-shadow: 0 0 0.1cm rgba(0, 0, 0, 0.5);
            -o-box-shadow: 0 0 0.1cm rgba(0, 0, 0, 0.5);
            -webkit-box-shadow: 0 0 0.1cm rgba(0, 0, 0, 0.5);
            -moz-box-shadow: 0 0 0.1cm rgba(0, 0, 0, 0.5);

        }

        @media print {

            body,
            page[size="A4"] {
                margin: 0;
                box-shadow: 0;

            }
        }

        .first {
            width: 100%;
            height: 29cm;
            margin: auto;
            padding: 5px 0px 0px 0px;
        }

        .second {
            width: 728px;
            height: 600px;
            margin: auto;
        }

        .third {
            width: 607px;
            height: 820px;
            margin: auto;
        }

        h2 {
            font-size: 24px;
        }

        #footer {
            width: 400px;
            margin: auto;
            background-color: #FFF;
        }

        #footer a {
            text-decoration: none;
            text-align: center;

        }

        @page {
            size: A4;
            margin: 0;
        }

        @media print {
            .first {

                margin: 0;
                border: initial;
                border-radius: initial;
                width: initial;
                min-height: initial;
                box-shadow: initial;
                background: initial;
                page-break-after: always;
                -webkit-print-color-adjust: exact;

            }
        }

        @media print {
            #print {
                display: none;
            }

            .print {
                display: none;
            }
        }

        .upon {
            width: 70%;
            height: auto;
            margin: auto;

        }

        #site_header_logo {
            position: relative;
            width: 15%;
            height: 100px;
            float: left;
            margin-top: 10px;
            padding-left: 80px;
        }

        a {
            text-decoration: none;
            color: inherit;
        }

        a.tooltip {
            outline: none;
        }

        a.tooltip strong {
            line-height: 30px;
        }

        a.tooltip:hover {
            text-decoration: none;
        }

        a.tooltip span {
            z-index: 10;
            display: none;
            padding: 14px 20px;
            margin-top: 30px;
            margin-left: 2px;
            width: 300px;
            line-height: 16px;
        }

        a.tooltip:hover span {
            display: inline;
            position: absolute;
            color: #111;
            border: 1px solid #DCA;
            background: #fffAF0;

        }

        .callout {
            z-index: 20;
            position: absolute;
            top: 30px;
            border: 0;
            left: -12px;
        }

        /*CSS3 extras*/
        a.tooltip span {
            border-radius: 4px;
            box-shadow: 5px 5px 8px #CCC;
        }

        .boxright {
            width: 35%;
            height: 100px;
            float: left;
        }

        .fbox1 {
            width: 320px;
            float: left;
            margin-left: 39px;
            border-collapse: collapse;
            text-align: left;
        }

        .fbox1 td {
            height: 25px;
            line-height: 16px
        }

        @media print {
            .offs {
                display: none;
            }
        }

        tr:hover {
            background-color: #91b8f7
        }

        @font-face {
            font-family: 'NikoshBAN';
            src: url('../assets/fonts/NikoshBAN.ttf') format('truetype');

        }
    </style>
    <link rel="stylesheet" media="screen,projection" type="text/css" href="<?php echo base_url() ?>css/loader_extras.css" />
    <title>Doctor Test RF Report</title>
</head>

<body>
    <?php
    error_reporting(0);
    $from_date = date('Y-m-d', strtotime($from_date));
    $to_date = date('Y-m-d', strtotime($to_date));
    $from_date_display = date('d-m-Y', strtotime($from_date));
    $to_date_display = date('d-m-Y', strtotime($to_date));

    $doctors = $doctor_id != ''
        ? $this->db->where('doctor_id', $doctor_id)->get('doctor')->result()
        : $this->db->get('doctor')->result();

    foreach ($doctors as $doctor_value) {
        // Test Reference Report
        $this->db->select('
        tc.test_category_name,
        tc.commission_type,
        tc.commission_rate,
        COUNT(pted.patient_test_entry_details_id) AS entry_count,
        SUM(pted.paid_each) AS total_paid
    ');
        $this->db->from('patient_test_entry_details pted');
        $this->db->join('doctor d', 'pted.reference_doctor_id = d.doctor_id', 'left');
        $this->db->join('test_categories tc', 'pted.test_category_id = tc.test_category_id', 'left');
        $this->db->where('pted.reference_doctor_id', $doctor_value->doctor_id);
        $this->db->where('pted.date >=', $from_date);
        $this->db->where('pted.date <=', $to_date);
        $this->db->group_by(['pted.reference_doctor_id', 'pted.test_category_id']);
        $results = $this->db->get()->result();

        // Emergency Service Report
        $this->db->select('
        es.name AS emergency_service_name,
        es.commission_type,
        es.commission_rate,
        COUNT(ed.emergency_details_id) AS entry_count,
        SUM(ed.amount) AS total_paid
    ');
        $this->db->from('emergency_details ed');
        $this->db->join('emergency_service es', 'ed.emergency_service_id = es.emergency_service_id', 'left');
        $this->db->where('ed.reference_doctor_id', $doctor_value->doctor_id);
        $this->db->where('es.commissionable', 'yes');
        $this->db->where('ed.date >=', $from_date);
        $this->db->where('ed.date <=', $to_date);
        $this->db->group_by(['ed.reference_doctor_id', 'ed.emergency_service_id']);
        $emergency_results = $this->db->get()->result();

        if (!empty($results)) {
            $sl = 1;
            $grand_total_paid = 0;
            $grand_total_commission = 0;
            $grand_total_entries = 0;
    ?>
            <table border="1" style="width: 98%; border-collapse: collapse; margin: 30px auto 0; color: black;">
                <tr style="background-color: #337AB7; color: white;">
                    <td colspan="5" style="text-align: center">Doctor Test Reference Report</td>
                </tr>
                <tr style="background-color: #337AB7; color: white;">
                    <td colspan="5" style="text-align: center">
                        Doctor Name: <b><?php echo htmlspecialchars($doctor_value->doctor_name); ?></b><br>
                        From: <b><?php echo $from_date_display; ?></b>
                        To: <b><?php echo $to_date_display; ?></b>
                    </td>
                </tr>
                <tr style="background-color: #f1f1f1; font-weight: bold;">
                    <td>Sl</td>
                    <td>Test Category</td>
                    <td>Total Test Entries</td>
                    <td>Total Paid</td>
                    <td>Total Commission</td>
                </tr>
                <?php foreach ($results as $row):
                    $commission = 0;
                    if ($row->commission_type === 'percentage') {
                        $commission = ($row->total_paid * $row->commission_rate) / 100;
                    } elseif ($row->commission_type === 'fixed') {
                        $commission = $row->entry_count * $row->commission_rate;
                    }

                    $grand_total_paid += $row->total_paid;
                    $grand_total_commission += $commission;
                    $grand_total_entries += $row->entry_count;
                ?>
                    <tr>
                        <td><?php echo $sl++; ?></td>
                        <td><?php echo htmlspecialchars($row->test_category_name); ?></td>
                        <td><?php echo $row->entry_count; ?></td>
                        <td><?php echo number_format($row->total_paid, 2); ?></td>
                        <td><?php echo number_format($commission, 2); ?></td>
                    </tr>
                <?php endforeach; ?>
                <tr style="font-weight: bold; background-color: #f9f9f9;">
                    <td colspan="2" style="text-align: right;">Total</td>
                    <td><?php echo $grand_total_entries; ?></td>
                    <td><?php echo number_format($grand_total_paid, 2); ?></td>
                    <td><?php echo number_format($grand_total_commission, 2); ?></td>
                </tr>
            </table>
        <?php
        }

        if (!empty($emergency_results)) {
            $es_sl = 1;
            $total_emergency_paid = 0;
            $total_emergency_commission = 0;
        ?>
            <table border="1" style="width: 98%; border-collapse: collapse; margin: 20px auto 0; color: black;">
                <tr style="background-color: #d9534f; color: white;">
                    <td colspan="5" style="text-align: center">Emergency Service Commission Report</td>
                </tr>
                <tr style="background-color: #f1f1f1; font-weight: bold;">
                    <td>Sl</td>
                    <td>Service</td>
                    <td>Total Entries</td>
                    <td>Total Paid</td>
                    <td>Total Commission</td>
                </tr>
                <?php foreach ($emergency_results as $erow):
                    $e_commission = 0;
                    if ($erow->commission_type === 'percentage') {
                        $e_commission = ($erow->total_paid * $erow->commission_rate) / 100;
                    } elseif ($erow->commission_type === 'fixed') {
                        $e_commission = $erow->entry_count * $erow->commission_rate;
                    }

                    $total_emergency_paid += $erow->total_paid;
                    $total_emergency_commission += $e_commission;
                ?>
                    <tr>
                        <td><?php echo $es_sl++; ?></td>
                        <td><?php echo htmlspecialchars($erow->emergency_service_name); ?></td>
                        <td><?php echo $erow->entry_count; ?></td>
                        <td><?php echo number_format($erow->total_paid, 2); ?></td>
                        <td><?php echo number_format($e_commission, 2); ?></td>
                    </tr>
                <?php endforeach; ?>
                <tr style="font-weight: bold; background-color: #f9f9f9;">
                    <td colspan="2" style="text-align: right;">Total</td>
                    <td><?php echo array_sum(array_column($emergency_results, 'entry_count')); ?></td>
                    <td><?php echo number_format($total_emergency_paid, 2); ?></td>
                    <td><?php echo number_format($total_emergency_commission, 2); ?></td>
                </tr>
            </table>
    <?php
        }
    }
    ?>



    <script src="<?php echo base_url(); ?>js/loader_jquery-min.js"></script>
    <script src="<?php echo base_url(); ?>js/loader_main.js"></script>

</body>

</html>