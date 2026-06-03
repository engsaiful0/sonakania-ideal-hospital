<?php
defined('BASEPATH') or exit('No direct script access allowed');

if (!function_exists('generate_password')) {
    function generate_password($length = 11)
    {
        // Generate a random string
        $randomString = bin2hex(random_bytes(8)); // 16 characters hex string

        // Hash the random string using MD5
        $md5Hash = md5($randomString);

        // Trim the MD5 hash to get the desired password length
        $password = substr($md5Hash, 0, $length);

        return $password;
    }
}

function get_test_group_by_id($test_group_id)
{
    // Get CodeIgniter instance
    $CI = get_instance();
    // Load the database if not already loaded
    $CI->load->database();
    $test_group = $CI->db->where('test_group_id', $test_group_id)->get('test_group')->row();
    if (!$test_group) {
        return null;
    }
    return $test_group;
}
function getStock($drug_id, $start_date = '2025-09-01')
{
    // Get CodeIgniter instance
    $CI = get_instance();

    // Load the database if not already loaded
    $CI->load->database();

    // Fetch the drug's opening stock
    $drug = $CI->db->where('drug_id', $drug_id)->get('drug')->row();
    $opening_stock = $drug ? $drug->opening_stock : 0;

    // -------------------------
    // Purchase quantity
    // -------------------------
    $purchase_quantity = $CI->db
        ->select('SUM(mpd.quantity + mpd.bonus_quantity) AS quantity')
        ->from('medicine_purchase_details mpd')
        ->join('medicine_purchase mp', 'mp.medicine_purchase_id = mpd.medicine_purchase_id')
        ->where('mpd.drug_id', $drug_id)
        ->where('mp.date >=', $start_date)
        ->get()
        ->row();

    $total_purchase_quantity = $purchase_quantity ? $purchase_quantity->quantity : 0;

    // -------------------------
    // Purchase return quantity
    // -------------------------
    $purchase_return_quantity = $CI->db
        ->select_sum('mprd.return_quantity', 'quantity')
        ->from('medicine_purchase_return_details mprd')
        ->join('medicine_purchase_return mpr', 'mpr.medicine_purchase_return_id = mprd.medicine_purchase_return_id')
        ->where('mprd.drug_id', $drug_id)
        ->where('mpr.date >=', $start_date)
        ->get()
        ->row();

    $total_purchase_return_quantity = $purchase_return_quantity ? $purchase_return_quantity->quantity : 0;

    // -------------------------
    // Sale quantity
    // -------------------------
    $sale_quantity = $CI->db
        ->select_sum('msd.quantity', 'quantity')
        ->from('medicine_sales_details msd')
        ->join('medicine_sales ms', 'ms.medicine_sale_id = msd.medicine_sale_id')
        ->where('msd.drug_id', $drug_id)
        ->where('ms.bill_date >=', $start_date)
        ->get()
        ->row();

    $total_sale_quantity = $sale_quantity ? $sale_quantity->quantity : 0;

    // -------------------------
    // Sale return without invoice quantity
    // -------------------------
    $sale_return_without_invoice_quantity = $CI->db
        ->select_sum('msd.quantity', 'quantity')
        ->from('medicine_sale_return_details_without_invocie msd')
        ->join('medicine_sale_returns_without_invoice ms', 'ms.medicine_sale_return_id_without_invoice = msd.medicine_sale_return_id_without_invoice')
        ->where('msd.drug_id', $drug_id)
        ->where('ms.return_date >=', $start_date)
        ->get()
        ->row();

    $total_sale_return_without_invoice_quantity = $sale_return_without_invoice_quantity ? $sale_return_without_invoice_quantity->quantity : 0;

    // -------------------------
    // Sale return quantity
    // -------------------------
    $sale_return_quantity = $CI->db
        ->select_sum('msrd.return_quantity', 'quantity')
        ->from('medicine_sale_return_details msrd')
        ->join('medicine_sale_return msr', 'msr.medicine_sale_return_id = msrd.medicine_sale_return_id')
        ->where('msrd.drug_id', $drug_id)
        ->where('msr.date >=', $start_date)
        ->get()
        ->row();

    $total_sale_return_quantity = $sale_return_quantity ? $sale_return_quantity->quantity : 0;

    // -------------------------
    // Expired quantity
    // -------------------------
    $expired_quantity = $CI->db
        ->select_sum('emd.quantity', 'quantity')
        ->from('expired_medicine_details emd')
        ->join('expired_medicines em', 'em.expired_medicine_id = emd.expired_medicine_id')
        ->where('emd.drug_id', $drug_id)
        ->where('em.date >=', $start_date)
        ->get()
        ->row();

    $total_expired_quantity = $expired_quantity ? $expired_quantity->quantity : 0;

    // -------------------------
    // Final stock calculation
    // -------------------------
    $total_stock = $opening_stock + $total_sale_return_without_invoice_quantity
        + ($total_purchase_quantity - $total_purchase_return_quantity)
        + ($total_sale_return_quantity - $total_sale_quantity)
        - $total_expired_quantity;
    return $total_stock;
    // Return total stock ensuring it is not negative
    // return max($total_stock, 0);
}


