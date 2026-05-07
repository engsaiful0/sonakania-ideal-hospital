<?php
$company = getCompany();

// Simple PDF generation using HTML with CSS styling
// Note: For proper PDF, this will work with browser print-to-PDF or external PDF libraries
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Directors List - PDF Export</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .company-name {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .report-title {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .report-date {
            font-size: 12px;
            color: #666;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            font-size: 10px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 4px;
            text-align: left;
        }
        th {
            background-color: #f5f5f5;
            font-weight: bold;
            font-size: 9px;
        }
        .total-row {
            background-color: #f0f0f0;
            font-weight: bold;
        }
        .number {
            text-align: right;
        }
        .center {
            text-align: center;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #666;
        }
        @media print {
            body { margin: 0; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="company-name">
            <?php echo $company ? $company->company_name : 'BGH Soft ERP'; ?>
        </div>
        <div class="report-title">Directors List</div>
        <div class="report-date">Generated on <?php echo date('d-m-Y H:i:s'); ?></div>
    </div>

    <table>
        <thead>
            <tr>
                <th>SL</th>
                <th>Name</th>
                <th>Unique ID</th>
                <th>Mobile</th>
                <th>Email</th>
                <th>Shares</th>
                <th>Share Value</th>
                <th>Total Amount</th>
                <th>Current Value</th>
                <th>Total Current</th>
                <th>Join Date</th>
                <th>Category</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sl = 1;
            $grand_total_original = 0;
            $grand_total_current = 0;
            foreach ($director_data as $data):
                // Calculate current share value
                $current_share_value = calculate_current_share_value(
                    $data->amount_per_share,
                    $data->yearly_share_value_increment_rate,
                    $data->date_of_join
                );
                $total_current_value = $current_share_value * (float)$data->no_of_share;
                
                $grand_total_original += (float)$data->total_amount;
                $grand_total_current += (float)$total_current_value;
            ?>
                <tr>
                    <td class="center"><?php echo $sl++; ?></td>
                    <td><?php echo htmlspecialchars($data->name); ?></td>
                    <td class="center"><?php echo htmlspecialchars($data->unique_id); ?></td>
                    <td><?php echo htmlspecialchars($data->mobile); ?></td>
                    <td><?php echo htmlspecialchars($data->email ?: 'N/A'); ?></td>
                    <td class="number"><?php echo number_format((float)$data->no_of_share); ?></td>
                    <td class="number"><?php echo number_format($data->amount_per_share, 2); ?></td>
                    <td class="number"><?php echo number_format($data->total_amount, 2); ?></td>
                    <td class="number"><?php echo number_format($current_share_value, 2); ?></td>
                    <td class="number"><?php echo number_format($total_current_value, 2); ?></td>
                    <td class="center"><?php echo !empty($data->date_of_join) ? date('d-m-Y', strtotime($data->date_of_join)) : 'N/A'; ?></td>
                    <td class="center"><?php echo htmlspecialchars($data->category ?: 'N/A'); ?></td>
                </tr>
            <?php endforeach; ?>
            <tr class="total-row">
                <td colspan="7" style="text-align: right; font-weight: bold;">Total:</td>
                <td class="number"><?php echo number_format((float)$grand_total_original, 2); ?></td>
                <td></td>
                <td class="number"><?php echo number_format((float)$grand_total_current, 2); ?></td>
                <td colspan="2"></td>
            </tr>
        </tbody>
    </table>

    <div class="footer">
        <p>Total Shareholders: <?php echo count($director_data); ?> | 
        Original Investment: <?php echo number_format($grand_total_original, 2); ?> | 
        Current Value: <?php echo number_format($grand_total_current, 2); ?></p>
        <p>Report generated from BGH Soft ERP System</p>
    </div>

    <script>
        // Auto-print when loaded
        window.onload = function() {
            window.print();
        }
    </script>
</body>
</html>
