<?php
$company = getCompany();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Directors Summary - Excel Export</title>
</head>
<body>
    <table border="1">
        <thead>
            <!-- Header -->
            <tr>
                <td colspan="10" align="center" style="font-size: 18px; font-weight: bold; height: 35px;">
                    <?php echo $company ? $company->company_name : 'BGH Soft ERP'; ?>
                </td>
            </tr>
            <tr>
                <td colspan="10" align="center" style="font-size: 14px; font-weight: bold; height: 30px;">
                    Shareholders Summary Report
                </td>
            </tr>
            <tr>
                <td colspan="10" align="center" style="font-size: 11px; height: 25px;">
                    Generated on <?php echo date('d F Y \a\t H:i:s'); ?>
                </td>
            </tr>
            <tr><td colspan="10">&nbsp;</td></tr>
            
            <!-- Headers -->
            <tr style="background-color: #4CAF50; color: white; font-weight: bold; height: 35px;">
                <td style="text-align: center;">SL</td>
                <td style="text-align: center;">Name</td>
                <td style="text-align: center;">Unique ID</td>
                <td style="text-align: center;">Mobile</td>
                <td style="text-align: center;">Shares</td>
                <td style="text-align: center;">Investment</td>
                <td style="text-align: center;">Current Value</td>
                <td style="text-align: center;">Growth</td>
                <td style="text-align: center;">Category</td>
                <td style="text-align: center;">Status</td>
            </tr>
        </thead>
        <tbody>
            <?php
            $sl = 1;
            $grand_total_original = 0;
            $grand_total_current = 0;
            $total_shares = 0;
            
            foreach ($director_data as $data):
                $current_share_value = calculate_current_share_value(
                    $data->amount_per_share,
                    $data->yearly_share_value_increment_rate,
                    $data->date_of_join
                );
                $total_current_value = $current_share_value * (float)$data->no_of_share;
                $growth_percentage = ((float)$data->total_amount > 0) ? 
                    (($total_current_value - (float)$data->total_amount) / (float)$data->total_amount) * 100 : 0;
                
                $grand_total_original += (float)$data->total_amount;
                $grand_total_current += $total_current_value;
                $total_shares += (float)$data->no_of_share;
                
                // Determine status based on investment amount
                $status = '';
                if ((float)$data->total_amount >= 100000) $status = 'Major';
                elseif ((float)$data->total_amount >= 50000) $status = 'Medium';
                else $status = 'Small';
            ?>
                <tr style="<?php echo $sl % 2 == 0 ? 'background-color: #f9f9f9;' : ''; ?>">
                    <td style="text-align: center;"><?php echo $sl++; ?></td>
                    <td><?php echo htmlspecialchars($data->name ?: 'N/A'); ?></td>
                    <td style="text-align: center;"><?php echo htmlspecialchars($data->unique_id ?: 'N/A'); ?></td>
                    <td><?php echo htmlspecialchars($data->mobile ?: 'N/A'); ?></td>
                    <td style="text-align: right;"><?php echo number_format((float)$data->no_of_share); ?></td>
                    <td style="text-align: right;"><?php echo number_format((float)$data->total_amount, 2); ?></td>
                    <td style="text-align: right;"><?php echo number_format($total_current_value, 2); ?></td>
                    <td style="text-align: right; <?php echo $growth_percentage >= 0 ? 'color: green;' : 'color: red;'; ?>">
                        <?php echo ($growth_percentage >= 0 ? '+' : '') . number_format($growth_percentage, 1); ?>%
                    </td>
                    <td style="text-align: center;"><?php echo htmlspecialchars($data->category ?: 'General'); ?></td>
                    <td style="text-align: center;"><?php echo $status; ?></td>
                </tr>
            <?php endforeach; ?>
            
            <!-- Total Row -->
            <tr style="background-color: #2196F3; color: white; font-weight: bold; height: 35px;">
                <td colspan="4" style="text-align: center;">TOTAL</td>
                <td style="text-align: right;"><?php echo number_format($total_shares); ?></td>
                <td style="text-align: right;"><?php echo number_format($grand_total_original, 2); ?></td>
                <td style="text-align: right;"><?php echo number_format($grand_total_current, 2); ?></td>
                <td style="text-align: right;">
                    <?php echo $grand_total_original > 0 ? number_format((($grand_total_current - $grand_total_original) / $grand_total_original) * 100, 1) : '0.0'; ?>%
                </td>
                <td colspan="2"></td>
            </tr>
        </tbody>
    </table>
    
    <!-- Category Breakdown -->
    <br><br>
    <table border="1">
        <thead>
            <tr style="background-color: #FF9800; color: white; font-weight: bold; height: 35px;">
                <td colspan="6" style="text-align: center;">CATEGORY WISE BREAKDOWN</td>
            </tr>
            <tr style="background-color: #ddd; font-weight: bold;">
                <td>Category</td>
                <td>Count</td>
                <td>Total Shares</td>
                <td>Total Investment</td>
                <td>Current Value</td>
                <td>Growth %</td>
            </tr>
        </thead>
        <tbody>
            <?php
            $categories = [];
            foreach ($director_data as $data) {
                $cat = $data->category ?: 'General';
                if (!isset($categories[$cat])) {
                    $categories[$cat] = [
                        'count' => 0,
                        'shares' => 0,
                        'investment' => 0,
                        'current' => 0
                    ];
                }
                $categories[$cat]['count']++;
                $categories[$cat]['shares'] += (float)$data->no_of_share;
                $categories[$cat]['investment'] += (float)$data->total_amount;
                
                $current_val = calculate_current_share_value(
                    $data->amount_per_share,
                    $data->yearly_share_value_increment_rate,
                    $data->date_of_join
                ) * (float)$data->no_of_share;
                $categories[$cat]['current'] += $current_val;
            }
            
            foreach ($categories as $cat_name => $cat_data):
                $growth = $cat_data['investment'] > 0 ? 
                    (($cat_data['current'] - $cat_data['investment']) / $cat_data['investment']) * 100 : 0;
            ?>
                <tr>
                    <td><?php echo htmlspecialchars($cat_name); ?></td>
                    <td style="text-align: right;"><?php echo $cat_data['count']; ?></td>
                    <td style="text-align: right;"><?php echo number_format($cat_data['shares']); ?></td>
                    <td style="text-align: right;"><?php echo number_format($cat_data['investment'], 2); ?></td>
                    <td style="text-align: right;"><?php echo number_format($cat_data['current'], 2); ?></td>
                    <td style="text-align: right; <?php echo $growth >= 0 ? 'color: green;' : 'color: red;'; ?>">
                        <?php echo ($growth >= 0 ? '+' : '') . number_format($growth, 1); ?>%
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>

