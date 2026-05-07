<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;

class QrCodeController extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
    }

    public function generate_qr_code($username, $password)
    {
        // Ensure to encode and validate inputs if necessary
        $username = urldecode($username);
        $password = urldecode($password);

        // The data to be encoded in the QR code
        $data = base_url('AuthController/login_with_qr_code') . '?username=' . urlencode($username) . '&password=' . urlencode($password);

        // Create QR Code
        $qrCode = new QrCode($data);
        // Set the size of the QR Code
        $qrCode->setSize(150); // Set size here (in pixels)

        $writer = new PngWriter();
        $imageResource = $writer->write($qrCode)->getString();

        // Set response headers
        header('Content-Type: image/png');

        // Output the image
        echo $imageResource;
    }
}
