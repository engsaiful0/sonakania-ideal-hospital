<?php
$today = date('Y-m-d');

if ($to_date != '') {
    $to_date = date('Y-m-d', strtotime($to_date));
}
if ($from_date  != '') {
    $from_date = date('Y-m-d', strtotime($from_date));
}

$this->db->select_sum('payable');
$this->db->from('opd_patient');
if ($from_date != '' and $to_date != '') {
    $this->db
        ->where('entry_date>=', $from_date)
        ->where('entry_date<=', $to_date);
} else if ($from_date != '' && $to_date == '') {
    $this->db
        ->where('entry_date', $from_date);
} else if ($to_date != '' && $from_date == '') {
    $this->db
        ->where('entry_date', $to_date);
}
$query = $this->db->get();
$total_opd_payable_today = $query->row()->payable ?? 0; // Return 0 if no income found





// Apply date filtering condition
// Define the date filtering condition
$date_condition = [
    'date >=' => $from_date,
    'date <=' => $to_date
];

$due_date_condition = [
    'due_payment_date >=' => $from_date,
    'due_payment_date <=' => $to_date
];

if (!empty($from_date) && !empty($to_date)) {
    $this->db->where('bill_date >=', $from_date);
    $this->db->where('bill_date <=', $to_date);
} elseif (!empty($from_date)) {
    $this->db->where('bill_date', $from_date);
} elseif (!empty($to_date)) {
    $this->db->where('bill_date', $to_date);
} else {
    $this->db->where('bill_date', $today); // Default to today's date
}

// Query for Medicine Sales
$this->db->select_sum('paid')->from('medicine_sales');
$query = $this->db->get();
$total_medicine_sells_today = $query->row()->paid ?? 0;

$this->db->select_sum('due_payment')->from('medicine_sales');
$this->db->where($due_date_condition);
$query = $this->db->get();
$total_medicine_sells_due_payment = $query->row()->due_payment ?? 0;

// Query for IPD Patient Paid Amount
$this->db->select_sum('paid_amount')->from('ipd_patient')->where($date_condition);
$query = $this->db->get();
$total_ipd_paid_amount_today = $query->row()->paid_amount ?? 0;

// Query for Emergency Payments
$this->db->select_sum('paid')->from('emergency')->where($date_condition);
$query = $this->db->get();
$total_emergency_today = $query->row()->paid ?? 0;

// Query for Physiotherapy Payments
$this->db->select_sum('paid')->from('phygiotherapy')->where($date_condition);
$query = $this->db->get();
$total_phygiotherapy_today = $query->row()->paid ?? 0;

// Query for Debit Voucher
$this->db->select_sum('total_amount')->from('debit_voucher')->where($date_condition);
$query = $this->db->get();
$total_debit_voucher_today = $query->row()->total_amount ?? 0;

// Query for Credit Voucher
$this->db->select_sum('total_amount')->from('credit_voucher')->where($date_condition);
$query = $this->db->get();
$total_credit_voucher_today = $query->row()->total_amount ?? 0;

// Query for OT Services Paid
$this->db->select_sum('paid')->from('ot_services')->where($date_condition);
$query = $this->db->get();
$total_ot_service_paid_today = $query->row()->paid ?? 0;

// Query for Test Entry (Direct Sales)
$this->db->select_sum('paid')->from('test_collection')->where($date_condition)->where('payment_type', 'from_direct_sales');
$query = $this->db->get();
$total_test_entry_today = $query->row()->paid ?? 0;

// Query for Test Due Collection
$this->db->select_sum('paid')->from('test_collection')->where($date_condition)->where('payment_type', 'from_due_collection');
$query = $this->db->get();
$total_test_due_collection_today = $query->row()->paid ?? 0;

//Test Return Calculation
$this->db->select_sum('total_return');
$this->db->from('patient_test_entry_details');
$this->db->where($date_condition); // Filter by today's date
$query = $this->db->get();
$total_test_return_today = $query->row()->total_return ?? 0; // Return 0 if no income found


// Query for discharge
$this->db->select_sum('paid');
$this->db->from('discharge');
if ($from_date != '' and $to_date != '') {
    $this->db
        ->where('discharge_date>=', $from_date)
        ->where('discharge_date<=', $to_date);
} else if ($from_date != '' && $to_date == '') {
    $this->db
        ->where('discharge_date', $from_date);
} else if ($to_date != '' && $from_date == '') {
    $this->db
        ->where('discharge_date', $to_date);
} else {
    $this->db->where('discharge_date', $today); // Default to today's date
}
$query = $this->db->get();
$total_discharge_value_today = $query->row()->paid ?? 0; // Return 0 if no income found

//Emergency Retrun Amount
$date_condition = [
    'date >=' => $from_date,
    'date <=' => $to_date
];
$this->db->select_sum('returnable_amount');
$this->db->from('emergency');

$this->db->where($date_condition); // Filter by date range
$query = $this->db->get();
$emergency_return_amount = $query->row()->returnable_amount ?? 0;