function getShelf($shelf_id)
{
    // Get CodeIgniter instance
    $CI = get_instance();

    // Load the database if not already loaded
    $CI->load->database();
    $shelf = $CI->db->where('shelf_id', $shelf_id)->get('shelfs')->row();
    return $shelf;
}
function getAllPanelTest()
{
    // Get CodeIgniter instance
    $CI = get_instance();
    // Load the database if not already loaded
    $CI->load->database();
    $panel_tests = $CI->db->order_by('panel_name','asc')->get('test_panels')->result();
    return $panel_tests;    
}
function getAllNurses()
{
    // Get CodeIgniter instance
    $CI = get_instance();

    // Load the database if not already loaded
    $CI->load->database();

    // Fetch nurses
    $nurses = $CI->db->select('employee.*')
        ->from('employee')
        ->join('men_power_categories', 'employee.men_power_category_id = men_power_categories.men_power_category_id')
        ->where('men_power_categories.name', 'Nurse')
        ->get()
        ->result();

    return $nurses;
}

function getAllTestNames()
{
    // Get CodeIgniter instance
    $CI = get_instance();
    // Load the database if not already loaded
    $CI->load->database();
    $tests = $CI->db->select('*')->order_by('test_name')->get('test')->result();
    return $tests;
}
function getAllTestNamesByGroup($test_group_id)
{
    // Get CodeIgniter instance
    $CI = get_instance();
    // Load the database if not already loaded
    $CI->load->database();
    $tests = $CI->db->where('test_group_id', $test_group_id)->order_by('test_name')->get('test')->result();
    return $tests;
}
function getManufacturer($manufacturer_id)
{
    // Get CodeIgniter instance
    $CI = get_instance();
    // Load the database if not already loaded
    $CI->load->database();
    $manufacturer = $CI->db->where('manufacturer_id', $manufacturer_id)->get('manufacturer')->row();
    return $manufacturer;
}
function getGoodsItem($item_id)
{
    // Get CodeIgniter instance
    $CI = get_instance();
    // Load the database if not already loaded
    $CI->load->database();
    $item = $CI->db->where('item_id', $item_id)->get('item')->row();
    return $item;
}
function getMonth($month_id)
{
    // Get CodeIgniter instance
    $CI = get_instance();
    // Load the database if not already loaded
    $CI->load->database();
    $month = $CI->db->where('month_id', $month_id)->get('month')->row();
    return $month;
}
function getYear($year_id)
{
    // Get CodeIgniter instance
    $CI = get_instance();
    // Load the database if not already loaded
    $CI->load->database();
    $year = $CI->db->where('year_id', $year_id)->get('year')->row();
    return $year;
}
function getCompany()
{
    // Get CodeIgniter instance
    $CI = get_instance();
    // Load the database if not already loaded
    $CI->load->database();
    $company = $CI->db->where('company_id', '1')->get('company')->row();;
    return $company;
}

