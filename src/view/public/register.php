<?
// load and register the Autoloader
include __DIR__ . '/../../model/Autoloader.model.php';
Model\Autoloader::register();


use Controller\Error\HTTPResponse;
use Controller\Users\Register;
use Controller\Settings\Settings;
use Controller\Users\UsersHandler;

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    HTTPResponse::displayForbidden();
}

// load settings into database
$settings = new Settings();
$settings->loadDatabase();

// try to create user
try {
    $handler = new Register();
    $username = $handler->register($_POST);
} catch (Error $error) {
    // catch any errors created from users input

    exit($error->getMessage());
} catch (Exception $error) {
    // catch any internal errors

    exit($error->getMessage());
}

// redirecting user to the login page
// easier to let user login that it is to login the user directly
header('Location: index.php'); # should have some kind of message that user was created