<?php

use CodeIgniter\Router\RouteCollection;




$routes->get('/', 'Home::index');
$routes->get('/about', 'Page::about');
$routes->get('/contact', 'Page::contact');
$routes->get('/faqs', 'Page::faqs');
$routes->get('/tos', 'Page::tos');
$routes->get('/page/tos', 'Page::tos');

$routes->get('/modul1-helper', 'ModulHelper::index');
$routes->get('/modul1-helper/set/(:segment)', 'ModulHelper::set/$1');


$routes->get('/mode-beta', 'Artikel::beta');
$routes->get('/mode-normal', 'Artikel::normal');

$routes->get('/artikel', 'Artikel::index');
$routes->get('/artikel/materi/(:segment)', 'Artikel::materi/$1');
$routes->get('/artikel/download/(:segment)', 'Artikel::downloadMateri/$1');
$routes->get('/artikel/(:any)', 'Artikel::view/$1');

$routes->match(['GET', 'POST'], '/user/login', 'User::login');
$routes->match(['GET', 'POST'], '/user/register', 'User::register');
$routes->match(['GET', 'POST'], '/user/forgot-password', 'User::forgotPassword');
$routes->get('/user', 'User::index');
$routes->get('/user/logout', 'User::logout');

$routes->options('api/login', 'Api\Auth::options');
$routes->post('api/login', 'Api\Auth::login');

$routes->group('dashboard', ['filter' => 'auth'], static function ($routes) {
    $routes->get('/', 'AjaxController::index');
    $routes->get('getData', 'AjaxController::getData');
    $routes->post('create', 'AjaxController::create');
    $routes->post('update/(:num)', 'AjaxController::update/$1');
    $routes->post('delete/(:num)', 'AjaxController::delete/$1');
});

 
$routes->get('/ajax', 'AjaxController::index', ['filter' => 'auth']);
$routes->get('/ajax/getData', 'AjaxController::getData', ['filter' => 'auth']);
$routes->post('/ajax/create', 'AjaxController::create', ['filter' => 'auth']);
$routes->post('/ajax/update/(:num)', 'AjaxController::update/$1', ['filter' => 'auth']);
$routes->post('/ajax/delete/(:num)', 'AjaxController::delete/$1', ['filter' => 'auth']);


 
 
$routes->options('post', 'Post::options');
$routes->options('post/(:num)', 'Post::options/$1');
$routes->get('post', 'Post::index');
$routes->get('post/(:num)', 'Post::show/$1');
$routes->post('post', 'Post::create', ['filter' => 'apiauth']);
$routes->put('post/(:num)', 'Post::update/$1', ['filter' => 'apiauth']);
$routes->patch('post/(:num)', 'Post::update/$1', ['filter' => 'apiauth']);
$routes->delete('post/(:num)', 'Post::delete/$1', ['filter' => 'apiauth']);

$routes->group('admin', ['filter' => 'auth'], static function ($routes) {
    $routes->get('artikel', 'Artikel::admin_index');
    $routes->match(['GET', 'POST'], 'artikel/add', 'Artikel::add');
    $routes->match(['GET', 'POST'], 'artikel/edit/(:num)', 'Artikel::edit/$1');
    $routes->post('artikel/delete/(:num)', 'Artikel::delete/$1');
});