function getCanteenRawGoodsItem($canteen_raw_goods_id)
{
    // Get CodeIgniter instance
    $CI = get_instance();
    // Load the database if not already loaded
    $CI->load->database();
    $canteen_good = $CI->db->where('canteen_raw_goods_id', $canteen_raw_goods_id)->get('canteen_raw_goods')->row();
    return $canteen_good;
}
function getCanteenReadyItem($canteen_ready_item_id)
{
    // Get CodeIgniter instance
    $CI = get_instance();
    // Load the database if not already loaded
    $CI->load->database();
    $canteen_ready_items = $CI->db->where('canteen_ready_item_id', $canteen_ready_item_id)->get('canteen_ready_items')->row();
    return $canteen_ready_items;
}
function getCanteenGoods($canteen_raw_goods_id)
{
    // Get CodeIgniter instance
    $CI = get_instance();
    // Load the database if not already loaded
    $CI->load->database();
    $canteen_goods = $CI->db->where('canteen_raw_goods_id', $canteen_raw_goods_id)->get('canteen_raw_goods')->row();
    return $canteen_goods;
}

function getDrugType($drug_type_id)
{
    // Get CodeIgniter instance
    $CI = get_instance();
    // Load the database if not already loaded
    $CI->load->database();
    $drug_type = $CI->db->where('drug_type_id', $drug_type_id)->get('drug_type')->row();
    return $drug_type;
}
function getDrug($drug_id)
{
    // Get CodeIgniter instance
    $CI = get_instance();
    // Load the database if not already loaded
    $CI->load->database();
    $drug = $CI->db->where('drug_id', $drug_id)->get('drug')->row();
    return $drug;
}
function getBankName($bank_name_id)
{
    // Get CodeIgniter instance
    $CI = get_instance();
    // Load the database if not already loaded
    $CI->load->database();
    $bank_names = $CI->db->where('bank_name_id', $bank_name_id)->get('bank_name')->row();
    return $bank_names;
}
function getBankAccountNumber($bank_account_id)
{
    // Get CodeIgniter instance
    $CI = get_instance();
    // Load the database if not already loaded
    $CI->load->database();
    $bank_account = $CI->db->where('bank_account_id', $bank_account_id)->get('bank_accounts')->row();
    return $bank_account;
}

function getEmployee($employee_id)
{
    // Get CodeIgniter instance
    $CI = get_instance();
    // Load the database if not already loaded
    $CI->load->database();
    $employees = $CI->db->where('employee_id', $employee_id)->get('employee')->row();
    return $employees;
}
function getUnit($unit_id)
{
    // Get CodeIgniter instance
    $CI = get_instance();
    // Load the database if not already loaded
    $CI->load->database();
    $unit = $CI->db->where('unit_id', $unit_id)->get('units')->row();
    return $unit;
}
function getAttendance($employee_id, $date)
{
    // Get CodeIgniter instance
    $CI = get_instance();
    // Load the database if not already loaded
    $CI->load->database();
    $attendance = $CI->db->where('employee_id', $employee_id)->where('date', $date)->get('attendance')->row();
    return $attendance;
}
function getUserById($user_id)
{
    // Get CodeIgniter instance
    $CI = get_instance();
    // Load the database if not already loaded
    $CI->load->database();
    $user = $CI->db->where('user_id', $user_id)->get('user')->row();
    return $user;
}
function getDirector($director_id)
{
    // Get CodeIgniter instance
    $CI = get_instance();
    // Load the database if not already loaded
    $CI->load->database();
    $directors = $CI->db->where('director_id', $director_id)->get('director')->row();
    return $directors;
}
function getAllDirectors()
{
    // Get CodeIgniter instance
    $CI = get_instance();
    // Load the database if not already loaded
    $CI->load->database();
    $directors = $CI->db->select('')->get('director')->result();
    return $directors;
}
function getPatientTestEntry($patient_test_entry_id)
{
    // Get CodeIgniter instance
    $CI = get_instance();
    // Load the database if not already loaded
    $CI->load->database();
    $patient_test_entry = $CI->db->where('patient_test_entry_id', $patient_test_entry_id)->get('patient_test_entry')->row();
    return $patient_test_entry;
}
function has_permission($permission_key)
{
    $permissions = isset($_SESSION['permissions']) ? $_SESSION['permissions'] : [];
    return in_array($permission_key, $permissions);
}


