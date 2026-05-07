<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ScriptController
 *
 * @author saiful
 */
class ScriptController extends CI_Controller
{
    public function test_discount_update()
    {
        ini_set('max_execution_time', 99900);
        $patient_test_entries = $this->db->select('*')
            ->where('patient_test_entry_id <=', 313)
            ->get('patient_test_entry')
            ->result();

        foreach ($patient_test_entries as $value) {
            $patient_test_entry_id = $value->patient_test_entry_id;
            $total_discount = $value->total_discount;
            $total_paid = $value->net_total; // Adjust if you store paid value in another field

            // Get all test details
            $patient_test_entry_details = $this->db->where('patient_test_entry_id', $patient_test_entry_id)
                ->get('patient_test_entry_details')
                ->result();

            // Calculate total of all test prices
            $total_price_sum = 0;
            foreach ($patient_test_entry_details as $detail) {
                $total_price_sum += $detail->total_price;
            }

            // Distribute discount and paid proportionally
            foreach ($patient_test_entry_details as $detail) {
                $detail_id = $detail->patient_test_entry_details_id;
                $price = $detail->total_price;

                // Avoid division by zero
                if ($total_price_sum > 0) {
                    $discount_each = ($price / $total_price_sum) * $total_discount;
                    $paid_each = ($price / $total_price_sum) * $total_paid;
                } else {
                    $discount_each = 0;
                    $paid_each = 0;
                }

                $update = [
                    'discount_each' => round($discount_each, 2),
                    'paid_each' => round($paid_each, 2)
                ];

                $this->db->where('patient_test_entry_details_id', $detail_id)
                    ->update('patient_test_entry_details', $update);
            }
        }
    }

    public function doctor_upload()
    {
        ini_set('max_execution_time', 99900);
        $doctor_entry_dos_dos = $this->db->select('*')->get('doctor_entry_dos_1')->result();
        foreach ($doctor_entry_dos_dos as $value) {
            $doctor_name  = $value->doctor_name;
            $expertise  = $value->expertise;
            $specialist  = $value->specialist;
            $department  = $value->department;
            $address  = $value->address;
            $contact  = $value->contact;
            $data = array(
                'doctor_name' => $doctor_name,
                'degree' => $expertise,
                'nationality_id ' => 1,
                'department_id' => $department,
                'doctorspecilization_id' => $specialist,
                'address' => $address,
                'mobile' => $contact,
            );
            $this->db->insert('doctor ', $data);
        }
    }
    public function ipd_service_upload()
    {
        ini_set('max_execution_time', 99900);
        $investigation_list_with_price_dos = $this->db->select('*')->get('investigation_list_with_price')->result();
        foreach ($investigation_list_with_price_dos as $value) {
            $name  = $value->name;
            $price  = $value->price;
            $data = array(
                'name' => $name,
                'price' => $price,
            );
            $this->db->insert('ipd_service_item ', $data);
        }
    }

    public function ipd_service_update()
    {
        ini_set('max_execution_time', 99900);
        $ipd_service_items = $this->db->select('*')->get('ipd_service_item')->result();
        foreach ($ipd_service_items as $value) {
            $name  = trim($value->name);
            $ipd_service_item = $value->ipd_service_item_id;
            $test = $this->db->where('test_name', $name)->get('test')->row();

            $data = array(
                'test_category_id' => $test->test_category_id
            );
            $this->db->where('ipd_service_item_id', $ipd_service_item)->update('ipd_service_item ', $data);
        }
    }
    public function emergency_details_update()
    {
        ini_set('max_execution_time', 99900);
        $emergencies = $this->db->select('*')->get('emergency')->result();
        foreach ($emergencies as $value) {
            $emergency_id  = $value->emergency_id;
            $date  = $value->date;
            $data = array(
                'date' => $date
            );
            $this->db->where('emergency_id', $emergency_id)->update('emergency_details ', $data);
        }
    }
    public function test_upload()
    {
        ini_set('max_execution_time', 99900);
        $investigation_lists_ms_dos = $this->db->select('*')->get('investigation_list_ms_dos')->result();
        foreach ($investigation_lists_ms_dos as $value) {
            $category  = $value->category;
            $status  = $value->status;
            $department_slip  = $value->department_slip;
            $test_name  = $value->test_name;
            $amount  = $value->amount;
            $sub_department  = $value->sub_department;
            $data = array(
                'test_name' => $test_name,
                'price' => $amount,
                'test_sub_group_id' => $sub_department,
                'test_group_id' => $category,
                'test_status_id' => $status,
                'department_slip_id' => $department_slip,
            );
            $this->db->insert('test ', $data);
        }
    }

