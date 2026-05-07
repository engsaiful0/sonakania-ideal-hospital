<?php
$route['canteen-dashboard'] = 'CanteenController/canteen_dashboard';
$route['add-ready-item-sell'] = 'CanteenSellController/add_ready_item_sell';
$route['view-ready-item-sell'] = 'CanteenSellController/view_ready_item_sell';
$route['print-canteen-ready-item-sell'] = 'CanteenSellController/print_canteen_ready_item_sell';
$route['print-canteen-ready-item-sell-with-id/(:any)'] = 'CanteenSellController/print_canteen_ready_item_sell_with_id/$1';
$route['edit-canteen-ready-item-sell/(:any)'] = 'CanteenSellController/edit_ready_item_sell/$1';

$route['add-purchase-canteen-goods'] = 'CanteenPurchaseController/add_purchase_canteen_goods';
$route['view-purchase-canteen-goods'] = 'CanteenPurchaseController/view_canteen_purchase_goods';


$route['print-canteen-purchase-goods/(:any)'] = 'CanteenPurchaseController/print_canteen_purchase_goods_with_id/$1';
$route['edit-canteen-purchase-goods/(:any)'] = 'CanteenPurchaseController/edit_canteen_purchase_goods/$1';
$route['print-canteen-purchase-goods'] = 'CanteenPurchaseController/print_canteen_purchase_goods';
$route['delete-this-canteen-purchase-goods/(:any)'] = 'CanteenPurchaseController/delete_this_canteen_purchase_goods/$1';


$route['add-inventory'] = 'CanteenReadyItemInventoryController/add_inventory';
$route['view-inventory'] = 'CanteenReadyItemInventoryController/view_inventory';
$route['print-canteen-ready-item-inventory'] = 'CanteenReadyItemInventoryController/print_canteen_ready_item_inventory';
$route['print-canteen-ready-item-inventory-with-id/(:any)'] = 'CanteenReadyItemInventoryController/print_canteen_ready_item_inventory_with_id/$1';
$route['edit-canteen-ready-item-inventory/(:any)'] = 'CanteenReadyItemInventoryController/edit_canteen_ready_item_inventory/$1';

$route['stock-list'] = 'CanteenReadyItemController/stock-list';
$route['stock-list'] = 'CanteenInventoryController/stock-list';


$route['raw-goods'] = 'SettingsCanteenController/raw_goods';
$route['ready-item'] = 'SettingsCanteenController/ready_item';
$route['raw-goods-supplier'] = 'SettingsCanteenController/raw_goods_supplier';
