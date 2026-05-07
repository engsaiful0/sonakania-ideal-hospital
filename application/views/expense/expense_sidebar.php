<ul class="box">
    <li>...<b>Income & Expense</b>.............</li><br /><br />
     <li><a  class="box_a" href="<?php echo site_url('ExpenseController/add_income_head') ?>">Add Income Head</a></li>
    <li><a  class="box_a" href="<?php echo site_url('ExpenseController/add_income') ?>">Add Income</a></li>
    <li><a  class="box_a" href="<?php echo site_url('ExpenseController/add_expense_head') ?>">Add Expense Head</a></li>
    <li><a  class="box_a" href="<?php echo site_url('ExpenseController/add_expense') ?>">Add Expense</a></li>
    <!--<li><a  class="box_a" href="<?php //echo site_url('ExpenseController/add_expense_all') ?>">Add Expense All</a></li>-->
    <li><a  class="box_a" href="<?php echo site_url('OwnerController/add_owner') ?>">Owner</a></li>

    <li><a  class="box_a" href="<?php echo site_url('OwnerController/add_owner_payment') ?>">Owner Payment</a></li>

    <?php
    $user_type = $this->session->userdata('user_type');
    if ($user_type == 'admin'):
        ?>
        <li><a  class="box_a" href="<?php echo site_url('ExpenseController/expense_report') ?>">Expense Report</a></li>
        <li><a  class="box_a" href="<?php echo site_url('ExpenseController/income_report') ?>">Income Report</a><br></li>
        <?php
    endif;
    ?>

</ul>
