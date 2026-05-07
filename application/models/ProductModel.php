<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of SaleModel
 *
 * @author sohag
 */
class ProductModel extends CI_Model {
    public function get_emergency($limit, $offset) {
        $query = $this->db->get('emergency', $limit, $offset);
        return $query->result();
    }

    public function count_all_emergency() {
        return $this->db->count_all('emergency');
    }

    
    function opd_patient_details($limit, $start = '', $doctor_id = '', $gender = '', $department_id = '', $from_date = '', $to_date='') {

        if ($department_id != '' && $doctor_id != '' && $gender != '' && $from_date != '' && $to_date != '') {

            $sql = "select  * from opd_patient where department_id='$department_id' AND doctor_id='$doctor_id' AND gender='$gender' AND entry_date>=$from_date AND entry_date<= $to_date and is_deleted='0' order by opd_patient_id desc limit " . $start . ", " . $limit;
        } else if ($department_id != '' && $doctor_id == '' && $gender != '' && $from_date != '' && $to_date != '') {
            $sql = "select  * from opd_patient where department_id='$department_id' AND gender='$gender' AND entry_date>=$from_date AND entry_date<= $to_date and is_deleted='0' order by opd_patient_id desc limit " . $start . ", " . $limit;
        } else if ($department_id != '' && $doctor_id != '' && $gender == '' && $from_date != '' && $to_date != '') {

            $sql = "select  * from opd_patient where department_id='$department_id' AND doctor_id='$doctor_id'  AND entry_date>=$from_date AND entry_date<= $to_date and is_deleted='0' order by opd_patient_id desc limit " . $start . ", " . $limit;
        } else if ($department_id != '' && $doctor_id != '' && $gender != '' && $from_date == '' && $to_date == '') {

            $sql = "select  * from opd_patient where department_id='$department_id' AND doctor_id='$doctor_id' AND gender='$gender'  and is_deleted='0' order by opd_patient_id desc limit " . $start . ", " . $limit;
        } else if ($department_id != '' && $doctor_id != '' && $gender == '' && $from_date == '' && $to_date == '') {

            $sql = "select  * from opd_patient where department_id='$department_id' AND doctor_id='$doctor_id'  and is_deleted='0' order by opd_patient_id desc limit " . $start . ", " . $limit;
        } else if ($department_id != '' && $doctor_id == '' && $gender == '' && $from_date != '' && $to_date != '') {

            $sql = "select  * from opd_patient where department_id='$department_id' AND entry_date>=$from_date AND entry_date<= $to_date and is_deleted='0' order by opd_patient_id desc limit " . $start . ", " . $limit;
        } else if ($department_id == '' && $doctor_id != '' && $gender = '' && $from_date != '' && $to_date != '') {

            $sql = "select  * from opd_patient where doctor_id='$doctor_id' AND entry_date>=$from_date AND entry_date<= $to_date and is_deleted='0' order by opd_patient_id desc limit " . $start . ", " . $limit;
        } else if ($department_id == '' && $doctor_id == '' && $gender != '' && $from_date != '' && $to_date != '') {

            $sql = "select  * from opd_patient where  gender='$gender' AND entry_date>=$from_date AND entry_date<= $to_date and is_deleted='0' order by opd_patient_id desc limit " . $start . ", " . $limit;
        } else if ($department_id != '' && $doctor_id != '' && $gender != '' && $from_date != '' && $to_date != '') {

            $sql = "select  * from opd_patient where department_id='$department_id' AND doctor_id='$doctor_id' AND gender='$gender' AND entry_date>=$from_date AND entry_date<= $to_date and is_deleted='0' order by opd_patient_id desc limit " . $start . ", " . $limit;
        } else if ($department_id != '' && $doctor_id == '' && $gender == '' && $from_date == '' && $to_date == '') {

            $sql = "select  * from opd_patient where department_id='$department_id'  and is_deleted='0' order by opd_patient_id desc limit " . $start . ", " . $limit;
        } else if ($department_id == '' && $doctor_id != '' && $gender == '' && $from_date == '' && $to_date == '') {

            $sql = "select  * from opd_patient where  doctor_id='$doctor_id' and is_deleted='0' order by opd_patient_id desc limit " . $start . ", " . $limit;
        } else if ($department_id == '' && $doctor_id == '' && $gender != '' && $from_date == '' && $to_date == '') {

            $sql = "select  * from opd_patient where  gender='$gender' and is_deleted='0' order by opd_patient_id desc limit " . $start . ", " . $limit;
        } else if ($department_id == '' && $doctor_id == '' && $gender == '' && $from_date != '' && $to_date != '') {

            $sql = "select  * from opd_patient where entry_date>=$from_date AND entry_date<= $to_date and is_deleted='0' order by opd_patient_id desc limit " . $start . ", " . $limit;
        } else if ($department_id == '' && $doctor_id == '' && $gender == '' && $from_date == '' && $to_date == '') {

            $sql = "select  * from opd_patient where is_deleted='0' order by opd_patient_id desc limit " . $start . ", " . $limit;
        }

        $query = $this->db->query($sql);
        //var_dump($query->result());
        return $query->result();
    }

