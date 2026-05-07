<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of RetialSaleController
 *
 * @author saiful
 */
class RetailSaleController extends CI_Controller {

    public function __construct() {
        parent::__construct();
        date_default_timezone_set('Asia/Dhaka');
        $this->load->database();
        $this->load->helper('url');
        if ($this->session->userdata('user_id') == '') {
            // redirect('LoginController');
        }
        $this->load->library('Grocery_crud');
        $this->load->library('pagination');
        $this->load->library('image_lib');
    }

    public function index() {
        $page_data = array(
            'page_name' => 'retail/add_retail_sale',
            'page_title' => 'Add Retail Sale',
            'sidebar' => 'retail/retail_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function retail_sell_return() {
        $page_data = array(
            'page_name' => 'retail/retail_sell_return',
            'page_title' => 'Add Retail Sale',
            'sidebar' => 'retail/retail_sidebar'
        );
        $this->load->view('content', $page_data);
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
        echo $product->retail_sell_price . '_' . $product->stock . '_' . $product->product_id . '_' . $product->purchase_price;
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

    public function no_of_installment_amount_field_load() {
        $no_of_installment = $_POST['no_of_installment'];


        $date = date('Y-m-d');
        for ($i = 1; $i <= $no_of_installment; $i++) {

            $date = date("Y-m-d", strtotime("+30 day", strtotime($date)));
            ?>
            <div class="form-group">
                <label class="control-label col-sm-4" for="pwd">Installment (<?php echo $i ?>)</label>
                <div class="col-sm-4">          
                    <input type="text"   class="form-control "  id="datepicker<?php echo $i ?>"  name="installment_date[]">
                </div>
                <div class="col-sm-4">          
                    <input type="text"  class="form-control"  id="installment_amount_<?php echo $i ?>"  name="installment_amount[]">
                </div>
            </div>
            <?php
        }
    }

    public function load_product_row_sell_return() {
        $data['id'] = $_POST['id'];
        $this->load->view('retail/load_product_row_sell_return', $data);
    }

    public function retail_sell_return_edit($retail_sell_return_id) {
        $data['retail_sell_return_id'] = $retail_sell_return_id;
        $this->load->view('retail/retail_sell_return_edit', $data);
    }

    public function load_product_row() {
        $data['id'] = $_POST['id'];
        $this->load->view('retail/load_product_row', $data);
    }

    public function load_installment_row() {
        $id = $_POST['id'];
        ?>
        <tr id="tr_<?php echo $id ?>" style="margin-top:3px;">
            <td>
                <input type="text" required="" readonly="" class="form-control datepicker"   id="installment_date_<?php echo $id ?>" placeholder="Installment Date" name="installment_date[]">
            </td>
            <td>
                <input type="text"   class="form-control"  id="installment_amount_<?php echo $id ?>" oninput="total_calculation()" placeholder="Enter Installment Amount" name="installment_amount[]">
            </td>

            <td><input type="button" onclick="SomeDeleteRowFunction(this)" style="width:50px" readonly id="add_more_<?php echo $id ?>" title="Click TO Remove"  value="-"  ></td>
        </tr>
        <?php
    }

    public function retail_customer_details($retail_customer_id) {
        $data = array(
            'retail_customer_id' => $retail_customer_id
        );
        $this->load->view('retail/retail_customer_details', $data);
    }

    public function edit_retail_sell_save() {
        $retail_customer_sells_id = $this->input->post('retail_customer_sells_id');
        //print_r($retail_customer_sells_id);
        // die;
        $retail_customer_id = $this->input->post('retail_customer_id');
        /* Customer Sells Table Start */
        /* Profit calculation start */
        $purchase_price = $this->input->post('purchase_price');
        $total_price = $this->input->post('total_price');

        /* Profit calculation end */
        if ($this->input->post('number_of_installment') == '') {/* Cash or installment identification */
            $cash_or_installment = 'Cash';
        } else {
            $cash_or_installment = 'Installment';
        }
        $data_sells = array(
            'retail_customer_id' => $retail_customer_id,
            'sub_total' => $this->input->post('sub_total'),
            'discount' => $this->input->post('discount'),
            'net_total' => $this->input->post('net_total'),
            'paid' => $this->input->post('paid'),
            'cash_or_bank' => $this->input->post('cash_or_bank'),
            'bank_id' => $this->input->post('bank_id'),
            'due' => $this->input->post('due'),
            'id_no' => $this->input->post('id_no'),
            'profit' => $this->input->post('profit'),
            'form_fee' => $this->input->post('form_fee'),
            'number_of_installment' => $this->input->post('number_of_installment'),
            'cash_or_installment' => $cash_or_installment,
            'date' => date('Y-m-d', strtotime($this->input->post('date'))),
        );
        $this->db->where('retail_customer_sells_id', $retail_customer_sells_id)
                ->update('retail_customer_sells', $data_sells);

        /* Customer Sells Table End */

        /* Product Return start */
        $retail_customer_sells_product = $this->db->where('retail_customer_sells_id', $retail_customer_sells_id)
                ->get('retail_customer_sold_product')
                ->result();
        foreach ($retail_customer_sells_product as $retail_customer_sells_value) {
            $retail_sells_unique_id = $retail_customer_sells_value->retail_sells_unique_id;
            $product_id = $retail_customer_sells_value->product_id;
            $quantity = $retail_customer_sells_value->quantity;
            $product = $this->db->where('product_id', $product_id)
                    ->get('product')
                    ->row();
            $update_stock = array(
                'stock' => $product->stock + $quantity,
            );
            $this->db->where('product_id', $product_id)
                    ->update('product', $update_stock);
        }
        /* Delete Previous Product start */
        $update_deleted = array(
            'is_deleted' => '1',
        );

        $this->db->where('retail_customer_sells_id', $retail_customer_sells_id)
                ->update('retail_customer_sold_product', $update_deleted);
        //   print_r($retail_customer_sells_id);
        //    die;
        /* Delete Previous Product end */
        /* Product Return end */
        /* Sells Payment Table start update */
        $data_sells_payment = array(
            'retail_customer_id' => $retail_customer_id,
            'amount' => $this->input->post('paid'),
            'collection_type' => 'From sells',
            'current_due' => $this->input->post('due'),
            'id_no' => $this->input->post('id_no'),
            'date' => date('Y-m-d', strtotime($this->input->post('date'))),
            'retail_customer_sells_id' => $retail_customer_sells_id,
        );
        $this->db->where('retail_customer_sells_id', $retail_customer_sells_id)
                ->update('retail_sell_payment', $data_sells_payment);
        /* Sells Payment Table End */

        $product_category_id = $this->input->post('product_category_id');
        $model_id = $this->input->post('model_id');
        $color_id = $this->input->post('color_id');

        $quantity = $this->input->post('quantity');
        $unit_price = $this->input->post('unit_price');
        $total_price = $this->input->post('total_price');
        $product_id = $this->input->post('product_id');
        $purchase_price = $this->input->post('purchase_price');
//        echo '<pre>';
//        print_r($product_category_id);
//          die;

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
                'retail_customer_id' => $retail_customer_id,
                'retail_customer_sells_id' => $retail_customer_sells_id,
                'product_category_id' => $product_category_id[$i],
                'model_id' => $model_id[$i],
                'color_id' => $color_id[$i],
                'quantity' => $quantity[$i],
                'unit_price' => $unit_price[$i],
                'total_price' => $total_price[$i],
                'product_id' => $product_id[$i],
                'id_no' => $this->input->post('id_no'),
                'date' => date('Y-m-d', strtotime($this->input->post('date'))),
                'retail_sells_unique_id' => $retail_sells_unique_id,
                'purchase_price' => $purchase_price[$i],
            );
            $this->db->insert('retail_customer_sold_product', $product_array);
        }
        /* Delete Previous Installment start */
        $update_deleted = array(
            'is_deleted' => '1',
        );
        $this->db->where('retail_customer_sells_id', $retail_customer_sells_id)
                ->update('retail_installment', $update_deleted);
        /* Delete Previous Installment end */
        /* Installment entry start */
        $installment_amount = $this->input->post('installment_amount');
        $installment_date = $this->input->post('installment_date');
        for ($j = 0; $j < count($installment_amount); $j++) {
            $data_installment = array(
                'installment_amount' => $installment_amount[$j],
                'due' => $installment_amount[$j],
                'installment_date' => date('Y-m-d', strtotime($installment_date[$j])),
                'retail_customer_id' => $retail_customer_id,
                'status' => 'Due',
                'retail_customer_sells_id' => $retail_customer_sells_id,
                'retail_sells_unique_id' => $retail_sells_unique_id,
            );
            $this->db->insert('retail_installment', $data_installment);
        }
        /* Installment entry end */
        $sdata['success'] = 'updated successully';
        $this->session->set_userdata($sdata);
        redirect('RetailSaleController/view_retail_sale_in_installment');
    }

