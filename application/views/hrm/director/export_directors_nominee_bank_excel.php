<?php
$company = getCompany();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Directors Nominee & Bank Details - Excel Export</title>
</head>
<body>
    <table border="1">
        <thead>
            <!-- Header -->
            <tr>
                <td colspan="14" align="center" style="font-size: 18px; font-weight: bold; height: 35px;">
                    <?php echo $company ? $company->company_name : 'BGH Soft ERP'; ?>
                </td>
            </tr>
            <tr>
                <td colspan="14" align="center" style="font-size: 14px; font-weight: bold; height: 30px;">
                    Directors Nominee & Banking Details
                </td>
            </tr>
            <tr>
                <td colspan="14" align="center" style="font-size: 11px; height: 25px;">
                    Generated on <?php echo date('d F Y \a\t H:i:s'); ?>
                </td>
            </tr>
            <tr><td colspan="14">&nbsp;</td></tr>
            
            <!-- Headers -->
            <tr style="background-color: #4CAF50; color: white; font-weight: bold; height: 35px;">
                <td rowspan="2" style="text-align: center;">SL</td>
                <td rowspan="2" style="text-align: center;">Director Name</td>
                <td rowspan="2" style="text-align: center;">Unique ID</td>
                <td rowspan="2" style="text-align: center;">Mobile</td>
                <td colspan="5" style="text-align: center;">Nominee Information</td>
                <td colspan="5" style="text-align: center;">Banking Details</td>
            </tr>
            <tr style="background-color: #ddd; font-weight: bold;">
                <!-- Nominee Information -->
                <td>Nominee Name</td>
                <td>Relation</td>
                <td>Mobile</td>
                <td>Email</td>
                <td>Address</td>
                
                <!-- Banking Details -->
                <td>Bank Name</td>
                <td>Branch</td>
                <td>Account Name</td>
                <td>Account Number</td>
                <td>Status</td>
            </tr>
        </thead>
        <tbody>
            <?php
            $sl = 1;
            
            foreach ($director_data as $data):
                // Get foreign key data safely
                $relation = !empty($data->relation_id) ? 
                    $this->db->where('relation_id', $data->relation_id)->get('relation')->row() : null;
                $bank_name = !empty($data->bank_name_id) ? 
                    $this->db->where('bank_name_id', $data->bank_name_id)->get('bank_name')->row() : null;
                
                // Check data completeness
                $nominee_complete = !empty($data->name_of_nominee) && !empty($data->nominee_mobile);
                $bank_complete = !empty($bank_name) && !empty($data->account_number);
                
                $overall_status = '';
                if ($nominee_complete && $bank_complete) $overall_status = 'Complete';
                elseif ($nominee_complete || $bank_complete) $overall_status = 'Partial';
                else $overall_status = 'Incomplete';
            ?>
                <tr style="<?php echo $sl % 2 == 0 ? 'background-color: #f9f9f9;' : ''; ?>">
                    <!-- Basic Info -->
                    <td style="text-align: center;"><?php echo $sl++; ?></td>
                    <td><?php echo htmlspecialchars($data->name ?: 'N/A'); ?></td>
                    <td style="text-align: center;"><?php echo htmlspecialchars($data->unique_id ?: 'N/A'); ?></td>
                    <td><?php echo htmlspecialchars($data->mobile ?: 'N/A'); ?></td>
                    
                    <!-- Nominee Information -->
                    <td><?php echo htmlspecialchars($data->name_of_nominee ?: 'N/A'); ?></td>
                    <td><?php echo $relation ? htmlspecialchars($relation->name) : 'N/A'; ?></td>
                    <td><?php echo htmlspecialchars($data->nominee_mobile ?: 'N/A'); ?></td>
                    <td><?php echo htmlspecialchars($data->nominee_email ?: 'N/A'); ?></td>
                    <td><?php echo htmlspecialchars($data->nominee_present_address ?: 'N/A'); ?></td>
                    
                    <!-- Banking Details -->
                    <td><?php echo $bank_name ? htmlspecialchars($bank_name->name) : 'N/A'; ?></td>
                    <td><?php echo htmlspecialchars($data->branch_name ?: 'N/A'); ?></td>
                    <td><?php echo htmlspecialchars($data->account_name ?: 'N/A'); ?></td>
                    <td><?php echo htmlspecialchars($data->account_number ?: 'N/A'); ?></td>
                    <td style="text-align: center; 
                        <?php if ($overall_status == 'Complete') echo 'color: green; font-weight: bold;';
                              elseif ($overall_status == 'Partial') echo 'color: orange; font-weight: bold;';
                              else echo 'color: red; font-weight: bold;'; ?>">
                        <?php echo $overall_status; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            
            <!-- Summary Row -->
            <tr style="background-color: #2196F3; color: white; font-weight: bold; height: 35px;">
                <td colspan="14" style="text-align: center;">
                    <?php
                    $complete_count = 0;
                    $partial_count = 0;
                    $incomplete_count = 0;
                    
                    foreach ($director_data as $data) {
                        $nominee_complete = !empty($data->name_of_nominee) && !empty($data->nominee_mobile);
                        $bank_name_exists = !empty($data->bank_name_id) ? 
                            $this->db->where('bank_name_id', $data->bank_name_id)->get('bank_name')->row() : null;
                        $bank_complete = !empty($bank_name_exists) && !empty($data->account_number);
                        
                        if ($nominee_complete && $bank_complete) $complete_count++;
                        elseif ($nominee_complete || $bank_complete) $partial_count++;
                        else $incomplete_count++;
                    }
                    ?>
                    SUMMARY: Complete (<?php echo $complete_count; ?>) | 
                    Partial (<?php echo $partial_count; ?>) | 
                    Incomplete (<?php echo $incomplete_count; ?>) | 
                    Total: <?php echo count($director_data); ?>
                </td>
            </tr>
        </tbody>
    </table>
    
    <!-- Missing Information Alert -->
    <br><br>
    <table border="1">
        <thead>
            <tr style="background-color: #FF5722; color: white; font-weight: bold; height: 35px;">
                <td colspan="6" style="text-align: center;">SHAREHOLDERS WITH MISSING INFORMATION</td>
            </tr>
            <tr style="background-color: #ddd; font-weight: bold;">
                <td>Name</td>
                <td>Unique ID</td>
                <td>Missing Nominee Info</td>
                <td>Missing Bank Info</td>
                <td>Priority</td>
                <td>Action Required</td>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($director_data as $data):
                $nominee_issues = [];
                $bank_issues = [];
                
                if (empty($data->name_of_nominee)) $nominee_issues[] = 'Name';
                if (empty($data->nominee_mobile)) $nominee_issues[] = 'Mobile';
                if (empty($data->relation_id)) $nominee_issues[] = 'Relation';
                
                $bank_name_exists = !empty($data->bank_name_id) ? 
                    $this->db->where('bank_name_id', $data->bank_name_id)->get('bank_name')->row() : null;
                if (empty($bank_name_exists)) $bank_issues[] = 'Bank Name';
                if (empty($data->branch_name)) $bank_issues[] = 'Branch';
                if (empty($data->account_number)) $bank_issues[] = 'Account Number';
                
                if (!empty($nominee_issues) || !empty($bank_issues)):
                    $priority = 'Medium';
                    if (count($nominee_issues) >= 2 && count($bank_issues) >= 2) $priority = 'High';
                    elseif (empty($data->name_of_nominee) || empty($bank_name_exists)) $priority = 'High';
                    
                    $action = '';
                    if (!empty($nominee_issues) && !empty($bank_issues)) $action = 'Update Both';
                    elseif (!empty($nominee_issues)) $action = 'Update Nominee';
                    elseif (!empty($bank_issues)) $action = 'Update Banking';
            ?>
                <tr>
                    <td><?php echo htmlspecialchars($data->name ?: 'N/A'); ?></td>
                    <td style="text-align: center;"><?php echo htmlspecialchars($data->unique_id ?: 'N/A'); ?></td>
                    <td><?php echo !empty($nominee_issues) ? implode(', ', $nominee_issues) : '-'; ?></td>
                    <td><?php echo !empty($bank_issues) ? implode(', ', $bank_issues) : '-'; ?></td>
                    <td style="text-align: center; 
                        <?php echo $priority == 'High' ? 'color: red; font-weight: bold;' : 'color: orange;'; ?>">
                        <?php echo $priority; ?>
                    </td>
                    <td style="text-align: center;"><?php echo $action; ?></td>
                </tr>
            <?php 
                endif;
            endforeach; ?>
        </tbody>
    </table>
</body>
</html>

