<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->post('admin/tickets/Conversation', 'AdminController::postConversationReply');
$routes->get('/', 'Home::index');

// 2. Load Shield Auth Routes (Login, Logout, Register)
service('auth')->routes($routes);

// Admin & SuperAdmin Routes
$routes->group('admin', ['filter' => 'group:admin,superadmin'], function($routes) {
    $routes->post('tickets/Conversation', 'AdminController::postConversationReply');
    $routes->get('dashboard', 'AdminController::dashboard');
    $routes->get('tickets', 'AdminController::index');
    $routes->get('tickets/view/(:num)', 'AdminController::viewTicket/$1');
    $routes->post('tickets/update', 'AdminController::updateTicket'); // Time-to-time responses
    $routes->get('archive-now', 'AdminController::archivePreviousMonth'); // For future analytics page
    // User Management
    $routes->get('users', 'AdminController::manageUsers');
    $routes->post('users/create', 'AdminController::addUser');
    $routes->post('users/change-password', 'AdminController::changePassword');
    $routes->post('users/update-role', 'AdminController::updateRole');
});

// SACCO User Routes
$routes->group('sacco', ['filter' => 'group:sacco_user'], function($routes) {
    $routes->get('dashboard', 'TicketController::index');
    $routes->get('tickets/new', 'TicketController::new');
    $routes->post('tickets/create', 'TicketController::create');
    $routes->get('tickets/view/(:num)', 'TicketController::show/$1');
    $routes->post('tickets/reply', 'TicketController::addReply');
});