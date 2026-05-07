<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of RetailCustomerPayment
 *
 * @author saiful
 */
class RetailCustomerPayment extends CI_Controller {

    public function __construct() {
        parent::__construct();
        date_default_timezone_set('Asia/Dhaka');
        $this->load->database();
        $this->load->helper('url');

        $this->load->library('Grocery_crud');
        if ($this->session->userdata('user_id') == '') {
            /// redirect('LoginController');
        }
    }

    //put your code here
    public function add_retail_due_payment() {
        $page_data = array(
            'page_name' => 'retail/add_retail_due_payment',
            'page_title' => 'View Retail Due Payment',
            'sidebar' => 'retail/retail_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function retail_customer_due_history_load() {
        $ids = explode('_', $_POST['retail_customer_sells_id_and_retail_customer_id']);
        $data['retail_customer_sells_id'] = $ids[1];
        $data['retail_customer_id'] = $ids[0];
        $this->load->view('retail/retail_customer_due_history_load', $data);
    }

    public function installment_payment_edit_save() {
        $date = $this->input->post('date');
        $retail_installment_id = $this->input->post('retail_installment_id');
        $retail_customer_id = $this->input->post('retail_customer_id');
        $retail_sell_payment_id = $this->input->post('retail_sell_payment_id');
        $retail_customer_sells_id = $this->input->post('retail_customer_sells_id');
        $bank_id = $this->input->post('bank_id');
        $cash_or_bank = $this->input->post('cash_or_bank');
        $payable_amount = $this->input->post('payable_amount');
        $discount_in_installment = $this->input->post('discount_in_installment');
        $discount_in_installment_edit = $this->input->post('discount_in_installment_edit');
        $retail_installment_amount_edit = $this->input->post('retail_installment_amount_edit');

        //  echo '<pre>';
        //  print_r($payable_amount);
        //  print_r($retail_installment_id);
        //die;
        //$installment_pay = $this->input->post('installment_pay');
        //  echo '<pre>';
        // print_r($payable_amount);
        //  print_r($
        $total_discount = 0;
        ///  print_r($discount_in_installment);
        //  die;
//        for ($i = 0; $i < count($discount_in_installment); $i++) {
//            if ($discount_in_installment[$i] == '')
//                continue;
//            $total_discount = $total_discount + $discount_in_installment[$i];
//        }
//        $total_discount_edit = 0;
//        for ($i = 0; $i < count($discount_in_installment_edit); $i++) {
//            if ($discount_in_installment_edit[$i] == '')
//                continue;
//            $total_discount_edit = $total_discount + $discount_in_installment_edit[$i];
//        }
        $retail_customer_sells = $this->db->where('retail_customer_sells_id', $retail_customer_sells_id)
                        ->get('retail_customer_sells')->row();
        $profit_update = array(
            'profit' => $retail_customer_sells->profit + $discount_in_installment_edit - $discount_in_installment
        );
        $this->db->where('retail_customer_sells_id', $retail_customer_sells_id)
                ->update('retail_customer_sells', $profit_update);
        $total_discount_in_installment = 0;
//        for ($i = 0; $i < count($payable_amount); $i++) {
//            if ($payable_amount[$i] == '')
//                continue;
//            $cash_or_bank = $cash_or_bank[$i];
//            $bank_id = $bank_id[$i];

            $retail_installment = $this->db->where('retail_installment_id', $retail_installment_id)
                            ->get('retail_installment')->row();
            if ($retail_installment->installment_amount == $retail_installment->paid - $retail_installment_amount_edit - $discount_in_installment_edit + $discount_in_installment + $payable_amount) {
                $data = array(
                    'discount_in_installment' => $retail_installment->discount_in_installment - $discount_in_installment_edit + $discount_in_installment,
                    'status' => 'Paid',
                    'paid' => $retail_installment->paid - $retail_installment_amount_edit + $payable_amount,
                    'due' => $retail_installment->due + $retail_installment_amount_edit - $payable_amount + $discount_in_installment_edit - $discount_in_installment,
                );
            } else {
                $data = array(
                    'discount_in_installment' => $retail_installment->discount_in_installment - $discount_in_installment_edit + $discount_in_installment,
                    'paid' => $retail_installment->paid - $retail_installment_amount_edit + $payable_amount,
                    'due' => $retail_installment->due + $retail_installment_amount_edit - $payable_amount + $discount_in_installment_edit - $discount_in_installment,
                );
            }

            $this->db->where('retail_installment_id', $retail_installment_id)
                    ->update('retail_installment', $data);
            $retail_customer = $this->db->where('retail_customer_id', $retail_customer_id)
                    ->get('retail_customer_sells')
                    ->row();
            $retail_customer_id_no = $this->db->where('retail_customer_id', $retail_customer_id)
                    ->get('retail_customer')
                    ->row();
            $current_due = $retail_customer->due + $retail_installment_amount_edit;
            $next_due = $retail_customer->due + $retail_installment_amount_edit + $discount_in_installment_edit - $payable_amount - $discount_in_installment;


            $data_to_sell_payment = array(
                'retail_customer_id' => $retail_customer_id,
                'retail_customer_sells_id' => $retail_customer_sells_id,
                'date' => date('Y-m-d', strtotime($date)),
                'amount' => $payable_amount,
                'retail_installment_id' => $retail_installment_id,
                'collection_type' => 'Installment Collection',
                'id_no' => $retail_customer_id_no->id_no,
                'current_due' => $current_due,
                'next_due' => $next_due,
                'cash_or_bank' => $cash_or_bank,
                'bank_id' => $bank_id
            );
            $this->db
                    ->where('retail_sell_payment_id', $retail_sell_payment_id)
                    ->update('retail_sell_payment', $data_to_sell_payment);
            ///$retail_sell_payment_id = $this->db->insert_id();

            $update_array = array(
                'paid' => $retail_customer->paid - $retail_installment_amount_edit + $payable_amount,
                'due' => $retail_customer->due + $retail_installment_amount_edit + $discount_in_installment_edit - $payable_amount - $discount_in_installment,
                'discount' => $retail_customer->discount + $discount_in_installment - $discount_in_installment_edit,
            );

            $this->db->where('retail_customer_id', $retail_customer_id)
                    ->update('retail_customer_sells', $update_array);

            $sdata['payment'] = 'payment successully';
            $data['retail_customer_sells_id'] = $retail_customer_sells_id;
            $data['retail_sell_payment_id'] = $retail_sell_payment_id;

            $this->session->set_userdata($sdata);

            $this->load->view('retail/print_retail_due_payment_invoice', $data, TRUE);
            $page_data = array(
                'page_name' => 'retail/print_retail_due_payment_invoice',
                'page_title' => 'Print Retail Due Payment',
                'sidebar' => 'retail/retail_sidebar'
            );
            $this->load->view('content', $page_data);       
    }

    public function installment_payment_save() {
        $date = $this->input->post('date');
        $retail_installment_id = $this->input->post('retail_installment_id');
        $retail_customer_id = $this->input->post('retail_customer_id');
        $retail_customer_sells_id = $this->input->post('retail_customer_sells_id');
        $bank_id = $this->input->post('bank_id');
        $cash_or_bank = $this->input->post('cash_or_bank');
        $payable_amount = $this->input->post('payable_amount');
        $discount_in_installment = $this->input->post('discount_in_installment');

        /* installment discount will be lessed from profit start */
        $retail_customer_sells = $this->db->where('retail_customer_sells_id', $retail_customer_sells_id)
                        ->get('retail_customer_sells')->row();
        $total_discount = 0;
        ///  print_r($discount_in_installment);
        //  die;
        for ($i = 0; $i < count($discount_in_installment); $i++) {
            if ($discount_in_installment[$i] == '')
                continue;
            $total_discount = $total_discount + $discount_in_installment[$i];
        }
        $profit_update = array(
            'profit' => $retail_customer_sells->profit - $total_discount
        );
        $this->db->where('retail_customer_sells_id', $retail_customer_sells_id)
                ->update('retail_customer_sells', $profit_update);
        /* installment discount will be lessed from profit end */
        //  echo '<pre>';
        //  print_r($payable_amount);
        //  print_r($retail_installment_id);
        //die;
        //$installment_pay = $this->input->post('installment_pay');
        $total_discount_in_installment = 0;
        for ($i = 0; $i < count($payable_amount); $i++) {
            if ($payable_amount[$i] == '')
                continue;
            $cash_or_bank = $cash_or_bank[$i];
            $bank_id = $bank_id[$i];
            //  echo '<pre>';
            // print_r($payable_amount);
            //  print_r($
            //    $total_discount_in_installment+= $discount_in_installment[$i];

            $retail_installment = $this->db->where('retail_installment_id', $retail_installment_id[$i])
                            ->get('retail_installment')->row();
            if ($retail_installment->installment_amount == $retail_installment->paid + $payable_amount[$i] + $discount_in_installment[$i]) {
                /* installment_amount==previous paid+current paid is equal then paid otherwise due */
                $data = array(
                    'status' => 'Paid',
                    'discount_in_installment' => $retail_installment->discount_in_installment + $discount_in_installment[$i],
                    'paid' => $retail_installment->paid + $payable_amount[$i],
                    'due' => $retail_installment->due - $payable_amount[$i] - $discount_in_installment[$i],
                );
            } else {
                $data = array(
                    'discount_in_installment' => $retail_installment->discount_in_installment + $discount_in_installment[$i],
                    'paid' => $retail_installment->paid + $payable_amount[$i],
                    'due' => $retail_installment->due - $payable_amount[$i] - $discount_in_installment[$i],
                );
            }

            $this->db->where('retail_installment_id', $retail_installment_id[$i])
                    ->update('retail_installment', $data);
            $retail_customer = $this->db->where('retail_customer_id', $retail_customer_id)
                    ->get('retail_customer_sells')
                    ->row();
            $retail_customer_id_no = $this->db->where('retail_customer_id', $retail_customer_id)
                    ->get('retail_customer')
                    ->row();
            $current_due = $retail_customer->due;
            $next_due = $retail_customer->due - $payable_amount[$i] - $discount_in_installment[$i];


            $data_to_sell_payment = array(
                'retail_customer_id' => $retail_customer_id,
                'retail_customer_sells_id' => $retail_customer_sells_id,
                'date' => date('Y-m-d', strtotime($date)),
                'amount' => $payable_amount[$i],
                'retail_installment_id' => $retail_installment_id[$i],
                'collection_type' => 'Installment Collection',
                'id_no' => $retail_customer_id_no->id_no,
                'current_due' => $current_due,
                'next_due' => $next_due,
                'cash_or_bank' => $cash_or_bank,
                'bank_id' => $bank_id
            );
            $this->db->insert('retail_sell_payment', $data_to_sell_payment);
            $retail_sell_payment_id = $this->db->insert_id();

            $update_array = array(
                'paid' => $retail_customer->paid + $payable_amount[$i],
                'due' => $retail_customer->due - $payable_amount[$i] - $discount_in_installment[$i],
                'discount' => $retail_customer->discount + $discount_in_installment[$i],
            );

            $this->db->where('retail_customer_id', $retail_customer_id)
                    ->update('retail_customer_sells', $update_array);

            $sdata['payment'] = 'payment successully';
            $data['retail_customer_sells_id'] = $retail_customer_sells_id;
            $data['retail_sell_payment_id'] = $retail_sell_payment_id;

            $this->session->set_userdata($sdata);

            $this->load->view('retail/print_retail_due_payment_invoice', $data, TRUE);
            $page_data = array(
                'page_name' => 'retail/print_retail_due_payment_invoice',
                'page_title' => 'Print Retail Due Payment',
                'sidebar' => 'retail/retail_sidebar'
            );
            $this->load->view('content', $page_data);
        }
    }

}
