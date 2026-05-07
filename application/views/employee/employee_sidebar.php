<ul class="box">
    <li>...<b>Employee Panel</b>.............</li><br /><br />
     <li><a  class="box_a" href="<?php echo site_url('EmployeeController/add_employee')?>">Add Employee</a></li>
      <li><a  class="box_a" href="<?php echo site_url('EmployeeController/add_employee_payroll')?>">View Employee</a></li>
    <li><a  class="box_a" href="<?php echo site_url('EmployeeController/add_employee_salary')?>">Increment</a></li>
     <?php
      $user_type = $this->session->userdata('user_type');
//    if ($user_type == 'admin'):
        ?>
        <li><a  class="box_a" href="<?php echo site_url('EmployeeController/employee_salary_report')?>">Employee Salary Report</a></li>
        <li><a  class="box_a" href="<?php echo site_url('EmployeeController/all_employee_salary_report')?>">All Employee Salary Report</a></li>
    
        <?php
//    endif;
    ?>
  
</ul>
