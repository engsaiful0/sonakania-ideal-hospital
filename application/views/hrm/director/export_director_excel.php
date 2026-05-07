<?php
$company = getCompany();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Director List - Excel Export</title>
</head>
<body>
    <table border="1">
        <thead>
            <tr>
                <td colspan="12" align="center" style="font-size: 18px; font-weight: bold;">
                    <?php echo $company ? $company->company_name : 'BGH Soft ERP'; ?>
                </td>
            </tr>
            <tr>
                <td colspan="12" align="center" style="font-size: 14px; font-weight: bold;">
                Director List - Generated on <?php echo date('d-m-Y H:i:s'); ?>
                </td>
            </tr>
            <tr>
                <td colspan="12" align="center">&nbsp;</td>
            </tr>
            <tr style="background-color: #ddd; font-weight: bold;">
                <td>SL</td>
                <td>Name</td>
                <td>Unique ID</td>
                <td>Mobile</td>
                <td>Email</td>
                <td>No of Share</td>
                <td>Share Value</td>
                <td>Total Amount</td>
                <td>Current Share Value</td>
                <td>Total Current Value</td>
                <td>Date of Join</td>
                <td>Category</td>
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
                    $data->total_amount,
                    $data->yearly_share_value_increment_rate,
                    $data->date_of_join
                );
                $total_current_value = $current_share_value * $data->no_of_share;
                
                $grand_total_original += $data->total_amount;
                $grand_total_current += $total_current_value;
            ?>
                <tr>
                    <td><?php echo $sl++; ?></td>
                    <td><?php echo $data->name; ?></td>
                    <td><?php echo $data->unique_id; ?></td>
                    <td><?php echo $data->mobile; ?></td>
                    <td><?php echo $data->email ?: 'N/A'; ?></td>
                    <td><?php echo number_format($data->no_of_share); ?></td>
                    <td><?php echo number_format($data->amount_per_share, 2); ?></td>
                    <td><?php echo number_format($data->total_amount, 2); ?></td>
                    <td><?php echo number_format($current_share_value, 2); ?></td>
                    <td><?php echo number_format($total_current_value, 2); ?></td>
                    <td><?php echo !empty($data->date_of_join) ? date('d-m-Y', strtotime($data->date_of_join)) : 'N/A'; ?></td>
                    <td><?php echo $data->category ?: 'N/A'; ?></td>
                </tr>
            <?php endforeach; ?>
            <tr style="background-color: #f0f0f0; font-weight: bold;">
                <td colspan="7" align="right">Total:</td>
                <td><?php echo number_format($grand_total_original, 2); ?></td>
                <td colspan="1"></td>
                <td><?php echo number_format($grand_total_current, 2); ?></td>
                <td colspan="2"></td>
            </tr>
        </tbody>
    </table>
</body>
</html>
