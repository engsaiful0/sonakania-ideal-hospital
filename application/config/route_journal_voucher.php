<?php
$route['add-journal-voucher'] = 'JournalVoucherController/add_journal_voucher';
$route['view-journal-voucher'] = 'JournalVoucherController/view_journal_voucher';
$route['print-journal-voucher/(:any)'] = 'JournalVoucherController/print_journal_voucher_with_id/$1';
$route['edit-journal-voucher/(:any)'] = 'JournalVoucherController/edit_journal_voucher/$1';
$route['print-journal-voucher'] = 'JournalVoucherController/print_journal_voucher';
$route['delete-this-journal-voucher/(:any)'] = 'JournalVoucherController/delete_this_journal_voucher/$1';