    function prescription_details($limit, $start = '', $patient_name = '', $gender = '', $date = '', $invoice_no = '') {
        if ($invoice_no != '') {

            $sql = "select  * from prescription where invoice_no='$invoice_no' and is_deleted='0' order by prescription_id desc limit " . $start . ", " . $limit;
        }
        if ($patient_name != '') {

            $sql = "select  * from prescription where patient_name='$patient_name' and is_deleted='0' order by prescription_id desc limit " . $start . ", " . $limit;
        }
        if ($gender != '') {
            //  var_dump($gender);
            $sql = "select  * from prescription where gender='$gender' and is_deleted='0' order by prescription_id desc limit " . $start . ", " . $limit;
        }
        if ($date != '') {
            //var_dump($date);
            $sql = "select  * from prescription where date='$date' and is_deleted='0' order by prescription_id desc limit " . $start . ", " . $limit;
        }
        if ($patient_name == '' && $gender == '' && $date == '' && $invoice_no == '') {
            $sql = "select  * from prescription where is_deleted='0' order by prescription_id desc limit " . $start . ", " . $limit;
        }
        $query = $this->db->query($sql);
        //var_dump($query->result());
        return $query->result();
    }

    function patient_details($limit, $start = '', $patient_name = '', $mobile_number = '', $patient_unique_id = '', $gender = '', $reference_media_id = '', $general_bed_id = '', $cabin_id = '', $date = '', $doctor_id = '') {
        $sql = '';
        if ($patient_name != '') {
            $sql = "select  * from patient where patient_name='$patient_name' and is_deleted='0' order by patient_id desc limit " . $start . ", " . $limit;
        }
        if ($mobile_number != '') {
            $sql = "select  * from patient where mobile_number='$mobile_number' and is_deleted='0' order by patient_id desc limit " . $start . ", " . $limit;
        }
        if ($patient_unique_id != '') {
            $sql = "select  * from patient where patient_unique_id='$patient_unique_id' and is_deleted='0' order by patient_id desc limit " . $start . ", " . $limit;
        }
        if ($gender != '') {
            $sql = "select  * from patient where gender='$gender' and is_deleted='0' order by patient_id desc limit " . $start . ", " . $limit;
        }
        if ($reference_media_id != '') {
            $sql = "select  * from patient where reference_media_id='$reference_media_id' and is_deleted='0' order by patient_id desc limit " . $start . ", " . $limit;
        }
        if ($general_bed_id != '') {
            $sql = "select  * from patient where general_bed_id='$general_bed_id' and is_deleted='0' order by patient_id desc limit " . $start . ", " . $limit;
        }
        if ($cabin_id != '') {
            $sql = "select  * from patient where cabin_id='$cabin_id' and is_deleted='0' order by patient_id desc limit " . $start . ", " . $limit;
        }
        if ($date != '') {
            $sql = "select  * from patient where date='$date' and is_deleted='0' order by patient_id desc limit " . $start . ", " . $limit;
        }
        if ($doctor_id != '') {
            $sql = "select  * from patient where doctor_id='$doctor_id' and is_deleted='0' order by patient_id desc limit " . $start . ", " . $limit;
        }
        if ($patient_name == '' && $mobile_number == '' && $patient_unique_id == '' && $gender == '' && $reference_media_id == '' && $general_bed_id == '' && $cabin_id == '' && $date == '' && $doctor_id == '') {
            $sql = "select  * from patient where is_deleted='0' order by patient_id desc limit " . $start . ", " . $limit;
        }
        //var_dump($sql);
        //  }
        $query = $this->db->query($sql);
// var_dump($query->result());
        return $query->result();
    }

