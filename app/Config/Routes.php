<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (file_exists(SYSTEMPATH . 'Config/Routes.php')) {
    require SYSTEMPATH . 'Config/Routes.php';
}

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('StatusController');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(true);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
// $routes->get('/', 'StatusController::index');
// $routes->post('/', 'StatusController::checkPostRequest');

$routes->match(['get', 'post'], '/', 'StatusController::index');


// auth routes
$routes->post('auth/regsiter', 'AuthController::register');
$routes->post('auth/login', 'AuthController::login');

// user routes
$routes->get('users', 'UserController::index', ['filter' => 'jwtauth']);
// $routes->get('users/create', 'UserController::create');
$routes->post('users', 'UserController::store');
$routes->get('users/(:num)', 'UserController::show/$1');
// $routes->get('users/(:num)/edit', 'UserController::edit/$1');
$routes->patch('users/(:num)', 'UserController::update/$1');
$routes->delete('users/(:num)', 'UserController::delete/$1');

// excel routes
$routes->get('excel-export', 'ExcelController::export');
$routes->post('excel-import', 'ExcelController::import');

/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (file_exists(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}