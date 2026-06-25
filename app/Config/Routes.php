<?php namespace Config;

$routes = Services::routes(true);

if (file_exists(SYSTEMPATH . 'Config/Routes.php'))
{
    require SYSTEMPATH . 'Config/Routes.php';
}

$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Users');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(true);

// --------------------------------------------------------------------
// Existing frontend routes
// --------------------------------------------------------------------
$routes->match(['get','post'], '/', 'Users::index', ['filter' => 'noauth']);
$routes->get('logout', 'Users::logout', ['filter' => 'auth']);
$routes->match(['get','post'], 'register', 'Users::register', ['filter' => 'noauth']);
$routes->match(['get','post'], 'profile', 'Users::profile', ['filter' => 'auth']);
$routes->get('dashboard', 'Dashboard::index', ['filter' => 'auth']);

// --------------------------------------------------------------------
// REST API Routes
// --------------------------------------------------------------------
$routes->post('user/registration', 'User::registration');
$routes->post('user/login',        'User::login');
$routes->post('staff', 'User::addEmployee');
$routes->get('user',               'User::index');         // fetch all users
$routes->get('user/(:num)',        'User::show/$1');       // fetch specific user
$routes->put('user/(:num)',        'User::update/$1');     // update user
$routes->post('user/(:num)/delete','User::delete/$1');     // delete user
$routes->get('branches',           'Branch::index');       // fetch all branches
$routes->get('departments',        'Department::index');   // fetch all departments
if (file_exists(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php'))
{
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}