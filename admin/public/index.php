<?php
/**
 * Admin Panel — Front Controller
 * 
 * All requests are routed through this file.
 */

// Load configuration
require_once __DIR__ . '/../config.php';

// Start session
\App\Core\Session::start();

// Create request
$request = new \App\Core\Request();

// Create router and define routes
$router = new \App\Core\Router();

// ============================================
// Auth routes (no authentication required)
// ============================================
$router->get('/login', 'AuthController@loginForm');
$router->post('/login', 'AuthController@login');
$router->get('/logout', 'AuthController@logout');

// ============================================
// Protected routes (authentication required)
// ============================================
$router->get('/dashboard', 'DashboardController@index');

// Projects
$router->get('/projects', 'ProjectController@index');
$router->get('/projects/create', 'ProjectController@create');
$router->post('/projects/create', 'ProjectController@store');
$router->get('/projects/edit/{id}', 'ProjectController@edit');
$router->post('/projects/edit/{id}', 'ProjectController@update');
$router->post('/projects/delete/{id}', 'ProjectController@destroy');

// Project screenshots
$router->get('/projects/{id}/screenshots', 'ScreenshotController@index');
$router->post('/projects/{id}/screenshots', 'ScreenshotController@upload');
$router->post('/screenshots/delete/{id}', 'ScreenshotController@destroy');

// Technologies
$router->get('/technologies', 'TechnologyController@index');
$router->get('/technologies/create', 'TechnologyController@create');
$router->post('/technologies/create', 'TechnologyController@store');
$router->get('/technologies/edit/{id}', 'TechnologyController@edit');
$router->post('/technologies/edit/{id}', 'TechnologyController@update');
$router->post('/technologies/delete/{id}', 'TechnologyController@destroy');

// Experience
$router->get('/experience', 'ExperienceController@index');
$router->get('/experience/create', 'ExperienceController@create');
$router->post('/experience/create', 'ExperienceController@store');
$router->get('/experience/edit/{id}', 'ExperienceController@edit');
$router->post('/experience/edit/{id}', 'ExperienceController@update');
$router->post('/experience/delete/{id}', 'ExperienceController@destroy');

// Profile
$router->get('/profile', 'ProfileController@edit');
$router->post('/profile', 'ProfileController@update');

// Export
$router->get('/export', 'ExportController@index');
$router->post('/export', 'ExportController@export');

// ============================================
// Dispatch the request
// ============================================
try {
    $router->dispatch($request);
} catch (\Throwable $e) {
    if (APP_DEBUG) {
        echo '<h1>Error</h1>';
        echo '<p>' . $e->getMessage() . '</p>';
        echo '<pre>' . $e->getTraceAsString() . '</pre>';
    } else {
        http_response_code(500);
        echo '500 Internal Server Error';
    }
}