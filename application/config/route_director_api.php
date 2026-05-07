<?php
$route['director/send-otp'] = 'director_app_api/OtpController/send_otp';
$route['director/verify-otp'] = 'director_app_api/OtpController/verify_otp';

$route['director/verify-sign-in-mobile-no'] = 'director_app_api/OtpController/verify_sign_in_mobile_no';

$route['director/daily-test-discount']       = 'director_app_api/TestDiscountController/getDailyTestDiscountSummary';
$route['director/weekly-test-discount']      = 'director_app_api/TestDiscountController/getWeeklyTestDiscountSummary';
$route['director/monthly-test-discount']     = 'director_app_api/TestDiscountController/getMonthlyTestDiscountSummary';
$route['director/sixmonthly-test-discount']  = 'director_app_api/TestDiscountController/getSixMonthlyTestDiscountSummary';
$route['director/yearly-test-discount']      = 'director_app_api/TestDiscountController/getYearlyTestDiscountSummary';
$route['director/total-test-discount']       = 'director_app_api/TestDiscountController/getTotalTestDiscountSummary';
$route['director/test-discount-top-ten-entries'] = 'director_app_api/TestDiscountController/getTestDiscountTopTenEntries';


$route['director/ipd/daily-ipd-discount']      = 'director_app_api/IpdDiscountController/getDailyIpdDiscountSummary';
$route['director/ipd/weekly-ipd-discount']     = 'director_app_api/IpdDiscountController/getWeeklyIpdDiscountSummary';
$route['director/ipd/monthly-ipd-discount']    = 'director_app_api/IpdDiscountController/getMonthlyIpdDiscountSummary';
$route['director/ipd/sixmonthly-ipd-discount'] = 'director_app_api/IpdDiscountController/getSixMonthlyIpdDiscountSummary';
$route['director/ipd/yearly-ipd-discount']     = 'director_app_api/IpdDiscountController/getYearlyIpdDiscountSummary';
$route['director/ipd/total-ipd-discount']      = 'director_app_api/IpdDiscountController/getTotalIpdDiscountSummary';
$route['director/ipd/ipd-discount-top-ten-entries'] = 'director_app_api/IpdDiscountController/getIpdDiscountTopTenEntries';

$route['director/info'] = 'director_app_api/DirectorController/getDirectorInfo';
$route['director/info/(:num)'] = 'director_app_api/DirectorController/getDirectorInfo/$1';


$route['director/physio/daily-physio-discount']      = 'director_app_api/PhysioDiscountController/getDailyPhysioDiscountSummary';
$route['director/physio/weekly-physio-discount']     = 'director_app_api/PhysioDiscountController/getWeeklyPhysioDiscountSummary';
$route['director/physio/monthly-physio-discount']     = 'director_app_api/PhysioDiscountController/getMonthlyPhysioDiscountSummary';
$route['director/physio/sixmonthly-physio-discount']  = 'director_app_api/PhysioDiscountController/getSixMonthlyPhysioDiscountSummary';
$route['director/physio/yearly-physio-discount']      = 'director_app_api/PhysioDiscountController/getYearlyPhysioDiscountSummary';
$route['director/physio/total-physio-discount']       = 'director_app_api/PhysioDiscountController/getTotalPhysioDiscountSummary';
$route['director/physio/physio-discount-top-ten-entries'] = 'director_app_api/PhysioDiscountController/getPhysioDiscountTopTenEntries';

$route['director/emergency/daily-emergency-discount']      = 'director_app_api/EmergencyDiscountController/getDailyEmergencyDiscountSummary';
$route['director/emergency/weekly-emergency-discount']     = 'director_app_api/EmergencyDiscountController/getWeeklyEmergencyDiscountSummary';
$route['director/emergency/monthly-emergency-discount']    = 'director_app_api/EmergencyDiscountController/getMonthlyEmergencyDiscountSummary';
$route['director/emergency/sixmonthly-emergency-discount'] = 'director_app_api/EmergencyDiscountController/getSixMonthlyEmergencyDiscountSummary';
$route['director/emergency/yearly-emergency-discount']     = 'director_app_api/EmergencyDiscountController/getYearlyEmergencyDiscountSummary';
$route['director/emergency/total-emergency-discount']      = 'director_app_api/EmergencyDiscountController/getTotalEmergencyDiscountSummary';
$route['director/emergency/emergency-discount-top-ten-entries'] = 'director_app_api/EmergencyDiscountController/getEmergencyDiscountTopTenEntries';


$route['director/total-discount'] = 'director_app_api/TotalDiscountController/getTotalDiscountAllTime';
$route['director/today-discount'] = 'director_app_api/TotalDiscountController/getTotalDiscountToday';
$route['director/week-discount'] = 'director_app_api/TotalDiscountController/getTotalDiscountThisWeek';
$route['director/month-discount'] = 'director_app_api/TotalDiscountController/getTotalDiscountThisMonth';
$route['director/year-discount'] = 'director_app_api/TotalDiscountController/getTotalDiscountThisYear';


