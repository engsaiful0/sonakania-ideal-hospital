<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of WholeSellController
 *
 * @author saiful
 */
class WholeSellController extends CI_Controller {

    public function __construct() {
        parent::__construct();
        date_default_timezone_set('Asia/Dhaka');
        if ($this->session->userdata('user_id') == '') {
            // redirect('LoginController');
        }
        $this->load->database();
        $this->load->helper('url');

        $this->load->library('Grocery_crud');
        $this->load->library('pagination');
    }

    public function load_product_row() {
        $id = $_POST['id'];
        $data = array(
            'id' => $id
        );
        $this->load->view('whole_sell/load_product_row', $data);
    }

    public function load_product_row_sell_return() {
        $id = $_POST['id'];
        $data = array(
            'id' => $id
        );
        $this->load->view('whole_sell/load_product_row_sell_return', $data);
    }

    public function stock_message() {
        error_reporting(0);
        $product_category_id = $_POST['product_category_id'];
        $model_id = $_POST['model_id'];
        $color_id = $_POST['color_id'];
        $product = $this->db->where('product_category_id', $product_category_id)
                        ->where('model_id', $model_id)
                        ->where('color_id', $color_id)
                        ->get('product')->row();
        if ($product->stock == 0) {
            ?>
            <div class="alert alert-warning">
                <strong>Warning!</strong>Stock of this product is insufficient.
            </div>
            <?php
        } else {
            return;
        }
    }

    public function prodcut_id() {
        error_reporting(0);
        $product_category_id = $_POST['product_category_id'];
        $model_id = $_POST['model_id'];
        $color_id = $_POST['color_id'];
        $product = $this->db->where('product_category_id', $product_category_id)
                        ->where('model_id', $model_id)
                        ->where('color_id', $color_id)
                        ->get('product')->row();
        echo $product->mrp . '_' . $product->product_id . '_' . $product->commision . '_' . $product->purchase_price;
    }

    public function color_stock_load() {
        error_reporting(0);
        $product_category_id = $_POST['product_category_id'];
        $model_id = $_POST['model_id'];
        $color_id = $_POST['color_id'];
        $product = $this->db->where('product_category_id', $product_category_id)
                        ->where('model_id', $model_id)
                        ->where('color_id', $color_id)
                        ->get('product')->row();
        echo $product->retail_sell_price . '_' . $product->stock . '_' . $product->product_id;
    }