//Physiotherapy Return Amount
$date_condition = [
    'date >=' => $from_date,
    'date <=' => $to_date
];
$this->db->select_sum('returnable_amount');
$this->db->from('phygiotherapy');

$this->db->where($date_condition); // Filter by date range

$query = $this->db->get();
$phygiotherapy_return_amount = $query->row()->returnable_amount ?? 0;

//OPD Returnable  Amount
$date_condition = [
    'entry_date >=' => $from_date,
    'entry_date <=' => $to_date
];
$this->db->select_sum('returnable_amount');
$this->db->from('opd_patient');
$this->db->where($date_condition); // Filter by today's date
$query = $this->db->get();
$opd_returnable_amount = $query->row()->returnable_amount ?? 0;

//Pharmacy Return Amount
$date_condition = [
    'date >=' => $from_date,
    'date <=' => $to_date
];
$this->db->select_sum('paid');
$this->db->from('medicine_sale_return');

$this->db->where($date_condition); // Filter by date range
$query = $this->db->get();
$medicine_sales_return_amount = $query->row()->paid ?? 0;
//fixed
?>
<table border="1" style="width: 100%;border-collapse:collapse;margin:0 auto;color:black;">

    <tbody>
        <tr>
            <td colspan="5" style="text-align:center">
                <h3 style="text-align: center">Today Summary</h3>
            </td>
        </tr>
        <tr>
            <th>#</th>
            <th>Title</th>
            <th>Income</th>
            <th>Return</th>
            <th>Expense</th>
            <th>Balance</th>
        </tr>
        <tr>
            <td colspan="5" style="text-align: center;">Summary report of date: <b><?php echo date('d-m-Y', strtotime($from_date)) ?> to <?php echo date('d-m-Y', strtotime($to_date)) ?></b></td>
        </tr>
        <tr>
            <td>1</td>
            <td>OPD</td>
            <td><?php echo  number_format($total_opd_payable_today) ?></td>
            <td><?php echo  number_format($opd_returnable_amount) ?></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td>2</td>
            <td>IPD</td>
            <td><?php echo  number_format($total_ipd_paid_amount_today) ?></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td>3</td>
            <td>Discharge</td>
            <td><?php echo  number_format($total_discharge_value_today) ?></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td>4</td>
            <td>Emergency</td>
            <td><?php echo  number_format($total_emergency_today) ?></td>
            <td><?php echo  number_format($emergency_return_amount) ?></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td>5</td>
            <td>Physiotherapy</td>
            <td><?php echo  number_format($total_phygiotherapy_today) ?></td>
            <td><?php echo  number_format($phygiotherapy_return_amount) ?></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td>6</td>
            <td>Test Entry</td>
            <td><?php echo  number_format($total_test_entry_today) ?></td>
            <td><?php echo  number_format($total_test_return_today) ?></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td>7</td>
            <td>Test Due Collection</td>
            <td><?php echo  number_format($total_test_due_collection_today) ?></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td>8</td>
            <td>Pharmacy</td>
            <td><?php echo  number_format($total_medicine_sells_today+$total_medicine_sells_due_payment) ?></td>
            <td><?php echo  number_format($medicine_sales_return_amount) ?></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td>9</td>
            <td>Credit Voucher</td>
            <td><?php echo  number_format($total_credit_voucher_today) ?></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td>10</td>
            <td>OT</td>
            <td><?php echo  number_format($total_ot_service_paid_today) ?></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td>11</td>
            <td>Debit Voucher</td>
            <td></td>
            <td></td>
            <td><?php echo  number_format($total_debit_voucher_today) ?></td>
            <td></td>
        </tr>
        <tr>
            <td></td>
            <td style="text-align:right">Total</td>
            <td><?php echo  number_format($total_ot_service_paid_today + $total_discharge_value_today + $total_test_entry_today + $total_test_due_collection_today + $total_opd_payable_today + $total_ipd_paid_amount_today + $total_emergency_today + $total_phygiotherapy_today + $total_medicine_sells_today+$total_medicine_sells_due_payment + $total_credit_voucher_today) ?></td>
            <td><?php echo  number_format($medicine_sales_return_amount + $opd_returnable_amount + $total_test_return_today + $emergency_return_amount + $phygiotherapy_return_amount) ?></td>
            <td><?php echo  number_format($total_debit_voucher_today) ?></td>
            <td><?php echo  number_format(($total_ot_service_paid_today + $total_discharge_value_today + $total_test_entry_today + $total_test_due_collection_today + $total_opd_payable_today + $total_ipd_paid_amount_today + $total_emergency_today + $total_phygiotherapy_today + $total_medicine_sells_today+$total_medicine_sells_due_payment + $total_credit_voucher_today) - ($medicine_sales_return_amount + $total_debit_voucher_today) - ($opd_returnable_amount + $total_test_return_today + $emergency_return_amount + $phygiotherapy_return_amount)) ?></td>
        </tr>
    </tbody>
</table>