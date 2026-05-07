<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ReportPharmacyController
 *
 * @author saiful
 */
class ReportPharmacyController extends CI_Controller
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

    public function medicine_sell_report()
    {
        $page_data = array(
            'page_name' => 'pharmacy/report/medicine_sell_report',
            'page_title' => 'Medicine Sell Report ',
            'sidebar' => 'report/report_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function medicine_sell_report_details($ids)
    {
        $data = array();
        $ids_array = explode('_', $ids);
        $data = array();
        $data['from_date'] = $ids_array[0];
        $data['to_date'] = $ids_array[1];
        $this->load->view('pharmacy/report/medicine_sell_report_details', $data);
    }
    public function medicine_sell_return_report()
    {
        $page_data = array(
            'page_name' => 'pharmacy/report/medicine_sell_return_report',
            'page_title' => 'Medicine Sell Report ',
            'sidebar' => 'report/report_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function medicine_sell_return_report_details($ids)
    {
        $data = array();
        $ids_array = explode('_', $ids);
        $data = array();
        $data['from_date'] = $ids_array[0];
        $data['to_date'] = $ids_array[1];
        $this->load->view('pharmacy/report/medicine_sell_return_report_details', $data);
    }
    public function medicine_purchase_report()
    {
        $page_data = array(
            'page_name' => 'pharmacy/report/medicine_purchase_report',
            'page_title' => 'Medicine Sell Report ',
            'sidebar' => 'report/report_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function medicine_purchase_report_details($ids)
    {
        $data = array();
        $ids_array = explode('_', $ids);
        $data = array();
        $data['from_date'] = $ids_array[0];
        $data['to_date'] = $ids_array[1];
        $this->load->view('pharmacy/report/medicine_purchase_report_details', $data);
    }

    public function medicine_purchase_supplier_details_report()
    {
        $page_data = array(
            'page_name' => 'pharmacy/report/medicine_purchase_supplier_details_report',
            'page_title' => 'Medicine Sell Report ',
            'sidebar' => 'report/report_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function medicine_purchase_supplier_details_report_load($ids)
    {
        $data = array();
        $ids_array = explode('_', $ids);
        $data = array();
        $data['from_date'] = $ids_array[0];
        $data['to_date'] = $ids_array[1];
        $data['supplier_id'] = $ids_array[2];
        $this->load->view('pharmacy/report/medicine_purchase_supplier_details_report_load', $data);
    }
    public function medicine_purchase_return_report()
    {
        $page_data = array(
            'page_name' => 'pharmacy/report/medicine_purchase_return_report',
            'page_title' => 'Medicine Sell Report ',
            'sidebar' => 'report/report_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function medicine_purchase_return_report_details($ids)
    {
        $data = array();
        $ids_array = explode('_', $ids);
        $data = array();
        $data['from_date'] = $ids_array[0];
        $data['to_date'] = $ids_array[1];
        $this->load->view('pharmacy/report/medicine_purchase_return_report_details', $data);
    }
    public function expired_medicine_report()
    {
        $page_data = array(
            'page_name' => 'pharmacy/report/expired_medicine_report',
            'page_title' => 'Medicine Sell Report ',
            'sidebar' => 'report/report_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function expired_medicine_report_details($ids)
    {
        $data = array();
        $ids_array = explode('_', $ids);
        $data = array();
        $data['from_date'] = $ids_array[0];
        $data['to_date'] = $ids_array[1];
        $this->load->view('pharmacy/report/expired_medicine_report_details', $data);
    }
    public function medicine_low_stock_report()
    {
        $page_data = array(
            'page_name' => 'pharmacy/report/medicine_low_stock_report',
            'page_title' => 'Medicine Sell Report ',
            'sidebar' => 'report/report_sidebar'
        );
        $this->load->view('content', $page_data);
    }
    public function medicine_low_stock_report_details($ids)
    {
        $data = array();
        $ids_array = explode('_', $ids);
        // URL decode the drug name to handle spaces and special characters
        $drug_name = urldecode($ids_array[0]);
        $data['drug_name'] = $drug_name;
        $data['low_stock_only'] = true;
        $this->load->view('pharmacy/report/medicine_low_stock_report_details', $data);
    }

    public function medicine_low_stock_report_details_with_search()
    {
        $data = array();
        // Get drug name from POST data to avoid URL encoding issues
        $drug_name = $this->input->post('drug_name');
        $data['drug_name'] = $drug_name ? trim($drug_name) : '';
        $data['low_stock_only'] = true;
        $this->load->view('pharmacy/report/medicine_low_stock_report_details', $data);
    }

    public function medicine_low_stock_report_details_without_parameter()
    {
        $data = array();
        $data['drug_name'] = '';
        $data['low_stock_only'] = true; // Flag to indicate this is for low stock report
        $this->load->view('pharmacy/report/medicine_low_stock_report_details', $data);
    }

    public function export_low_stock_to_excel()
    {
        // Get low stock medicines
        $low_stock_medicines = $this->get_low_stock_medicines();
        
        // Try to load PhpSpreadsheet, fallback to CSV if not available
        if (file_exists(APPPATH . 'third_party/PhpSpreadsheet/vendor/autoload.php')) {
            $this->export_low_stock_with_phpspreadsheet($low_stock_medicines);
        } else {
            $this->export_low_stock_with_simple_excel($low_stock_medicines);
        }
    }

    private function get_low_stock_medicines()
    {
        $this->db->select('*');
        $this->db->from('drug');
        $this->db->where('reorder_quantity >', 0); // Only drugs with reorder quantity set
        $this->db->order_by('drug_name', 'ASC');
        $all_drugs = $this->db->get()->result();

        $low_stock_drugs = [];
        foreach ($all_drugs as $drug) {
            $current_stock = getStock($drug->drug_id);
            if ($current_stock < $drug->reorder_quantity) {
                $drug->current_stock = $current_stock;
                $low_stock_drugs[] = $drug;
            }
        }

        return $low_stock_drugs;
    }

    private function export_low_stock_with_phpspreadsheet($medicines)
    {
        require_once(APPPATH . 'third_party/PhpSpreadsheet/vendor/autoload.php');

        // Create new Spreadsheet object
        $spreadsheet = new PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set document properties
        $spreadsheet->getProperties()
            ->setCreator('Hospital Management System')
            ->setTitle('Low Stock Medicines Report')
            ->setSubject('Low Stock Medicines Report')
            ->setDescription('Low Stock Medicines Report generated on ' . date('Y-m-d H:i:s'));

        // Set sheet title
        $sheet->setTitle('Low Stock Medicines');

        // Add headers
        $sheet->setCellValue('A1', 'Low Stock Medicines Report');
        $sheet->mergeCells('A1:H1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        $sheet->setCellValue('A2', 'Generated on: ' . date('d-m-Y H:i:s'));
        $sheet->mergeCells('A2:H2');
        $sheet->getStyle('A2')->getAlignment()->setHorizontal(PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        // Set column headers
        $headers = ['Sl', 'Medicine Name', 'Current Stock', 'Reorder Quantity', 'Shortage', 'MRP', 'Purchase Rate', 'Status'];
        $columnLetters = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H'];

        for ($i = 0; $i < count($headers); $i++) {
            $sheet->setCellValue($columnLetters[$i] . '4', $headers[$i]);
            $sheet->getStyle($columnLetters[$i] . '4')->getFont()->setBold(true);
        }

        // Add data
        $row = 5;
        $sl = 1;
        foreach ($medicines as $medicine) {
            $shortage = $medicine->reorder_quantity - $medicine->current_stock;
            $status = ($medicine->current_stock <= 0) ? 'Out of Stock' : 'Low Stock';

            $sheet->setCellValue('A' . $row, $sl++);
            $sheet->setCellValue('B' . $row, $medicine->drug_name);
            $sheet->setCellValue('C' . $row, $medicine->current_stock);
            $sheet->setCellValue('D' . $row, $medicine->reorder_quantity);
            $sheet->setCellValue('E' . $row, $shortage);
            $sheet->setCellValue('F' . $row, $medicine->mrp ?? 0);
            $sheet->setCellValue('G' . $row, $medicine->purchase_rate ?? 0);
            $sheet->setCellValue('H' . $row, $status);
            $row++;
        }

        // Auto-size columns
        foreach ($columnLetters as $letter) {
            $sheet->getColumnDimension($letter)->setAutoSize(true);
        }

        // Set filename and headers for download
        $filename = 'low_stock_medicines_' . date('Y-m-d') . '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer = new PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

    private function export_low_stock_with_simple_excel($medicines)
    {
        $this->load->library('Simple_excel');
        
        $this->simple_excel->set_filename('low_stock_medicines_' . date('Y-m-d') . '.csv');
        
        // Add headers
        $headers = ['Sl', 'Medicine Name', 'Current Stock', 'Reorder Quantity', 'Shortage', 'MRP', 'Purchase Rate', 'Status'];
        $this->simple_excel->add_headers($headers);
        
        // Add data
        $sl = 1;
        foreach ($medicines as $medicine) {
            $shortage = $medicine->reorder_quantity - $medicine->current_stock;
            $status = ($medicine->current_stock <= 0) ? 'Out of Stock' : 'Low Stock';

            $row_data = [
                $sl++,
                $medicine->drug_name,
                $medicine->current_stock,
                $medicine->reorder_quantity,
                $shortage,
                $medicine->mrp ?? 0,
                $medicine->purchase_rate ?? 0,
                $status
            ];
            
            $this->simple_excel->add_row($row_data);
        }
        
        $this->simple_excel->output();
    }

    public function print_low_stock_report()
    {
        $data['low_stock_medicines'] = $this->get_low_stock_medicines();
        $this->load->view('pharmacy/report/print_low_stock_report', $data);
    }
    public function medicine_stock_report()
    {
        $page_data = array(
            'page_name' => 'pharmacy/report/medicine_stock_report',
            'page_title' => 'Medicine Sell Report ',
            'sidebar' => 'report/report_sidebar'
        );
        $this->load->view('content', $page_data);
    }

    public function medicine_stock_report_details($ids)
    {
        $data = array();
        $ids_array = explode('_', $ids);
        $data = array();
        $data['drug_name'] = $ids_array[0];

        $this->load->view('pharmacy/report/medicine_stock_report_details', $data);
    }
    public function medicine_stock_report_details_without_parameter()
    {
        $data = array();

        $data = array();
        $data['drug_name'] = '';

        $this->load->view('pharmacy/report/medicine_stock_report_details', $data);
    }
}
