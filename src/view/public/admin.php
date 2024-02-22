<?php
// initialize necessary services
require __DIR__ . '/../../model/Includes.model.php';
Model\Includes::initialize();

include __DIR__ . '/../admin/AdminPagesTemplate.trait.php';

// load needed classes
use Controller\Error\HTTPResponse;
use Controller\Users\Authenticate;
use View\Admin\Pages\AdminPagesHome;
use Model\AdminPagesAutoloader;
use Controller\Settings\Settings;



// check if user has the proper rights to view the page
if (!Authenticate::isAdministrator()) {
    HTTPResponse::displayForbidden();
}

// register Autoloader for sites on the admin panel
AdminPagesAutoloader::register($_SERVER['REQUEST_URI']);

// load the corresponding class and check its existence
$class = AdminPagesAutoloader::extractClassName();
if (class_exists($class)) {

    //load Settings into database
    $settings = new Settings();
    $settings->loadDatabase();

    $instance = $class::createInstance();

    // handle requests
    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        try {
            $instance->handleGetRequest($_GET);
        } catch (Error $error) {
            // catch any user generated errors

            // user is redirected to the set error page on a get request
            http_response_code(400);
            header('Location: ' . $instance->errorPathGet . '?err=' . $error->getMessage() . '&type=error');
        }
    } elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
        try {
            $instance->handlePostRequest($_POST);
        } catch (Error $error) {
            // catch any user generated errors

            // user is redirected to the set error page on a post request
            http_response_code(400);
            header('Location: ' . $instance->errorPathPost . '?err=' . $error->getMessage() . '&type=error');
            exit();
        }
    }

    // start output buffering to allow redirects even after we have echoed items
    ob_start();

    // render page
    $instance->renderPage();

    // send site to user
    ob_end_flush();
}
// if the request is going to /admin we want to send the user to the panel home
elseif ($class == 'View\Admin\Pages\AdminPages') {

    AdminPagesHome::createInstance()->renderPage();
}
// if the proper page can not be found the user is send to the panel home
else {

    // if the page is not found we send the user home
    http_response_code(404);
    AdminPagesHome::createInstance()->renderPage();
}
