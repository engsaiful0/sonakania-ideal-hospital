<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Simple Excel Library - Fallback for systems without PhpSpreadsheet
 * This creates CSV files that open in Excel
 */
class Simple_excel
{
    protected $CI;
    protected $data = array();
    protected $filename = 'export.csv';
    
    public function __construct()
    {
        $this->CI =& get_instance();
    }
    
    public function set_filename($filename)
    {
        $this->filename = $filename;
    }
    
    public function add_row($row_data)
    {
        $this->data[] = $row_data;
    }
    
    public function add_headers($headers)
    {
        array_unshift($this->data, $headers);
    }
    
    public function output($dest = 'D')
    {
        if ($dest == 'D') {
            // Set headers for CSV download
            header('Content-Type: text/csv');
            header('Content-Disposition: attachment; filename="' . $this->filename . '"');
            header('Cache-Control: max-age=0');
            
            // Create file pointer connected to output stream
            $output = fopen('php://output', 'w');
            
            // Add BOM for UTF-8 support in Excel
            fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Write CSV data
            foreach ($this->data as $row) {
                fputcsv($output, $row);
            }
            
            fclose($output);
            exit;
        }
    }
    
    public function clear_data()
    {
        $this->data = array();
    }
}
