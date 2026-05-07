<?php
$route['generate_qr_code/(:any)/(:any)'] = 'QrCodeController/generate_qr_code/$1/$2';
$route['auth/login_with_qr_code'] = 'AuthController/login_with_qr_code';
