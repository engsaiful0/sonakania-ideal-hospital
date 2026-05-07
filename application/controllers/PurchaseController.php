<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of PurchaseController
 *
 * @author Lenovo
 */
class PurchaseController extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->load->database();
        $this->load->helper('url');
        date_default_timezone_set('Asia/Dhaka');
        $this->load->library('Grocery_crud');
        if ($this->session->userdata('user_id') == '') {
            redirect('LoginController');
        }
        $this->load->library('pagination');
    }

    private $defaults = array();

    public function index()
    {
    }

    function add_purchase_drug()
    {
 
        //  $this->load->view('sale', $this->defaults);
        $page_data = array(
            'page_name' => 'purchase/add_purchase',
            'page_title' => 'Purchase',
            'sidebar' => 'pharmacy/pharmacy_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function invoice_load()
    {
        $manufacture_id = $_POST['manufacture_id'];
        $sql = $this->db->where('supplier', $manufacture_id)->get('purchase')->result();
?><option>Select Invoice</option>
        <?php
        foreach ($sql as $value) {
        ?>
            <option value="<?php echo $value->purchase_id ?>"><?php echo $value->mrr ?></option>
        <?php
        }
    }

    public function addMoreSale()
    {
        $next_id = $_POST['next_id'];
        ?>
        <tr>
            <td style="padding:5px;">
                <select name="type_name[]" class="form-control" id="type_name_<?php echo $next_id ?>" sequence=0 onchange="drug_name_load(this.id)" required="" style="width:150px;">
                    <option value="" selected="">Select Type</option>
                    <?php
                    $sql = $this->db->select('*')->get('drug_type')->result();

                    foreach ($sql as $value) {
                    ?>
                        <option value="<?php echo $value->drug_type_id ?>"><?php echo $value->type_name ?></option>
                    <?php
                    }
                    ?>
                </select>
            </td>
            <td style="padding:5px;">
                <select name="drug[]" class="form-control" id="drug_add<?php echo $next_id ?>" onchange="getdrugdetails(this, event)" required="" style="width:150px;" sequence=<?php echo $next_id; ?>>

                </select>
            </td>
            <td style="padding:5px;">
                <input type="text" value="0" id="sales_rate<?php echo $next_id ?>" name="sales_rate[]" class="form-control" sequence=<?php echo $next_id ?> required="" onkeyup="getamount(this, event)">
            </td>
            <td style="padding:5px;">
                <input type="text" value="0" id="wsr<?php echo $next_id ?>" name="wsr[]" class="form-control" sequence=0 required="" onkeyup="getamount(this, event)">
            </td>
            <td style="padding:5px;">
                <input type="text" value="0" id="qty<?php echo $next_id ?>" name="qty[]" class="form-control" sequence=<?php echo $next_id ?> onkeyup="getamount(this, event)" required="" onkeyup="getamount(this, event)">
            </td>
            <td style="padding:5px;">
                <input type="text" value="" id="discounteach<?php echo $next_id ?>" name="discounteach[]" class="form-control" sequence=<?php echo $next_id ?> onkeyup="getamount(this, event)">
            </td>


            <td style="padding:5px;">
                <input type="text" value="" id="stock<?php echo $next_id ?>" name="stock[]" class="form-control" sequence=<?php echo $next_id ?> required="" readonly="">
            </td>


            <input type="hidden" value="0" id="pur_rate<?php echo $next_id ?>" name="pur_rate[]" class="form-control" sequence=<?php echo $next_id ?> readonly="">

            <td style="padding:5px;">
                <input type="text" id="self_no<?php echo $next_id ?>" name="self_no[]" class="form-control" sequence=<?php echo $next_id ?> readonly="">
            </td>

            <td style="padding:5px;">
                <input type="text" id="amount<?php echo $next_id ?>" name="amount[]" class="form-control amount" readonly="" sequence=<?php echo $next_id ?>>
            </td>
            <td style="padding:5px;">
                <button class="btn btn-danger  btn-xs remove" type="button" sequence=<?php echo $next_id ?> onclick="removetr(this, event)"><i class="glyphicon glyphicon-remove"></i></button>
            </td>
        </tr>
    <?php
    }

    public function addMorePurchase()
    {
        $next_id = $_POST['next_id'];
        $data = array(
            'next_id' => $next_id,
        );
        $this->load->view('purchase/addMorePurchase', $data);
    }

    public function addMorePurchase_Edit()
    {
        $next_id = $_POST['next_id'];
        $supplier = $_POST['supplier'];
        $data = array(
            'next_id' => $next_id,
            'supplier' => $supplier
        );
        $this->load->view('purchase/addMorePurchase_Edit', $data);
    }

    function addMorePurchaseReturn()
    {
        $next_id = $_POST['next_id'];
        $supplier = $_POST['supplier'];
    ?>
        <tr>
            <td>
                <select name="type_name[]" class="form-control" id="type_name_<?php echo $next_id ?>" sequence=0 onchange="drug_name_load(this.id)" required="" style="width:110px;">
                    <option value="" selected="">Select Type</option>
                    <?php
                    $sql = $this->db->select('*')->get('drug_type')->result();

                    foreach ($sql as $value) {
                    ?>
                        <option value="<?php echo $value->drug_type_id ?>"><?php echo $value->type_name ?></option>
                    <?php
                    }
                    ?>
                </select>
            </td>
            <td style="padding:5px;">

                <select name="drug[]" class="form-control" id="drug_add<?php echo $next_id ?>" sequence=<?php echo $next_id ?> onchange="details(this, event);" required="" style="width:160px;">
                    <option value="" selected="">Select Drug</option>
                    <?php
                    $sql = $this->db->where('manufacturer', $supplier)->get('drug')->result();

                    foreach ($sql as $value) {
                    ?>
                        <option value="<?php echo $value->drug_id ?>"><?php echo $value->drug_name ?></option>
                    <?php
                    }
                    ?>
                </select>
            </td>
            <td style="padding:5px;">
                <input type="text" value="" id="boxqty<?php echo $next_id ?>" name="boxqty[]" class="form-control" sequence=<?php echo $next_id ?> onkeyup="getamount(this, event), getqty(this, event)" required="">
            </td>

            <td style="padding:5px;">
                <input type="text" value="" id="pbq<?php echo $next_id ?>" name="pbq[]" class="form-control" sequence=<?php echo $next_id ?> onkeyup="getamount(this, event), purchase_rate(this, event), getqty(this, event)" required="">
            </td>
            <td style="padding:5px;">
                <input type="text" value="" id="boxrate<?php echo $next_id ?>" name="boxrate[]" class="form-control" sequence=<?php echo $next_id ?> onkeyup="purchase_rate(this, event), getamount(this, event)" required="">
            </td>
            <td style="padding:5px;">
                <input type="text" value="" id="invoice_date<?php echo $next_id ?>" style="width:100px;" name="expdate[]" class="form-control date<?php echo $next_id ?>" sequence=<?php echo $next_id ?> required="">
            </td>
            <td style="padding:5px;">
                <input type="text" value="" id="discount<?php echo $next_id ?>" style="width:40px;" name="discount[]" class="form-control" sequence=<?php echo $next_id ?> onkeyup="purchase_rate(this, event), getamount(this, event)">
            </td>

            <td style="padding:5px;">
                <input type="text" value="" id="pur_rate<?php echo $next_id ?>" name="pur_rate[]" class="form-control" sequence=<?php echo $next_id ?> required="">
            </td>
            <td style="padding:5px;">
                <input type="text" value="" id="mrp<?php echo $next_id ?>" name="mrp[]" class="form-control" sequence=<?php echo $next_id ?>>
            </td>
            <td style="padding:5px;">
                <input type="text" value="" id="wsr<?php echo $next_id ?>" name="whole_sale_rate[]" class="form-control" sequence=0 required="">
            </td>

            <td style="padding:5px;">
                <input type="text" value="" id="qty<?php echo $next_id ?>" name="qty[]" class="form-control" readonly="" sequence=<?php echo $next_id ?>>
            </td>
            <td style="padding:5px;">
                <input type="text" value="" id="stock<?php echo $next_id ?>" name="stock[]" class="form-control" readonly="" sequence=<?php echo $next_id ?> required="">
            </td>
            <td style="padding:5px;">
                <input type="text" value="" id="amount<?php echo $next_id ?>" name="amount[]" class="form-control amount" readonly="" sequence=<?php echo $next_id ?> required="">
            </td>
            <td style="padding:5px;">
                <button class="btn btn-danger  btn-xs remove" type="button" sequence=<?php echo $next_id ?> onclick="removetr(this, event)"><i class="glyphicon glyphicon-remove"></i></button>
            </td>
        </tr>
    <?php
    }

    public function supplierget()
    {
        $supplier = $_POST['supplier'];

        $sql = $this->db->where('manufacturer', $supplier)->get('drug')->result();
    ?>
        <option value="">Select Drug</option>
        <?php
        foreach ($sql as $value) {
        ?>
            <option value="<?php echo $value->drug_id ?>"><?php echo $value->drug_name ?></option>
        <?php
        }
    }

    public function add_drug_name()
    {
        $type_name_val = $_POST['type_name_val'];

        $sql = $this->db->where('type', $type_name_val)->get('drug')->result();
        ?>
        <option value="">Select Drug</option>
        <?php
        foreach ($sql as $value) {
        ?>
            <option value="<?php echo $value->drug_id ?>"><?php echo $value->drug_name ?></option>
        <?php
        }
    }

    public function add_drug_name_purchase()
    {
        $type_name_val = $_POST['type_name_val'];
        $supplier = $_POST['supplier'];

        $sql = $this->db->where('type', $type_name_val)

            ->get('drug')->result();
        ?>
        <option value="">Select Drug</option>
        <?php
        foreach ($sql as $value) {
        ?>
            <option value="<?php echo $value->drug_id ?>"><?php echo $value->drug_name ?></option>
        <?php
        }
    }

    function supplier()
    {
        $this->grocery_crud->set_subject('Supplier');
        $output = $this->grocery_crud->set_table('supplier')
            //->where('supplier.company_id', $this->session->userdata('company_id'))
            ->columns('name', 'phone', 'email', 'address', 'contact_person', 'contact_person_phone')
            ->field_type('company_id', 'hidden', $this->session->userdata('company_id'))
            ->callback_after_insert(array($this, '_add_supplier_to_supplier_account'))
            ->callback_after_update(array($this, '_add_supplier_to_supplier_account'))
            ->render();
        $this->load->view('common/pharmacy_template', $output);
    }

    function _add_supplier_to_supplier_account($post_array, $primary_key)
    {
        $supplier_account_data = array(
            'supplier_id' => $primary_key
        );
        $this->db->insert('supplier_account', $supplier_account_data);
    }

    function employee_setup()
    {
        $this->grocery_crud->set_subject('Employee');
        $output = $this->grocery_crud->set_table('employee')
            ->columns('employee_name', 'address', 'phone')
            ->fields('employee_name', 'address', 'phone')
            ->required_fields('employee_name', 'phone')
            ->render();
        $this->load->view('common/pharmacy_template', $output);
    }

    function employee_salary()
    {
        $this->grocery_crud->set_subject('Employee Salary');
        $output = $this->grocery_crud->set_table('employee_salary')
            ->set_relation('employee_id', 'employee', 'employee_name')
            ->columns('employee_id', 'salary', 'month')
            ->fields('employee_id', 'salary', 'month')
            ->required_fields('employee_id', 'salary', 'month')
            ->display_as('employee_id', 'Employee Name')
            ->display_as('month', 'Date')
            ->render();
        $this->load->view('common/pharmacy_template', $output);
    }

    function stock()
    {
        $config['base_url'] = site_url('product/stock');
        $config['total_rows'] = $this->db->count_all('drug');
        $config['per_page'] = 600;
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
        $manufacture_id = NULL;
        $manufacture_id = $this->input->post('manufacture_id');
        $data['result'] = $this->user_model->get_drug($config["per_page"], $data['page'], $manufacture_id);

        $data['pagination'] = $this->pagination->create_links();


        $this->defaults['productname'] = '';
        // $data['manufacture_id'] = $manufacture_id;
        //  $this->load->view('sale_register/sale_register_details_print',$data);
        $this->load->view('stock', $data);


        //$this->load->view('stock', $data);
    }

    function _callback_drug($value)
    {
        $dr = $this->db->where('drug_id', $value)->get('drug')->row();
        print_r($drug);
        $dr_type = $this->db->where('drug_type_id', $dr->type)->get('drug_type')->row();
        return $dr->drug_name . '-' . $dr_type->type_name;
    }

    function open_stock()
    {
        $this->grocery_crud->set_subject('Opening Stock');
        $output = $this->grocery_crud->set_table('stock')
            ->columns('drug', 'quantity')
            ->fields('drug', 'quantity')
            ->set_relation('drug', 'drug', '{drug_name}-{drug_type_name}')
            ->render();
        $this->load->view('common/pharmacy_template', $output);
    }

    function customer_load()
    {
        //        print_r();
        //        die;
        $customer_type = $_POST['customer_type'];
        $sql = $this->db->where('customer_type', $customer_type)->get('customer')->result();
        foreach ($sql as $value) {
        ?>
            <option value="<?php echo $value->customer_id ?>"><?php echo $value->name ?></option>
        <?php
        }
    }

    function update_value($value, $pr_key)
    {
        $sql = $this->db->where('stock_id', $pr_key)->get('stock')->row();
        $drug = $this->db->where('drug_id', $sql->drug)->update('drug', array('expdate' => $sql->expDate, 'mrp' => $sql->mrp, 'pur_rate' => $sql->pur_rate));
        return;
    }

    function _get_total($purchase_id)
    {
        $query = "select sum(total_price) as total from purchase_details where purchase_id=" . $purchase_id;
        $result = $this->db->query($query);
        foreach ($result->result() as $rows) {
            $total = $rows->total;
        }
        return $total;
    }

    function _addtostock($post_array, $primary_key)
    {
        $drug = $post_array['drug'];
        $company_id = $this->session->userdata('company_id');
        $query = "select quantity from stock where drug=" . $drug . " and company_id=" . $company_id;
        $result = $this->db->query($query);
        $numrow = $result->num_rows();
        if ($numrow > 0) {
            $query = "update stock set quantity=quantity+" . $post_array['quantity'] . " where drug=" . $drug . " and company_id=" . $company_id;
            $result = $this->db->query($query);
        } else {
            $values = array(
                'drug' => $drug,
                'quantity' => $post_array['quantity'],
                'company_id' => $company_id
            );
            $insert = $this->db->insert('stock', $values);
        }
        return true;
    }

    function _removestock($primary_key)
    {
        $company_id = $this->session->userdata('company_id');
        $query = "select quantity,drug from purchase_details where purchase_details_id=" . $primary_key;
        $result = $this->db->query($query);
        foreach ($result->result() as $row) {
            $quantity = $row->quantity;
            $drug = $row->drug;
        }
        $query = "update stock set quantity=quantity-" . $quantity . " where drug=" . $drug . " and company_id=" . $company_id;
        $result = $this->db->query($query);
    }

    function sale()
    {
        $this->load->view('sale', $this->defaults);
    }

    function sale_edit()
    {
        $sales_id = $this->input->post('sales_id');
        $data['sales_id'] = $sales_id;
        $this->load->view('sale_edit', $data);
    }

    function purchase_edit($purchase_id)
    {
        //$purchase_id = $this->input->post('purchase_id');
        $data['purchase_id'] = $purchase_id;
        $this->load->view('purchase/edit_purchase', $data, true);
        $page_data = array(
            'page_name' => 'purchase/edit_purchase',
            'page_title' => 'Purchase',
            'sidebar' => 'purchase/purchase_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function edit_purchase_save()
    {
        $purchase_id = $this->input->post('purchase_id');
        $pur_type = $this->input->post('pur_type');
        /* Start due_and_paid_status */
        $paid = $this->input->post('paid');
        $total = $this->input->post('total');
        if ($paid < $total) {
            $due_and_paid_status = 'Due';
        } else if ($paid == $total) {
            $due_and_paid_status = 'Paid';
        }
        /* End due_and_paid_statusF */
        $purchase_data = array(
            'mrr' => $this->input->post('mrr'),
            'mrr_date' => date('Y-m-d', strtotime($this->input->post('mrr_date'))),
            'invoice' => $this->input->post('invoice'),
            'invoice_date' => date('Y-m-d', strtotime($this->input->post('invoice_date'))),
            'total' => $this->input->post('total'),
            'supplier' => $this->input->post('supplier'),
            'payment_type' => $this->input->post('paymenttype'),
            'paid' => $this->input->post('paid'),
            'due' => $this->input->post('due'),
            'due_and_paid_status' => $due_and_paid_status,
            'user_id' => $this->session->userdata('user_id'),
        );
        $this->db->where('purchase_id', $purchase_id)->update('purchase', $purchase_data);

        $purchase_expanditure_statment = array(
            'mrr' => $this->input->post('mrr'),
            'purchase_id' => $purchase_id,
            'mrr_date' => date('Y-m-d', strtotime($this->input->post('mrr_date'))),
            'payment_type' => $this->input->post('paymenttype'),
            'paid_amount' => $this->input->post('paid'),
            'supplier' => $this->input->post('supplier'),
            'user_id' => $this->session->userdata('user_id'),
        );
        $insert = $this->db->where('purchase_id', $purchase_id)->update('purchase_expanditure_statement', $purchase_expanditure_statment);

        /* To Supplier Account */
        $sql_customer_account = $this->db->where('supplier_id', $this->input->post('supplier'))->get('supplier_account')->row();

        $customer_account_data = array(
            'total_amount' => $sql_customer_account->total_amount + $this->input->post('total') - $this->input->post('total_edit'),
            'paid_amount' => $sql_customer_account->paid_amount + $this->input->post('paid') - $this->input->post('paid_edit'),
            'due_amount' => $sql_customer_account->due_amount + $this->input->post('due') - $this->input->post('due_edit'),
        );
        $this->db->where('supplier_id', $this->input->post('supplier'))->update('supplier_account', $customer_account_data); //Update customer account for old customer          
        /* To Supplier Account */


        //        print_r($purchase_id);
        //        die;
        $purchase_details = array();
        $drug = $this->input->post('drug');
        $type_name = $this->input->post('type_name');

        $boxqty = $this->input->post('boxqty');
        $pbq = $this->input->post('pbq');
        $boxrate = $this->input->post('boxrate');

        $discount = $this->input->post('discount');
        $pur_rate = $this->input->post('pur_rate');

        $mrp = $this->input->post('mrp');

        $qty = $this->input->post('qty');
        $qty_edit = $this->input->post('qty_edit');
        $amount = $this->input->post('amount');
        $this->db->where('purchase_id', $purchase_id)->delete('purchase_details'); /* To delete previous purchase details */
        for ($loop = 0; $loop < count($drug); $loop++) {
            $purchase_details[] = array(
                'purchase_id' => $purchase_id,
                'drug' => $drug[$loop],
                'type' => $type_name[$loop],
                'boxqty' => $boxqty[$loop],
                'pbq' => $pbq[$loop],
                'boxrate' => $pbq[$loop],
                'discount' => $discount[$loop],
                'pur_rate' => $pur_rate[$loop],
                'mrp' => $mrp[$loop],
                'qty' => $qty[$loop],
                'amount' => $amount[$loop]
            );
        }
        if ($insert) {
            $insert = $this->db->insert_batch('purchase_details', $purchase_details);
        }
        // echo '<pre>';
        //   print_r($qty);
        //  print_r($re_order_qty);
        //  die;
        if ($insert) {
            for ($loop = 0; $loop < count($drug); $loop++) {
                $drugid = $drug[$loop];
                //  echo '<pr>';
                //  echo $re_order_qty;
                //dei;
                $purchase_quantity = $qty[$loop];
                $qty_edit_each = $qty_edit[$loop];
                // $re_order_qty = $re_order_qty[$loop];

                $sql_update_drug = $this->db->where('drug_id', $drugid)->get('drug')->row();

                $update_drug_qty = $sql_update_drug->stock + $purchase_quantity - $qty_edit_each;

                $this->db->where('drug_id', $drugid)->update('drug', array('stock' => $update_drug_qty));



                $data_up = array(
                    'pur_rate' => $pur_rate[$loop],
                    'type' => $type_name[$loop],
                    'boxrate' => $boxrate[$loop],
                    'mrp' => $mrp[$loop],
                    'pur_rate' => $pur_rate[$loop],
                );
                $update_stock = $this->db->where('drug_id', $drugid)->update('drug', $data_up);
            }
        }
        $sdata = array('update' => 'updated');
        $this->session->set_userdata($sdata);

        $mrr = '';
        $config['base_url'] = site_url('PruchaseController/view_purchase');
        $config['total_rows'] = $this->db->where('is_deleted', '0')->count_all('purchase');
        $config['per_page'] = "50";
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
        $data['detailsList'] = $this->ProductModel->get_purchase_details($config["per_page"], $data['page'], NULL, $mrr);

        $data['pagination'] = $this->pagination->create_links();
        $this->load->view('purchase/view_purchase', $data, true);
        $page_data = array(
            'page_name' => 'purchase/view_purchase',
            'page_title' => 'View Pharmacy',
            'sidebar' => 'pharmacy/pharmacy_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    function sale_delete()
    {
        $sales_id = $this->input->post('sales_id');

        $this->db->where('sales_id', $sales_id)->delete('sales');
        $this->db->where('sales_id', $sales_id)->delete('sales_details');
        $data = array();
        $data['date_from'] = $this->input->post('date_from');
        $data['date_to'] = $this->input->post('date_to');

        $this->load->view('report/sales_report', $data);
    }

    function purchase_delete($purchase_id)
    {


        $delete = array(
            'is_deleted' => 1
        );
        $sdata = array(
            'delete' => 'deleted'
        );
        $this->session->set_userdata($sdata);

        $this->db->where('purchase_id', $purchase_id)->update('purchase', $delete);
        $this->db->where('purchase_id', $purchase_id)->update('purchase_details', $delete);


        $mrr = '';
        // $config['base_url'] = site_url('PruchaseController/view_purchase');
        $config['total_rows'] = $this->db->where('is_deleted', '0')->count_all('purchase');
        $config['per_page'] = 100;
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
        $data['detailsList'] = $this->ProductModel->get_purchase_details($config["per_page"], $data['page'], NULL, $mrr);

        $data['pagination'] = $this->pagination->create_links();
        $this->load->view('purchase/view_purchase', $data, true);
        $page_data = array(
            'page_name' => 'purchase/view_purchase',
            'page_title' => 'View Pharmacy',
            'sidebar' => 'pharmacy/pharmacy_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    function test_sale()
    {
        $this->load->view('test_sale_details', $this->defaults);
    }

    function test_sale_edit()
    {
        $id = $this->input->post('id');
        $data['id'] = $id;
        $this->load->view('test_sale_details_edit', $data);
    }

    function test_report_edit()
    {
        $id = $this->input->post('id');
        $data['id'] = $id;
        $this->load->view('test_sale_details_edit', $data);
    }

    function getdruginfo()
    {
        $drugid = $this->input->post('drugid');
        $company_id = $this->session->userdata('company_id');
        $condition = array(
            "drug_id" => $drugid,
            "drug.company_id" => $company_id
        );
        $this->db->where($condition);
        $this->db->select('sales_rate,purchase_rate,shelf,quantity');
        $this->db->from('drug');
        $this->db->join('stock', 'stock.drug=drug.drug_id', 'left');
        $result = $this->db->get();
        $quantity = 0;
        $details = array();
        if ($result->num_rows()) {
            foreach ($result->result() as $row) {
                $details['unitprice'] = $row->sales_rate;
                $details['purchase_rate'] = $row->purchase_rate;
                $details['shelf'] = $row->shelf;
                $details['stock'] = $row->quantity;
            }
        }
        echo json_encode($details);
    }

    function purchase_product()
    {
        $this->load->view('purchase_product', $this->defaults);
    }

    public function purchase_details_print($purchase_id)
    {
        $data['purchase_id'] = $purchase_id;
        $this->load->view('purchase/purchase_details_print', $data, true);
        $page_data = array(
            'page_name' => 'purchase/purchase_details_print',
            'page_title' => 'View Pharmacy',
            'sidebar' => 'purchase/purchase_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    function confirm_purchase()
    {
        $pur_type = $this->input->post('pur_type');

        if ($pur_type == 'return') {
            echo $return = $this->return_purchase();
            return;
        }
        /* Start due_and_paid_status */
        $paid = $this->input->post('paid');
        $total = $this->input->post('total');
        if ($paid < $total) {
            $due_and_paid_status = 'Due';
        } else if ($paid == $total) {
            $due_and_paid_status = 'Paid';
        }
        /* End due_and_paid_statusF */
        $purchase_data = array(
            'mrr' => $this->input->post('mrr'),
            'mrr_date' => date('Y-m-d', strtotime($this->input->post('mrr_date'))),
            'invoice' => $this->input->post('invoice'),
            'invoice_date' => date('Y-m-d', strtotime($this->input->post('invoice_date'))),
            'total' => $this->input->post('total'),
            'supplier' => $this->input->post('supplier'),
            'payment_type' => $this->input->post('paymenttype'),
            'paid' => $this->input->post('paid'),
            'due' => $this->input->post('due'),
            'due_and_paid_status' => $due_and_paid_status,
            'user_id' => $this->session->userdata('user_id'),
            'company_id' => $this->session->userdata('company_id')
        );
        $purchase_expanditure_statment = array(
            'mrr' => $this->input->post('mrr'),

            'mrr_date' => date('Y-m-d', strtotime($this->input->post('mrr_date'))),
            'payment_type' => $this->input->post('paymenttype'),
            'paid_amount' => $this->input->post('paid'),
            'supplier' => $this->input->post('supplier'),
            'user_id' => $this->session->userdata('user_id'),
        );
        $insert = $this->db->insert('purchase_expanditure_statement', $purchase_expanditure_statment);
        $insert_pr = $this->db->insert('purchase', $purchase_data);
        if ($insert_pr)
            $purchase_id = $this->db->insert_id();
        /* To Supplier Account */
        $sql_customer_account = $this->db->where('supplier_id', $this->input->post('supplier'))->get('supplier_account')->row();
        $customer_account_data = array(
            'total_amount' => $sql_customer_account->total_amount + $this->input->post('total'),
            'paid_amount' => $sql_customer_account->paid_amount + $this->input->post('paid'),
            'due_amount' => $sql_customer_account->due_amount + $this->input->post('due'),
        );
        $this->db->where('supplier_id', $this->input->post('supplier'))->update('supplier_account', $customer_account_data); //Update customer account for old customer          
        /* To Supplier Account */


        //        print_r($purchase_id);
        //        die;
        $purchase_details = array();
        $drug = $this->input->post('drug');
        $type_name = $this->input->post('type_name');

        $boxqty = $this->input->post('boxqty');
        $pbq = $this->input->post('pbq');
        $boxrate = $this->input->post('boxrate');
        $vat = $this->input->post('vat');
        $expdate = $this->input->post('expdate');
        $discount = $this->input->post('discount');
        $pur_rate = $this->input->post('pur_rate');

        $mrp = $this->input->post('mrp');
        $re_order_qty = $this->input->post('re_order_qty');
        //  echo '<pre>';
        //  print_r($re_order_qty);
        //  die;
        //  whole_sale_rate
        //  die;
        $qty = $this->input->post('qty');
        $amount = $this->input->post('amount');
        $stock = $this->input->post('stock');
        for ($loop = 0; $loop < count($drug); $loop++) {
            $purchase_details[] = array(
                'purchase_id' => $purchase_id,
                'drug' => $drug[$loop],
                'type' => $type_name[$loop],
                'boxqty' => $boxqty[$loop],
                'pbq' => $pbq[$loop],
                'boxrate' => $pbq[$loop],
                'discount' => $discount[$loop],
                'pur_rate' => $pur_rate[$loop],
                'mrp' => $mrp[$loop],
                'qty' => $qty[$loop],
                'amount' => $amount[$loop]
            );
        }
        if ($insert) {
            $insert = $this->db->insert_batch('purchase_details', $purchase_details);
        }
        // echo '<pre>';
        //   print_r($qty);
        //  print_r($re_order_qty);
        //  die;
        if ($insert) {
            for ($loop = 0; $loop < count($drug); $loop++) {
                $drugid = $drug[$loop];
                //  echo '<pr>';
                //  echo $re_order_qty;
                //dei;
                $purchase_quantity = $qty[$loop];
                // $re_order_qty = $re_order_qty[$loop];

                $sql_update_drug = $this->db->where('drug_id', $drugid)->get('drug')->row();

                $update_drug_qty = $sql_update_drug->stock + $purchase_quantity;

                $this->db->where('drug_id', $drugid)->update('drug', array('stock' => $update_drug_qty));



                $data_up = array(
                    'pur_rate' => $pur_rate[$loop],
                    'type' => $type_name[$loop],
                    'boxrate' => $boxrate[$loop],
                    'mrp' => $mrp[$loop],
                    'pur_rate' => $pur_rate[$loop],
                );
                $update_stock = $this->db->where('drug_id', $drugid)->update('drug', $data_up);
            }
        }
        if ($update_stock)
            echo "1";
    }

    function confirm_purchase_edit()
    {
        $pur_type = $this->input->post('pur_type');
        $purchase_id = $this->input->post('purchase_id');


        /* Start due_and_paid_status */
        $paid = $this->input->post('paid');
        $total = $this->input->post('total');
        if ($paid < $total) {
            $due_and_paid_status = 'Due';
        } else if ($paid == $total) {
            $due_and_paid_status = 'Paid';
        }
        /* End due_and_paid_statusF */
        $purchase_data = array(
            'mrr' => $this->input->post('mrr'),
            'mrr_date' => date('Y-m-d', strtotime($this->input->post('mrr_date'))),
            'invoice' => $this->input->post('invoice'),
            'invoice_date' => date('Y-m-d', strtotime($this->input->post('invoice_date'))),
            'total' => $this->input->post('total'),
            'supplier' => $this->input->post('supplier'),
            'payment_type' => $this->input->post('paymenttype'),
            'paid' => $this->input->post('paid'),
            'due' => $this->input->post('due'),
            'due_and_paid_status' => $due_and_paid_status,
            'user_id' => $this->session->userdata('user_id'),
            'company_id' => $this->session->userdata('company_id')
        );
        $purchase_expanditure_statment = array(
            'mrr' => $this->input->post('mrr'),
            'mrr_date' => date('Y-m-d', strtotime($this->input->post('mrr_date'))),
            'payment_type' => $this->input->post('paymenttype'),
            'paid_amount' => $this->input->post('paid'),
            'supplier' => $this->input->post('supplier'),
            'user_id' => $this->session->userdata('user_id'),
        );
        $this->db->where('mrr', $this->input->post('mrr'))->delete('purchase_expanditure_statement'); /* To delete previous record */
        $insert = $this->db->insert('purchase_expanditure_statement', $purchase_expanditure_statment);
        $insert_pr = $this->db->where('purchase_id', $purchase_id)->update('purchase', $purchase_data);

        /* To Supplier Account */
        $sql_customer_account = $this->db->where('supplier_id', $this->input->post('supplier'))->get('supplier_account')->row();
        $customer_account_data = array(
            'total_amount' => $sql_customer_account->total_amount + $this->input->post('total') - $this->input->post('previous_total'),
            'paid_amount' => $sql_customer_account->paid_amount + $this->input->post('paid') - $this->input->post('previous_paid'),
            'due_amount' => $sql_customer_account->due_amount + $this->input->post('due') - $this->input->post('previous_due'),
        );
        $this->db->where('supplier_id', $this->input->post('supplier'))->update('supplier_account', $customer_account_data); //Update customer account for old customer          
        /* To Supplier Account */


        //        print_r($purchase_id);
        //        die;
        $purchase_details = array();
        $drug = $this->input->post('drug');
        $type_name = $this->input->post('type_name');

        $boxqty = $this->input->post('boxqty');
        $pbq = $this->input->post('pbq');
        $boxrate = $this->input->post('boxrate');
        $vat = $this->input->post('vat');
        $expdate = $this->input->post('expdate');
        $discount = $this->input->post('discount');
        $pur_rate = $this->input->post('pur_rate');
        $whole_sale_rate = $this->input->post('whole_sale_rate');
        $mrp = $this->input->post('mrp');
        $re_order_qty = $this->input->post('re_order_qty');
        //  echo '<pre>';
        //  print_r($re_order_qty);
        //  die;
        //  whole_sale_rate
        //  die;
        $qty = $this->input->post('qty');
        $amount = $this->input->post('amount');
        $stock = $this->input->post('stock');
        $this->db->where('purchase_id', $purchase_id)->delete('purchase_details'); /* To delete previous record */
        for ($loop = 0; $loop < count($drug); $loop++) {
            $purchase_details[] = array(
                'purchase_id' => $purchase_id,
                'drug' => $drug[$loop],
                'type' => $type_name[$loop],
                'boxqty' => $boxqty[$loop],
                're_order_qty' => $re_order_qty[$loop],
                'pbq' => $pbq[$loop],
                'expdate' => $expdate[$loop],
                'boxrate' => $pbq[$loop],
                'vat' => $vat[$loop],
                'discount' => $discount[$loop],
                'pur_rate' => $pur_rate[$loop],
                'mrp' => $mrp[$loop],
                'whole_sale_rate' => $whole_sale_rate[$loop],
                'qty' => $qty[$loop],
                'amount' => $amount[$loop]
            );
        }
        if ($insert) {
            $insert = $this->db->insert_batch('purchase_details', $purchase_details);
        }
        // echo '<pre>';
        //   print_r($qty);
        //  print_r($re_order_qty);
        //  die;
        if ($insert) {
            for ($loop = 0; $loop < count($drug); $loop++) {
                $drugid = $drug[$loop];
                //  echo '<pr>';
                //  echo $re_order_qty;
                //dei;
                $purchase_quantity = $qty[$loop];
                // $re_order_qty = $re_order_qty[$loop];

                $sql_update_drug = $this->db->where('drug_id', $drugid)->get('drug')->row();

                $update_drug_qty = $sql_update_drug->stock + $purchase_quantity;

                $this->db->where('drug_id', $drugid)->update('drug', array('stock' => $update_drug_qty));

                //  $query = "update drug set stock=stock+" . $purchase_quantity . " where drug_id=" . $drugid;
                //  $update_stock_drug = $this->db->query($query);
                //
                //                $query = "update stock set quantity=quantity+" . $purchase_quantity . " where drug=" . $drugid;
                //                $update_stock = $this->db->query($query);
                //                $affected = $this->db->affected_rows();
                //                if (!$affected) {
                //                    $stock_data = array(
                //                        'drug' => $drugid,
                //                        'quantity' => $purchase_quantity,
                //                        'company_id' => $this->session->userdata('company_id')
                //                    );
                //                    $update_stock = $this->db->insert('stock', $stock_data);
                //                }

                $data_up = array(
                    'pur_rate' => $pur_rate[$loop],
                    'type' => $type_name[$loop],
                    'boxrate' => $boxrate[$loop],
                    'mrp' => $mrp[$loop],
                    'pur_rate' => $pur_rate[$loop],
                    'whole_sale_rate' => $whole_sale_rate[$loop],
                    'expdate' => date('Y-m-d', strtotime($expdate[$loop])),
                );
                $update_stock = $this->db->where('drug_id', $drugid)->update('drug', $data_up);
            }
        }
        if ($update_stock)
            echo "1";
    }

    function todaysale()
    {
        $this->load->view('todaysale', $this->defaults);
    }

    function due_purchase_pay_view()
    {
        $data = array();
        $data['mrr'] = $this->input->post('mrr');
        $this->load->view('due_purchase_pay_view', $data);
    }

    function supplier_due_pay_view()
    {
        $data = array();
        $data['supplier_id'] = $this->input->post('supplier_id');
        $this->load->view('supplier_due_pay_view', $data);
    }

    function purchase_due_pay_data_save()
    {
        $mrr = $this->input->post('mrr');
        $total = $this->input->post('total');
        $paid = $this->input->post('paid');
        $remaining_due = $this->input->post('remaining_due');
        $bill_date = $this->input->post('bill_date');
        $supplier_id = $this->input->post('supplier_id');

        $payment_amount = $this->input->post('payment_amount');
        /* Start Paid Calculation */
        if ($total == ($paid + $payment_amount)) {
            $pay_status = 'Paid';
        } else {
            $pay_status = 'Due';
        }
        /* End Paid Calculation */
        $update_paid_amount = $paid + $payment_amount;
        $this->db->where('mrr', $mrr)->update('purchase', array('paid' => $update_paid_amount, 'due' => $remaining_due, 'due_and_paid_status' => $pay_status));


        /* Start Sales Income Statement */
        $purchase_expanse_statement = array(
            'mrr' => $this->input->post('mrr'),
            'mrr_date' => date('Y-m-d', strtotime($this->input->post('bill_date'))),
            'supplier' => $this->input->post('supplier_id'),
            'paid_amount' => $this->input->post('payment_amount'),
            'payment_type' => $this->input->post('payment_type'),
            'user_id' => $this->session->userdata('user_id'),
        );
        // print_r($sales_income_statement);
        // die;
        $this->db->insert('purchase_expanditure_statement', $purchase_expanse_statement);

        /* End Sales Income Statement */
        $insert_id = $this->db->insert_id();
        $data = array();
        $data['total'] = $total;
        $data['paid'] = $update_paid_amount;
        $data['payment_amount'] = $payment_amount;
        $data['due'] = $remaining_due;
        $data['mrr_date'] = $bill_date;
        $data['supplier_id'] = $supplier_id;
        $this->load->view('purchase_due_payment_print', $data);
    }

    function todaybuy()
    {
        $this->load->view('todaybuy', $this->defaults);
    }

    function complete_sale()
    {
        $this->load->library('cart');
        $this->load->view('confirm_sale', $_POST);
    }

    function getstock()
    {
        $drug = $this->input->post('drug');
        $query = 'select quantity from stock where drug=' . $drug;
        $result = $this->db->query($query);
        $quantity = 0;
        foreach ($result->result() as $row) {
            $quantity = $row->quantity;
        }
        print $quantity;
    }

    function getmrp()
    {
        $drug = $this->input->post('drug');
        $mrp = get_single_value('mrp', 'drug', array('drug_id' => $drug));
        echo $mrp;
    }

    function purchase_return()
    {
        $this->load->view('purchase_return', $this->defaults);
    }

    function get_purchase_details()
    {
        $purchase_id = $this->input->post('purchase_id');
        if (!$purchase_id) {
            return;
        }
        echo $purchase_details = $this->load->view('purchase_details', TRUE);
    }

    function return_purchase()
    {
        $purchase_data = array(
            'mrr' => $this->input->post('mrr'),
            'mrr_date' => date('Y-m-d', strtotime($this->input->post('mrr_date'))),
            'invoice' => $this->input->post('invoice'),
            'invoice_date' => date('Y-m-d', strtotime($this->input->post('invoice_date'))),
            'supplier' => $this->input->post('supplier'),
            'total' => $this->input->post('paid'),
        );
        $insert = $this->db->insert('purchase_return', $purchase_data);

        /* To supplier account */
        /* To Supplier Account */
        $sql_customer_account = $this->db->where('supplier_id', $this->input->post('supplier'))->get('supplier_account')->row();
        $customer_account_data = array(
            'total_amount' => $sql_customer_account->total_amount - $this->input->post('paid'),
        );
        $this->db->where('supplier_id', $this->input->post('supplier'))->update('supplier_account', $customer_account_data); //Update customer account for old customer          
        /* To Supplier Account */

        /* To supplier account */

        if ($insert)
            $purchase_id = $this->db->insert_id();
        $purchase_details = array();
        $drug = $this->input->post('drug');
        $boxqty = $this->input->post('boxqty');
        $pbq = $this->input->post('pbq');
        $boxrate = $this->input->post('boxrate');

        $discount = $this->input->post('discount');
        $pur_rate = $this->input->post('pur_rate');
        $mrp = $this->input->post('mrp');
        $qty = $this->input->post('qty');


        $amount = $this->input->post('amount');
        $stock = $this->input->post('stock');
        for ($loop = 0; $loop < count($drug); $loop++) {
            $purchase_details[] = array(
                'purchase_return_id' => $purchase_id,
                'drug' => $drug[$loop],
                'boxqty' => $boxqty[$loop],
                'pbq' => $pbq[$loop],
                'discount' => $discount[$loop],
                'pur_rate' => $pur_rate[$loop],
                'mrp' => $mrp[$loop],
                'qty' => $qty[$loop],
                'amount' => $amount[$loop]
            );
        }
        if ($insert) {
            $insert = $this->db->insert_batch('purchase_return_details', $purchase_details);
        }
        if ($insert) {
            for ($loop = 0; $loop < count($drug); $loop++) {
                $drugid = $drug[$loop];
                $purchase_quantity = $qty[$loop];
                $query = "update drug set stock=stock-" . $purchase_quantity . " where drug_id=" . $drugid;
                $update_stock_drug = $this->db->query($query);
            }
        }
        if ($update_stock_drug)
            echo "1";
    }

    function confirm_test_sale()
    {
        $bill_no = $this->input->post('bill_no');

        $data = array();


        $test_sales = array(
            'bill_no' => $this->input->post('bill_no'),
            'bill_date' => date('Y-m-d', strtotime($this->input->post('bill_date'))),
            'patient_name' => $this->input->post('patient_name'),
            'age' => $this->input->post('age'),
            'refd_by' => $this->input->post('refd_by'),
            'discount' => $this->input->post('discount'),
            'nettotal' => $this->input->post('nettotal'),
            'paid' => $this->input->post('paid'),
            'due' => $this->input->post('due'),
            'user_id' => $this->session->userdata('user_id'),
        );
        $insert = $this->db->insert('path_test_sale_mst', $test_sales);

        $test_sales_id = $this->db->insert_id();
        $test_details = array();
        $test_name = $this->input->post('test_name');
        $sales_rate = $this->input->post('sales_rate');
        $qty = $this->input->post('qty');

        $amount = $this->input->post('amount');
        for ($loop = 0; $loop < count($test_name); $loop++) {
            $test_details[] = array(
                'mst_id' => $test_sales_id,
                'test_name_id' => $test_name[$loop],
                'price' => $sales_rate[$loop],
                'qty' => $qty[$loop],
                'amount' => $amount[$loop]
            );
        }

        $insert = $this->db->insert_batch('path_test_sale_dtls', $test_details);

        $data['mst_id'] = $test_sales_id;

        $this->load->view('test_print', $data);
    }

    function confirm_test_sale_edit()
    {
        $bill_no = $this->input->post('bill_no');
        $mst_id = $this->input->post('mst_id');

        $data = array();


        $test_sales = array(
            'bill_no' => $this->input->post('bill_no'),
            'bill_date' => date('Y-m-d', strtotime($this->input->post('bill_date'))),
            'patient_name' => $this->input->post('patient_name'),
            'age' => $this->input->post('age'),
            'refd_by' => $this->input->post('refd_by'),
            'discount' => $this->input->post('discount'),
            'nettotal' => $this->input->post('nettotal'),
            'paid' => $this->input->post('paid'),
            'due' => $this->input->post('due'),
            'user_id' => $this->session->userdata('user_id'),
        );
        $this->db->where('id', $mst_id)->update('path_test_sale_mst', $test_sales);

        // $test_sales_id = $this->db->insert_id();
        $this->db->where('mst_id', $mst_id)->delete('path_test_sale_dtls');
        $test_details = array();
        $test_name = $this->input->post('test_name');
        $sales_rate = $this->input->post('sales_rate');
        $qty = $this->input->post('qty');

        $amount = $this->input->post('amount');
        for ($loop = 0; $loop < count($test_name); $loop++) {
            $test_details[] = array(
                'mst_id' => $mst_id,
                'test_name_id' => $test_name[$loop],
                'price' => $sales_rate[$loop],
                'qty' => $qty[$loop],
                'amount' => $amount[$loop]
            );
        }
        // echo '<pre>';
        //   print_r($test_details);
        //  die;
        $this->db->insert_batch('path_test_sale_dtls', $test_details);

        $data['mst_id'] = $mst_id;

        $this->load->view('test_print', $data);
    }

    function test_print()
    {
        $this->load->view('test_print');
    }

    function loadTestRow()
    {
        $id = $_POST['id'];
        ?>
        <tr id="<?php echo $id ?>">
            <td style="padding:5px;">
                <select name="test_name[]" class="form-control" id="test_name" sequence="<?php echo $id ?>" onchange="getdrugdetails(this, event)" required="" style="width:250px;">
                    <option value="" selected="">Select Test Name</option>
                    <?php
                    echo make_select('pat_test_name', 'id', 'test_name', array('company_id' => $this->session->userdata('company_id')));
                    ?>
                </select>
            </td>
            <td style="padding:5px;">
                <input value="" sequence="<?php echo $id ?>" type="text" readonly="" value="0" id="sales_rate" name="sales_rate[]" class="form-control" required="" onkeyup="getamount(this, event)">
            </td>
            <!--<td style="padding:5px;">-->
            <input type="hidden" value="" value="1" id="qty" name="qty[]" class="form-control" onkeyup="getamount(this, event)" required="" onkeyup="getamount(this, event)">
            <!--</td>-->
            <td style="padding:5px;">
                <input sequence="<?php echo $id ?>" value="" type="text" id="amount" name="amount[]" class="form-control amount" readonly="">
            </td>
            <td style="padding:5px;">
                <button class="btn btn-danger  btn-xs remove" type="button" onclick="removetr(this, event)"><i class="glyphicon glyphicon-remove"></i></button>
            </td>
        </tr>
<?php
    }

    function confirm_sale()
    {
        /* Start Pay Status Calculation */
        $nettotal = $this->input->post('nettotal');
        $paid = $this->input->post('paid');
        $pay_status = 0;


        if ($nettotal == $paid) {
            $pay_status = 'Paid';
        } else if ($nettotal > $paid) {
            $pay_status = 'Due';
        }

        /* End Pay Status Calculation */
        $customer = $this->input->post('customer_id');

        $new_customer_data = array(
            'name' => $this->input->post('name'),
            'customer_type' => $this->input->post('customer_type'),
            'address' => $this->input->post('address'),
            'phone' => $this->input->post('phone'),
            'email' => $this->input->post('email')
        );

        $cust = $this->db->where('name', $customer)->get('customer');
        $next_customer_id = 0;
        if ($customer == 'New Customer') {
            $this->db->insert('customer', $new_customer_data); //For new Customer
            $next_customer_id = $this->db->order_by('customer_id', 'desc')->limit('1')->get('customer')->row();

            //echo'<pre>';
            //print_r($next_customer_id);
            $customer_id = $next_customer_id->customer_id;


            $sales = array(
                'bill_no' => $this->input->post('bill_no'),
                'bill_date' => date('Y-m-d', strtotime($this->input->post('bill_date'))),
                'customer' => $customer_id,
                'total' => $this->input->post('total'),
                'discount' => $this->input->post('discount'),
                'nettotal' => $this->input->post('nettotal'),
                'pay_status' => $pay_status,
                'paid' => $this->input->post('paid'),
                'due' => $this->input->post('due'),
                'user_id' => $this->session->userdata('user_id'),
                'company_id' => $this->session->userdata('company_id')
            );

            $customer_account_data = array(
                'customer_id' => $customer_id,
                'total_amount' => $this->input->post('nettotal'),
                'paid_amount' => $this->input->post('paid'),
                'due_amount' => $this->input->post('due_amount'),
            );
            $this->db->insert('customer_account', $customer_account_data); //Customer accunt for new Customer

            /* Start Sales Income Statement */
            $sales_income_statement = array(
                'bill_no' => $this->input->post('bill_no'),
                'bill_date' => date('Y-m-d', strtotime($this->input->post('bill_date'))),
                'customer' => $customer_id + 1,
                'paid_amount' => $this->input->post('total'),
            );
            $this->db->insert('sales_income_statement', $sales_income_statement);
            /* End Sales Income Statement */
            $insert = $this->db->insert('sales', $sales);
            if ($insert)
                $sales_id = $this->db->insert_id();
        } else {
            $sales = array(
                'bill_no' => $this->input->post('bill_no'),
                'bill_date' => date('Y-m-d', strtotime($this->input->post('bill_date'))),
                'customer' => $this->input->post('customer_id'),
                'total' => $this->input->post('total'),
                'discount' => $this->input->post('discount'),
                'paid' => $this->input->post('paid'),
                'due' => $this->input->post('due'),
                'pay_status' => $pay_status,
                'nettotal' => $this->input->post('nettotal'),
                'user_id' => $this->session->userdata('user_id'),
                'company_id' => $this->session->userdata('company_id')
            );
            /* Start Sales Income Statement */
            $sales_income_statement = array(
                'bill_no' => $this->input->post('bill_no'),
                'bill_date' => date('Y-m-d', strtotime($this->input->post('bill_date'))),
                'customer' => $this->input->post('customer_id'),
                'paid_amount' => $this->input->post('total'),
            );
            $sql_customer_account = $this->db->where('customer_id', $this->input->post('customer_id'))->get('customer_account')->row();
            $customer_account_data = array(
                'total_amount' => $sql_customer_account->total_amount + $this->input->post('nettotal'),
                'paid_amount' => $sql_customer_account->paid_amount + $this->input->post('paid'),
                'due_amount' => $sql_customer_account->due_amount + $this->input->post('due'),
            );
            $this->db->where('customer_id', $this->input->post('customer_id'))->update('customer_account', $customer_account_data); //Update customer account for old customer          

            $this->db->insert('sales_income_statement', $sales_income_statement);
            /* End Sales Income Statement */
            $insert = $this->db->insert('sales', $sales);
            if ($insert)
                $sales_id = $this->db->insert_id();
        }
        $type = $this->input->post('type');
        if ($type == 'return') {
            echo $retun = $this->return_sales();
            return;
        }

        $purchase_details = array();
        $drug = $this->input->post('drug');
        $type_name = $this->input->post('type_name');
        $sales_rate = $this->input->post('sales_rate');
        $qty = $this->input->post('qty');
        $discounteach = $this->input->post('discounteach');
        $pur_rate = $this->input->post('pur_rate');
        $amount = $this->input->post('amount');
        for ($loop = 0; $loop < count($drug); $loop++) {
            $sales_details[] = array(
                'sales_id' => $sales_id,
                'drug' => $drug[$loop],
                'drug_type_id' => $type_name[$loop],
                'sales_rate' => $sales_rate[$loop],
                'qty' => $qty[$loop],
                'discounteach' => $discounteach[$loop],
                'pur_rate' => $pur_rate[$loop],
                'amount' => $amount[$loop]
            );
        }
        if ($insert) {
            $insert = $this->db->insert_batch('sales_details', $sales_details);
        }
        if ($insert) {
            for ($loop = 0; $loop < count($drug); $loop++) {
                $drugid = $drug[$loop];
                $purchase_quantity = $qty[$loop];
                $query = "update drug set stock=stock-" . $purchase_quantity . " where drug_id=" . $drugid;
                $update_stock_drug = $this->db->query($query);

                $query = "update stock set quantity=quantity-" . $purchase_quantity . " where drug=" . $drugid . " and company_id=" . $this->session->userdata('company_id');
                $update_stock = $this->db->query($query);
            }
        }
        if ($update_stock)
            echo "1";
    }

    function confirm_edit_sale()
    {
        /* Start Pay Status Calculation */
        $nettotal = $this->input->post('nettotal');
        $paid = $this->input->post('paid');
        $pay_status = 0;


        if ($nettotal == $paid) {
            $pay_status = 'Paid';
        } else if ($nettotal > $paid) {
            $pay_status = 'Due';
        }

        /* End Pay Status Calculation */
        $customer = $this->input->post('customer_id');
        $sales_id = $this->input->post('sales_id');

        $new_customer_data = array(
            'name' => $this->input->post('name'),
            'customer_type' => $this->input->post('customer_type'),
            'address' => $this->input->post('address'),
            'phone' => $this->input->post('phone'),
            'email' => $this->input->post('email')
        );

        $cust = $this->db->where('name', $customer)->get('customer');
        $next_customer_id = 0;

        $sales = array(
            'bill_no' => $this->input->post('bill_no'),
            'bill_date' => date('Y-m-d', strtotime($this->input->post('bill_date'))),
            'customer' => $this->input->post('customer_id'),
            'total' => $this->input->post('total'),
            'discount' => $this->input->post('discount'),
            'paid' => $this->input->post('paid'),
            'due' => $this->input->post('due'),
            'pay_status' => $pay_status,
            'nettotal' => $this->input->post('nettotal'),
            'user_id' => $this->session->userdata('user_id'),
            'company_id' => $this->session->userdata('company_id')
        );
        /* Start Sales Income Statement */
        $sales_income_statement = array(
            'bill_no' => $this->input->post('bill_no'),
            'bill_date' => date('Y-m-d', strtotime($this->input->post('bill_date'))),
            'customer' => $this->input->post('customer_id'),
            'paid_amount' => $this->input->post('total'),
        );
        $sql_customer_account = $this->db->where('customer_id', $this->input->post('customer_id'))
            ->get('customer_account')->row();
        $previous_nettotal = $this->input->post('previous_nettotal');
        $previous_paid = $this->input->post('previous_paid');
        $previous_due = $this->input->post('previous_due');
        $customer_account_data = array(
            'total_amount' => $sql_customer_account->total_amount + $this->input->post('nettotal') - $previous_nettotal,
            'paid_amount' => $sql_customer_account->paid_amount + $this->input->post('paid') - $previous_paid,
            'due_amount' => $sql_customer_account->due_amount + $this->input->post('due') - $previous_due,
        );
        $this->db->where('customer_id', $this->input->post('customer_id'))
            ->update('customer_account', $customer_account_data); //Update customer account for old customer          

        $this->db->insert('sales_income_statement', $sales_income_statement);
        /* End Sales Income Statement */
        $this->db->where('sales_id', $sales_id)
            ->update('sales', $sales);
        //            if ($insert)

        $type = $this->input->post('type');
        if ($type == 'return') {
            echo $retun = $this->return_sales();
            return;
        }

        $purchase_details = array();
        $drug = $this->input->post('drug');
        // echo '<pre>';
        // print_r($drug);
        // die();
        $type_name = $this->input->post('type_name');
        $sales_rate = $this->input->post('sales_rate');
        $qty = $this->input->post('qty');
        $discounteach = $this->input->post('discounteach');
        $pur_rate = $this->input->post('pur_rate');
        $amount = $this->input->post('amount');
        $this->db->where('sales_id', $sales_id)->delete('sales_details');
        for ($loop = 0; $loop < count($drug); $loop++) {
            $sales_details[] = array(
                'sales_id' => $sales_id,
                'drug' => $drug[$loop],
                'drug_type_id' => $type_name[$loop],
                'sales_rate' => $sales_rate[$loop],
                'qty' => $qty[$loop],
                'discounteach' => $discounteach[$loop],
                'pur_rate' => $pur_rate[$loop],
                'amount' => $amount[$loop]
            );
        }

        $this->db->insert_batch('sales_details', $sales_details);

        echo "1";
    }

    function sale_return()
    {
        $this->load->view('sales_return', $this->defaults);
    }

    function due_pay_view()
    {


        $data = array();
        $data['customer_id'] = $this->input->post('customer_id');

        $this->load->view('due_pay_view', $data);
    }

    function due_pay_data_save()
    {
        //        $bill_no = $this->input->post('bill_no');
        $nettotal = $this->input->post('nettotal');
        $paid = $this->input->post('paid');
        $remaining_due = $this->input->post('remaining_due');
        $date = $this->input->post('date');
        $customer_id = $this->input->post('customer_id');

        $payment_amount = $this->input->post('payment_amount');


        $update_paid_amount = $paid + $payment_amount;
        /* Update customer Account */
        $sql_customer_acc = $this->db->where('customer_id', $this->input->post('customer_id'))->get('customer_account')->row();
        $total_paid_amount = $sql_customer_acc->paid_amount + $this->input->post('payment_amount');
        $total_due_amount = $sql_customer_acc->due_amount - $this->input->post('payment_amount');
        $this->db->where('customer_id', $this->input->post('customer_id'))->update('customer_account', array('paid_amount' => $total_paid_amount, 'due_amount' => $total_due_amount));

        $due_collection = array(
            'date' => date('Y-m-d', strtotime($this->input->post('date'))),
            'customer_id' => $this->input->post('customer_id'),
            'amount' => $this->input->post('payment_amount'),
        );
        $this->db->insert('due_collection', $due_collection); //Insert Data to the due collection table
        /* Update customer Account */
        /* End Sales Income Statement */
        $insert_id = $this->db->insert_id();
        $data = array();
        $data['nettotal'] = $nettotal;
        $data['paid'] = $update_paid_amount;
        $data['payment_amount'] = $payment_amount;
        $data['due'] = $remaining_due;
        $data['bill_date'] = $date;
        $data['customer_id'] = $customer_id;
        $this->load->view('report/due_payment_print', $data);
    }

    public function supplier_pay_data_save()
    {

        $nettotal = $this->input->post('nettotal');
        $paid = $this->input->post('paid');
        $remaining_due = $this->input->post('remaining_due');
        $date = $this->input->post('date');
        $supplier_id = $this->input->post('supplier_id');

        $payment_amount = $this->input->post('payment_amount');


        $update_paid_amount = $paid + $payment_amount;
        /* Update customer Account */
        $sql_customer_acc = $this->db->where('supplier_id', $this->input->post('supplier_id'))->get('supplier_account')->row();
        $total_paid_amount = $sql_customer_acc->paid_amount + $this->input->post('payment_amount');
        $total_due_amount = $sql_customer_acc->due_amount - $this->input->post('payment_amount');
        $this->db->where('supplier_id', $this->input->post('supplier_id'))->update('supplier_account', array('paid_amount' => $total_paid_amount, 'due_amount' => $total_due_amount));

        $due_payment = array(
            'date' => date('Y-m-d', strtotime($this->input->post('date'))),
            'supplier_id' => $this->input->post('supplier_id'),
            'amount' => $this->input->post('payment_amount'),
        );
        $this->db->insert('due_payment', $due_payment); //Insert Data to the due collection table
        /* Update customer Account */
        /* End Sales Income Statement */
        //$insert_id = $this->db->insert_id();
        $data = array();
        $data['nettotal'] = $nettotal;
        $data['paid'] = $update_paid_amount;
        $data['payment_amount'] = $payment_amount;
        $data['due'] = $remaining_due;
        $data['date'] = $date;
        $data['supplier_id'] = $supplier_id;
        $this->load->view('due_supplier_payment_print', $data);
    }

    function get_sales_details()
    {
        $data['sales_id'] = $this->input->post('sales_id');
        //        if (!$sales_id) {
        //            return;
        //        }
        $this->load->view('sales_details', $data);
    }

    function return_sales()
    {
        $sales = array(
            'bill_no' => $this->input->post('bill_no'),
            'bill_date' => date('Y-m-d', strtotime($this->input->post('bill_date'))),
            'sale_return_date' => date('Y-m-d', strtotime($this->input->post('sale_return_date'))),
            'customer' => $this->input->post('customer_name'),
            'total' => $this->input->post('total'),
            'discount' => $this->input->post('discount'),
            'payable_total' => $this->input->post('payable_total'),
            'user_id' => $this->session->userdata('user_id'),
            'company_id' => $this->session->userdata('company_id')
        );
        $insert = $this->db->insert('sales_return', $sales);
        if ($insert)
            $sales_return_id = $this->db->insert_id();
        $sales_details = array();
        $drug = $this->input->post('drug');
        $sales_rate = $this->input->post('sales_rate');
        $qty = $this->input->post('qty');
        $pur_rate = $this->input->post('pur_rate');
        $amount = $this->input->post('amount');
        for ($loop = 0; $loop < count($drug); $loop++) {
            $sales_details[] = array(
                'sales_return_id' => $sales_return_id,
                'drug' => $drug[$loop],
                'sales_rate' => $sales_rate[$loop],
                'qty' => $qty[$loop],
                'pur_rate' => $pur_rate[$loop],
                'amount' => $amount[$loop]
            );
        }
        if ($insert) {
            $insert = $this->db->insert_batch('sales_return_details', $sales_details);
        }
        /* Update customer account */
        $sql_customer_account = $this->db->where('customer_id', $this->input->post('customer_id'))->get('customer_account')->row();
        $update_total_amount = $sql_customer_account->total_amount - $this->input->post('payable_total');

        $update_due_amount = $sql_customer_account->due_amount - $this->input->post('payable_total');
        if ($update_due_amount < 0) {
            $update_paid_amount = $sql_customer_account->paid_amount + $update_due_amount;
            $update_due_amount = 0; /* To control due amount */
            $this->db->where('customer_id', $this->input->post('customer_id'))->update('customer_account', array('paid_amount' => $update_paid_amount, 'total_amount' => $update_total_amount, 'due_amount' => $update_due_amount));
        } else {
            $this->db->where('customer_id', $this->input->post('customer_id'))->update('customer_account', array('total_amount' => $update_total_amount, 'due_amount' => $update_due_amount));
        }


        /* Update customer account */
        if ($insert) {
            for ($loop = 0; $loop < count($drug); $loop++) {
                $drugid = $drug[$loop];
                $purchase_quantity = $qty[$loop];

                $query = "update drug set stock=stock+" . $purchase_quantity . " where drug_id=" . $drugid;
                $update_stock_drug = $this->db->query($query);

                $query = "update stock set quantity=quantity+" . $purchase_quantity . " where drug=" . $drugid . " and company_id=" . $this->session->userdata('company_id');
                $update_stock = $this->db->query($query);
            }
        }
        if ($update_stock)
            echo "1";
    }

    function details()
    {
        $drug = $this->input->post('drug');

        $sql = $this->db->where('drug_id', $drug)->get('drug')->row();
        $details = array(
            'stock' => $sql->stock,
            'pur_rate' => $sql->pur_rate,
            'mrp' => $sql->mrp,
            'wsr' => $sql->whole_sale_rate,
        );
        echo json_encode($details);
    }

    function details_purchase_return()
    {
        $drug = $this->input->post('drug');

        $sql = $this->db->where('drug_id', $drug)->get('drug')->row();
        $details = array(
            'stock' => $sql->stock,
            'boxrate' => $sql->boxrate,
            'pur_rate' => $sql->pur_rate,
            'mrp' => $sql->mrp,
            'wsr' => $sql->whole_sale_rate,
            'invoice_date' => $sql->expdate,
        );
        echo json_encode($details);
    }

    function stock_details()
    {
        $stock_data = $this->load->view('stock_details', $_POST, TRUE);
        echo $stock_data;
    }

    public function view_purchase()
    {
        $mrr = $this->input->post('mrr');
        $config['base_url'] = site_url('PurchaseController/view_purchase');
        $config['total_rows'] = $this->db->where('is_deleted', '0')->count_all('purchase');
        $config['per_page'] = "10";
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
        $config['prev_link'] = '« Previous'; // Previous link text
        $config['prev_tag_open'] = '<li class="prev">';
        $config['prev_tag_close'] = '</li>';
        $config['next_link'] = 'Next »'; // Next link text
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
        $data['detailsList'] = $this->ProductModel->get_purchase_details($config["per_page"], $data['page'], NULL, $mrr);

        $data['pagination'] = $this->pagination->create_links();
        $this->load->view('purchase/view_purchase', $data, true);

        $page_data = array(
            'page_name' => 'purchase/view_purchase',
            'page_title' => 'View Drug Purchase',
            'sidebar' => 'pharmacy/pharmacy_sidebar'
        );
        $this->load->view('content', $page_data);
    }
}