function getSMSAPI()
{
    // Get CodeIgniter instance
    $CI = get_instance();
    // Load the database if not already loaded
    $CI->load->database();
    $sms_api = $CI->db->where('id', '1')->get('sms_api')->row();
    return $sms_api;
}
function getDoctorById($doctor_id)
{
    // Get CodeIgniter instance
    $CI = get_instance();
    // Load the database if not already loaded
    $CI->load->database();
    $doctor = $CI->db->where('doctor_id', $doctor_id)->get('doctor')->row();
    return $doctor;
}

function makeBedAvailable($bed_id)
{
    // Get CodeIgniter instance
    $CI = get_instance();
    // Load the database if not already loaded
    $CI->load->database();
    $CI->db->where('bed_id', $bed_id)->update('bed', [
        'status' => 'Available',
        'ipd_patient_id' => '',
    ]);
    return true;
}
function makeCabinAvailable($cabin_id)
{
    // Get CodeIgniter instance
    $CI = get_instance();
    // Load the database if not already loaded
    $CI->load->database();
    $CI->db->where('cabin_id', $cabin_id)->update('cabin', [
        'status' => 'Available',
        'ipd_patient_id' => '',
    ]);
    return true;
}
function getDischargeReasonByID($discharge_reason_id)
{
    // Get CodeIgniter instance
    $CI = get_instance();
    // Load the database if not already loaded
    $CI->load->database();
    $discharge_reason = $CI->db->where('discharge_reason_id', $discharge_reason_id)->get('discharge_reasons')->row();
    return $discharge_reason;
}
function getSugeryById($surgery_id)
{
    // Get CodeIgniter instance
    $CI = get_instance();
    // Load the database if not already loaded
    $CI->load->database();
    $surgery = $CI->db->where('surgery_id', $surgery_id)->get('surgeries')->row();
    return $surgery;
}

function getSupplier($supplier_id)
{
    // Get CodeIgniter instance
    $CI = get_instance();
    // Load the database if not already loaded
    $CI->load->database();
    $suppliers = $CI->db->where('supplier_id', $supplier_id)->get('supplier')->row();
    return $suppliers;
}
function getCanteenGoodsSupplier($canteen_goods_supplier_id)
{
    // Get CodeIgniter instance
    $CI = get_instance();
    // Load the database if not already loaded
    $CI->load->database();
    $suppliers = $CI->db->where('canteen_goods_supplier_id', $canteen_goods_supplier_id)->get('canteen_goods_supplier')->row();
    return $suppliers;
}

