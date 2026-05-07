<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ExportController
 *
 * @author Lenovo
 */
class ExportController extends CI_Controller {

    //put your code here

    function export() {
        $file_name = "Product_Stock_and_Value.xls";
        header("Content-type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename={$file_name}");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");

        $this->load->view("report/stock_balance_reprot");
    }

    function export_product_lsit() {
        $file_name = "Product_List.xls";
        header("Content-type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename={$file_name}");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");

        $this->load->view("report/product_list");
    }

}
