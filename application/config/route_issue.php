<?php
$route['add-issue'] = 'IssueController/add_issue';
$route['view-issue'] = 'IssueController/view_issue';
$route['view-issue/(:num)'] = 'IssueController/view_issue/$1';
$route['print-issue/(:any)'] = 'IssueController/print_issue_with_id/$1';
$route['edit-issue/(:any)'] = 'IssueController/edit_print/$1';
$route['print-issue'] = 'IssueController/print_issue';
$route['delete-this-issue/(:any)'] = 'IssueController/delete_this_issue/$1';
$route['edit-issue/(:any)'] = 'IssueController/edit_issue/$1';
$route['department-issue-report'] = 'ReportIssueController/department_issue_report';