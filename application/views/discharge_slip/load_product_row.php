<tr id="tr_<?php echo $id ?>" style="margin-top:3px;">
    <td>     
        <?php echo $id ?>    
    </td>     
    <td style="width: 20%;">         
        <select name="type_name[]" class="form-control" id="type_name_id_<?php echo $id ?>" onchange="drug_name_load(this.id)" required="" style="width:100%;">             
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
    <td style="width: 16%;">         
        <select type="text" style="width: 100%" required="" class="form-control" id="medicin_id_<?php echo $id ?>" name="medicin_id[]">             
            <option selected="" value="" disabled=""></option>           
        </select>      
    </td>     
    <td style="width: 15%;">         
        <select type="text" required="" style="width: 100%" class="form-control" id="medicin_times_id_<?php echo $id ?>" name="medicin_times_id[]">             
            <option selected="" value="" disabled=""></option>              
            <?php             
            $medicin_times = $this->db->select('*')->get('medicin_times')->result();             
            foreach ($medicin_times as $value) {                 
            ?>                 
                <option value="<?php echo $value->medicin_times_id; ?>"><?php echo $value->medicin_times_name; ?></option>                 
            <?php             
            }             
            ?>          
        </select>     
    </td>     
    <td style="width: 15%;">         
        <input type="text" required="" style="width: 100%" oninput="validateIntegerInput(this)" class="form-control" id="days_<?php echo $id ?>" placeholder="Days" name="days[]">     
    </td>     
    <td style="width: 15%;">         
        <select type="text" style="width: 100%" class="form-control" id="day_or_month_or_year_or_colbay_<?php echo $id ?>" name="day_or_month_or_year_or_colbay[]">             
            <option>দিন</option>             
            <option>মাস</option>             
            <option>বছর</option>             
            <option>চলবে</option>         
        </select>     
    </td>     
    <td>         
        <input type="button" onclick="SomeDeleteRowFunction(this)" style="width:50px" readonly id="add_more_<?php echo $id ?>" title="Click TO Remove" value="-"  >     
    </td> 
</tr>
