<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Simple PDF Library - Fallback for systems without TCPDF
 * This creates a simple HTML-based PDF alternative
 */
class Simple_pdf
{
    protected $CI;
    protected $html = '';
    protected $title = 'Document';
    
    public function __construct()
    {
        $this->CI =& get_instance();
    }
    
    public function set_title($title)
    {
        $this->title = $title;
    }
    
    public function add_html($html)
    {
        $this->html .= $html;
    }
    
    public function output($filename = 'document.html', $dest = 'D')
    {
        $full_html = $this->generate_full_html();
        
        if ($dest == 'D') {
            // Download as HTML file (browsers can print/save as PDF)
            header('Content-Type: text/html');
            header('Content-Disposition: attachment; filename="' . $filename . '"');
            echo $full_html;
        } else {
            echo $full_html;
        }
    }
    
    private function generate_full_html()
    {
        return '<!DOCTYPE html>
<html>
<head>
    <title>' . htmlspecialchars($this->title) . '</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        table { width: 100%; border-collapse: collapse; margin: 10px 0; }
        th, td { border: 1px solid #000; padding: 8px; text-align: left; }
        th { background-color: #f0f0f0; font-weight: bold; }
        @media print {
            body { margin: 0; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>
    <div class="no-print">
        <button onclick="window.print()">Print this page</button>
        <hr>
    </div>
    ' . $this->html . '
</body>
</html>';
    }
}
