<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <style>
        body {
            width: 100%;
            background: rgb(204, 204, 204);
            overflow-x: hidden;
            font-size: 12px;

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
            margin-top: 40px
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
            font-size: 10px;
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
                font-size: 12px !important;
            }
        }

        @media print {
            #print {
                display: none;
            }

            .print {
                display: none;
            }

            .no-print {
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
            font-size: 10px;
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
            width: 50%;
            height: 100px;
            float: left;
        }

        .fbox1 {
            width: 320px;
            float: left;
            margin-left: 39px;
            border-collapse: collapse;
            text-align: left;
            font-size: 10px;
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

        .low-stock {
            background-color: white;
            color: black;
        }

        .out-of-stock {
            background-color: whitesmoke;
            color: black;
        }

        .export-buttons {
            margin: 20px auto;
            text-align: center;
            width: 90%;
        }

        .export-buttons button {
            margin: 5px;
            padding: 10px 20px;
            font-size: 14px;
            cursor: pointer;
        }

        @font-face {
            font-family: 'NikoshBAN';
            src: url('../assets/fonts/NikoshBAN.ttf') format('truetype');

        }
    </style>

    <title>Medicine Low Stock Report</title>
</head>

<body>
    <!-- Export Buttons -->
    <div class="export-buttons no-print">
        <button onclick="window.print()" style="background-color: #337ab7; color: white; border: none; border-radius: 4px;">
            🖨️ Print Report
        </button>
        <button onclick="exportToExcel()" style="background-color: #5cb85c; color: white; border: none; border-radius: 4px;">
            📊 Export to Excel
        </button>
    </div>

    <?php
    // Get all drugs with reorder quantity set
    $this->db->select('*');
    $this->db->from('drug');
    $this->db->where('reorder_quantity >', 0);
    if ($drug_name != "") {
        // Use LIKE for partial matching, making search more flexible
        $this->db->like('drug_name', $drug_name);
    }
    $this->db->order_by('drug_name', 'ASC');
    $all_drugs = $this->db->get()->result();

    // Filter for low stock medicines
    $low_stock_medicines = [];
    $total_shortage_value = 0;
    $total_low_stock_count = 0;

    foreach ($all_drugs as $drug) {
        $current_stock = getStock($drug->drug_id);
        if ($current_stock < $drug->reorder_quantity) {
            $drug->current_stock = $current_stock;
            $drug->shortage = $drug->reorder_quantity - $current_stock;
            $drug->shortage_value = $drug->shortage * (float)($drug->purchase_rate ?? 0);
            $low_stock_medicines[] = $drug;
            $total_shortage_value += $drug->shortage_value;
            $total_low_stock_count++;
        }
    }

    $total_medicines = count($low_stock_medicines);
    $page_limit = ceil($total_medicines / 50);
    if ($page_limit == 0) $page_limit = 1;

    if ($total_medicines == 0): // If there are no low stock medicines
    ?>
        <page size="A4">
            <div class="first">
                <div class="second">
                    <table border="1" class="table table-bordered table-hover" style="width: 90%;margin: 0 auto;color:black;border-collapse:collapse;">
                        <tr style="background-color: #0074B3;color: white;">
                            <td colspan="8" style="text-align: center"><b>Medicine Low Stock Report</b></td>
                        </tr>
                        <tr style="background-color: #f8f9fa;">
                            <td colspan="8" style="text-align: center; padding: 20px;">
                                <h3 style="color: #28a745;">✓ No Low Stock Medicines Found</h3>
                                <p>All medicines are adequately stocked above their reorder levels.</p>
                                <p><strong>Generated on:</strong> <?php echo date('d-m-Y H:i:s'); ?></p>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </page>
    <?php
    else: // If there are low stock medicines to display
        $medicines_per_page = 50;
        $from = 0;
        
        for ($page_no = 1; $page_no <= $page_limit; $page_no++):
            $to = min($from + $medicines_per_page, $total_medicines);
        ?>
            <page size="A4">
                <div class="first">
                    <div class="second">
                        <table border="1" class="table table-bordered table-hover" style="width: 98%;margin: 0 auto;color:black;border-collapse:collapse;">
                            <tr style="background-color: #dc3545;color: white;">
                                <td colspan="8" style="text-align: center">
                                    <b>🚨 Medicine Low Stock Report</b><br>
                                    <small>Medicines requiring immediate attention</small>
                                </td>
                            </tr>
                            <?php if ($page_no == 1): ?>
                            <tr style="background-color: #f8f9fa;">
                                <td colspan="8" style="text-align: center; padding: 10px;">
                                    <strong>Total Low Stock Medicines: <?php echo $total_low_stock_count; ?> | 
                                    Total Shortage Value: ৳<?php echo number_format($total_shortage_value, 2); ?> |
                                    Generated: <?php echo date('d-m-Y H:i:s'); ?></strong>
                                </td>
                            </tr>
                            <?php endif; ?>
                            <tr style="background-color: #e9ecef;">
                                <td><strong>Sl</strong></td>
                                <td><strong>Medicine Name</strong></td>
                                <td><strong>Current Stock</strong></td>
                                <td><strong>Reorder Qty</strong></td>
                                <td><strong>Shortage</strong></td>
                                <td><strong>MRP</strong></td>
                                <td><strong>Purchase Rate</strong></td>
                                <td><strong>Status</strong></td>
                            </tr>
                            <?php
                            $sl = $from + 1;
                            
                            for ($i = $from; $i < $to; $i++) {
                                $medicine = $low_stock_medicines[$i];
                                $status = ($medicine->current_stock <= 0) ? 'OUT OF STOCK' : 'LOW STOCK';
                                $row_class = ($medicine->current_stock <= 0) ? 'out-of-stock' : 'low-stock';
                            ?>
                                <tr class="<?php echo $row_class; ?>">
                                    <td><?php echo $sl++; ?></td>
                                    <td><strong><?php echo htmlspecialchars($medicine->drug_name); ?></strong></td>
                                    <td style="text-align: center;">
                                        <span style="font-weight: bold; color: black;">
                                            <?php echo number_format($medicine->current_stock); ?>
                                        </span>
                                    </td>
                                    <td style="text-align: center;"><?php echo number_format($medicine->reorder_quantity); ?></td>
                                    <td style="text-align: center; color: black;">
                                        <strong><?php echo number_format($medicine->shortage); ?></strong>
                                    </td>
                                    <td style="text-align: right;">৳<?php echo number_format($medicine->mrp ?? 0, 2); ?></td>
                                    <td style="text-align: right;">৳<?php echo number_format($medicine->purchase_rate ?? 0, 2); ?></td>
                                    <td style="text-align: center;">
                                        <span style="font-weight: bold; color: black;">
                                            <?php echo $status; ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php
                            }
                            ?>
                            <?php if ($page_no == $page_limit): // Summary on last page ?>
                            <tr style="background-color: #343a40; color: white;">
                                <td colspan="2" style="text-align: right; font-weight: bold;">SUMMARY:</td>
                                <td style="text-align: center; font-weight: bold;">
                                    Total Items: <?php echo $total_low_stock_count; ?>
                                </td>
                                <td colspan="2" style="text-align: center; font-weight: bold;">
                                    Total Shortage Value
                                </td>
                                <td colspan="3" style="text-align: center; font-weight: bold;">
                                    ৳<?php echo number_format($total_shortage_value, 2); ?>
                                </td>
                            </tr>
                            <?php endif; ?>
                        </table>
                    </div>
                </div>
            </page>
    <?php
            $from = $to;
        endfor;
    endif;
    ?>

    <script>
        function exportToExcel() {
            window.open('<?php echo site_url('ReportPharmacyController/export_low_stock_to_excel'); ?>', '_blank');
        }
    </script>


</body>

</html>