    function patient_test_entry_details($limit, $start, $patient_test_entry_id = '', $patient_name = '', $mobile = '') {
        $sql = '';
        //print_r($patient_test_entry_id);
        //// die;
        if ($patient_test_entry_id != '') {
            $sql = "select  * from patient_test_entry where patient_test_entry_id='$patient_test_entry_id' and is_deleted='0' order by patient_test_entry_id desc limit " . $start . ", " . $limit;
        }
        if ($patient_name != '') {
            $sql = "select  * from patient_test_entry where patient_test_entry_id='$patient_name' and is_deleted='0' order by patient_test_entry_id desc limit " . $start . ", " . $limit;
        }
        if ($mobile != '') {
            $sql = "select  * from patient_test_entry where patient_test_entry_id='$mobile' and is_deleted='0' order by patient_test_entry_id desc limit " . $start . ", " . $limit;
        }
        if ($mobile == '' && $patient_test_entry_id == '' && $patient_name == '') {
            $sql = "select  * from patient_test_entry where is_deleted='0' order by patient_test_entry_id desc limit " . $start . ", " . $limit;
        }
//        echo '<pre>';
//        print_r($sql);
//        die;
        $query = $this->db->query($sql);
// var_dump($query->result());
        return $query->result();
    }

    function get_test_result_details($limit, $start, $test_group_id, $test_id, $patient_test_entry_id) {

        if ($test_group_id != '' && $test_id == '' && $patient_test_entry_id != '') {
            $sql = "select  * from test_result where test_group_id='$test_group_id' AND test_id='$test_id' AND  and is_deleted='0'  order by test_result_id desc limit " . $start . ", " . $limit;
        } else if ($test_group_id != '' && $test_id != '' && $patient_test_entry_id == '') {
            $sql = "select  * from test_result where test_id='$test_id' AND test_group_id='$test_group_id'  and is_deleted='0' order by test_result_id desc limit " . $start . ", " . $limit;
        } else if ($test_group_id != '' && $test_id == '' && $patient_test_entry_id != '') {
            $sql = "select  * from test_result where patient_test_entry_id='$patient_test_entry_id' AND test_group_id='$test_group_id'  and is_deleted='0' order by test_result_id desc limit " . $start . ", " . $limit;
        } else if ($test_group_id == '' && $test_id == '' && $patient_test_entry_id != '') {
            $sql = "select  * from test_result where patient_test_entry_id='$patient_test_entry_id'  and is_deleted='0' order by test_result_id desc limit " . $start . ", " . $limit;
        } else if ($test_group_id != '' && $test_id == '' && $patient_test_entry_id == '') {
            $sql = "select  * from test_result where test_group_id='$test_group_id'  and is_deleted='0' order by test_result_id desc limit " . $start . ", " . $limit;
        } else if ($test_group_id == '' && $test_id != '' && $patient_test_entry_id == '') {
            $sql = "select  * from test_result where test_id='$test_id'  and is_deleted='0' order by test_result_id desc limit " . $start . ", " . $limit;
        } else if ($test_group_id == '' && $test_id == '' && $patient_test_entry_id == '') {
            $sql = "select  * from test_result where is_deleted='0' order by test_result_id desc limit " . $start . ", " . $limit;
        }
        $query = $this->db->query($sql);
// var_dump($query->result());
        return $query->result();
    }