    public function drug_upload()
    {
        ini_set('max_execution_time', 99900);
        $book1_ms_dos_entries = $this->db->select('*')->get('book1_ms_dos')->result();
        foreach ($book1_ms_dos_entries as $value) {
            $drug_name  = $value->drug_name;
            $opening_stock  = $value->opening_stock;
            $mrp  = $value->mrp;
            $purchase_rate  = $value->purchase_rate;
            $data = array(
                'drug_name' => $drug_name,
                'mrp' => $mrp,
                'purchase_rate' => $purchase_rate,
                'opening_stock' => $opening_stock,
            );
            $this->db->insert('drug ', $data);
        }
    }
    //put your code here
    public function test_update()
    {
        ini_set('max_execution_time', 99900);
        $patient_test_entries = $this->db->select('*')->get('patient_test_entry')->result();
        foreach ($patient_test_entries as $value) {
            $patient_test_entry_id  = $value->patient_test_entry_id;
            $update = array(
                'total_discount' => (float)$value->discount + (float)$value->director_discount
            );
            $this->db->where('patient_test_entry_id ', $patient_test_entry_id)
                ->update('patient_test_entry', $update);
        }
    }
    public function index()
    {
        ini_set('max_execution_time', 99900);
        $manufacturers = $this->db->select('*')->get('manufacturer')->result();
        foreach ($manufacturers as $value) {
            $name  = $value->name;
            $update = array(
                'manufacturer_id' => $value->manufacturer_id
            );
            $this->db->where('manufacturer_id ', $name)
                ->update('medicine_15', $update);
        }
    }
    public function index2()
    {
        ini_set('max_execution_time', 99900);
        $drug_types = $this->db->select('*')->get('drug_type')->result();
        foreach ($drug_types as $value) {
            $type_name  = $value->type_name;
            $update = array(
                'drug_type_id' => $value->drug_type_id
            );
            $this->db->where('drug_type_id ', $type_name)
                ->update('medicine_15', $update);
        }
    }
    public function share_holder_update()
    {
        ini_set('max_execution_time', 99900);
        $share_holders = $this->db->select('*')->get('share_holder')->result();
        $unique_id_series = 1;
        foreach ($share_holders as $value) {
            $name  = $value->name;
            $id  = $value->id;
            $update = array(
                'name' => $name,
                'unique_id' => 'BGHS-' . $unique_id_series
            );
            $this->db->where('id ', $id)
                ->update('share_holder', $update);
            $unique_id_series++;
        }
    }
    public function patient_test_entry_details_update()
    {
        ini_set('max_execution_time', 99900);
        $patient_test_entry_details = $this->db->select('*')->get('patient_test_entry_details')->result();
        foreach ($patient_test_entry_details as $value) {
            $test_id  = $value->test_id;
            $patient_test_entry_details_id  = $value->patient_test_entry_details_id;
            $test = $this->db->where('test_id', $test_id)->get('test')->row();
            $update = array(
                'test_category_id' => $test->test_category_id
            );
            $this->db->where('patient_test_entry_details_id ', $patient_test_entry_details_id)
                ->update('patient_test_entry_details', $update);
        }
    }
    public function index1()
    {
        ini_set('max_execution_time', 99900);
        $retail_customer_sold_product = $this->db->select('*')->get('retail_customer_sold_product')->result();
        foreach ($retail_customer_sold_product as $value) {
            $retial_customer_sold_product_id = $value->retial_customer_sold_product_id;
            $update = array(
                'date' => $value->data_insert_time
            );
            $this->db->where('retial_customer_sold_product_id', $retial_customer_sold_product_id)
                ->update('retail_customer_sold_product', $update);
        }

        //        $whole_customer_sold_product = $this->db->select('*')->get('whole_customer_sold_product')->result();
        //        foreach ($whole_customer_sold_product as $value) {
        //            $whole_customer_sold_product_id = $value->whole_customer_sold_product_id;
        //            $update = array(
        //                'date' => $value->data_insert_time
        //            );
        //            $this->db->where('whole_customer_sold_product_id', $whole_customer_sold_product_id)
        //                    ->update('whole_customer_sold_product', $update);
        //        }
        //        $category = $this->db->select('*')->get('product_category')->result();
        //        foreach ($category as $category_value) {
        //            $models = $this->db->where('product_category_id', $category_value->product_category_id)->get('model')->result();
        //            $colors = 0;
        //            foreach ($models as $models_value) {
        //                $colors_check = $this->db
        //                        ->where('model_id', $models_value->model_id)
        //                        ->get('color');
        //                if ($colors_check->num_rows() > 0) {
        //                    $colors = $colors_check->result();
        //                    break;
        //                }
        //            }
        //            foreach ($models as $value) {
        //                foreach ($colors as $colors_value) {
        //                    $color_exist = $this->db
        //                            ->where('model_id', $value->model_id)
        //                            ->where('color_name', $colors_value->color_name)
        //                            ->get('color');
        //                    if ($color_exist->num_rows() == 0) {
        //                        $data = array(
        //                            'model_id' => $value->model_id,
        //                            'color_name' => $colors_value->color_name
        //                        );
        //                        $this->db->insert('color', $data);
        //                    } else {
        //                        continue;
        //                    }
        //                }
        //            }
        //        }
    }
}