function getDebitAccount($debit_account_id)
{
    // Get CodeIgniter instance
    $CI = get_instance();
    // Load the database if not already loaded
    $CI->load->database();
    $debit_account = $CI->db->where('debit_account_id', $debit_account_id)->get('debit_account')->row();
    return $debit_account;
}
function getCreditAccount($credit_account_id)
{
    // Get CodeIgniter instance
    $CI = get_instance();
    // Load the database if not already loaded
    $CI->load->database();
    $credit_account = $CI->db->where('credit_account_id', $credit_account_id)->get('credit_account')->row();
    return $credit_account;
}
function convertNumberToWord($num = false, $currency = false)
{
    // Clean input: remove commas, spaces, and trim
    $num = str_replace(array(',', ' '), '', trim($num));
    if (!$num || !is_numeric($num)) {
        return false;
    }
    $num = (int) $num;
    if ($num == 0) {
        return "Zero" . ($currency ? " " . $currency : "");
    }

    // Word lists for units, tens, and hundreds
    $list1 = array(
        '',
        'One',
        'Two',
        'Three',
        'Four',
        'Five',
        'Six',
        'Seven',
        'Eight',
        'Nine',
        'Ten',
        'Eleven',
        'Twelve',
        'Thirteen',
        'Fourteen',
        'Fifteen',
        'Sixteen',
        'Seventeen',
        'Eighteen',
        'Nineteen'
    );
    $list2 = array(
        '',
        'Ten',
        'Twenty',
        'Thirty',
        'Forty',
        'Fifty',
        'Sixty',
        'Seventy',
        'Eighty',
        'Ninety'
    );
    $list3 = array(
        '',           // 10^0 (units)
        'Thousand',   // 10^3
        'Lakh',       // 10^5
        'Crore',      // 10^7
        'Arab',       // 10^9 (Billion)
        'Kharab',     // 10^11
        'Neel',       // 10^13
        'Padma',      // 10^15
        'Shankh'      // 10^17
    );

    // Reverse the number string for grouping
    $num_str = strrev((string) $num);
    $groups = array();

    // Group digits: first 3 digits, then pairs of 2 digits
    $groups[] = substr($num_str, 0, 3); // Units, tens, hundreds
    $remaining = substr($num_str, 3);
    for ($i = 0; $i < strlen($remaining); $i += 2) {
        $groups[] = substr($remaining, $i, 2);
    }

    $words = array();
    // Process each group from lowest to highest unit
    for ($i = 0; $i < count($groups); $i++) {
        $group = (int) strrev($groups[$i]); // Reverse back and convert to integer
        if ($group) {
            $hundreds = (int) ($group / 100);
            $hundreds_str = ($hundreds ? $list1[$hundreds] . ' Hundred ' : '');
            $tens = $group % 100;
            $tens_str = '';
            $singles_str = '';

            if ($tens < 20) {
                $tens_str = ($tens ? $list1[$tens] . ' ' : '');
            } else {
                $tens_val = (int) ($tens / 10);
                $tens_str = $list2[$tens_val] . ' ';
                $singles_val = $tens % 10;
                $singles_str = ($singles_val ? $list1[$singles_val] . ' ' : '');
            }

            $level_word = ($group && isset($list3[$i])) ? $list3[$i] . ' ' : '';
            $segment = trim($hundreds_str . $tens_str . $singles_str . $level_word);
            if ($segment) {
                $words[] = $segment;
            }
        }
    }

    // Reverse to get highest unit first and join with spaces
    $result = implode(' ', array_reverse($words));
    return $currency ? $result . ' ' . $currency : $result;
}
function convertNumberToWord_english($num = false)
{
    $num = str_replace(array(',', ' '), '', trim($num));
    if (!$num) {
        return false;
    }
    $num = (int) $num;
    $words = array();
    $list1 = array(
        '',
        'One',
        'Two',
        'Three',
        'Four',
        'Five',
        'Six',
        'Seven',
        'Eight',
        'Nine',
        'Ten',
        'Eleven',
        'Twelve',
        'Thirteen',
        'Fourteen',
        'Fifteen',
        'Sixteen',
        'Seventeen',
        'Eighteen',
        'Nineteen'
    );
    $list2 = array('', 'Ten', 'Twenty', 'Thirty', 'Forty', 'Fifty', 'Sixty', 'Seventy', 'Eighty', 'Ninety', 'Hundred');
    $list3 = array(
        '',
        'Thousand',
        'Million',
        'Billion',
        'Trillion',
        'Quadrillion',
        'Quintillion',
        'Sextillion',
        'Septillion',
        'Octillion',
        'Nonillion',
        'Decillion',
        'Undecillion',
        'Duodecillion',
        'Tredecillion',
        'Quattuordecillion',
        'Quindecillion',
        'Sexdecillion',
        'Septendecillion',
        'Octodecillion',
        'Novemdecillion',
        'Vigintillion'
    );
    $num_length = strlen($num);
    $levels = (int) (($num_length + 2) / 3);
    $max_length = $levels * 3;
    $num = substr('00' . $num, -$max_length);
    $num_levels = str_split($num, 3);
    for ($i = 0; $i < count($num_levels); $i++) {
        $levels--;
        $hundreds = (int) ($num_levels[$i] / 100);
        $hundreds = ($hundreds ? ' ' . $list1[$hundreds] . ' hundred' . ' ' : '');
        $tens = (int) ($num_levels[$i] % 100);
        $singles = '';
        if ($tens < 20) {
            $tens = ($tens ? ' ' . $list1[$tens] . ' ' : '');
        } else {
            $tens = (int) ($tens / 10);
            $tens = ' ' . $list2[$tens] . ' ';
            $singles = (int) ($num_levels[$i] % 10);
            $singles = ' ' . $list1[$singles] . ' ';
        }
        $words[] = $hundreds . $tens . $singles . (($levels && (int) ($num_levels[$i])) ? ' ' . $list3[$levels] . ' ' : '');
    } //end for loop
    $commas = count($words);
    if ($commas > 1) {
        $commas = $commas - 1;
    }
    return implode(' ', $words);
}

