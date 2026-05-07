<?php
$company = getCompany();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Director Financial Report - Excel Export</title>
</head>
<body>
    <table border="1">
        <thead>
            <!-- Header -->
            <tr>
                <td colspan="12" align="center" style="font-size: 18px; font-weight: bold; height: 35px;">
                    <?php echo $company ? $company->company_name : 'BGH Soft ERP'; ?>
                </td>
            </tr>
            <tr>
                <td colspan="12" align="center" style="font-size: 14px; font-weight: bold; height: 30px;">
                    Directors Financial Analysis Report
                </td>
            </tr>
            <tr>
                <td colspan="12" align="center" style="font-size: 11px; height: 25px;">
                    Generated on <?php echo date('d F Y \a\t H:i:s'); ?>
                </td>
            </tr>
            <tr><td colspan="12">&nbsp;</td></tr>
            
            <!-- Headers -->
            <tr style="background-color: #4CAF50; color: white; font-weight: bold; height: 35px;">
                <td rowspan="2" style="text-align: center;">SL</td>
                <td rowspan="2" style="text-align: center;">Director</td>
                <td colspan="3" style="text-align: center;">Share Details</td>
                <td colspan="4" style="text-align: center;">Financial Performance</td>
                <td colspan="3" style="text-align: center;">Discount Benefits</td>
            </tr>
            <tr style="background-color: #ddd; font-weight: bold;">
                <td>Shares</td>
                <td>Price/Share</td>
                <td>Join Date</td>
                <td>Investment</td>
                <td>Current Value</td>
                <td>Gain/Loss</td>
                <td>ROI %</td>
                <td>Medical</td>
                <td>Pharmacy</td>
                <td>Total Benefits</td>
            </tr>
        </thead>
        <tbody>
            <?php
            $sl = 1;
            $total_investment = 0;
            $total_current = 0;
            $total_gain_loss = 0;
            
            foreach ($director_data as $data):
                $current_share_value = calculate_current_share_value(
                    $data->amount_per_share,
                    $data->yearly_share_value_increment_rate,
                    $data->date_of_join
                );
                $total_current_value = $current_share_value * (float)$data->no_of_share;
                $gain_loss = $total_current_value - (float)$data->total_amount;
                $roi_percentage = ((float)$data->total_amount > 0) ? 
                    ($gain_loss / (float)$data->total_amount) * 100 : 0;
                
                // Calculate discount benefits (assumed annual usage)
                $medical_discount = ((float)$data->ipd_discount + (float)$data->opd_discount + (float)$data->emergency_discount) / 3;
                $pharmacy_discount = (float)$data->pharmachy_discount + (float)$data->test_discount;
                $total_benefits = $medical_discount + $pharmacy_discount;
                
                $total_investment += (float)$data->total_amount;
                $total_current += $total_current_value;
                $total_gain_loss += $gain_loss;
                
                // Calculate years invested
                $years_invested = 0;
                if (!empty($data->date_of_join)) {
                    $join_date = new DateTime($data->date_of_join);
                    $current_date = new DateTime();
                    $years_invested = $join_date->diff($current_date)->y + ($join_date->diff($current_date)->m / 12);
                }
            ?>
                <tr style="<?php echo $sl % 2 == 0 ? 'background-color: #f9f9f9;' : ''; ?>">
                    <td style="text-align: center;"><?php echo $sl++; ?></td>
                    <td><?php echo htmlspecialchars($data->name ?: 'N/A'); ?></td>
                    
                    <!-- Share Details -->
                    <td style="text-align: right;"><?php echo number_format((float)$data->no_of_share); ?></td>
                    <td style="text-align: right;"><?php echo number_format((float)$data->amount_per_share, 2); ?></td>
                    <td style="text-align: center;"><?php echo !empty($data->date_of_join) ? date('d-m-Y', strtotime($data->date_of_join)) : 'N/A'; ?></td>
                    
                    <!-- Financial Performance -->
                    <td style="text-align: right;"><?php echo number_format((float)$data->total_amount, 2); ?></td>
                    <td style="text-align: right;"><?php echo number_format($total_current_value, 2); ?></td>
                    <td style="text-align: right; <?php echo $gain_loss >= 0 ? 'color: green;' : 'color: red;'; ?>">
                        <?php echo ($gain_loss >= 0 ? '+' : '') . number_format($gain_loss, 2); ?>
                    </td>
                    <td style="text-align: right; <?php echo $roi_percentage >= 0 ? 'color: green;' : 'color: red;'; ?>">
                        <?php echo ($roi_percentage >= 0 ? '+' : '') . number_format($roi_percentage, 1); ?>%
                    </td>
                    
                    <!-- Discount Benefits -->
                    <td style="text-align: center;"><?php echo number_format($medical_discount, 1); ?>%</td>
                    <td style="text-align: center;"><?php echo number_format($pharmacy_discount, 1); ?>%</td>
                    <td style="text-align: center;"><?php echo number_format($total_benefits, 1); ?>%</td>
                </tr>
            <?php endforeach; ?>
            
            <!-- Total Row -->
            <tr style="background-color: #2196F3; color: white; font-weight: bold; height: 35px;">
                <td colspan="5" style="text-align: center;">PORTFOLIO TOTAL</td>
                <td style="text-align: right;"><?php echo number_format($total_investment, 2); ?></td>
                <td style="text-align: right;"><?php echo number_format($total_current, 2); ?></td>
                <td style="text-align: right; <?php echo $total_gain_loss >= 0 ? 'color: lightgreen;' : 'color: lightcoral;'; ?>">
                    <?php echo ($total_gain_loss >= 0 ? '+' : '') . number_format($total_gain_loss, 2); ?>
                </td>
                <td style="text-align: right;">
                    <?php echo $total_investment > 0 ? number_format(($total_gain_loss / $total_investment) * 100, 1) : '0.0'; ?>%
                </td>
                <td colspan="3"></td>
            </tr>
        </tbody>
    </table>
    
    <!-- Financial Summary -->
    <br><br>
    <table border="1">
        <thead>
            <tr style="background-color: #FF9800; color: white; font-weight: bold; height: 35px;">
                <td colspan="4" style="text-align: center;">PORTFOLIO PERFORMANCE SUMMARY</td>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="font-weight: bold;">Total Shareholders:</td>
                <td><?php echo count($director_data); ?></td>
                <td style="font-weight: bold;">Average Investment:</td>
                <td style="text-align: right;"><?php echo count($director_data) > 0 ? number_format($total_investment / count($director_data), 2) : '0.00'; ?></td>
            </tr>
            <tr>
                <td style="font-weight: bold;">Total Portfolio Value:</td>
                <td style="text-align: right;"><?php echo number_format($total_investment, 2); ?></td>
                <td style="font-weight: bold;">Current Market Value:</td>
                <td style="text-align: right;"><?php echo number_format($total_current, 2); ?></td>
            </tr>
            <tr>
                <td style="font-weight: bold;">Total Appreciation:</td>
                <td style="text-align: right; <?php echo $total_gain_loss >= 0 ? 'color: green;' : 'color: red;'; ?>">
                    <?php echo ($total_gain_loss >= 0 ? '+' : '') . number_format($total_gain_loss, 2); ?>
                </td>
                <td style="font-weight: bold;">Overall ROI:</td>
                <td style="text-align: right; <?php echo ($total_gain_loss >= 0) ? 'color: green;' : 'color: red;'; ?>">
                    <?php echo $total_investment > 0 ? number_format(($total_gain_loss / $total_investment) * 100, 2) : '0.00'; ?>%
                </td>
            </tr>
            <tr style="background-color: #f0f0f0;">
                <td colspan="4" style="text-align: center; font-style: italic;">
                    Report generated automatically by BGH Soft ERP - ShareHolder Management System
                </td>
            </tr>
        </tbody>
    </table>
</body>
</html>