    function doctors_payment_details($limit, $start, $doctor_id = "") {

        if ($doctor_id != '') {
            $sql = "select  * from doctor_commission_payment where doctor_id='$doctor_id' and is_deleted='0' order by doctor_commission_payment_id desc limit " . $start . ", " . $limit;
        } else {
            $sql = "select  * from doctor_commission_payment where is_deleted='0' order by doctor_commission_payment_id desc limit " . $start . ", " . $limit;
        }
        $query = $this->db->query($sql);
// var_dump($query->result());
        return $query->result();
    }

  

    function get_test_configuration_details($limit, $start, $test_group_id = "", $test_id = "") {
        if ($test_group_id != '' && $test_id != '') {
            $sql = "select  * from test_configuration where test_group_id='$test_group_id' AND test_id='$test_id'  and is_deleted='0' order by test_configuration_id desc limit " . $start . ", " . $limit;
        } else if ($test_group_id != '' && $test_id == '') {
            $sql = "select  * from test_configuration where test_group_id='$test_group_id'  and is_deleted='0' order by test_configuration_id desc limit " . $start . ", " . $limit;
        } else if ($test_group_id == '' && $test_id != '') {
            $sql = "select  * from test_configuration where test_id='$test_id'  and is_deleted='0' order by test_configuration_id desc limit " . $start . ", " . $limit;
        } else if ($test_group_id == '' && $test_id == '') {
            $sql = "select  * from test_configuration where is_deleted='0' order by test_configuration_id desc limit " . $start . ", " . $limit;
        }
        $query = $this->db->query($sql);
// var_dump($query->result());
        return $query->result();
    }

    function get_sells_details($limit, $start, $bill_no = "") {
        if ($bill_no != '') {
            $sql = "select  * from sales where is_deleted='0' and bill_no='$bill_no' order by sales_id desc limit " . $start . ", " . $limit;
        } else {
            $sql = "select  * from sales where is_deleted='0' order by sales_id desc limit " . $start . ", " . $limit;
        }
        $query = $this->db->query($sql);
// var_dump($query->result());
        return $query->result();
    }

    function get_purchase_details($limit, $start, $st = NULL, $mrr = "") {
        if ($mrr != '') {
            $sql = "select  * from purchase where is_deleted='0' and mrr='$mrr' order by purchase_id desc limit " . $start . ", " . $limit;
        } else {
            $sql = "select  * from purchase where is_deleted='0' order by purchase_id desc limit " . $start . ", " . $limit;
        }
        $query = $this->db->query($sql);
// var_dump($query->result());
        return $query->result();
    }

    public function get_retail_due_payment_details($limit, $start, $st = NULL, $retail_customer_id = "", $id_no = "") {
        if ($retail_customer_id != '' && $id_no == '') {
            $sql = "select  * from retail_sell_payment where collection_type='Installment Collection' and  retail_customer_id='$retail_customer_id'  order by retail_sell_payment_id desc limit " . $start . ", " . $limit;
        } else if ($retail_customer_id == '' && $id_no != '') {
            $sql = "select  * from retail_sell_payment where collection_type='Installment Collection' and  retail_customer_id='$id_no'  order by retail_sell_payment_id desc limit " . $start . ", " . $limit;
        } else {
            $sql = "select  * from retail_sell_payment where collection_type='Installment Collection' order by retail_sell_payment_id desc limit " . $start . ", " . $limit;
        }
        $query = $this->db->query($sql);
// var_dump($query->result());
        return $query->result();
    }

