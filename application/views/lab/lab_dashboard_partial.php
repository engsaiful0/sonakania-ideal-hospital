<?php
$this->db->select("patient_test_entry.*, GROUP_CONCAT(test.test_name SEPARATOR ', ') AS test_names");
$this->db->from("patient_test_entry");
$this->db->join("patient_test_entry_details", "patient_test_entry_details.patient_test_entry_id = patient_test_entry.patient_test_entry_id");
$this->db->join("test", "test.test_id = patient_test_entry_details.test_id");
$this->db->where("patient_test_entry.is_sample_collected", "no");
$this->db->group_by("patient_test_entry.patient_test_entry_id");

$patient_test_entry = $this->db->get()->result_array();
?>

<table class="table table-bordered table-striped table-hover">
    <thead>
        <tr>
            <th colspan="6" style="text-align: center;">Sample Collection Status</th>
        </tr>
        <tr>
            <th>#</th>
            <th>Test Entry ID</th>
            <th>Patient Name</th>
            <th>Test Names</th>
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
                <td><?php echo ucfirst($entry['is_sample_collected']); ?></td>

                <td>
                    <a class="btn btn-primary" href="<?php echo base_url("sample-collect/$patient_test_entry_id") ?>">Collect</a>
                </td>
            </tr>
        <?php } ?>
    </tbody>
</table>