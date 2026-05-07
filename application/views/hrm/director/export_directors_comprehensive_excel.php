<?php
$company = getCompany();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Comprehensive Director List - Excel Export</title>
</head>
<body>
    <table border="1">
        <thead>
            <!-- Header Section -->
            <tr>
                <td colspan="20" align="center" style="font-size: 20px; font-weight: bold; height: 40px;">
                    <?php echo $company ? $company->company_name : 'BGH Soft ERP'; ?>
                </td>
            </tr>
            <tr>
                <td colspan="20" align="center" style="font-size: 16px; font-weight: bold; height: 30px;">
                    Comprehensive Director Report
                </td>
            </tr>
            <tr>
                <td colspan="20" align="center" style="font-size: 12px; height: 25px;">
                    Generated on <?php echo date('d F Y \a\t H:i:s'); ?>
                </td>
            </tr>
            <tr>
                <td colspan="20">&nbsp;</td>
            </tr>
            
            <!-- Main Headers -->
            <tr style="background-color: #4CAF50; color: white; font-weight: bold; height: 35px;">
                <td rowspan="2" style="text-align: center; vertical-align: middle;">SL</td>
                <td colspan="4" style="text-align: center; vertical-align: middle;">Personal Information</td>
                <td colspan="4" style="text-align: center; vertical-align: middle;">Share Information</td>
                <td colspan="3" style="text-align: center; vertical-align: middle;">Financial Details</td>
                <td colspan="4" style="text-align: center; vertical-align: middle;">Nominee Information</td>
                <td colspan="4" style="text-align: center; vertical-align: middle;">Bank & Discounts</td>
            </tr>
            
            <!-- Sub Headers -->
            <tr style="background-color: #ddd; font-weight: bold; height: 30px;">
                <!-- Personal Information -->
                <td>Name</td>
                <td>Unique ID</td>
                <td>Mobile</td>
                <td>Email</td>
                
                <!-- Share Information -->
                <td>No of Shares</td>
                <td>Share Price</td>
                <td>Category</td>
                <td>Join Date</td>
                
                <!-- Financial Details -->
                <td>Original Amount</td>
                <td>Current Value</td>
                <td>Appreciation</td>
                
                <!-- Nominee Information -->
                <td>Nominee Name</td>
                <td>Relation</td>
                <td>Nominee Mobile</td>
                <td>Nominee Email</td>
                
                <!-- Bank & Discounts -->
                <td>Bank Details</td>
                <td>IPD Discount</td>
                <td>OPD Discount</td>
                <td>Test Discount</td>
            </tr>
        </thead>
        <tbody>
            <?php
            $sl = 1;
            $grand_total_original = 0;
            $grand_total_current = 0;
            $total_shares = 0;
            $total_appreciation = 0;
            
            foreach ($director_data as $data):
                // Calculate current share value
                $current_share_value = calculate_current_share_value(
                    $data->amount_per_share,
                    $data->yearly_share_value_increment_rate,
                    $data->date_of_join
                );
                $total_current_value = $current_share_value * (float)$data->no_of_share;
                $appreciation = $total_current_value - (float)$data->total_amount;
                
                $grand_total_original += (float)$data->total_amount;
                $grand_total_current += $total_current_value;
                $total_shares += (float)$data->no_of_share;
                $total_appreciation += $appreciation;
                
                // Get foreign key data safely
                $nationality = !empty($data->nationality_id) ? 
                    $this->db->where('nationality_id', $data->nationality_id)->get('nationality')->row() : null;
                $religion = !empty($data->religion_id) ? 
                    $this->db->where('religion_id', $data->religion_id)->get('religion')->row() : null;
                $profession = !empty($data->profession_id) ? 
                    $this->db->where('profession_id', $data->profession_id)->get('profession')->row() : null;
                $relation = !empty($data->relation_id) ? 
                    $this->db->where('relation_id', $data->relation_id)->get('relation')->row() : null;
                $bank_name = !empty($data->bank_name_id) ? 
                    $this->db->where('bank_name_id', $data->bank_name_id)->get('bank_name')->row() : null;
                
                $bank_details = '';
                if ($bank_name) {
                    $bank_details = $bank_name->name;
                    if (!empty($data->branch_name)) {
                        $bank_details .= ' - ' . $data->branch_name;
                    }
                    if (!empty($data->account_number)) {
                        $bank_details .= ' (A/C: ' . $data->account_number . ')';
                    }
                }
            ?>
                <tr style="<?php echo $sl % 2 == 0 ? 'background-color: #f9f9f9;' : ''; ?>">
                    <!-- SL -->
                    <td style="text-align: center;"><?php echo $sl++; ?></td>
                    
                    <!-- Personal Information -->
                    <td><?php echo htmlspecialchars($data->name ?: 'N/A'); ?></td>
                    <td style="text-align: center;"><?php echo htmlspecialchars($data->unique_id ?: 'N/A'); ?></td>
                    <td><?php echo htmlspecialchars($data->mobile ?: 'N/A'); ?></td>
                    <td><?php echo htmlspecialchars($data->email ?: 'N/A'); ?></td>
                    
                    <!-- Share Information -->
                    <td style="text-align: right;"><?php echo number_format((float)$data->no_of_share); ?></td>
                    <td style="text-align: right;"><?php echo number_format((float)$data->amount_per_share, 2); ?></td>
                    <td style="text-align: center;"><?php echo htmlspecialchars($data->category ?: 'N/A'); ?></td>
                    <td style="text-align: center;"><?php echo !empty($data->date_of_join) ? date('d-m-Y', strtotime($data->date_of_join)) : 'N/A'; ?></td>
                    
                    <!-- Financial Details -->
                    <td style="text-align: right;"><?php echo number_format((float)$data->total_amount, 2); ?></td>
                    <td style="text-align: right;"><?php echo number_format($total_current_value, 2); ?></td>
                    <td style="text-align: right; <?php echo $appreciation >= 0 ? 'color: green;' : 'color: red;'; ?>">
                        <?php echo ($appreciation >= 0 ? '+' : '') . number_format($appreciation, 2); ?>
                    </td>
                    
                    <!-- Nominee Information -->
                    <td><?php echo htmlspecialchars($data->name_of_nominee ?: 'N/A'); ?></td>
                    <td><?php echo $relation ? htmlspecialchars($relation->name) : 'N/A'; ?></td>
                    <td><?php echo htmlspecialchars($data->nominee_mobile ?: 'N/A'); ?></td>
                    <td><?php echo htmlspecialchars($data->nominee_email ?: 'N/A'); ?></td>
                    
                    <!-- Bank & Discounts -->
                    <td><?php echo htmlspecialchars($bank_details ?: 'N/A'); ?></td>
                    <td style="text-align: center;"><?php echo number_format((float)$data->ipd_discount, 1); ?>%</td>
                    <td style="text-align: center;"><?php echo number_format((float)$data->opd_discount, 1); ?>%</td>
                    <td style="text-align: center;"><?php echo number_format((float)$data->test_discount, 1); ?>%</td>
                </tr>
            <?php endforeach; ?>
            
            <!-- Summary Row -->
            <tr style="background-color: #2196F3; color: white; font-weight: bold; height: 35px;">
                <td colspan="5" style="text-align: center; vertical-align: middle;">TOTAL SUMMARY</td>
                <td style="text-align: right;"><?php echo number_format($total_shares); ?></td>
                <td colspan="3"></td>
                <td style="text-align: right;"><?php echo number_format($grand_total_original, 2); ?></td>
                <td style="text-align: right;"><?php echo number_format($grand_total_current, 2); ?></td>
                <td style="text-align: right; <?php echo $total_appreciation >= 0 ? 'color: lightgreen;' : 'color: lightcoral;'; ?>">
                    <?php echo ($total_appreciation >= 0 ? '+' : '') . number_format($total_appreciation, 2); ?>
                </td>
                <td colspan="8"></td>
            </tr>
            
            <!-- Statistics -->
            <tr>
                <td colspan="20">&nbsp;</td>
            </tr>
            <tr style="background-color: #f0f0f0; font-weight: bold;">
                <td colspan="4">Total Shareholders:</td>
                <td style="text-align: right;"><?php echo count($director_data); ?></td>
                <td colspan="15"></td>
            </tr>
            <tr style="background-color: #f0f0f0; font-weight: bold;">
                <td colspan="4">Average Investment per Shareholder:</td>
                <td style="text-align: right;"><?php echo count($director_data) > 0 ? number_format($grand_total_original / count($director_data), 2) : '0.00'; ?></td>
                <td colspan="15"></td>
            </tr>
            <tr style="background-color: #f0f0f0; font-weight: bold;">
                <td colspan="4">Overall Appreciation Percentage:</td>
                <td style="text-align: right;"><?php echo $grand_total_original > 0 ? number_format((($grand_total_current - $grand_total_original) / $grand_total_original) * 100, 2) : '0.00'; ?>%</td>
                <td colspan="15"></td>
            </tr>
        </tbody>
    </table>
    
    <!-- Additional Details Sheet -->
    <br><br>
    <table border="1">
        <thead>
            <tr style="background-color: #FF9800; color: white; font-weight: bold; height: 35px;">
                <td colspan="8" style="text-align: center;">DETAILED PERSONAL INFORMATION</td>
            </tr>
            <tr style="background-color: #ddd; font-weight: bold;">
                <td>Name</td>
                <td>Father's Name</td>
                <td>Mother's Name</td>
                <td>Gender</td>
                <td>NID Number</td>
                <td>Present Address</td>
                <td>Permanent Address</td>
                <td>Nationality</td>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($director_data as $data): 
                $nationality = !empty($data->nationality_id) ? 
                    $this->db->where('nationality_id', $data->nationality_id)->get('nationality')->row() : null;
            ?>
                <tr>
                    <td><?php echo htmlspecialchars($data->name ?: 'N/A'); ?></td>
                    <td><?php echo htmlspecialchars($data->father_name ?: 'N/A'); ?></td>
                    <td><?php echo htmlspecialchars($data->mother_name ?: 'N/A'); ?></td>
                    <td><?php echo htmlspecialchars($data->gender ?: 'N/A'); ?></td>
                    <td><?php echo htmlspecialchars($data->nid_number ?: 'N/A'); ?></td>
                    <td><?php echo htmlspecialchars($data->present_address ?: 'N/A'); ?></td>
                    <td><?php echo htmlspecialchars($data->permanent_address ?: 'N/A'); ?></td>
                    <td><?php echo $nationality ? htmlspecialchars($nationality->name) : 'N/A'; ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>

