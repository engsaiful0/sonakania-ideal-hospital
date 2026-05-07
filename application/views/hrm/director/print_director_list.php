<?php
$company = getCompany();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Shareholders List - Print</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
        }
        .company-name {
            font-size: 28px;
            font-weight: bold;
            margin-bottom: 10px;
            text-transform: uppercase;
        }
        .report-title {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .report-info {
            font-size: 14px;
            color: #666;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            font-size: 12px;
        }
        th, td {
            border: 1px solid #333;
            padding: 8px 4px;
            text-align: left;
        }
        th {
            background-color: #f5f5f5;
            font-weight: bold;
            text-align: center;
        }
        .total-row {
            background-color: #e9e9e9;
            font-weight: bold;
            border-top: 2px solid #333;
        }
        .number {
            text-align: right;
        }
        .center {
            text-align: center;
        }
        .summary {
            margin-top: 30px;
            padding: 15px;
            background-color: #f9f9f9;
            border: 1px solid #ddd;
        }
        .summary-title {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .summary-item {
            margin: 5px 0;
            font-size: 14px;
        }
        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 12px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 15px;
        }
        @media print {
            body { 
                margin: 0; 
                padding: 15px;
            }
            .no-print { 
                display: none; 
            }
            table {
                font-size: 10px;
            }
            th, td {
                padding: 4px 2px;
            }
        }
        .print-button {
            position: fixed;
            top: 20px;
            right: 20px;
            background: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            z-index: 1000;
        }
        .print-button:hover {
            background: #0056b3;
        }
    </style>
</head>
<body>
    <button class="print-button no-print" onclick="window.print()">🖨️ Print</button>

    <div class="header">
        <div class="company-name">
            <?php echo $company ? $company->company_name : 'BGH Soft ERP'; ?>
        </div>
        <div class="report-title">Shareholders List Report</div>
        <div class="report-info">
            Generated on <?php echo date('l, d F Y \a\t H:i:s'); ?> | 
            Total Records: <?php echo count($director_data); ?>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th rowspan="2">SL</th>
                <th rowspan="2">Name</th>
                <th rowspan="2">Unique ID</th>
                <th rowspan="2">Mobile</th>
                <th rowspan="2">Email</th>
                <th rowspan="2">No. of<br>Shares</th>
                <th colspan="2">Original Investment</th>
                <th colspan="2">Current Value</th>
                <th rowspan="2">Join Date</th>
                <th rowspan="2">Category</th>
            </tr>
            <tr>
                <th>Per Share</th>
                <th>Total</th>
                <th>Per Share</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sl = 1;
            $grand_total_original = 0;
            $grand_total_current = 0;
            $total_shares = 0;
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
                $total_shares += (float)$data->no_of_share;
            ?>
                <tr>
                    <td class="center"><?php echo $sl++; ?></td>
                    <td><?php echo htmlspecialchars($data->name); ?></td>
                    <td class="center"><?php echo htmlspecialchars($data->unique_id); ?></td>
                    <td><?php echo htmlspecialchars($data->mobile); ?></td>
                    <td><?php echo htmlspecialchars($data->email ?: 'N/A'); ?></td>
                    <td class="number"><?php echo number_format((float)$data->no_of_share); ?></td>
                    <td class="number"><?php echo number_format((float)$data->amount_per_share, 2); ?></td>
                    <td class="number"><?php echo number_format((float)$data->total_amount, 2); ?></td>
                    <td class="number"><?php echo number_format((float)$current_share_value, 2); ?></td>
                    <td class="number"><?php echo number_format((float)$total_current_value, 2); ?></td>
                    <td class="center"><?php echo !empty($data->date_of_join) ? date('d-m-Y', strtotime($data->date_of_join)) : 'N/A'; ?></td>
                    <td class="center"><?php echo htmlspecialchars($data->category ?: 'N/A'); ?></td>
                </tr>
            <?php endforeach; ?>
            <tr class="total-row">
                <td colspan="5" style="text-align: center; font-weight: bold;">TOTAL</td>
                <td class="number"><?php echo number_format((float)$total_shares); ?></td>
                <td class="center">-</td>
                <td class="number"><?php echo number_format((float)$grand_total_original, 2); ?></td>
                <td class="center">-</td>
                <td class="number"><?php echo number_format((float)$grand_total_current, 2); ?></td>
                <td colspan="2" class="center">-</td>
            </tr>
        </tbody>
    </table>

    <div class="summary">
        <div class="summary-title">Summary</div>
        <div class="summary-item"><strong>Total Shareholders:</strong> <?php echo count($director_data); ?></div>
        <div class="summary-item"><strong>Total Shares:</strong> <?php echo number_format((float)$total_shares); ?></div>
        <div class="summary-item"><strong>Original Investment:</strong> ৳ <?php echo number_format((float)$grand_total_original, 2); ?></div>
        <div class="summary-item"><strong>Current Market Value:</strong> ৳ <?php echo number_format((float)$grand_total_current, 2); ?></div>
        <div class="summary-item"><strong>Value Appreciation:</strong> ৳ <?php echo number_format((float)$grand_total_current - (float)$grand_total_original, 2); ?> 
            (<?php echo (float) $grand_total_original > 0 ? number_format((($grand_total_current - (float)$grand_total_original) / (float)$grand_total_original) * 100, 2) : 0; ?>%)
        </div>
    </div>

    <div class="footer">
        <p><strong><?php echo $company ? $company->company_name : 'BGH Soft ERP'; ?></strong></p>
        <p>This is a computer-generated report. No signature required.</p>
        <p>Printed on <?php echo date('d/m/Y \a\t H:i:s'); ?></p>
    </div>
</body>
</html>