/**
 * Calculate current share value based on original amount, yearly increment rate, and date of join
 * Uses compound interest formula: Current Value = Original Value × (1 + rate/100)^years
 * 
 * @param float $original_amount - Original share value/amount per share
 * @param float $yearly_increment_rate - Yearly increment rate as percentage
 * @param string $date_of_join - Date when the shareholder joined (Y-m-d format)
 * @return float - Current share value rounded to 2 decimal places
 */
function calculate_current_share_value($original_amount, $yearly_increment_rate = 0, $date_of_join = '')
{
    // Convert to float values
    $original_amount = (float) $original_amount;
    $yearly_increment_rate = (float) $yearly_increment_rate;
    
    // If no original amount, return 0
    if ($original_amount <= 0) {
        return 0;
    }
    
    $date_of_join = trim((string) $date_of_join);

    // If no date of join or increment rate, return original amount
    if ($date_of_join === '' || $yearly_increment_rate <= 0) {
        return round($original_amount, 2);
    }

    // MySQL zero / invalid dates — PHP maps 0000-00-00 to year -1 and ~2k+ "years" of compounding
    if ($date_of_join === '0000-00-00' || strncmp($date_of_join, '0000-', 5) === 0) {
        return round($original_amount, 2);
    }

    try {
        // Create DateTime objects
        $join_date = new DateTime($date_of_join);
        $current_date = new DateTime();

        $join_year = (int) $join_date->format('Y');
        $current_year = (int) $current_date->format('Y');
        if ($join_year < 1900 || $join_year > $current_year + 1) {
            return round($original_amount, 2);
        }

        // Calculate the difference
        $interval = $join_date->diff($current_date);

        // $interval->days (from diff()) is total calendar days — do not add $interval->y
        // or full years are counted twice and compound growth explodes.
        if ($interval->invert === 1) {
            $years_diff = 0;
        } else {
            $years_diff = $interval->days / 365.25;
        }

        // Guard against bad data / calendar edge cases blowing up pow()
        $years_diff = min(max($years_diff, 0), 200);

        // Apply compound interest formula: Current Value = Original Value × (1 + rate/100)^years
        $current_value = $original_amount * pow(1 + ($yearly_increment_rate / 100), $years_diff);

        if (!is_finite($current_value)) {
            return round($original_amount, 2);
        }

        // Round to 2 decimal places
        return round($current_value, 2);
        
    } catch (Exception $e) {
        // If date parsing fails, return original amount
        return round($original_amount, 2);
    }
}

/**
 * Get shareholder current share value by ID
 * Retrieves shareholder data and calculates current share value
 * 
 * @param int $share_holder_id - ID of the shareholder
 * @return float - Current share value
 */
function get_shareholder_current_value($share_holder_id)
{
    // Get CodeIgniter instance
    $CI = get_instance();
    // Load the database if not already loaded
    $CI->load->database();
    
    // Get shareholder data
    $shareholder = $CI->db->where('id', $share_holder_id)->get('share_holder')->row();
    
    if (!$shareholder) {
        return 0;
    }
    
    // Calculate current share value
    return calculate_current_share_value(
        $shareholder->amount_per_share,
        $shareholder->yearly_share_value_increment_rate,
        $shareholder->date_of_join
    );
}