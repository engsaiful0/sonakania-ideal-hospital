<?php
$route['general-store'] = 'GeneralStoreController/general_store_dashboard';

// Fixed Asset Management (Store module)
$route['fixed-assets'] = 'FixedAssetController/dashboard';
$route['fixed-assets/register'] = 'FixedAssetController/assets';
$route['fixed-assets/reports'] = 'FixedAssetController/reports';
$route['fixed-assets/add'] = 'FixedAssetController/add';
$route['fixed-assets/edit/(:num)'] = 'FixedAssetController/edit/$1';
$route['fixed-assets/maintenance/(:num)'] = 'FixedAssetController/maintenance/$1';
$route['fixed-assets/ajax/asset-datatable'] = 'FixedAssetController/asset_datatable';
$route['fixed-assets/ajax/save-asset'] = 'FixedAssetController/save_asset';
$route['fixed-assets/ajax/update-asset'] = 'FixedAssetController/update_asset';
$route['fixed-assets/ajax/delete-asset'] = 'FixedAssetController/delete_asset';
$route['fixed-assets/ajax/maintenance-datatable'] = 'FixedAssetController/maintenance_datatable';
$route['fixed-assets/ajax/save-maintenance'] = 'FixedAssetController/save_maintenance';
$route['fixed-assets/ajax/delete-maintenance'] = 'FixedAssetController/delete_maintenance';
$route['fixed-assets/categories'] = 'FixedAssetCategoryController/index';
$route['fixed-assets/categories/datatable'] = 'FixedAssetCategoryController/datatable';
$route['fixed-assets/categories/save'] = 'FixedAssetCategoryController/save';
$route['fixed-assets/categories/update'] = 'FixedAssetCategoryController/update';
$route['fixed-assets/categories/delete'] = 'FixedAssetCategoryController/delete';
