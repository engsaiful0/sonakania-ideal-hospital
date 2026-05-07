<?php
$patient_test_entry_id = $this->session->userdata('patient_test_entry_id');
?>


<div class="container-fluid" style=" background-color: white;width: 100%;">
    <div class="panel panel-primary" style="width: 100%;margin: 0 auto">
        <div class="panel-heading">
            <h3 style="text-align: center">My Reports</h3>
        </div>
        <div class="panel-body">
            <?php
            $patient_test_entry = $this->db->select('*')->where('patient_test_entry_id', $patient_test_entry_id)->get('patient_test_entry')->row();
            $this->db->select_sum('paid');
            $this->db->from('test_collection');
            $this->db->where('payment_type', 'from_due_collection');
            $this->db->where('patient_test_entry_id', $patient_test_entry_id);
            $this->db->where('date', date('Y-m-d')); // Filter by today's date
            $query = $this->db->get();
            $total_test_due_collection = $query->row()->paid ?? 0; // Return 0 if no income found

            $this->db->select_sum('paid');
            $this->db->from('test_collection');
            $this->db->where('payment_type', 'from_direct_sales');
            $this->db->where('patient_test_entry_id', $patient_test_entry_id);
            $this->db->where('date', date('Y-m-d')); // Filter by today's date
            $query = $this->db->get();
            $total_test_sell_collection = $query->row()->paid ?? 0; // Return 0 if no income found
            $status = '';

            if ($patient_test_entry->net_total == ($total_test_due_collection + $total_test_sell_collection)) {
                $status = "Paid";
            } else {
                $status = "Unpaid";
            }

            ?>
            <?php if (isset($patient_test_entry)) : ?>
                <table class="table table-hover table-bordered table-condensed">
                    <tr>
                        <td>#</td>
                        <td>Patient</td>
                        <td>Test</td>
                        <td>Date</td>
                        <td>Status</td>
                        <?php
                        if ($status == 'Paid') {
                        ?>
                            <td>View</td>
                        <?php
                        }
                        ?>

                    </tr>
                    <?php

                    $patient_test_entry_details = $this->db->select('*')
                        ->where('patient_test_entry_id', $patient_test_entry_id)
                        ->get('patient_test_entry_details')
                        ->result();

                    $test_names = [];

                    foreach ($patient_test_entry_details as $details) {
                        $test = $this->db->where('test_id', $details->test_id)
                            ->get('test')
                            ->row();

                        if ($test) {
                            $test_names[] = $test->test_name; // Assuming test_name is the column for test name
                        }
                    }
                    $test_names_string = implode(', ', $test_names);
                    // Now $test_names contains all test names
                    // print_r($test_names);
                    $sl = 1;

                    ?>
                    <tr>
                        <td><?php echo $sl++ ?></td>
                        <td><?php echo $patient_test_entry->patient_name ?></td>
                        <td><?php echo $test_names_string ?></td>
                        <td><?php echo date('d-m-Y', strtotime($patient_test_entry->date)) ?></td>
                        <td><?php echo $status ?></td>
                        <?php
                        if ($status == 'Paid') {
                        ?>
                            <td>
                                <a class="btn btn-primary" href="<?php echo base_url('customer/view_report/' . $patient_test_entry->patient_test_entry_id) ?>">View</a>
                            </td>
                        <?php
                        }
                        ?>
                    </tr>
                </table>

            <?php else : ?>
                <p style="text-align:center">No records found.</p>
            <?php endif; ?>

        </div>
    </div>
</div>