    public function get_retail_customer_sell_details_in_cash($limit, $start, $st = NULL, $retail_customer_unique_id = '', $id_no = '', $retail_customer_name = '') {
        $sql = '';
//  var_dump($retail_customer_unique_id . '<br>');
//die;
        if ($retail_customer_unique_id != '' && $id_no == '' && $retail_customer_name == '') {
            $sql = "select  * from retail_customer_sells where is_deleted='0' and cash_or_installment='Cash' and retail_customer_id='$retail_customer_unique_id'  order by retail_customer_sells_id desc limit " . $start . ", " . $limit;
        } else if ($retail_customer_unique_id == '' && $id_no != '' && $retail_customer_name == '') {
            $sql = "select  * from retail_customer_sells where is_deleted='0' and cash_or_installment='Cash' and retail_customer_id='$id_no'  order by retail_customer_sells_id desc limit " . $start . ", " . $limit;
        } else if ($retail_customer_unique_id == '' && $id_no == '' && $retail_customer_name != '') {
            $sql = "select  * from retail_customer_sells where is_deleted='0' and cash_or_installment='Cash' and retail_customer_id='$retail_customer_name'  order by retail_customer_sells_id desc limit " . $start . ", " . $limit;
        } else if ($retail_customer_unique_id == '' && $id_no == '' && $retail_customer_name == '') {
            $sql = "select  * from retail_customer_sells where is_deleted='0' and cash_or_installment='Cash' order by retail_customer_sells_id desc limit " . $start . ", " . $limit;
        }


        $query = $this->db->query($sql);
//   var_dump($query->result());
//  die;

        return $query->result();
    }

    public function get_retail_sell_return_details($limit, $start, $st = NULL, $retail_customer_name = '') {
        $sql = '';
        if ($retail_customer_name != '') {
            $sql = "select  * from retail_customer_sells_return_product where is_deleted='0' and  and retail_customer_id='$retail_customer_name'  order by retail_customer_sells_return_product_id desc limit " . $start . ", " . $limit;
        } else {
            $sql = "select  * from retail_customer_sells_return_product where is_deleted='0'  order by retail_customer_sells_return_product_id desc limit " . $start . ", " . $limit;
        }
        $query = $this->db->query($sql);
        return $query->result();
    }

    public function get_retail_customer_sell_details_installment($limit, $start, $st = NULL, $retail_customer_unique_id = '', $id_no = '', $retail_customer_name = '') {
        $sql = '';
//  print_r($retail_customer_unique_id . '<br>');
//   print_r($id_no . '<br>');
// print_r($retail_customer_name . '<br>');
// die;
        if ($retail_customer_unique_id != '' && $id_no == '' && $retail_customer_name == '') {
            $sql = "select  * from retail_customer_sells where is_deleted='0' and cash_or_installment='Installment' and retail_customer_id='$retail_customer_unique_id'  order by retail_customer_sells_id desc limit " . $start . ", " . $limit;
        } else if ($retail_customer_unique_id == '' && $id_no != '' && $retail_customer_name == '') {
            $sql = "select  * from retail_customer_sells where is_deleted='0' and cash_or_installment='Installment' and retail_customer_id='$id_no'  order by retail_customer_sells_id desc limit " . $start . ", " . $limit;
        } else if ($retail_customer_unique_id == '' && $id_no == '' && $retail_customer_name != '') {
            $sql = "select  * from retail_customer_sells where is_deleted='0' and cash_or_installment='Installment' and retail_customer_id='$retail_customer_name'  order by retail_customer_sells_id desc limit " . $start . ", " . $limit;
        } else if ($retail_customer_unique_id == '' && $id_no == '' && $retail_customer_name == '') {

            $sql = "select  * from retail_customer_sells where is_deleted='0' and cash_or_installment='Installment' order by retail_customer_sells_id desc limit " . $start . ", " . $limit;
        }



        $query = $this->db->query($sql);
//    var_dump($query->result());
//  die;

        return $query->result();
    }

