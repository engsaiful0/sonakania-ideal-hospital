
<form action="<?php echo site_url('PatientController/opd_patient_payment_save') ?>"  method="post">


    <table border="1" border="1" style="width: 98%;border-collapse:collapse;margin:0 auto;color:black;margin-top: 30px;">

        <tr>
            <td colspan="8" style="text-align: center">OPD Patient Report From date <b><?php echo date('d-m-Y', strtotime($from_date)); ?></b> To date <b><?php echo date('d-m-Y', strtotime($to_date)); ?></b></td>
        </tr>

        <tr>
            <td>Sl</td>
            <td>Patient Name</td> 
            <td>Age</td>
            <td>Gender</td>  
            <td>Department</td>  
            <td>Doctor</td>  
            <td>Visiting Fee</td>  
            <td>% of Amount</td>  
            <td>Date</td>
        </tr>
        <?php
        $query = '';
        $from = -45;
        if ($doctor_id != '') {
            $from = $from + 45;
            $this->db->select('*');
            $this->db->order_by('doctor_id', 'asc');
            $this->db->where('entry_date>=', date('Y-m-d', strtotime($from_date)))
                    ->where('doctor_id', $doctor_id)
                    ->where('is_doctor_commision_paid', 'no')
                    ->where('entry_date<=', date('Y-m-d', strtotime($to_date)))->where('is_deleted', '0');
            $this->db->from('opd_patient');
            $this->db->limit(45, $from);
            $query_profit = $this->db->get();
            $query = $query_profit->result();
        } else {
            $from = $from + 45;
            $this->db->select('*');
            $this->db->order_by('doctor_id', 'asc');
            $this->db->where('entry_date>=', date('Y-m-d', strtotime($from_date)))
                    ->where('is_doctor_commision_paid', 'no')
                    ->where('entry_date<=', date('Y-m-d', strtotime($to_date)))->where('is_deleted', '0');
            $this->db->from('opd_patient');
            $this->db->limit(45, $from);
            $query_profit = $this->db->get();
            $query = $query_profit->result();
        }
        $grand_percentage = 0;
        $grand_payable = 0;
        $sl = 1;
        foreach ($query as $query_value) {

            $doctor = $this->db->where('doctor_id', $query_value->doctor_id)
                            ->get('doctor')->row();
            $department = $this->db->where('department_id', $query_value->department_id)
                            ->get('department')->row();
            ?>
            <tr>
                <td>
                    <input type="hidden" name="doctor_id" value="<?php echo $query_value->doctor_id ?>">
                    <input type="hidden" name="from_date" value="<?php echo $from_date ?>">
                    <input type="hidden" name="to_date" value="<?php echo $to_date ?>">
                    <input type="hidden" name="opd_patient_id[]" value="<?php echo $query_value->opd_patient_id ?>">
                    <?php echo $sl++ ?>
                </td>
                <td>
                    <?php echo $query_value->opd_patient_name ?>
                </td>

                <td>
                    <?php echo $query_value->age ?>
                </td>
                <td>
                    <?php echo $query_value->gender ?>
                </td>
                <td>
                    <?php echo $department->department_name ?>
                </td>
                <td>
                    <?php echo $doctor->doctor_name ?>
                </td>


                <td>
                    <?php
                    echo $query_value->payable;
                    $grand_payable += $query_value->payable;
                    ?>
                </td>
                <td>
                    <?php
                    echo $query_value->payable * ($doctor->opd_patient_percentage / 100);
                    $grand_percentage += $query_value->payable * ($doctor->opd_patient_percentage / 100);
                    ?>
                </td>
                <td>
                    <?php echo date('d-m-Y', strtotime($query_value->entry_date)) ?>
                </td>
            </tr>
            <?php
        }
        ?>
        <tr>
            <td colspan="5">&nbsp;</td>
            <td colspan="" style="text-align: right;font-weight: bold">Total</td>
            <td style="font-weight: bold"><?php echo $grand_payable ?></td>
            <td style="font-weight: bold"><?php echo $grand_percentage ?></td>
            <td></td>
        </tr>
        <tr>
            <td><input type="submit" value="Save" class="btn btn-primary"></td>
        </tr>
    </table>
    </form>
