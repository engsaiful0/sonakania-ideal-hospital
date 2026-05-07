<style>
    @media print {
        body * {
            visibility: hidden;
        }

        #report,
        #report * {
            visibility: visible;
            overflow: visible;
        }

        #report {
            position: absolute;
            left: 0;
            top: 0;
        }

        .p1 {
            line-height: 80% !important;
        }
    }

    .p1 {
        line-height: 80% !important;
    }
</style>

<div class="row">
    <div class="col-md-12">
        <button onclick="window.print()" id="sumbit_button" class="btn btn-primary">Print</button>
    </div>

</div>
<div id="report" style="width: 90%;margin:0 auto;margin-left:45px;;margin-top:50px;">

    <?php
    
    $nationality = null;
    if (!empty($director_data->nationality_id)) {
        $nationality = $this->db->where('nationality_id', $director_data->nationality_id)->get('nationality')->row();
    }
    
    $religion = null;
    if (!empty($director_data->religion_id)) {
        $religion = $this->db->where('religion_id', $director_data->religion_id)->get('religion')->row();
    }
    
    $profession = null;
    if (!empty($director_data->profession_id)) {
        $profession = $this->db->where('profession_id', $director_data->profession_id)->get('profession')->row();
    }
    
    $relation = null;
    if (!empty($director_data->relation_id)) {
        $relation = $this->db->where('relation_id', $director_data->relation_id)->get('relation')->row();
    }
    
    $bank_name = null;
    if (!empty($director_data->bank_name_id)) {
        $bank_name = $this->db->where('bank_name_id', $director_data->bank_name_id)->get('bank_name')->row();
    }
    ?>
    <?php
    $compnay = $this->db->where('company_id', '1')->get('company')->row();
    ?>

    <div class="customer-copy" style="margin-top: 50px; ">


        <div class="name" style="width: 100%;margin-bottom: 10px;">
            <table border="1" style="width: 100%;border-collapse:collapse;margin:0 auto;color:black;">
                <tr>
                    <td>Share Holder Name</td>

                    <td>
                        <b><?php echo $director->name ?></b> </b>
                    </td>
                    <td>ID</td>
                    <td>
                        <b><?php echo $director->unique_id ?></b> </b>
                    </td>
                </tr>
                <tr>
                    <td>Father Name</td>

                    <td>
                        <b><?php echo $director->father_name ?></b> </b>
                    </td>
                    <td>Mother Name</td>
                    <td>
                        <b><?php echo $director->mother_name ?></b> </b>
                    </td>
                </tr>
                <tr>
                    <td>Mobile</td>

                    <td>
                        <b><?php echo $director->mobile ?></b> </b>
                    </td>
                    <td>Email</td>
                    <td>
                        <b><?php echo $director->email ?></b> </b>
                    </td>
                </tr>
                <tr>
                    <td>Gender</td>

                    <td>
                        <b><?php echo $director->gender ?></b> </b>
                    </td>
                    <td>NID Number</td>
                    <td>
                        <b><?php echo $director->nid_number ?></b> </b>
                    </td>
                </tr>
                <tr>
                    <td>Present Address</td>

                    <td>
                        <b><?php echo $director->present_address ?></b> </b>
                    </td>
                    <td>Parmanent Address</td>
                    <td>
                        <b><?php echo $director->permanent_address ?></b> </b>
                    </td>
                </tr>
                <tr>
                    <td>Present Address</td>

                    <td>
                        <b><?php echo $director->present_address ?></b> </b>
                    </td>
                    <td>Parmanent Address</td>
                    <td>
                        <b><?php echo $director->permanent_address ?></b> </b>
                    </td>
                </tr>
                <tr>
                    <td>Nationality</td>

                    <td>
                        <b><?php echo $nationality ? $nationality->name : 'N/A' ?></b> </b>
                    </td>
                    <td>Religion</td>
                    <td>
                        <b><?php echo $religion ? $religion->religion_name : 'N/A' ?></b> </b>
                    </td>
                </tr>
                <tr>
                    <td>Date of Join</td>

                    <td>
                        <b> <?php echo !empty($director->date_of_join) ? date('d-m-Y', strtotime($director->date_of_join)) : 'N/A' ?></b>
                    </td>
                    <td>Profession</td>
                    <td>
                        <b><?php echo $profession ? $profession->profession_name : 'N/A' ?></b> </b>
                    </td>
                </tr>
                <tr>
                    <td>No fo Share</td>

                    <td>
                        <b><?php echo $director->no_of_share ?></b> </b>
                    </td>
                    <td>Amount Per Share</td>
                    <td>
                        <b><?php echo $director->amount_per_share ?></b> </b>
                    </td>
                </tr>
                <tr>
                    <td>Total Amount</td>

                    <td>
                        <b><?php echo $director->total_amount ?></b> </b>  |Current Share Value:
                        <b><?php 
                            // Calculate current share value using helper function
                            $current_share_value = calculate_current_share_value(
                                $director->total_amount, 
                                $director->yearly_share_value_increment_rate, 
                                $director->date_of_join
                            );
                            echo number_format($current_share_value, 2);
                        ?></b>  
                    </td>
                    <td>Discount</td>
                    <td>
                        IPD:<b><?php echo $director->ipd_discount ?>%</b> OPD:<b><?php echo $director->opd_discount ?>%</b> Test:<b><?php echo $director->test_discount ?>%</b><br>
                        Emergency:<b><?php echo $director->emergency_discount ?>%</b> Phygiotherapy:<b><?php echo $director->phygiotherapy_discount ?>%</b><br>
                        Pharmachy:<b><?php echo $director->pharmachy_discount ?>%</b>
                    </td>
                </tr>
                <tr>
                    <td>Picture</td>

                    <td>
                        <?php
                        if ($director->picture == '') {
                        ?>
                            <img style="height: 100px;width: 100px;" src="<?php echo base_url() ?>assets/image_icon.jpg">
                    </td>
                <?php
                        } else {
                ?>
                    <img style="height: 100px;width: 100px;" src="<?php echo base_url() ?>assets/director/<?php echo $director->picture ?>"></td>
                <?php
                        }
                ?>
                </td>
                <td>NID</td>
                <td>
                    <?php
                    if ($director->nid_file == '') {
                    ?>
                        <img style="height: 100px;width: 100px;" src="<?php echo base_url() ?>assets/image_icon.jpg">
                </td>
            <?php
                    } else {
            ?>
                <img style="height: 100px;width: 100px;" src="<?php echo base_url() ?>assets/director/<?php echo $director->nid_file ?>"></td>
            <?php
                    }
            ?>
            </td>
                </tr>
                <tr>
                    <td colspan="4" style="text-align:center">Nominee Information</td>
                </tr>
                <tr>
                    <td>Name of Nominee</td>

                    <td>
                        <b><?php echo $director->name_of_nominee ?></b> </b>
                    </td>
                    <td>Relation</td>
                    <td>
                        <b><?php echo $relation ? $relation->relation_name : 'N/A' ?></b> </b>
                    </td>
                </tr>
                <tr>
                    <td>Nominee Mobile</td>

                    <td>
                        <b><?php echo $director->nominee_mobile ?></b> </b>
                    </td>
                    <td>Nominee Email</td>
                    <td>
                        <b><?php echo $director->nominee_email ?></b> </b>
                    </td>
                </tr>
                <tr>
                    <td>Nominee Present Address</td>

                    <td>
                        <b><?php echo $director->nominee_present_address ?></b> </b>
                    </td>
                    <td>Nominee Parmanent Address</td>
                    <td>
                        <b><?php echo $director->nominee_parmanent_address ?></b> </b>
                    </td>
                </tr>

                <tr>
                    <td colspan="4" style="text-align:center">Bank Information</td>
                </tr>
                <tr>
                    <td>Bank Name</td>

                    <td>
                        <b><?php echo $bank_name ? $bank_name->name : 'N/A' ?></b> </b>
                    </td>
                    <td>Branch Name</td>
                    <td>
                        <b><?php echo $director->branch_name ?></b> </b>
                    </td>
                </tr>
                <tr>
                    <td>Account Name</td>

                    <td>
                        <b><?php echo $director->account_name ?></b> </b>
                    </td>
                    <td>Account Number</td>
                    <td>
                        <b><?php echo $director->account_number ?></b> </b>
                    </td>
                </tr>
            </table>
        </div>
        <div class="product" style="height: 300px; ">

            <p style="text-align: right;padding-right: 100px;padding-top: 20px; ">___________<br>Manager</p>
            <!--<p style="text-align: center;margin-top: 100px;">Software developed by Bijoy LAB Web & IT Solution Ltd:01818-650864,www.bijoylab.com</p>-->

        </div>
    </div>


</div>