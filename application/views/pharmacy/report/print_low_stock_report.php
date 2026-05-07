<style>
    @media print {
        body * {
            visibility: hidden;
        }

        #report,
        #report * {
            visibility: visible;
        }

        #report {
            position: absolute;
            left: 0;
            top: 0;
            margin: 20px;
            padding: 0;
            width: 100%;
        }

        .no-print {
            display: none !important;
        }

        table {
            page-break-inside: auto;
        }

        tr {
            page-break-inside: avoid;
            page-break-after: auto;
        }

        html,
        body {
            margin: 0;
            padding: 0;
        }
    }

    .print-header {
        text-align: center;
        margin-bottom: 20px;
    }

    .print-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }

    .print-table th,
    .print-table td {
        border: 1px solid #000;
        padding: 8px;
        text-align: left;
        font-size: 12px;
    }

    .print-table th {
        background-color: #f0f0f0;
        font-weight: bold;
    }

    .print-footer {
        margin-top: 20px;
        text-align: center;
        font-size: 10px;
    }

    .low-stock {
        background-color: white;
        color: black;
    }

    .out-of-stock {
        background-color: whitesmoke;
        color: black;
    }

    .alert-box {
        background-color: #f8d7da;
        border: 1px solid #f5c6cb;
        color: #721c24;
        padding: 15px;
        margin: 20px 0;
        border-radius: 4px;
    }
</style>

<div class="row no-print">
    <div class="col-md-12">
        <button onclick="window.print()" class="btn btn-primary">Print</button>
        <button onclick="window.close()" class="btn btn-default">Close</button>
    </div>
</div>

<div id="report">
    <?php
    $company = $this->db->where('company_id', '1')->get('company')->row();
    ?>

    <div class="print-header">
        <?php if (!empty($company->logo)): ?>
            <img src="<?php echo base_url() ?>assets/images/<?php echo $company->logo ?>" 
                 style="width: 100px; margin-bottom: 10px;" alt="Company Logo">
        <?php endif; ?>
        
        <h2><?php echo $company->company_name ?? 'Hospital Management System' ?></h2>
        <p><?php echo $company->address ?? '' ?></p>
        <p>
            <?php if (!empty($company->email)): ?>Email: <?php echo $company->email ?><?php endif; ?>
            <?php if (!empty($company->web)): ?>, Web: <?php echo $company->web ?><?php endif; ?>
        </p>
        <h3 style="margin-top: 20px; color: #dc3545;">🚨 Medicine Low Stock Report</h3>
        <p>Generated on: <?php echo date('d-m-Y H:i:s') ?></p>
    </div>

    <?php if (!empty($low_stock_medicines) && is_array($low_stock_medicines)): ?>
        
        <?php
        // Calculate summary
        $total_shortage_value = 0;
        $out_of_stock_count = 0;
        foreach ($low_stock_medicines as $medicine) {
            $total_shortage_value += ($medicine->reorder_quantity - $medicine->current_stock) * ($medicine->purchase_rate ?? 0);
            if ($medicine->current_stock <= 0) {
                $out_of_stock_count++;
            }
        }
        ?>

        <div class="alert-box">
            <strong>⚠️ URGENT ATTENTION REQUIRED</strong><br>
            <strong>Total Low Stock Items:</strong> <?php echo count($low_stock_medicines); ?> |
            <strong>Out of Stock Items:</strong> <?php echo $out_of_stock_count; ?> |
            <strong>Total Shortage Value:</strong> ৳<?php echo number_format($total_shortage_value, 2); ?>
        </div>

        <table class="print-table">
            <thead>
                <tr>
                    <th style="width: 5%;">Sl</th>
                    <th style="width: 25%;">Medicine Name</th>
                    <th style="width: 10%;">Current Stock</th>
                    <th style="width: 10%;">Reorder Qty</th>
                    <th style="width: 10%;">Shortage</th>
                    <th style="width: 10%;">MRP</th>
                    <th style="width: 10%;">Purchase Rate</th>
                    <th style="width: 10%;">Shortage Value</th>
                    <th style="width: 10%;">Status</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sl = 1;
                foreach ($low_stock_medicines as $medicine):
                    $shortage = $medicine->reorder_quantity - $medicine->current_stock;
                    $shortage_value = $shortage * ($medicine->purchase_rate ?? 0);
                    $status = ($medicine->current_stock <= 0) ? 'OUT OF STOCK' : 'LOW STOCK';
                    $row_class = ($medicine->current_stock <= 0) ? 'out-of-stock' : 'low-stock';
                ?>
                <tr class="<?php echo $row_class; ?>">
                    <td><?php echo $sl++; ?></td>
                    <td><strong><?php echo htmlspecialchars($medicine->drug_name); ?></strong></td>
                    <td style="text-align: center;">
                        <strong><?php echo number_format($medicine->current_stock); ?></strong>
                    </td>
                    <td style="text-align: center;"><?php echo number_format($medicine->reorder_quantity); ?></td>
                    <td style="text-align: center; color: black;">
                        <strong><?php echo number_format($shortage); ?></strong>
                    </td>
                    <td style="text-align: right;">৳<?php echo number_format($medicine->mrp ?? 0, 2); ?></td>
                    <td style="text-align: right;">৳<?php echo number_format($medicine->purchase_rate ?? 0, 2); ?></td>
                    <td style="text-align: right;">৳<?php echo number_format($shortage_value, 2); ?></td>
                    <td style="text-align: center;">
                        <strong><?php echo $status; ?></strong>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="print-footer">
            <div class="alert-box" style="margin-top: 30px;">
                <strong>📋 SUMMARY</strong><br>
                <strong>Total Low Stock Medicines:</strong> <?php echo count($low_stock_medicines); ?> |
                <strong>Total Items Out of Stock:</strong> <?php echo $out_of_stock_count; ?> |
                <strong>Total Required Investment:</strong> ৳<?php echo number_format($total_shortage_value, 2); ?>
            </div>
            
            <p style="margin-top: 30px;">
                <span style="float: left;">Software By: Bijoylab, www.bijoylab.com</span>
                <span style="float: right;">Generated By: <?php echo $this->session->userdata('user_name') ?? 'System'; ?></span>
                <div style="clear: both;"></div>
            </p>
        </div>

    <?php else: ?>
        <div class="alert-box" style="background-color: #d4edda; border-color: #c3e6cb; color: #155724;">
            <h3 style="color: #28a745;">✅ EXCELLENT NEWS!</h3>
            <p><strong>No Low Stock Medicines Found</strong></p>
            <p>All medicines are adequately stocked above their reorder levels.</p>
            <p>Your pharmacy inventory management is on track!</p>
        </div>
    <?php endif; ?>
</div>

<script>
    // Auto print when page loads
    window.onload = function() {
        // Small delay to ensure everything is loaded
        setTimeout(function() {
            window.print();
        }, 500);
    };
</script>