    public function get_whole_sell_details($limit, $start, $st = NULL, $dealer_id = "") {
        if ($dealer_id != '') {
            $sql = "select  * from whole_customer_sells where dealer_id='$dealer_id' and is_deleted='0'  order by whole_customer_sells_id desc limit " . $start . ", " . $limit;
        } else {
            $sql = "select  * from whole_customer_sells where is_deleted='0'  order by whole_customer_sells_id desc limit " . $start . ", " . $limit;
        }
        $query = $this->db->query($sql);
// var_dump($query->result());
        return $query->result();
    }

    public function get_whole_sell_return_details($limit, $start, $st = NULL, $dealer_id = "") {
        if ($dealer_id != '') {
            $sql = "select  * from whole_customer_return_sell_product where dealer_id='$dealer_id' and is_deleted='0'  order by whole_customer_return_sell_product_id desc limit " . $start . ", " . $limit;
        } else {
            $sql = "select  * from whole_customer_return_sell_product where is_deleted='0'  order by whole_customer_return_sell_product_id desc limit " . $start . ", " . $limit;
        }
        $query = $this->db->query($sql);
// var_dump($query->result());
        return $query->result();
    }

    public function get_whole_customer_payment_details($limit, $start, $st = NULL, $dealer_id = "") {

        if ($dealer_id != '') {
            $sql = "select  * from whole_customer_payment where dealer_id='$dealer_id' and is_deleted='0'  order by dealer_id asc limit " . $start . ", " . $limit;
        } else {
            $sql = "select  * from whole_customer_payment where is_deleted='0'  order by dealer_id asc limit " . $start . ", " . $limit;
        }
        $query = $this->db->query($sql);
// var_dump($query->result());
        return $query->result();
    }

    function get_retail_customer_details($limit, $start, $st = NULL, $retail_customer_unique_id = '', $id_no = '', $retail_customer_name = '') {

        $sql = '';
        if ($retail_customer_name != '') {
            $sql = "select  * from retail_customer where retail_customer_id='$retail_customer_name' and is_deleted=0 order by retail_customer_id asc limit " . $start . ", " . $limit;
        } else if ($retail_customer_unique_id != '') {
            $sql = "select  * from retail_customer where retail_customer_id='$retail_customer_unique_id' and is_deleted=0 order by retail_customer_id asc limit " . $start . ", " . $limit;
        } else if ($id_no != '') {
            $sql = "select  * from retail_customer where retail_customer_id='$id_no' and is_deleted=0 order by retail_customer_id asc limit " . $start . ", " . $limit;
        } else {
            $sql = "select  * from retail_customer where is_deleted=0 order by retail_customer_id desc limit " . $start . ", " . $limit;
        }
        $query = $this->db->query($sql);
        return $query->result();
    }

    function purchase_product_details($limit, $start, $st = NULL, $product_category_id = '', $model_id = '', $color_id = '') {
        if ($st == "NULL")
            $st = "";
        $sql = '';
        if ($product_category_id != '' && $model_id != '' && $color_id != '') {
            $sql = "select  * from purchase_product where product_category_id='$product_category_id' and model_id='$model_id' and color_id='$color_id' and is_deleted=0 order by purchase_product_id desc limit " . $start . ", " . $limit;
        }
        if ($product_category_id != '' && $model_id != '' && $color_id == '') {
            $sql = "select  * from purchase_product where product_category_id='$product_category_id' and model_id='$model_id' and is_deleted=0 order by purchase_product_id desc limit " . $start . ", " . $limit;
        }
        if ($product_category_id != '' && $model_id == '' && $color_id == '') {
            $sql = "select  * from purchase_product where product_category_id='$product_category_id' and is_deleted=0 order by purchase_product_id desc limit " . $start . ", " . $limit;
        } else if ($product_category_id == '' && $model_id == '' && $color_id == '') {
            $sql = "select  * from purchase_product where is_deleted=0 order by purchase_product_id desc limit " . $start . ", " . $limit;
        }
        $query = $this->db->query($sql);
        return $query->result();
    }

}
