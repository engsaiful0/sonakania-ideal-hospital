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
        <h3 style="margin-top: 20px;">Doctor Serial Report</h3>
        <p>Generated on: <?php echo date('d-m-Y H:i:s') ?></p>
    </div>

    <?php if (!empty($selected_serials) && is_array($selected_serials)): ?>
        <table class="print-table">
            <thead>
                <tr>
                    <th style="width: 5%;">Sl</th>
                    <th style="width: 15%;">Patient Name</th>
                    <th style="width: 10%;">Mobile</th>
                    <th style="width: 10%;">Age</th>
                    <th style="width: 8%;">Gender</th>
                    <th style="width: 15%;">Doctor</th>
                    <th style="width: 8%;">Serial</th>
                    <th style="width: 12%;">Visiting Date</th>
                    <th style="width: 12%;">Entry Date</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sl = 1;
                foreach ($selected_serials as $serial):
                    $doctor = $this->db->where('doctor_id', $serial->doctor_id)->get('doctor')->row();
                    $department = $this->db->where('department_id', $serial->department_id)->get('department')->row();
                ?>
                <tr>
                    <td><?php echo $sl++; ?></td>
                    <td><?php echo htmlspecialchars($serial->patient_name ?? ''); ?></td>
                    <td><?php echo htmlspecialchars($serial->mobile_number ?? ''); ?></td>
                    <td>
                        <?php
                        $age_parts = [];
                        if ($serial->age_year > 0) {
                            $age_parts[] = $serial->age_year . ' ' . ($serial->age_year == 1 ? 'Year' : 'Years');
                        }
                        if ($serial->age_month > 0) {
                            $age_parts[] = $serial->age_month . ' ' . ($serial->age_month == 1 ? 'Month' : 'Months');
                        }
                        if ($serial->age_day > 0) {
                            $age_parts[] = $serial->age_day . ' ' . ($serial->age_day == 1 ? 'Day' : 'Days');
                        }
                        echo implode(' ', $age_parts);
                        ?>
                    </td>
                    <td><?php echo htmlspecialchars($serial->gender ?? ''); ?></td>
                    <td>
                        <?php echo htmlspecialchars($doctor->doctor_name ?? ''); ?>
                        <?php if (!empty($doctor->degree)): ?>
                            <br><small><?php echo htmlspecialchars($doctor->degree); ?></small>
                        <?php endif; ?>
                    </td>
                    <td><?php echo htmlspecialchars($serial->serial_numaber ?? ''); ?></td>
                    <td>
                        <?php echo date('d-m-Y', strtotime($serial->visiting_date)); ?>
                        <?php if (!empty($serial->visiting_time)): ?>
                            <br><?php echo $serial->visiting_time; ?>
                        <?php endif; ?>
                    </td>
                    <td><?php echo date('d-m-Y', strtotime($serial->entry_date)); ?></td>
                  
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="print-footer">
            <p><strong>Total Records: <?php echo count($selected_serials); ?></strong></p>
            <p style="margin-top: 30px;">
                <span style="float: left;">Software By: Bijoylab, www.bijoylab.com</span>
                <span style="float: right;">Printed By: <?php echo $this->session->userdata('user_name') ?? 'System'; ?></span>
                <div style="clear: both;"></div>
            </p>
        </div>

    <?php else: ?>
        <div class="print-header">
            <p style="color: red;">No records found to print.</p>
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
