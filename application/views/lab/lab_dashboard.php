<link href="<?php echo base_url() ?>css/card_boostrap.css" rel="stylesheet">
<!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> -->

<div class="container-fluid" style="background-color: white; width: 100%;">
    <?php $permissions = $this->session->userdata('permissions'); ?>
    <?php if (in_array('lab_dashboard', $permissions)) { ?>
        <div class="panel panel-primary" style="width: 100%; margin: 0 auto;">
            <div class="panel-heading">
                <h3 style="text-align: center;">Lab Dashboard</h3>
            </div>
            <div class="panel-body">
                <div id="new_test_alert" style="display: none; padding: 10px; text-align: center; background: #28a745; color: white; font-size: 16px;">
                    🔔 New Test Entry Available! Please Check. 🔔
                </div>
                <div id="lab_dashboard_content">
                    <div class="card l-bg-orange-dark" id="lab_dashboard_card" style="width: 100%; margin: 0 auto;">
                        <?php
                        $this->db->select("patient_test_entry.*, GROUP_CONCAT(test.test_name SEPARATOR ', ') AS test_names");
                        $this->db->from("patient_test_entry");
                        $this->db->join("patient_test_entry_details", "patient_test_entry_details.patient_test_entry_id = patient_test_entry.patient_test_entry_id");
                        $this->db->join("test", "test.test_id = patient_test_entry_details.test_id");
                        $this->db->where("patient_test_entry.is_sample_collected", "no");
                        $this->db->group_by("patient_test_entry.patient_test_entry_id");

                        $patient_test_entry = $this->db
    ->order_by('patient_test_entry_id', 'DESC')
    ->limit(100)
    ->get() // specify the table name here
    ->result_array();

                        ?>
                        <table class="table table-bordered table-striped table-hover" id="lab_dashboard_table">
                            <thead>
                                <tr>
                                    <th colspan="8" style="text-align: center;">Sample Collection Status</th>
                                </tr>
                                <tr>
                                    <th>#</th>
                                    <th>Test Entry ID</th>
                                    <th>Patient Name</th>
                                    <th>Test Names</th>
                                    <th>Date</th>
                                    <th>Time</th>
                                    <th>Sample Collection Status</th>
                                    <th>Sample Collect</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $serial = 1;
                                foreach ($patient_test_entry as $entry) {
                                    $patient_test_entry_id = $entry['patient_test_entry_id'];
                                ?>
                                    <tr>
                                        <td><?php echo $serial++; ?></td>
                                        <td><?php echo $entry['invoice_no']; ?></td>
                                        <td><?php echo $entry['patient_name']; ?></td>
                                        <td><?php echo $entry['test_names']; ?></td>
                                        <td><?php echo date('d-m-Y', strtotime($entry['date'])) ?></td>
                                            <td><?php echo $entry['time']; ?></td>
                                        <td><?php echo ucfirst($entry['is_sample_collected']); ?></td>
                                        <td>
                                            <a class="btn btn-primary" href="<?php echo base_url("sample-collect/$patient_test_entry_id") ?>">Collect</a>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    <?php } ?>
</div>
<script>
    $(document).ready(function() {
        let previousTestCount = 0; // Store the previous test count to compare

        function reloadDashboard() {
            $.ajax({
                url: "<?php echo base_url('LabController/reload_dashboard'); ?>",
                type: "GET",
                success: function(response) {
                    console.log("AJAX success! Data received:", response); // Debugging

                    if (response.success) {
                        // Update the lab dashboard content with new HTML
                        $("#lab_dashboard_content").html(response.html);

                        let currentTestCount = response.test_count; // Get new test count

                        // Show toast message only if new test entries are added
                        if (currentTestCount > previousTestCount) {
                            $.toast({
                                heading: 'Success',
                                text: '🔔 New Test Entry Available! Please Check.',
                                showHideTransition: 'slide',
                                position: 'top-right',
                                hideAfter: 1000,
                                icon: 'success'
                            });
                        }

                        // Update the previous test count
                        previousTestCount = currentTestCount;
                    }
                },
                error: function(xhr, status, error) {
                    console.error("AJAX Error:", status, error); // Debugging
                }
            });
        }

        // Reload every 30 seconds
        setInterval(reloadDashboard, 10000);

        // Function to show a toast message
        function showToast(message) {
            const toast = document.createElement("div");
            toast.className = "toast";
            toast.innerText = message;
            document.body.appendChild(toast);

            // Make the toast appear and disappear after 5 seconds
            setTimeout(function() {
                toast.classList.add("show");
            }, 100);

            setTimeout(function() {
                toast.classList.remove("show");
                document.body.removeChild(toast);
            }, 5000);
        }
    });
</script>