    public function add_retail_sell_save() {
        // error_reporting(0);
        $retail_customer_id = $this->input->post('retail_customer_id');

        if ($retail_customer_id == 'New Retail Customer') {

            $config['upload_path'] = 'assets/retailer_picture/';
            $config['allowed_types'] = 'gif|jpg|png';
            $config['overwrite'] = FALSE;
            $config['encrypt_name'] = TRUE;
            $error = array();
            $sdata = array();
            $this->load->library('upload', $config);
            $picture = 0;
            $this->upload->do_upload('picture');
            $sdata = $this->upload->data();
            $picture = $sdata['file_name'];

            $config['upload_path'] = 'assets/retailer_signature/';
            $config['allowed_types'] = 'gif|jpg|png';
            $config['overwrite'] = FALSE;
            $config['encrypt_name'] = TRUE;
            $error = array();
            $sdata = array();
            $this->load->library('upload', $config);
            $signature = 0;
            if (!$this->upload->do_upload('signature')) {
                $error = $this->upload->display_errors();
            } else {
                $this->upload->do_upload('signature');
                $sdata = $this->upload->data();
                $signature = $sdata['file_name'];
            }


            $config['upload_path'] = 'assets/retailer_picture/';
            $config['allowed_types'] = 'gif|jpg|png';
            $config['overwrite'] = FALSE;
            $config['encrypt_name'] = TRUE;
            $error = array();
            $sdata = array();
            $this->load->library('upload', $config);
            $reference_one_signature = 0;
            if (!$this->upload->do_upload('reference_one_signature')) {
                $error = $this->upload->display_errors();
            } else {
                $this->upload->do_upload('reference_one_signature');
                $sdata = $this->upload->data();
                $reference_one_signature = $sdata['file_name'];
            }

            $config['upload_path'] = 'assets/retailer_picture/';
            $config['allowed_types'] = 'gif|jpg|png';
            $config['overwrite'] = FALSE;
            $config['encrypt_name'] = TRUE;
            $error = array();
            $sdata = array();
            $this->load->library('upload', $config);
            $reference_one_picture = 0;
            if (!$this->upload->do_upload('reference_one_picture')) {
                $error = $this->upload->display_errors();
            } else {
                $this->upload->do_upload('reference_one_picture');
                $sdata = $this->upload->data();
                $reference_one_picture = $sdata['file_name'];
            }


            $config['upload_path'] = 'assets/retailer_picture/';
            $config['allowed_types'] = 'gif|jpg|png';
            $config['overwrite'] = FALSE;
            $config['encrypt_name'] = TRUE;
            $error = array();
            $sdata = array();
            $this->load->library('upload', $config);
            $reference_two_signature = 0;
            if (!$this->upload->do_upload('reference_two_signature')) {
                $error = $this->upload->display_errors();
            } else {
                $this->upload->do_upload('reference_two_signature');
                $sdata = $this->upload->data();
                $reference_two_signature = $sdata['file_name'];
            }

            $config['upload_path'] = 'assets/retailer_picture/';
            $config['allowed_types'] = 'gif|jpg|png';
            $config['overwrite'] = FALSE;
            $config['encrypt_name'] = TRUE;
            $error = array();
            $sdata = array();
            $this->load->library('upload', $config);
            $reference_two_picture = 0;
            if (!$this->upload->do_upload('reference_two_picture')) {
                $error = $this->upload->display_errors();
            } else {
                $this->upload->do_upload('reference_two_picture');
                $sdata = $this->upload->data();
                $reference_two_picture = $sdata['file_name'];
            }

            $uniqu_id = $this->db->select('*')->get('retail_customer_uniqueid');
            $retail_customer_unique_id = 'RE' . str_pad($uniqu_id->num_rows() + 1, 5, '0', STR_PAD_LEFT);
            $customer_array_uniqu = array(
                'retail_customer_uniqueid' => $retail_customer_unique_id
            );
            $customer_array = array(
                'retail_customer_name' => $this->input->post('customer_name'),
                'address' => $this->input->post('address'),
                'mobile' => $this->input->post('mobile'),
                'retail_customer_unique_id' => $retail_customer_unique_id,
                'id_no' => $this->input->post('id_no'),
                'date' => date('Y-m-d', strtotime($this->input->post('date'))),
                'fathers_name' => $this->input->post('fathers_name'),
                'signature' => $signature,
                'picture' => $picture,
                'reference_one_id_no' => $this->input->post('reference_one_id_no'),
                'reference_one_name' => $this->input->post('reference_one_name'),
                'reference_one_fathers_name' => $this->input->post('reference_one_fathers_name'),
                'reference_one_address' => $this->input->post('reference_one_address'),
                'reference_one_mobile' => $this->input->post('reference_one_mobile'),
                'reference_two_signature' => $reference_two_signature,
                'reference_two_picture' => $reference_two_picture,
                'reference_two_id_no' => $this->input->post('reference_two_id_no'),
                'reference_two_name' => $this->input->post('reference_two_name'),
                'reference_two_fathers_name' => $this->input->post('reference_two_fathers_name'),
                'reference_two_address' => $this->input->post('reference_two_address'),
                'reference_two_mobile' => $this->input->post('reference_two_mobile'),
                'reference_two_signature' => $reference_two_signature,
                'reference_two_picture' => $reference_two_picture,
                'reference_one_relation' => $this->input->post('reference_one_relation'),
                'reference_two_relation' => $this->input->post('reference_two_relation'),
                'nid' => $this->input->post('nid'),
                'birth_certificate' => $this->input->post('birth_certificate'),
                'job_id' => $this->input->post('job_id'),
                'bank_check' => $this->input->post('bank_check'),
            );
            $this->db->insert('retail_customer', $customer_array);
            $retail_customer_id = $this->db->insert_id();
            $this->db->insert('retail_customer_uniqueid', $customer_array_uniqu);
        } else {
            $retail_customer = $this->db
                    ->where('retail_customer_id', $retail_customer_id)
                    ->get('retail_customer_sells')
                    ->row();
            $update = array(
                'net_total' => $retail_customer->net_total + $this->input->post('total_amount'),
                'paid' => $retail_customer->paid + $this->input->post('paid'),
                'due' => $retail_customer->due + $this->input->post('due')
            );
            $this->db->where('retail_customer_id', $retail_customer_id)
                    ->update('retail_customer_sells', $update); /* To update the total amount and paid amount and due amount */
        }
        $uniqu_id = $this->db->select('*')->get('retail_customer_sells');
        $retail_sells_unique_id = 'RS' . str_pad($uniqu_id->num_rows() + 1, 5, '0', STR_PAD_LEFT);

        /* Profit calculation start */
        $purchase_price = $this->input->post('purchase_price');
        $total_price = $this->input->post('total_price');

        /* Customer Sells Table Start */
        if ($this->input->post('number_of_installment') == '') {/* Cash or installment identification */
            $cash_or_installment = 'Cash';
        } else {
            $cash_or_installment = 'Installment';
        }
        $data_sells = array(
            'retail_customer_id' => $retail_customer_id,
            'sub_total' => $this->input->post('sub_total'),
            'retail_sells_unique_id' => $retail_sells_unique_id,
            'discount' => $this->input->post('discount'),
            'net_total' => $this->input->post('net_total'),
            'paid' => $this->input->post('paid'),
            'cash_or_bank' => $this->input->post('cash_or_bank'),
            'bank_id' => $this->input->post('bank_id'),
            'due' => $this->input->post('due'),
            'id_no' => $this->input->post('id_no'),
            'profit' => $this->input->post('profit'),
            'form_fee' => $this->input->post('form_fee'),
            'number_of_installment' => $this->input->post('number_of_installment'),
            'cash_or_installment' => $cash_or_installment,
            'date' => date('Y-m-d', strtotime($this->input->post('date'))),
        );
        $this->db->insert('retail_customer_sells', $data_sells);
        $retail_customer_sells_id = $this->db->insert_id();
        /* Customer Sells Table End */

        /* Sells Payment Table start */
        $data_sells_payment = array(
            'retail_customer_id' => $retail_customer_id,
            'amount' => $this->input->post('paid'),
            'current_due' => $this->input->post('due'),
            'id_no' => $this->input->post('id_no'),
            'collection_type' => 'From sells',
            'date' => date('Y-m-d', strtotime($this->input->post('date'))),
            'retail_customer_sells_id' => $retail_customer_sells_id,
        );
        $this->db->insert('retail_sell_payment', $data_sells_payment);
        /* Sells Payment Table End */

        $product_category_id = $this->input->post('product_category_id');
        $model_id = $this->input->post('model_id');
        $color_id = $this->input->post('color_id');

        $quantity = $this->input->post('quantity');
        $unit_price = $this->input->post('unit_price');
        $total_price = $this->input->post('total_price');
        $product_id = $this->input->post('product_id');
        $purchase_price = $this->input->post('purchase_price');
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
                'retail_customer_id' => $retail_customer_id,
                'retail_customer_sells_id' => $retail_customer_sells_id,
                'product_category_id' => $product_category_id[$i],
                'model_id' => $model_id[$i],
                'color_id' => $color_id[$i],
                'quantity' => $quantity[$i],
                'unit_price' => $unit_price[$i],
                'total_price' => $total_price[$i],
                'product_id' => $product_id[$i],
                'id_no' => $this->input->post('id_no'),
                'purchase_price' => $purchase_price[$i],
                'date' => date('Y-m-d', strtotime($this->input->post('date'))),
                'retail_sells_unique_id' => $retail_sells_unique_id,
            );
            $this->db->insert('retail_customer_sold_product', $product_array);
        }
        /* Installment entry start */
        $installment_amount = $this->input->post('installment_amount');
        $installment_date = $this->input->post('installment_date');
        for ($j = 0; $j < count($installment_amount); $j++) {
            $data_installment = array(
                'installment_amount' => $installment_amount[$j],
                'due' => $installment_amount[$j],
                'installment_date' => date('Y-m-d', strtotime($installment_date[$j])),
                'retail_customer_id' => $retail_customer_id,
                'status' => 'Due',
                'retail_customer_sells_id' => $retail_customer_sells_id,
                'retail_sells_unique_id' => $retail_sells_unique_id,
            );
            $this->db->insert('retail_installment', $data_installment);
        }
        /* Installment entry end */
        $data['retail_customer_sells_id'] = $retail_customer_sells_id;
        $this->load->view('retail/print_retail_sell_invoice', $data, TRUE);
        $sdata['success'] = 'saved successully';
        $this->session->set_userdata($sdata);
        $page_data = array(
            'page_name' => 'retail/print_retail_sell_invoice',
            'page_title' => 'Print Retail Sell',
            'sidebar' => 'retail/retail_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function retail_sell_invoice_print($retail_customer_sells_id) {
        $data = array();
        $data['retail_customer_sells_id'] = $retail_customer_sells_id;
        $this->load->view('retail/print_retail_sell_invoice', $data);
    }

    public function retail_sell_invoice_print_in_cash($retail_customer_sells_id) {
        $data = array();
        $data['retail_customer_sells_id'] = $retail_customer_sells_id;
        $this->load->view('retail/print_retail_sell_invoice_in_cash', $data);
    }

    public function edit_retail_customer_save() {
        $retail_customer_id = $this->input->post('retail_customer_id');
        $product_name = $this->input->post('product_name');
        $reference_one_relation = $this->input->post('reference_one_relation');
        $reference_two_relation = $this->input->post('reference_two_relation');


        $config1 = array();
        $config1['upload_path'] = 'assets/retailer_picture/';
        $config1['allowed_types'] = 'gif|jpg|png';
        $config1['overwrite'] = FALSE;
        $config1['encrypt_name'] = TRUE;
        $error = array();
        $signature = '';
        $sdata1 = array();
        $this->load->library('upload', $config1);
        $this->upload->do_upload('signature');
        $sdata1 = $this->upload->data();
        $signature = $sdata1['file_name'];

        if ($signature == '') {
            $signature = $this->input->post('signature_edit');
        }



        $config1 = array();
        $config1['upload_path'] = 'assets/retailer_picture/';
        $config1['allowed_types'] = 'gif|jpg|png';
        $config1['overwrite'] = FALSE;
        $config1['encrypt_name'] = TRUE;
        $error = array();
        $picture = '';
        $sdata1 = array();
        $this->load->library('upload', $config1);
        $this->upload->do_upload('picture');
        $sdata1 = $this->upload->data();
        $picture = $sdata1['file_name'];
        if ($picture == '') {
            $picture = $this->input->post('picture_edit');
        }

        //  die();
        $reference_one_signature = 0;
        $config2 = array();
        $config2['upload_path'] = 'assets/retailer_picture/';
        $config2['allowed_types'] = 'gif|jpg|png';
        $config2['overwrite'] = FALSE;
        $config2['encrypt_name'] = TRUE;
        $error = array();
        $sdata2 = array();
        $this->load->library('upload', $config2);
        $this->upload->do_upload('reference_one_signature');
        $sdata2 = $this->upload->data();
        $reference_one_signature = $sdata2['file_name'];
        if ($reference_one_signature == '') {
            $reference_one_signature = $this->input->post('reference_one_signature_edit');
        }

        $reference_one_picture = 0;
        $config2 = array();
        $config2['upload_path'] = 'assets/retailer_picture/';
        $config2['allowed_types'] = 'gif|jpg|png';
        $config2['overwrite'] = FALSE;
        $config2['encrypt_name'] = TRUE;
        $error = array();
        $sdata2 = array();
        $this->load->library('upload', $config2);
        $this->upload->do_upload('reference_one_picture');
        $sdata2 = $this->upload->data();
        $reference_one_picture = $sdata2['file_name'];
        if ($reference_one_picture == '') {
            $reference_one_picture = $this->input->post('reference_one_picture_edit');
        }

        $reference_two_signature = 0;
        $config = array();
        $config['upload_path'] = 'assets/retailer_picture/';
        $config['allowed_types'] = 'gif|jpg|png';
        $config['overwrite'] = FALSE;
        $config['encrypt_name'] = TRUE;
        $error = array();
        $sdata = array();
        $this->load->library('upload', $config);
        $this->upload->do_upload('reference_two_signature');
        $sdata = $this->upload->data();
        $reference_two_signature = $sdata['file_name'];
        if ($reference_two_signature == '') {
            $reference_two_signature = $this->input->post('reference_two_signature_edit');
        }

        $reference_two_picture = 0;
        $config = array();
        $config['upload_path'] = 'assets/retailer_picture/';
        $config['allowed_types'] = 'gif|jpg|png';
        $config['overwrite'] = FALSE;
        $config['encrypt_name'] = TRUE;
        $error = array();
        $sdata = array();
        $this->load->library('upload', $config);
        $this->upload->do_upload('reference_two_picture');
        $sdata = $this->upload->data();
        $reference_two_picture = $sdata['file_name'];
        if ($reference_two_picture == '') {
            $reference_two_picture = $this->input->post('reference_two_picture_edit');
        }

        $data = array(
            'id_no' => $this->input->post('id_no'),
            'date' => date('Y-m-d', strtotime($this->input->post('date'))),
            'retail_customer_name' => $this->input->post('customer_name'),
            'fathers_name' => $this->input->post('fathers_name'),
            'address' => $this->input->post('address'),
            'mobile' => $this->input->post('mobile'),
            'signature' => $signature,
            'picture' => $picture,
            'reference_one_id_no' => $this->input->post('reference_one_id_no'),
            'reference_one_name' => $this->input->post('reference_one_name'),
            'reference_one_fathers_name' => $this->input->post('reference_one_fathers_name'),
            'reference_one_address' => $this->input->post('reference_one_address'),
            'reference_one_mobile' => $this->input->post('reference_one_mobile'),
            'reference_one_signature' => $reference_one_signature,
            'reference_one_picture' => $reference_one_picture,
            'reference_two_id_no' => $this->input->post('reference_two_id_no'),
            'reference_two_name' => $this->input->post('reference_two_name'),
            'reference_two_fathers_name' => $this->input->post('reference_two_fathers_name'),
            'reference_two_address' => $this->input->post('reference_two_address'),
            'reference_two_mobile' => $this->input->post('reference_two_mobile'),
            'reference_two_picture' => $reference_two_picture,
            'model_id' => $this->input->post('model_id'),
            'reference_one_relation' => $this->input->post('reference_one_relation'),
            'reference_two_relation' => $this->input->post('reference_two_relation'),
            'nid' => $this->input->post('nid'),
            'birth_certificate' => $this->input->post('birth_certificate'),
            'job_id' => $this->input->post('job_id'),
            'bank_check' => $this->input->post('bank_check'),
        );
        $this->db->where('retail_customer_id', $retail_customer_id)
                ->update('retail_customer', $data);
        $sdata = array(
            'update' => 'updated successfully'
        );
        $this->session->set_userdata($sdata);

        $config['base_url'] = site_url('RetailSaleController/view_retail_customer');
        $config['total_rows'] = $this->db->count_all('retail_customer');
        $config['per_page'] = "40";
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
        $data['detailsList'] = $this->ProductModel->get_retail_customer_details($config["per_page"], $data['page'], NULL);

        $data['pagination'] = $this->pagination->create_links();
        // $data['model_id'] = $model_id;
        // $data['product_category_id'] = $product_category_id;

        $this->load->view('retail/view_retail_customer', $data, true);
        $page_data = array(
            'page_name' => 'retail/view_retail_customer',
            'page_title' => 'View Retial Customer',
            'sidebar' => 'retail/retail_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function delete_this_retail_sell($retail_customer_sells_id) {
        $data_delete = array(
            'is_deleted' => 1
        );
        $this->db->where('retail_customer_sells_id', $retail_customer_sells_id)
                ->update('retail_customer_sells', $data_delete);

        $sdata['deleted'] = 'deleted successully';
        $this->session->set_userdata($sdata);
        $page_data = array(
            'page_name' => 'retail/view_retail_sale',
            'page_title' => 'View Retail Sale',
            'sidebar' => 'retail/retail_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function delete_this_retail_customer_sells($retail_customer_sells_id) {
        $data_delete = array(
            'is_deleted' => '1'
        );
        $this->db->where('retail_customer_sells_id', $retail_customer_sells_id)
                ->update('retail_customer_sells', $data_delete);
        $sdata['deleted'] = 'deleted successully';
        redirect('RetailSaleController/view_retail_sale_in_cash');
    }

    public function add_retail_customer_save() {
        $product_name = $this->input->post('product_name');
        $reference_one_relation = $this->input->post('reference_one_relation');
        $reference_two_relation = $this->input->post('reference_two_relation');

        $config = array();
        $config['upload_path'] = 'assets/retailer_picture/';
        $config['overwrite'] = FALSE;
        $config['encrypt_name'] = TRUE;
        $config['allowed_types'] = 'gif|jpg|png';
        $error = array();
        $sdata = array();
        $this->load->library('upload', $config);
        $picture = 0;
        $this->upload->do_upload('picture');
        $sdata = $this->upload->data();
        $picture = $sdata['file_name'];



        $config_signature = array();
        $config_signature['upload_path'] = 'assets/retailer_picture/';
        $config_signature['allowed_types'] = 'gif|jpg|png';
        $config_signature['overwrite'] = FALSE;
        $config_signature['encrypt_name'] = TRUE;
        $error = array();
        $sdata = array();
        $this->load->library('upload', $config_signature);
        $signature = 0;
        $this->upload->do_upload('signature');

        $sdata = $this->upload->data();
        //  echo '<pre>';
        //  print_r($sdata);
        //  die;
        $signature = $sdata['file_name'];




        $config_retailer_reference_one = array();
        $config_retailer_reference_one['upload_path'] = 'assets/retailer_picture/';
        $config_retailer_reference_one['allowed_types'] = 'gif|jpg|png';
        $config_retailer_reference_one['overwrite'] = FALSE;
        $config_retailer_reference_one['encrypt_name'] = TRUE;
        $error = array();
        $sdata = array();
        $this->load->library('upload', $config_retailer_reference_one);
        $reference_one_signature = 0;
        $this->upload->do_upload('reference_one_signature');

        $sdata = $this->upload->data();
        $reference_one_signature = $sdata['file_name'];

        $config_retailer_reference_one = array();
        $config_retailer_reference_one['upload_path'] = 'assets/retailer_picture/';
        $config_retailer_reference_one['allowed_types'] = 'gif|jpg|png';
        $config_retailer_reference_one['overwrite'] = FALSE;
        $config_retailer_reference_one['encrypt_name'] = TRUE;
        $error = array();
        $sdata = array();
        $this->load->library('upload', $config_retailer_reference_one);
        $reference_one_picture = 0;
        $this->upload->do_upload('reference_one_picture');

        $sdata = $this->upload->data();
        $reference_one_picture = $sdata['file_name'];



        $config_retailer_reference_one1 = array();
        $config_retailer_reference_one1['upload_path'] = 'assets/retailer_picture/';
        $config_retailer_reference_one1['allowed_types'] = 'gif|jpg|png';
        $config_retailer_reference_one1['overwrite'] = FALSE;
        $config_retailer_reference_one1['encrypt_name'] = TRUE;
        $error = array();
        $sdata = array();
        $this->load->library('upload', $config_retailer_reference_one1);
        $reference_two_signature = 0;
        $this->upload->do_upload('reference_two_signature');
        $sdata = $this->upload->data();
        $reference_two_signature = $sdata['file_name'];


        $config_retailer_reference_one1 = array();
        $config_retailer_reference_one1['upload_path'] = 'assets/retailer_picture/';
        $config_retailer_reference_one1['allowed_types'] = 'gif|jpg|png';
        $config_retailer_reference_one1['overwrite'] = FALSE;
        $config_retailer_reference_one1['encrypt_name'] = TRUE;
        $error = array();
        $sdata = array();
        $this->load->library('upload', $config_retailer_reference_one1);
        $reference_two_picture = 0;
        $this->upload->do_upload('reference_two_picture');
        $sdata = $this->upload->data();
        $reference_two_picture = $sdata['file_name'];

//die;
        $data = array(
            'retail_customer_unique_id' => $this->input->post('retail_customer_unique_id'),
            'id_no' => $this->input->post('id_no'),
            'date' => date('Y-m-d', strtotime($this->input->post('date'))),
            'retail_customer_name' => $this->input->post('customer_name'),
            'fathers_name' => $this->input->post('fathers_name'),
            'address' => $this->input->post('address'),
            'mobile' => $this->input->post('mobile'),
            'signature' => $signature,
            'picture' => $picture,
            'reference_one_id_no' => $this->input->post('reference_one_id_no'),
            'reference_one_name' => $this->input->post('reference_one_name'),
            'reference_one_fathers_name' => $this->input->post('reference_one_fathers_name'),
            'reference_one_address' => $this->input->post('reference_one_address'),
            'reference_one_mobile' => $this->input->post('reference_one_mobile'),
            'reference_one_signature' => $reference_one_signature,
            'reference_one_picture' => $reference_one_picture,
            'reference_two_id_no' => $this->input->post('reference_two_id_no'),
            'reference_two_name' => $this->input->post('reference_two_name'),
            'reference_two_fathers_name' => $this->input->post('reference_two_fathers_name'),
            'reference_two_address' => $this->input->post('reference_two_address'),
            'reference_two_mobile' => $this->input->post('reference_two_mobile'),
            'reference_two_signature' => $reference_two_signature,
            'reference_two_picture' => $reference_two_picture,
            'model_id' => $this->input->post('model_id'),
            'reference_one_relation' => $reference_one_relation,
            'reference_two_relation' => $reference_two_relation,
            'nid' => $this->input->post('nid'),
            'birth_certificate' => $this->input->post('birth_certificate'),
            'job_id' => $this->input->post('job_id'),
            'bank_check' => $this->input->post('bank_check'),
        );
        $this->db->insert('retail_customer', $data);
        $retail_customer_id = $this->db->insert_id();

        /* Unique id save start */
        $customer_array_uniqu = array(
            'retail_customer_uniqueid' => $this->input->post('retail_customer_unique_id')
        );
        $this->db->insert('retail_customer_uniqueid', $customer_array_uniqu);
        /* Unique id save end */

        $retail_customer_sells = $this->db->select('*')->get('retail_customer_sells');
        $retail_sells_unique_id = 'IN' . str_pad($retail_customer_sells->num_rows() + 1, 5, '0', STR_PAD_LEFT);


        /* To sells table start */
        $data_sell = array(
            'retail_customer_id' => $retail_customer_id,
            'net_total' => $this->input->post('total_amount'),
            'paid' => $this->input->post('paid_amount'),
            'due' => $this->input->post('due_amount'),
            'retail_sells_unique_id' => $retail_sells_unique_id,
            'date' => date('Y-m-d', strtotime($this->input->post('date'))),
            'number_of_installment' => count($this->input->post('installment_amount')),
            'cash_or_installment' => 'Installment',
        );
        $this->db->insert('retail_customer_sells', $data_sell);
        $retail_customer_sells_id = $this->db->insert_id();
        /* To sells table end */

        $data_sells_payment = array(
            'retail_customer_id' => $retail_customer_id,
            'amount' => $this->input->post('paid'),
            'collection_type' => 'From sells',
            'date' => date('Y-m-d', strtotime($this->input->post('date'))),
            'retail_customer_sells_id' => $retail_customer_sells_id,
        );

        /* Installment table start */
        $installment_amount = $this->input->post('installment_amount');
        $installment_date = $this->input->post('installment_date');


        $retail_installment = $this->db->select('*')->get('retail_installment');
        $installment_unique_id = 'IN' . str_pad($retail_installment->num_rows() + 1, 5, '0', STR_PAD_LEFT);


        for ($i = 0; $i < count($installment_amount); $i++) {
            $data = array(
                'id_no' => $this->input->post('id_no'),
                'retail_customer_id' => $retail_customer_id,
                'retail_customer_sells_id' => $retail_customer_sells_id,
                'installment_unique_id' => $installment_unique_id,
                'installment_date' => date('Y-m-d', strtotime($installment_date[$i])),
                'installment_amount' => $installment_amount[$i],
                'due' => $installment_amount[$i],
                'status' => 'Due'
            );
            $this->db->insert('retail_installment', $data);
        }
        /* Installment table End */

        $sdata['success'] = 'saved successully';
        $this->session->set_userdata($sdata);
        // die;
        $page_data = array(
            'page_name' => 'retail/add_retail_customer',
            'page_title' => 'Add Retail Customer',
            'sidebar' => 'retail/retail_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function view_retail_customer() {
        $id_no = $this->input->post('id_no');
        $retail_customer_unique_id = $this->input->post('retail_customer_unique_id');
        $retail_customer_name = $this->input->post('retail_customer_name');

        $config['base_url'] = site_url('RetailSaleController/view_retail_customer');
        $config['total_rows'] = $this->db->count_all('retail_customer');
        $config['per_page'] = "40";
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
        $data['detailsList'] = $this->ProductModel->get_retail_customer_details($config["per_page"], $data['page'], NULL, $retail_customer_name, $retail_customer_unique_id, $id_no);

        $data['pagination'] = $this->pagination->create_links();
        // $data['model_id'] = $model_id;
        // $data['product_category_id'] = $product_category_id;

        $this->load->view('retail/view_retail_customer', $data, true);
        $sdata['sl'] = 1;
        $sl = $this->session->userdata('sl');
        if ($sl > 1) {
            
        } else {

            $this->session->set_userdata($sdata);
        }


        $page_data = array(
            'page_name' => 'retail/view_retail_customer',
            'page_title' => 'View Retial Customer',
            'sidebar' => 'retail/retail_sidebar'
        );
        //$sl=$this->session->userdata('sl');
        //print_r($sl);
        $this->load->view('content', $page_data);
    }

    public function retial_custoemr_info_load() {
        $retail_customer_id = $_POST['retail_customer_id'];
        $retail_customer = $this->db->where('retail_customer_id', $retail_customer_id)
                        ->get('retail_customer')->row();
        echo $retail_customer->retail_customer_name . '_' . $retail_customer->address . "_" . $retail_customer->mobile . "_" . $retail_customer->fathers_name;
    }

    public function view_retail_sell_return() {

        $retail_customer_name = $this->input->post('view_retail_sell_return');

        $config['base_url'] = site_url('RetailSaleController/view_retail_sale_in_cash');
        $config['total_rows'] = $this->db->count_all('retail_customer_sells_return_product');
        $config['per_page'] = "40";
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
        $data['detailsList'] = $this->ProductModel->get_retail_sell_return_details($config["per_page"], $data['page'], NULL, $retail_customer_name);

        $data['pagination'] = $this->pagination->create_links();
        $this->load->view('retail/view_retail_sell_return', $data, true);
        $page_data = array(
            'page_name' => 'retail/view_retail_sell_return',
            'page_title' => 'View Retail Sell Return',
            'sidebar' => 'retail/retail_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function view_retail_sale_in_cash() {
        $id_no = $this->input->post('id_no');
        $retail_customer_unique_id = $this->input->post('retail_customer_unique_id');
        $retail_customer_name = $this->input->post('retail_customer_name');

        $config['base_url'] = site_url('RetailSaleController/view_retail_sale_in_cash');
        $config['total_rows'] = $this->db->where('cash_or_installment ', 'Cash')->count_all('retail_customer_sells');
        $config['per_page'] = "40";
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
        $data['detailsList'] = $this->ProductModel->get_retail_customer_sell_details_in_cash($config["per_page"], $data['page'], NULL, $retail_customer_unique_id, $id_no, $retail_customer_name);

        $data['pagination'] = $this->pagination->create_links();
        $this->load->view('retail/view_retail_sale_in_cash', $data, true);
        $page_data = array(
            'page_name' => 'retail/view_retail_sale_in_cash',
            'page_title' => 'View Retail Sale',
            'sidebar' => 'retail/retail_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function view_reatil_due_payment() {
        $id_no = $this->input->post('id_no');
        $retail_customer_id = $this->input->post('retail_customer_id');
        $config['base_url'] = site_url('RetailSaleController/view_reatil_due_payment');
        $config['total_rows'] = $this->db->where('collection_type', 'Installment Collection')->count_all('retail_sell_payment');
        $config['per_page'] = "40";
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
        $data['detailsList'] = $this->ProductModel->get_retail_due_payment_details($config["per_page"], $data['page'], NULL, $retail_customer_id, $id_no);

        $data['pagination'] = $this->pagination->create_links();
        $this->load->view('retail/view_reatil_due_payment', $data, true);
        $page_data = array(
            'page_name' => 'retail/view_reatil_due_payment',
            'page_title' => 'View Retail Due Payment',
            'sidebar' => 'retail/retail_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function retail_due_payment_edit($retail_sell_payment_id) {
        $data['retail_sell_payment_id'] = $retail_sell_payment_id;
        $this->load->view('retail/retail_due_payment_edit', $data);
    }

    public function view_retail_sale_in_installment() {
        $id_no = $this->input->post('id_no');
        $retail_customer_unique_id = $this->input->post('retail_customer_unique_id');
        $retail_customer_name = $this->input->post('retail_customer_name');

        $config['base_url'] = site_url('RetailSaleController/view_retail_sale_in_installment');
        $config['total_rows'] = $this->db->where('cash_or_installment', 'Installment')->count_all('retail_customer_sells');
        $config['per_page'] = "40";
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
        $data['detailsList'] = $this->ProductModel->get_retail_customer_sell_details_installment($config["per_page"], $data['page'], NULL, $retail_customer_unique_id, $id_no, $retail_customer_name);

        $data['pagination'] = $this->pagination->create_links();
        $this->load->view('retail/view_retail_sale_in_installment', $data, true);
        $page_data = array(
            'page_name' => 'retail/view_retail_sale_in_installment',
            'page_title' => 'View Retail Sale In Installment',
            'sidebar' => 'retail/retail_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function retail_sell_edit($retail_customer_sells_id) {
        $data['retail_customer_sells_id'] = $retail_customer_sells_id;
        $this->load->view('retail/retail_sell_edit', $data);
    }

    public function retail_due_payment_print($retail_sell_payment_id) {
        $data['retail_sell_payment_id'] = $retail_sell_payment_id;
        $this->load->view('retail/retail_due_payment_print', $data);
    }

    public function add_retail_customer() {
        $page_data = array(
            'page_name' => 'retail/add_retail_customer',
            'page_title' => 'Add Retail Customer',
            'sidebar' => 'retail/retail_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function add_retail_customer_iframe() {
        $crud = new Grocery_crud();
        $edit_check = $crud->getState();
        $crud->set_table('retail_customer');
        $crud->set_subject('retail_customer');
        $crud->required_fields('retail_customer_name', 'address', 'mobile');

        $crud->columns('retail_customer_unique_id', 'retail_customer_name', 'address', 'mobile', 'total_amount', 'paid_amount', 'due_amount');
        $crud->fields('retail_customer_unique_id', 'retail_customer_name', 'address', 'mobile', 'paid_amount', 'due_amount');
        $crud->display_as('retail_customer_name', 'Retail Customer Name');
        $crud->callback_add_field('retail_customer_unique_id', function () {
            $uniqu_id = $this->db->select('*')->get('retail_customer_uniqueid');
            $retail_customer_unique_id = 'ER' . str_pad($uniqu_id->num_rows() + 1, 5, '0', STR_PAD_LEFT);
            return '<input type="text" maxlength="50" value="' . $retail_customer_unique_id . '" name="retail_customer_unique_id"  readonly>';
        });
        $crud->callback_after_insert(array($this, 'retail_customer_unique_id_insert'));
        $crud->callback_after_update(array($this, 'total_amount_update'));
        $crud->display_as('retail_customer_unique_id', 'Retail Customer Unique Id');
        $output = $crud->render();
        $this->load->view('frame/grocery_crud_view.php', $output);
    }

    function total_amount_update($post_array, $primary_key) {
        $amount = array(
            'total_amount' => $post_array['paid_amount'] + $post_array['due_amount']
        );
        $this->db->where('retail_customer_id', $primary_key)->update(retail_customer, $amount);
        return true;
    }

    function retail_customer_unique_id_insert($post_array, $primary_key) {
        $retail_customer_unique_id = array(
            "retail_customer_uniqueid_id" => $primary_key,
            "retail_customer_uniqueid" => $post_array['retail_customer_unique_id']
        );
        $amount = array(
            'total_amount' => $post_array['paid_amount'] + $post_array['due_amount']
        );
        $paid_amount = array(
            'paid_amount' => $post_array['paid_amount']
        );
        $this->db->where('retail_customer_id', $primary_key)->update(retail_customer, $amount);

        $this->db->insert('retail_customer_payment', $paid_amount);

        $this->db->insert('retail_customer_uniqueid', $retail_customer_unique_id);

        return true;
    }

    public function retail_customer_edit($retail_customer_id) {
        $data = array(
            'retail_customer_id' => $retail_customer_id
        );
        $this->load->view('retail/retail_customer_edit', $data);
    }

    public function edit_retail_sell_return_save() {
        $retail_sell_return_id = $this->input->post('retail_sell_return_id');
        $retail_customer_id = $this->input->post('retail_customer_id');
        $retail_customer = $this->db
                ->where('retail_customer_id', $retail_customer_id)
                ->get('retail_customer_sells')
                ->row();
        $product_id = $this->input->post('product_id');
        $product_ids = '';
        for ($i = 0; $i < count($product_id); $i++) {
            $product_ids = $product_ids . '*' . $product_id[$i];
        }
        $return_sell_array = array(
            'product_ids' => $product_ids,
            'retail_customer_id' => $this->input->post('retail_customer_id'),
            'unique_id' => $unique_id,
            'sub_total' => $this->input->post('sub_total'),
            'net_total' => $this->input->post('net_total'),
            'date' => date('Y-m-d', strtotime($this->input->post('date')))
        );
        $this->db
                ->where('retail_sell_return_id', $retail_sell_return_id)
                ->update('retail_sell_return', $return_sell_array);

        $update = array(
            'net_total' => $retail_customer->net_total + $this->input->post('net_total_edit') - $this->input->post('net_total'),
        );
        $this->db->where('retail_customer_id', $retail_customer_id)
                ->update('retail_customer_sells', $update); /* To update the total amount and paid amount and due amount */

        $product_category_id = $this->input->post('product_category_id');
        $model_id = $this->input->post('model_id');
        $color_id = $this->input->post('color_id');
        $quantity = $this->input->post('quantity');
        $unit_price = $this->input->post('unit_price');
        $total_price = $this->input->post('total_price');

        $purchase_price = $this->input->post('purchase_price');
        $profit_for_each_product = $this->input->post('profit_for_each_product');
        $quantity_edit = $this->input->post('quantity_edit');

        $this->db->where('retail_sell_return_id', $retail_sell_return_id)
                ->delete('retail_customer_sells_return_product'); /* To delete the previous data */
//        echo '<pre>';
//        print_r($product_category_id);
        //   die;
        $retail_sell_return = $this->db->where('retail_sell_return_id', $retail_sell_return_id)
                        ->get('retail_sell_return')->row();

        for ($i = 0; $i < count($product_category_id); $i++) {
            /* Update Stock start */
            $product = $this->db->where('product_id', $product_id[$i])
                            ->get('product')->row();
            $update_stock = array(
                'stock' => $product->stock - $quantity_edit[$i] + $quantity[$i]/* Previous stock + current stock */
            );
            $this->db->where('product_id', $product_id[$i])
                    ->update('product', $update_stock);
            /* Update Stock end */
            $product_array = array(
                'retail_customer_id' => $retail_customer_id,
                'retail_sell_return_id' => $retail_sell_return_id,
                'unique_id' => $retail_sell_return->unique_id,
                'product_category_id' => $product_category_id[$i],
                'model_id' => $model_id[$i],
                'color_id' => $color_id[$i],
                'quantity' => $quantity[$i],
                'unit_price' => $unit_price[$i],
                'total_price' => $total_price[$i],
                'product_id' => $product_id[$i],
                'id_no' => $this->input->post('id_no'),
                'purchase_price' => $purchase_price[$i],
                'profit_for_each_product' => $profit_for_each_product[$i],
                'date' => date('Y-m-d', strtotime($this->input->post('date')))
            );
            $this->db->insert('retail_customer_sells_return_product', $product_array);
        }


        $sdata['success'] = 'saved successully';
        $this->session->set_userdata($sdata);
        redirect('RetailSaleController/view_retail_sell_return');
    }

    public function add_retail_sell_return_save() {
        $uniqu_id = $this->db->select('*')->get('retail_sell_return');
        $unique_id = 'RS' . str_pad($uniqu_id->num_rows() + 1, 5, '0', STR_PAD_LEFT);

        $retail_customer_id = $this->input->post('retail_customer_id');
        $retail_customer = $this->db
                ->where('retail_customer_id', $retail_customer_id)
                ->get('retail_customer_sells')
                ->row();
        $product_id = $this->input->post('product_id');
        $product_ids = '';
        for ($i = 0; $i < count($product_id); $i++) {
            $product_ids = $product_ids . '*' . $product_id[$i];
        }
        $return_sell_array = array(
            'product_ids' => $product_ids,
            'retail_customer_id' => $this->input->post('retail_customer_id'),
            'unique_id' => $unique_id,
            'sub_total' => $this->input->post('sub_total'),
            'net_total' => $this->input->post('net_total'),
            'date' => date('Y-m-d', strtotime($this->input->post('date')))
        );
        $this->db->insert('retail_sell_return', $return_sell_array);
        $retail_sell_return_id = $this->db->insert_id();

        $update = array(
            'net_total' => $retail_customer->net_total - $this->input->post('total_amount'),
            'paid' => $retail_customer->paid - $this->input->post('paid'),
            'due' => $retail_customer->due - $this->input->post('due'),
            'profit' => $retail_customer->due - $this->input->post('profit')
        );
        $this->db->where('retail_customer_id', $retail_customer_id)
                ->update('retail_customer_sells', $update); /* To update the total amount and paid amount and due amount */

        $product_category_id = $this->input->post('product_category_id');
        $model_id = $this->input->post('model_id');
        $color_id = $this->input->post('color_id');
        $quantity = $this->input->post('quantity');
        $unit_price = $this->input->post('unit_price');
        $total_price = $this->input->post('total_price');

        $purchase_price = $this->input->post('purchase_price');
        $profit_for_each_product = $this->input->post('profit_for_each_product');
//        echo '<pre>';
//        print_r($product_category_id);
        //   die;

        for ($i = 0; $i < count($product_category_id); $i++) {
            /* Update Stock start */
            $product = $this->db->where('product_id', $product_id[$i])
                            ->get('product')->row();
            $update_stock = array(
                'stock' => $product->stock + $quantity[$i]/* Previous stock + current stock */
            );
            $this->db->where('product_id', $product_id[$i])
                    ->update('product', $update_stock);
            /* Update Stock end */
            $product_array = array(
                'retail_customer_id' => $retail_customer_id,
                'retail_sell_return_id' => $retail_sell_return_id,
                'unique_id' => $unique_id,
                'product_category_id' => $product_category_id[$i],
                'model_id' => $model_id[$i],
                'color_id' => $color_id[$i],
                'quantity' => $quantity[$i],
                'unit_price' => $unit_price[$i],
                'total_price' => $total_price[$i],
                'product_id' => $product_id[$i],
                'id_no' => $this->input->post('id_no'),
                'purchase_price' => $purchase_price[$i],
                'profit_for_each_product' => $profit_for_each_product[$i],
                'date' => date('Y-m-d', strtotime($this->input->post('date')))
            );
            $this->db->insert('retail_customer_sells_return_product', $product_array);
        }


        $sdata['success'] = 'saved successully';
        $this->session->set_userdata($sdata);
        $page_data = array(
            'page_name' => 'retail/retail_sell_return',
            'page_title' => 'Retial Sell Return',
            'sidebar' => 'retail/retail_sidebar'
        );
        $this->load->view('content', $page_data);
    }

}
