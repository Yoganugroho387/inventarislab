<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING - Sistem Inventaris Lab
| -------------------------------------------------------------------------
*/

$route['default_controller'] = 'welcome/index';
$route['404_override'] = 'errors/page_404';
$route['translate_uri_dashes'] = FALSE;

// === AUTH ===
$route['login'] = 'login/login_form';
$route['logout'] = 'login/logout';

// === ADMIN ROUTES ===
$route['admin/dashboard'] = 'admin/dashboard/index';

// Users
$route['admin/users'] = 'admin/users/index';
$route['admin/users/create'] = 'admin/users/create';
$route['admin/users/store'] = 'admin/users/store';
$route['admin/users/edit/(:num)'] = 'admin/users/edit/$1';
$route['admin/users/update/(:num)'] = 'admin/users/update/$1';
$route['admin/users/delete/(:num)'] = 'admin/users/delete/$1';

// Categories
$route['admin/categories'] = 'admin/categories/index';
$route['admin/categories/store'] = 'admin/categories/store';
$route['admin/categories/update/(:num)'] = 'admin/categories/update/$1';
$route['admin/categories/delete/(:num)'] = 'admin/categories/delete/$1';

// Locations
$route['admin/locations'] = 'admin/locations/index';
$route['admin/locations/store'] = 'admin/locations/store';
$route['admin/locations/update/(:num)'] = 'admin/locations/update/$1';
$route['admin/locations/delete/(:num)'] = 'admin/locations/delete/$1';

// Units
$route['admin/units'] = 'admin/units/index';
$route['admin/units/store'] = 'admin/units/store';
$route['admin/units/update/(:num)'] = 'admin/units/update/$1';
$route['admin/units/delete/(:num)'] = 'admin/units/delete/$1';

// Items
$route['admin/items'] = 'admin/items/index';
$route['admin/items/create'] = 'admin/items/create';
$route['admin/items/store'] = 'admin/items/store';
$route['admin/items/download_template'] = 'admin/items/download_template';
$route['admin/items/import_csv'] = 'admin/items/import_csv';
$route['admin/items/edit/(:num)'] = 'admin/items/edit/$1';
$route['admin/items/update/(:num)'] = 'admin/items/update/$1';
$route['admin/items/delete/(:num)'] = 'admin/items/delete/$1';
$route['admin/items/view/(:num)'] = 'admin/items/view/$1';

// Borrowings
$route['admin/borrowings'] = 'admin/borrowings/index';
$route['admin/borrowings/create'] = 'admin/borrowings/create';
$route['admin/borrowings/store'] = 'admin/borrowings/store';
$route['admin/borrowings/view/(:num)'] = 'admin/borrowings/view/$1';
$route['admin/borrowings/print/(:num)'] = 'admin/borrowings/print_letter/$1';
$route['admin/borrowings/approve/(:num)'] = 'admin/borrowings/approve/$1';
$route['admin/borrowings/reject/(:num)'] = 'admin/borrowings/reject/$1';

$route['admin/returns'] = 'admin/returns/index';
$route['admin/returns/find_by_item_code'] = 'admin/returns/find_by_item_code';
$route['admin/returns/create/(:num)'] = 'admin/returns/create/$1';
$route['admin/returns/store/(:num)'] = 'admin/returns/store/$1';

// Disbursements (Consumables Outflow)
$route['admin/disbursements'] = 'admin/disbursements/index';
$route['admin/disbursements/create'] = 'admin/disbursements/create';
$route['admin/disbursements/store'] = 'admin/disbursements/store';

// Asset Maintenance Logs
$route['admin/maintenance'] = 'admin/maintenance/index';
$route['admin/maintenance/create'] = 'admin/maintenance/create';
$route['admin/maintenance/store'] = 'admin/maintenance/store';
$route['admin/maintenance/edit/(:num)'] = 'admin/maintenance/edit/$1';
$route['admin/maintenance/update/(:num)'] = 'admin/maintenance/update/$1';

// Borrowing Calendar / Booking
$route['admin/borrowings/calendar'] = 'admin/borrowings/calendar';
$route['admin/borrowings/calendar_events'] = 'admin/borrowings/calendar_events';

// Reports
$route['admin/reports'] = 'admin/reports/index';
$route['admin/reports/export'] = 'admin/reports/export';

// Activity Logs
$route['admin/activity-logs'] = 'admin/activity_logs/index';

// Settings
$route['admin/settings'] = 'admin/settings/index';
$route['admin/settings/update'] = 'admin/settings/update';

// Admin Bebas Lab
$route['admin/bebas-lab'] = 'admin/bebas_lab/index';
$route['admin/bebas-lab/print/(:any)'] = 'admin/bebas_lab/print_letter/$1';

// Admin Profile
$route['admin/profile'] = 'admin/profile/index';
$route['admin/profile/update'] = 'admin/profile/update';

// === STAFF ROUTES ===
$route['staff/dashboard'] = 'staff/dashboard/index';

$route['staff/items'] = 'staff/items/index';
$route['staff/items/view/(:num)'] = 'staff/items/view/$1';

$route['staff/borrowings'] = 'staff/borrowings/index';
$route['staff/borrowings/create'] = 'staff/borrowings/create';
$route['staff/borrowings/store'] = 'staff/borrowings/store';
$route['staff/borrowings/view/(:num)'] = 'staff/borrowings/view/$1';
$route['staff/borrowings/print/(:num)'] = 'staff/borrowings/print_letter/$1';

$route['staff/returns'] = 'staff/returns/index';
$route['staff/returns/find_by_item_code'] = 'staff/returns/find_by_item_code';
$route['staff/returns/create/(:num)'] = 'staff/returns/create/$1';
$route['staff/returns/store/(:num)'] = 'staff/returns/store/$1';

// Staff Disbursements
$route['staff/disbursements'] = 'staff/disbursements/index';
$route['staff/disbursements/create'] = 'staff/disbursements/create';
$route['staff/disbursements/store'] = 'staff/disbursements/store';

// Staff Calendar
$route['staff/borrowings/calendar'] = 'staff/borrowings/calendar';
$route['staff/borrowings/calendar_events'] = 'staff/borrowings/calendar_events';

$route['staff/profile'] = 'staff/profile/index';
$route['staff/profile/update'] = 'staff/profile/update';

// Staff Bebas Lab
$route['staff/bebas-lab'] = 'staff/bebas_lab/index';
$route['staff/bebas-lab/print/(:any)'] = 'staff/bebas_lab/print_letter/$1';

// === SUPPORT DEVELOPER ===
$route['support-developer'] = 'admin/support/index';
$route['admin/support-developer'] = 'admin/support/index';
$route['staff/support-developer'] = 'staff/support/index';

// Verification Routes
$route['verify/document/(:any)'] = 'verify/document/$1';
$route['verify/borrowing/(:num)'] = 'verify/borrowing/$1';