    public function whole_sell_edit_save() {
        /* Stock Return during sell update start */
        $whole_customer_sells_id = $this->input->post('whole_customer_sells_id');
        $whole_sell_product = $this->db
                ->where('whole_customer_sells_id', $whole_customer_sells_id)
                ->get('whole_customer_sold_product')
                ->result();
        foreach ($whole_sell_product as $whole_sell_product_value) {
            $product = $this->db->where('product_id', $whole_sell_product_value->product_id)
                            ->get('product')->row();
            $update = array(
                'stock' => $product->stock + $whole_sell_product_value->quantity
            );
            $this->db->where('product_id', $whole_sell_product_value->product_id)
                    ->update('product', $update);
        }

        /* Stock Return during sell update end */
        $delete_update = array('is_deleted' => 1);
        $this->db->where('whole_customer_sells_id', $whole_customer_sells_id)
                ->update('whole_customer_sold_product', $delete_update);
        /* To delete the previouse */

        /* To update dealer account before  start */
        $dealer_id = $this->input->post('dealer_id');
        $dealer = $this->db->where('dealer_id', $dealer_id)
                ->get('dealer')
                ->row();
        $whole_customer_sells = $this->db->where('dealer_id', $dealer_id)
                ->get('whole_customer_sells')
                ->row();

        $update_array = array(
            'paid_amount' => $dealer->paid_amount - $whole_customer_sells->paid,
            'due_amount' => $dealer->due_amount + $whole_customer_sells->paid,
        );
        $due_amount = $dealer->due_amount + $whole_customer_sells->paid;
        $this->db->where('dealer_id', $dealer_id)
                ->update('dealer', $update_array);

        /* To update dealer account end */

        $discount = $this->input->post('discount');
        /* Profit calculation start */
        $commision = $this->input->post('commision');
        $total_price = $this->input->post('total_price');

        /* Profit calculation end */
        $data_sells = array(
            'dealer_id' => $this->input->post('dealer_id'),
            'sub_total' => $this->input->post('sub_total'),
            'discount' => $this->input->post('discount'),
            'net_total' => $this->input->post('net_total'),
            'paid' => $this->input->post('paid'),
            'cash_or_bank' => $this->input->post('cash_or_bank'),
            'bank_id' => $this->input->post('bank_id'),
            'money_receipt_number' => $this->input->post('money_receipt_number'),
            'due' => $this->input->post('due'),
            'profit' => $this->input->post('profit'),
            'date' => date('Y-m-d', strtotime($this->input->post('date'))),
        );

        $this->db->where('whole_customer_sells_id', $whole_customer_sells_id)
                ->update('whole_customer_sells', $data_sells);

        //  if ($paid > 0) {
        $data_sell_payment = array(
            'dealer_id' => $this->input->post('dealer_id'),
            'paid' => $this->input->post('paid'),
            'net_total' => $this->input->post('net_total'),
            'total_due' => $due_amount + $this->input->post('due'),
            'current_due' => $due_amount + $this->input->post('due'),
            'date' => date('Y-m-d', strtotime($this->input->post('date'))),
            'whole_customer_sells_id' => $whole_customer_sells_id,
            'cash_or_bank' => $this->input->post('cash_or_bank'),
            'bank_id' => $this->input->post('bank_id'),
        );
        $this->db->where('whole_customer_sells_id', $whole_customer_sells_id)
                ->update('whole_customer_payment', $data_sell_payment);
        //}

        /* To update dealer account before  start */
        $dealer_id = $this->input->post('dealer_id');
        $dealer = $this->db->where('dealer_id', $dealer_id)
                ->get('dealer')
                ->row();


        $update_array = array(
            'paid_amount' => $dealer->paid_amount + $this->input->post('paid'),
            'due_amount' => $dealer->due_amount - $this->input->post('paid'),
        );
        $this->db->where('dealer_id', $dealer_id)
                ->update('dealer', $update_array);

        /* To update dealer account end */


        $product_category_id = $this->input->post('product_category_id');
        $model_id = $this->input->post('model_id');
        $color_id = $this->input->post('color_id');

        $quantity = $this->input->post('quantity');
        $unit_price = $this->input->post('unit_price');
        $total_price = $this->input->post('total_price');
        $product_id = $this->input->post('product_id');
        $purchase_price = $this->input->post('purchase_price');
        $commision = $this->input->post('commision');
        $profit_each = $this->input->post('profit_each');


        for ($i = 0; $i < count($product_category_id); $i++) {
            /* Update Stock start */
            $product = $this->db->where('product_id', $product_id[$i])
                            ->get('product')->row();
            $update_stock = array(
                'stock' => $product->stock - $quantity[$i]/* Previous stock minus current stock */
            );
            $this->db->where('product_id', $product_id[$i])
                    ->update('product', $update_stock);
            /* Update Stock end */
            $product_array = array(
                'dealer_id' => $this->input->post('dealer_id'),
                'whole_customer_sells_id' => $whole_customer_sells_id,
                'product_category_id' => $product_category_id[$i],
                'model_id' => $model_id[$i],
                'color_id' => $color_id[$i],
                'quantity' => $quantity[$i],
                'unit_price' => $unit_price[$i],
                'total_price' => $total_price[$i],
                'quantity' => $quantity[$i],
                'purchase_price' => $purchase_price[$i],
                'commision' => $commision[$i],
                'profit_each' => $profit_each[$i],
                'date' => date('Y-m-d', strtotime($this->input->post('date')))
            );
            $this->db->insert('whole_customer_sold_product', $product_array);
        }
        $sdata['update'] = 'saved successully';
        $this->session->set_userdata($sdata);
        $dealer_id = $this->input->post('dealer_id');

        $config['base_url'] = site_url('WholeSellController/view_payment');
        $config['total_rows'] = $this->db->count_all('whole_customer_payment');
        $config['per_page'] = "30";
        $config["uri_segment"] = 3;
        $choice = $config["total_rows"] / $config["per_page"];
        $config["num_links"] = floor($choice);
        // integrate bootstrap pagination
        $config['full_tag_open'] = '<ul class="pagination">';
        $config['full_tag_close'] = '</ul>';
        $config['first_link'] = false;
        $config['last_link'] = false;
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';
        $config['prev_link'] = '«';
        $config['prev_tag_open'] = '<li class="prev">';
        $config['prev_tag_close'] = '</li>';
        $config['next_link'] = '»';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active"><a href="#">';
        $config['cur_tag_close'] = '</a></li>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $this->pagination->initialize($config);

        $data['page'] = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

        // get books list
        $data['detailsList'] = $this->ProductModel->get_whole_sell_details($config["per_page"], $data['page'], NULL, $dealer_id);

        $data['pagination'] = $this->pagination->create_links();

        $data['dealer_id'] = $dealer_id;

        $this->load->view('whole_sell/view_whole_sell', $data, true);
        $page_data = array(
            'page_name' => 'whole_sell/view_whole_sell',
            'page_title' => 'View Whole Sell',
            'sidebar' => 'whole_sell/whole_sell_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function edit_whole_sell_return_save() {
        $whole_sell_return_id = $this->input->post('whole_sell_return_id');
        $dealer_id = $this->input->post('dealer_id');
        $discount = $this->input->post('discount');
        /* Profit calculation start */
        $commision = $this->input->post('commision');
        $total_price = $this->input->post('total_price');
        $profit = $this->input->post('profit');
        $profit_each = $this->input->post('profit_each');
        $product_id = $this->input->post('product_id');
        $ids = '';
        for ($i = 0; $i < count($product_id); $i++) {
            $ids = $ids . '*' . $product_id[$i]; /* To get all product ids */
        }
        $data_return = array(
            'dealer_id' => $this->input->post('dealer_id'),
            'product_ids' => $ids,
            'net_total' => $this->input->post('net_total'),
            'sub_total' => $this->input->post('sub_total'),
            'date' => date('Y-m-d', strtotime($this->input->post('date'))),
        );
        $this->db->where('whole_sell_return_id', $whole_sell_return_id)
                ->update('whole_sell_return', $data_return);
        $whole_sell_return = $this->db
                        ->where('whole_sell_return_id', $whole_sell_return_id)
                        ->get('whole_sell_return')->row();
        $delete=array(
            'is_deleted'=>'1'
        );
        $this->db->where('whole_sell_return_id', $whole_sell_return_id)->update('whole_customer_return_sell_product',$delete);
        /* To delete the previous data */

        /* To update dealer account start */
        $dealer = $this->db->where('dealer_id', $dealer_id)
                ->get('dealer')
                ->row();
        $total_amount = 0;
        $paid_amount = 0;
        $due_amount = 0;
        if ($dealer == '') {
            $total_amount = 0;
            $paid_amount = 0;
            $due_amount = 0;
        } else {
            $total_amount = $dealer->total_amount;
            $paid_amount = $dealer->paid_amount;
            $due_amount = $dealer->due_amount;
        }
        // var_dump($paid_amount);
        //  die;

        $update_array = array(
            'total_amount' => $total_amount + $this->input->post('net_total_edit'),
        );
        $this->db->where('dealer_id', $dealer_id)
                ->update('dealer', $update_array); /* Restore the nettotal */

        $update_array = array(
            'total_amount' => $total_amount - $this->input->post('net_total'),
        );
        $this->db->where('dealer_id', $dealer_id)
                ->update('dealer', $update_array); /* Subtract the current net total */

        /* To update dealer account end */

        $product_category_id = $this->input->post('product_category_id');
        $model_id = $this->input->post('model_id');
        $color_id = $this->input->post('color_id');

        $quantity = $this->input->post('quantity');
        $unit_price = $this->input->post('unit_price');
        $total_price = $this->input->post('total_price');
        $product_id = $this->input->post('product_id');
        $purchase_price = $this->input->post('purchase_price');
        $commision = $this->input->post('commision');
//        echo '<pre>';
//        print_r($product_category_id);
        //   die;

        for ($i = 0; $i < count($product_category_id); $i++) {
            /* Update Stock start */
            $product = $this->db->where('product_id', $product_id[$i])
                            ->get('product')->row();
            $update_stock = array(
                'stock' => $product->stock - $quantity_edit[$i]/* Previous stock plus current stock */
            );
            $this->db->where('product_id', $product_id[$i])
                    ->update('product', $update_stock); /* Restor the previous quantity */


            $update_stock = array(
                'stock' => $product->stock + $quantity[$i]/* Previous stock plus current stock */
            );
            $this->db->where('product_id', $product_id[$i])
                    ->update('product', $update_stock);


            /* Update Stock end */
            $product_array = array(
                'dealer_id' => $this->input->post('dealer_id'),
                'whole_sell_return_id' => $whole_sell_return_id,
                'product_category_id' => $product_category_id[$i],
                'unique_id' => $whole_sell_return->unique_id,
                'product_id' => $product_id[$i],
                'model_id' => $model_id[$i],
                'color_id' => $color_id[$i],
                'quantity' => $quantity[$i],
                'unit_price' => $unit_price[$i],
                'total_price' => $total_price[$i],
                'quantity' => $quantity[$i],
                'purchase_price' => $purchase_price[$i],
                'commision' => $commision[$i],
                'profit_each' => $profit_each[$i],
                'date' => date('Y-m-d', strtotime($this->input->post('date'))),
            );
            $this->db->insert('whole_customer_return_sell_product', $product_array);
        }
        $sdata['success'] = 'saved successully';
        $this->session->set_userdata($sdata);
        redirect('WholeSellController/view_whole_sell_return');
    }

    public function add_whole_sell_return_save() {
        $uniqu_id = $this->db->select('*')->get('whole_sell_return');
        $unique_id = 'WR' . str_pad($uniqu_id->num_rows() + 1, 5, '0', STR_PAD_LEFT);
        $dealer_id = $this->input->post('dealer_id');
        $discount = $this->input->post('discount');
        /* Profit calculation start */
        $commision = $this->input->post('commision');
        $total_price = $this->input->post('total_price');
        $profit = $this->input->post('profit');
        $profit_each = $this->input->post('profit_each');
        $product_id = $this->input->post('product_id');
        $ids = '';
        for ($i = 0; $i < count($product_id); $i++) {
            $ids = $ids . '*' . $product_id[$i]; /* To get all product ids */
        }
        $data_return = array(
            'dealer_id' => $this->input->post('dealer_id'),
            'product_ids' => $ids,
            'net_total' => $this->input->post('net_total'),
            'sub_total' => $this->input->post('sub_total'),
            'unique_id' => $unique_id,
            'date' => date('Y-m-d', strtotime($this->input->post('date'))),
        );
        $this->db->insert('whole_sell_return', $data_return);


        /* To update dealer account start */
        $whole_sell_return_id = $this->db->insert_id();



        /* To update dealer account start */
        $dealer = $this->db->where('dealer_id', $dealer_id)
                ->get('dealer')
                ->row();
        $total_amount = 0;
        $paid_amount = 0;
        $due_amount = 0;
        if ($dealer == '') {
            $total_amount = 0;
            $paid_amount = 0;
            $due_amount = 0;
        } else {
            $total_amount = $dealer->total_amount;
            $paid_amount = $dealer->paid_amount;
            $due_amount = $dealer->due_amount;
        }
        // var_dump($paid_amount);
        //  die;

        $update_array = array(
            'total_amount' => $total_amount - $this->input->post('net_total'),
            'paid_amount' => ($paid_amount - $this->input->post('paid')) * 1,
            'due_amount' => $due_amount - $this->input->post('due'),
        );
        $this->db->where('dealer_id', $dealer_id)
                ->update('dealer', $update_array);

        /* To update dealer account end */

        $product_category_id = $this->input->post('product_category_id');
        $model_id = $this->input->post('model_id');
        $color_id = $this->input->post('color_id');

        $quantity = $this->input->post('quantity');
        $unit_price = $this->input->post('unit_price');
        $total_price = $this->input->post('total_price');
        $product_id = $this->input->post('product_id');
        $purchase_price = $this->input->post('purchase_price');
        $commision = $this->input->post('commision');
//        echo '<pre>';
//        print_r($product_category_id);
        //   die;

        for ($i = 0; $i < count($product_category_id); $i++) {
            /* Update Stock start */
            $product = $this->db->where('product_id', $product_id[$i])
                            ->get('product')->row();
            $update_stock = array(
                'stock' => $product->stock + $quantity[$i]/* Previous stock plus current stock */
            );
            $this->db->where('product_id', $product_id[$i])
                    ->update('product', $update_stock);


            /* Update Stock end */
            $product_array = array(
                'dealer_id' => $this->input->post('dealer_id'),
                'whole_sell_return_id' => $whole_sell_return_id,
                'product_category_id' => $product_category_id[$i],
                'unique_id' => $unique_id,
                'product_id' => $product_id[$i],
                'model_id' => $model_id[$i],
                'color_id' => $color_id[$i],
                'quantity' => $quantity[$i],
                'unit_price' => $unit_price[$i],
                'total_price' => $total_price[$i],
                'quantity' => $quantity[$i],
                'purchase_price' => $purchase_price[$i],
                'commision' => $commision[$i],
                'profit_each' => $profit_each[$i],
                'date' => date('Y-m-d', strtotime($this->input->post('date'))),
            );
            $this->db->insert('whole_customer_return_sell_product', $product_array);
        }
        $sdata['success'] = 'saved successully';
        $this->session->set_userdata($sdata);
        $page_data = array(
            'page_name' => 'whole_sell/whole_sell_return',
            'page_title' => 'View Whole Sell',
            'sidebar' => 'whole_sell/whole_sell_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function add_whole_sell_save() {
        $uniqu_id = $this->db->select('*')->get('whole_customer_sells');
        $whole_sell_unique_id = 'WS' . str_pad($uniqu_id->num_rows() + 1, 5, '0', STR_PAD_LEFT);
        $dealer_id = $this->input->post('dealer_id');
        $discount = $this->input->post('discount');
        /* Profit calculation start */
        $commision = $this->input->post('commision');
        $total_price = $this->input->post('total_price');
        $profit = $this->input->post('profit');
        /* Profit calculation end */
        $data_sells = array(
            'dealer_id' => $this->input->post('dealer_id'),
            'sub_total' => $this->input->post('sub_total'),
            'discount' => $this->input->post('discount'),
            'whole_sell_unique_id' => $whole_sell_unique_id,
            'net_total' => $this->input->post('net_total'),
            'cash_or_bank' => $this->input->post('cash_or_bank'),
            'bank_id' => $this->input->post('bank_id'),
            'paid' => $this->input->post('paid'),
            'money_receipt_number' => $this->input->post('money_receipt_number'),
            'due' => $this->input->post('due'),
            'profit' => $profit,
            'date' => date('Y-m-d', strtotime($this->input->post('date'))),
        );
        $this->db->insert('whole_customer_sells', $data_sells);
        $whole_customer_sells_id = $this->db->insert_id();



        /* To update dealer account start */
        $dealer = $this->db->where('dealer_id', $dealer_id)
                ->get('dealer')
                ->row();
        $total_amount = 0;
        $paid_amount = 0;
        $due_amount = 0;
        if ($dealer == '') {
            $total_amount = 0;
            $paid_amount = 0;
            $due_amount = 0;
        } else {
            $total_amount = $dealer->total_amount;
            $paid_amount = $dealer->paid_amount;
            $due_amount = $dealer->due_amount;
        }
        // var_dump($paid_amount);
        //  die;

        $update_array = array(
            'total_amount' => $total_amount + $this->input->post('net_total'),
            'paid_amount' => ($paid_amount + $this->input->post('paid')) * 1,
            'due_amount' => $due_amount + $this->input->post('due'),
        );
        $this->db->where('dealer_id', $dealer_id)
                ->update('dealer', $update_array);
        $paid = $paid_amount + $this->input->post('paid');

        // if ($paid > 0) {
        $data_sell_payment = array(
            'dealer_id' => $this->input->post('dealer_id'),
            'net_total' => $this->input->post('net_total'),
            'paid' => $this->input->post('paid'),
            'total_due' => $due_amount + $this->input->post('due'),
            'current_due' => $due_amount + $this->input->post('due'),
            'date' => date('Y-m-d', strtotime($this->input->post('date'))),
            'whole_customer_sells_id' => $whole_customer_sells_id,
            'cash_or_bank' => $this->input->post('cash_or_bank'),
            'bank_id' => $this->input->post('bank_id'),
        );
        $this->db->insert('whole_customer_payment', $data_sell_payment);
        //  }
        /* To update dealer account end */

        $product_category_id = $this->input->post('product_category_id');
        $model_id = $this->input->post('model_id');
        $color_id = $this->input->post('color_id');

        $quantity = $this->input->post('quantity');
        $unit_price = $this->input->post('unit_price');
        $total_price = $this->input->post('total_price');
        $product_id = $this->input->post('product_id');
        $purchase_price = $this->input->post('purchase_price');
        $commision = $this->input->post('commision');
        $profit_each = $this->input->post('profit_each');
//        echo '<pre>';
//        print_r($product_category_id);
        //   die;

        for ($i = 0; $i < count($product_category_id); $i++) {
            /* Update Stock start */
            $product = $this->db->where('product_id', $product_id[$i])
                            ->get('product')->row();
            $update_stock = array(
                'stock' => $product->stock - $quantity[$i]/* Previous stock minus current stock */
            );
            $this->db->where('product_id', $product_id[$i])
                    ->update('product', $update_stock);


            /* Update Stock end */
            $product_array = array(
                'dealer_id' => $this->input->post('dealer_id'),
                'whole_customer_sells_id' => $whole_customer_sells_id,
                'product_category_id' => $product_category_id[$i],
                'product_id' => $product_id[$i],
                'model_id' => $model_id[$i],
                'color_id' => $color_id[$i],
                'quantity' => $quantity[$i],
                'unit_price' => $unit_price[$i],
                'total_price' => $total_price[$i],
                'quantity' => $quantity[$i],
                'purchase_price' => $purchase_price[$i],
                'commision' => $commision[$i],
                'profit_each' => $profit_each[$i],
                'date' => date('Y-m-d', strtotime($this->input->post('date')))
            );
            $this->db->insert('whole_customer_sold_product', $product_array);
        }
        $data['whole_customer_sells_id'] = $whole_customer_sells_id;
        $this->load->view('whole_sell/print_whole_sell_invoice', $data, TRUE);
        $sdata['success'] = 'saved successully';
        $this->session->set_userdata($sdata);
        $page_data = array(
            'page_name' => 'whole_sell/print_whole_sell_invoice',
            'page_title' => 'Add Whole Sell Invoice Print',
            'sidebar' => 'whole_sell/whole_sell_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function whole_sell_delete($whole_customer_sells_id) {

        /* To update dealer account before  start */
        $whole_customer_sells = $this->db->where('whole_customer_sells_id', $whole_customer_sells_id)
                ->get('whole_customer_sells')
                ->row();

        $dealer_id = $this->input->post('dealer_id');
        $dealer = $this->db->where('dealer_id', $whole_customer_sells->dealer_id)
                ->get('dealer')
                ->row();


        $update_array = array(
            'paid_amount' => $dealer->paid_amount - $whole_customer_sells->paid,
            'due_amount' => $dealer->due_amount + $whole_customer_sells->paid,
        );
        $this->db->where('dealer_id', $dealer_id)
                ->update('dealer', $update_array);

        /* To update dealer account end */
        $data = array(
            'is_deleted' => 1
        );
        $this->db->where('whole_customer_sells_id', $whole_customer_sells_id)
                ->update('whole_customer_sells', $data);

        $this->db->where('whole_customer_sells_id', $whole_customer_sells_id)
                ->update('whole_customer_sold_product', $data);

        $sdata['deleted'] = 'deleted successully';
        $this->session->set_userdata($sdata);
        $page_data = array(
            'page_name' => 'whole_sell/view_whole_sell',
            'page_title' => 'View Whole Sell',
            'sidebar' => 'whole_sell/whole_sell_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function view_whole_sell_return() {
        $dealer_id = $this->input->post('dealer_id');
        $config['base_url'] = site_url('WholeSellController/view_whole_sell_return');
        $config['total_rows'] = $this->db->count_all('whole_customer_return_sell_product');
        $config['per_page'] = "30";
        $config["uri_segment"] = 3;
        $choice = $config["total_rows"] / $config["per_page"];
        $config["num_links"] = floor($choice);
        // integrate bootstrap pagination
        $config['full_tag_open'] = '<ul class="pagination">';
        $config['full_tag_close'] = '</ul>';
        $config['first_link'] = false;
        $config['last_link'] = false;
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';
        $config['prev_link'] = '«';
        $config['prev_tag_open'] = '<li class="prev">';
        $config['prev_tag_close'] = '</li>';
        $config['next_link'] = '»';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active"><a href="#">';
        $config['cur_tag_close'] = '</a></li>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $this->pagination->initialize($config);

        $data['page'] = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

        // get books list
        $data['detailsList'] = $this->ProductModel->get_whole_sell_return_details($config["per_page"], $data['page'], NULL, $dealer_id);

        $data['pagination'] = $this->pagination->create_links();

        $data['dealer_id'] = $dealer_id;

        $this->load->view('whole_sell/view_whole_sell_return', $data, true);

        $page_data = array(
            'page_name' => 'whole_sell/view_whole_sell_return',
            'page_title' => 'View Whole Sell',
            'sidebar' => 'whole_sell/whole_sell_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function add_whole_sell() {
        $page_data = array(
            'page_name' => 'whole_sell/add_whole_sell',
            'page_title' => 'Add Whole Sell',
            'sidebar' => 'whole_sell/whole_sell_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function add_due_payment_save() {
        $dealer_id = $this->input->post('dealer_id');
        $data = array();
        $data['dealer_id'] = $this->input->post('dealer_id');
        $data['paid'] = $this->input->post('total_amount');
        $data['cash_or_bank'] = $this->input->post('cash_or_bank');
        $data['bank_id'] = $this->input->post('bank_id');
        $data['total_due'] = $this->input->post('total_due');
        $data['current_due'] = $this->input->post('current_due');

        $data['date'] = date('Y-m-d', strtotime($this->input->post('date')));
        $this->db->insert('whole_customer_payment', $data);

        /* To update dealer account start */
        $dealer = $this->db->where('dealer_id', $dealer_id)
                ->get('dealer')
                ->row();

        $update_array = array(
            'paid_amount' => $dealer->paid_amount + $this->input->post('total_amount'),
            'due_amount' => $dealer->due_amount - $this->input->post('total_amount'),
        );
        $this->db->where('dealer_id', $dealer_id)
                ->update('dealer', $update_array);
        /* To update dealer account end */
        $sdata['success'] = 'saved successully';
        $this->session->set_userdata($sdata);

        $page_data = array(
            'page_name' => 'whole_sell/due_payment',
            'page_title' => 'Add Due Payment',
            'sidebar' => 'whole_sell/whole_sell_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function due_payment() {
        $page_data = array(
            'page_name' => 'whole_sell/due_payment',
            'page_title' => 'Add Due Payment',
            'sidebar' => 'whole_sell/whole_sell_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    function due_history_load() {
        $dealer_id = $_POST['dealer_id'];
        $dealer = $this->db->where('dealer_id', $dealer_id)
                ->get('dealer')
                ->row();
        echo $dealer->due_amount;
    }

    public function view_whole_sell() {
        $dealer_id = $this->input->post('dealer_id');

        $config['base_url'] = site_url('WholeSellController/view_payment');
        $config['total_rows'] = $this->db->count_all('whole_customer_payment');
        $config['per_page'] = "30";
        $config["uri_segment"] = 3;
        $choice = $config["total_rows"] / $config["per_page"];
        $config["num_links"] = floor($choice);
        // integrate bootstrap pagination
        $config['full_tag_open'] = '<ul class="pagination">';
        $config['full_tag_close'] = '</ul>';
        $config['first_link'] = false;
        $config['last_link'] = false;
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';
        $config['prev_link'] = '«';
        $config['prev_tag_open'] = '<li class="prev">';
        $config['prev_tag_close'] = '</li>';
        $config['next_link'] = '»';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active"><a href="#">';
        $config['cur_tag_close'] = '</a></li>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $this->pagination->initialize($config);

        $data['page'] = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

        // get books list
        $data['detailsList'] = $this->ProductModel->get_whole_sell_details($config["per_page"], $data['page'], NULL, $dealer_id);

        $data['pagination'] = $this->pagination->create_links();

        $data['dealer_id'] = $dealer_id;

        $this->load->view('whole_sell/view_whole_sell', $data, true);
        $page_data = array(
            'page_name' => 'whole_sell/view_whole_sell',
            'page_title' => 'View Whole Sell',
            'sidebar' => 'whole_sell/whole_sell_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function whole_sell_edit($whole_customer_sells_id) {
        $data['whole_customer_sells_id'] = $whole_customer_sells_id;
        $this->load->view('whole_sell/whole_sell_edit', $data);
    }

    public function whole_sell_return_edit($whole_sell_return_id) {
        $data['whole_sell_return_id'] = $whole_sell_return_id;
        $this->load->view('whole_sell/whole_sell_return_edit', $data);
    }

    public function whole_sell_details($whole_customer_sells_id) {
        $data['whole_customer_sells_id'] = $whole_customer_sells_id;
        $this->load->view('whole_sell/print_whole_sell_invoice', $data);
    }

    public function whole_sell_return() {
        $page_data = array(
            'page_name' => 'whole_sell/whole_sell_return',
            'page_title' => 'View Whole Sell',
            'sidebar' => 'whole_sell/whole_sell_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function whole_sell_payment_edit($whole_customer_payment_id) {
        $data['whole_customer_payment_id'] = $whole_customer_payment_id;
        $this->load->view('whole_sell/whole_sell_payment_edit', $data);
    }

    public function edit_due_payment_save() {
        $whole_customer_payment_id = $this->input->post('whole_customer_payment_id');
        $dealer_id = $this->input->post('dealer_id');
        /* To update dealer account start */
        $whole_customer_payment = $this->db->where('whole_customer_payment_id', $whole_customer_payment_id)
                ->get('whole_customer_payment')
                ->row();

        $dealer = $this->db->where('dealer_id', $whole_customer_payment->dealer_id)
                ->get('dealer')
                ->row();

        $update_array = array(
            'paid_amount' => $dealer->paid_amount - $whole_customer_payment->paid,
            'due_amount' => $dealer->due_amount + $whole_customer_payment->paid,
        );
        $this->db->where('dealer_id', $dealer_id)
                ->update('dealer', $update_array);
        /* To update dealer account end */
        $data = array();
        $data['dealer_id'] = $this->input->post('dealer_id');
        $data['paid'] = $this->input->post('paid');
        $data['cash_or_bank'] = $this->input->post('cash_or_bank');
        $data['bank_id'] = $this->input->post('bank_id');
        $data['total_due'] = $this->input->post('total_due');
        $data['current_due'] = $this->input->post('current_due');

        $data['date'] = date('Y-m-d', strtotime($this->input->post('date')));
        $this->db->where('whole_customer_payment_id', $whole_customer_payment_id)
                ->update('whole_customer_payment', $data);

        /* To update dealer account start */
        $dealer = $this->db->where('dealer_id', $dealer_id)
                ->get('dealer')
                ->row();

        $update_array = array(
            'paid_amount' => $dealer->paid_amount + $this->input->post('total_amount'),
            'due_amount' => $dealer->due_amount - $this->input->post('total_amount'),
        );
        $this->db->where('dealer_id', $dealer_id)
                ->update('dealer', $update_array);
        /* To update dealer account end */
        $sdata['success'] = 'saved successully';
        $this->session->set_userdata($sdata);

        redirect('WholeSellController/view_payment');
    }

    public function whole_customer_payment_delete($whole_customer_payment_id) {

        /* To update dealer account before  start */
        $whole_customer_payment = $this->db->where('whole_customer_payment_id', $whole_customer_payment_id)
                ->get('whole_customer_payment')
                ->row();

        $dealer_id = $this->input->post('dealer_id');
        $dealer = $this->db->where('dealer_id', $whole_customer_payment->dealer_id)
                ->get('dealer')
                ->row();


        $update_array = array(
            'paid_amount' => $dealer->paid_amount - $whole_customer_payment->paid,
            'due_amount' => $dealer->due_amount + $whole_customer_payment->paid,
        );
        $this->db->where('dealer_id', $dealer_id)
                ->update('dealer', $update_array);

        /* To update dealer account end */
        $data = array(
            'is_deleted' => 1
        );


        $this->db->where('whole_customer_payment_id', $whole_customer_payment_id)
                ->update('whole_customer_payment', $data);

        $sdata['deleted'] = 'deleted successully';
        $this->session->set_userdata($sdata);
        redirect('WholeSellController/view_payment');
    }

    public function view_payment() {
        $dealer_id = $this->input->post('dealer_id');

        $config['base_url'] = site_url('WholeSellController/view_payment');
        $config['total_rows'] = $this->db->count_all('whole_customer_payment');
        $config['per_page'] = "30";
        $config["uri_segment"] = 3;
        $choice = $config["total_rows"] / $config["per_page"];
        $config["num_links"] = floor($choice);
        // integrate bootstrap pagination
        $config['full_tag_open'] = '<ul class="pagination">';
        $config['full_tag_close'] = '</ul>';
        $config['first_link'] = false;
        $config['last_link'] = false;
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';
        $config['prev_link'] = '«';
        $config['prev_tag_open'] = '<li class="prev">';
        $config['prev_tag_close'] = '</li>';
        $config['next_link'] = '»';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active"><a href="#">';
        $config['cur_tag_close'] = '</a></li>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $this->pagination->initialize($config);

        $data['page'] = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

        // get books list
        $data['detailsList'] = $this->ProductModel->get_whole_customer_payment_details($config["per_page"], $data['page'], NULL, $dealer_id);

        $data['pagination'] = $this->pagination->create_links();

        $data['dealer_id'] = $dealer_id;

        $this->load->view('whole_sell/view_payment', $data, true);
        $page_data = array(
            'page_name' => 'whole_sell/view_payment',
            'page_title' => 'View Product',
            'sidebar' => 'whole_sell/whole_sell_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function bank_name_load() {
        $bank_or_cash = $_POST['bank_or_cash'];
        if ($bank_or_cash == 'Bank') {
            $bank = $this->db->select('*')->get('bank')->result();
            ?>
            <option selected="" disabled="">Select Bank</option>
            <?php
            foreach ($bank as $bank_value) {
                ?>
                <option value="<?php echo $bank_value->bank_id ?>"><?php echo $bank_value->bank_name . '-' . $bank_value->account_number ?></option>
                <?php
            }
        } else {
            ?>
            <option selected="" disabled="">Select Bank</option>
            <?php
        }
    }

}
