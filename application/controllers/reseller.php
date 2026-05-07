<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Reseller extends CI_Controller {

    /**
     * Optical mark reader software
     * Developed by Shafayat Hossain Masum
     * Seniour Programmer
     * Khuji.com/Zaman IT
     */
    private $defaults = array();

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        if (!is_reseller())
            return;
        $this->load->model('user_model');
        $user_id = $this->session->userdata('user_id');
        $condition = array('user_id' => $user_id);
        $details = $this->user_model->userdetails($condition);
        $this->defaults['userinfo'] = $details;
        if (!$details['reseller']) {
            $navivationleft = array('myaccount|My Account');
        } else {
            $navivationleft = array('myaccount|My Account', 'reseller|Reseller Data');
        }
        $userlistcondition = array(
            "parentid" => $user_id
        );
        $uselist = $this->user_model->userlist($userlistcondition);
        $this->defaults['navigationleft'] = $navivationleft;
        $this->defaults['userlist'] = $uselist;
        $this->load->view('reseller', $this->defaults, $details);
    }

    public function customer_load() {
        // print_r();
        // die;
        $customer_type = $_POST['customer_type'];
        $sql = $this->db->where('customer_type', $customer_type)->get('customer')->result();
        ?>
<option>Select</option>
            <option>New Customer</option>
            <?php
        foreach ($sql as $value) {
            
            ?>
            <option value="<?php echo $value->customer_id ?>"><?php echo $value->name ?></option>
            <?php
        }
    }
    public function customer_load_deu()
    {
        $customer_type = $_POST['customer_type'];
        $sql = $this->db->where('customer_type', $customer_type)->get('customer')->result();
        ?>
<option></option>
            <option></option>
            <?php
        foreach ($sql as $value) {
            
            ?>
            
            <option value="<?php echo $value->customer_id ?>"><?php echo $value->name . '-' . $value->customer_type . '-' . $value->address ?></option>
            <?php
        }
    }
    public function sales_id_load() {
                $customer_id = $_POST['customer_id'];
        $sql = $this->db->where('customer', $customer_id)->get('sales')->result();
        ?>
<option>Select Bill No</option>
            <option></option>
            <?php
        foreach ($sql as $value) {
            
            ?>
            
            <option value="<?php echo $value->sales_id ?>"><?php echo $value->bill_no?></option>
            <?php
        }
    }

    public function customer_due_load() {
        $customer_id = $_POST['customer_id'];
        $sql = $this->db->where('customer_id', $customer_id)->select_sum('due_amount')->get('customer_account')->row();
        echo $sql->due_amount;
    }

    public function customer_info_load() {
        ?>

        <td width="80px;">Name</td>
        <td align="left" style="padding:5px;"><input type="text" required="" id="name" name="name" class="form-control" /></td>
        <td width="80px;">Address</td>
        <td align="left" style="padding:5px;"><input type="text"  id="address" name="address" class="form-control" /></td>

        <?php
    }

    public function customer_info_load1() {
        ?>


        <td width="80px;">Phone</td>
        <td align="left" style="padding:5px;"><input type="text"  id="phone" name="phone" class="form-control" /></td>
        <td width="80px;">Email</td>
        <td align="left" style="padding:5px;"><input type="text"  id="email" name="email" class="form-control" /></td>



        <?php
    }
    public function due_pay_view()
    {
        $this->load->view('due_pay_view');
    }
            function addcredit() {
        if (!is_reseller())
            return;
        $error = array();
        $user_id = $this->input->post("subuser_id");
        $credit = $this->input->post("subusercredit");
        $parentid = $this->session->userdata('user_id');
        $conditioncheckparent = array("user_id" => $user_id, "parentid" => $parentid);
        $isparent = $this->user_model->checkparent($conditioncheckparent);
        if (!$isparent) {
            $error['user_id'] = "This user is not in your user list.";
        } else {
            if ($this->validation->numeric($credit)) {
                $conditioncredit = array('user_id' => $parentid);
                $details = $this->user_model->userdetails($conditioncredit);
                $owncredit = $details['credit'];
                if ($owncredit > 0) {
                    if ($owncredit < $credit) {
                        $error['credit'] = 'You can not share more than ' . $owncredit . ' credit';
                    }
                } else if ($owncredit == 0) {
                    if ($owncredit < $credit) {
                        $error['credit'] = 'You have no credit to share';
                    }
                }
            } else {
                $error['credit'] = 'Credit allows numeric value only.';
            }
        }
        $childdetilscondition = array('user_id' => $user_id);
        $childdetails = $this->user_model->userdetails($childdetilscondition);
        $currentcredit = $childdetails['credit'];
        $updatedcredit = $credit + $currentcredit;
        if ($updatedcredit < 0) {
            $error['credit'] = 'Credit can not be a negetive value.';
        }
        if (count($error)) {
            $error = implode($error, "<br>");
            $result = result('danger', $error);
            echo json_encode(array("type" => "error", "result" => $result));
            return;
        }
        $result = $this->user_model->updatecredit($user_id, $credit);
        echo json_encode(array("type" => "success", "Credit Updated"));
    }

}
?>