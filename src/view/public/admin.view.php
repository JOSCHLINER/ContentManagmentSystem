<?php
# only enabled for testing purposes
$_SESSION['userPrivileges'] = 'a';

include __DIR__ . '/../admin/AdminPagesTemplate.trait.php';

// load and register the Autoloader
include __DIR__ . '/../../model/Autoloader.model.php';
Model\Autoloader::register();

// load needed classes
use Controller\Error\HTTPResponse;
use Controller\Users\Authenticate;
use View\Admin\Pages\AdminPagesHome;
use Model\AdminPagesAutoloader;



// check if user has the proper rights to view the page
if (!Authenticate::isAdministrator()) {
    HTTPResponse::displayForbidden();
}

// register Autoloader for sites on the admin panel
AdminPagesAutoloader::register($_SERVER['REQUEST_URI']);

// load the corresponding class and check its existence
$class = AdminPagesAutoloader::extractClassName();
if (class_exists($class)) {

    // rendering the page
    $class::createInstance()->renderPage();
}   else {

    // if the page is not found we send the user home
    http_response_code(404);
    AdminPagesHome::createInstance()->renderPage();
}
