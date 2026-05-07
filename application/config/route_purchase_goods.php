<?php
$route['add-purchase-goods'] = 'PurchaseGoodsController/add_purchase_goods';
$route['view-purchase-goods'] = 'PurchaseGoodsController/view_purchase_goods';
$route['print-purchase-goods/(:any)'] = 'PurchaseGoodsController/print_purchase_goods_with_id/$1';
$route['edit-purchase-goods/(:any)'] = 'PurchaseGoodsController/edit_purchase_goods/$1';
$route['print-purchase-goods'] = 'PurchaseGoodsController/print_purchase_goods';
$route['delete-this-purchase-goods/(:any)'] = 'PurchaseGoodsController/delete_this_purchase_goods/$1';
$route['goods-stock-report'] = 'PurchaseGoodsController/goods_stock_report';






