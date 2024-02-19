<?
// load and register the Autoloader
include __DIR__ . '/../../model/Autoloader.model.php';
Model\Autoloader::register();


use Controller\Error\HTTPResponse;
use Controller\Users\Login;
use Controller\Settings\Settings;

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    HTTPResponse::displayForbidden();
}

// load settings into database
$settings = new Settings();
$settings->loadDatabase();

// try to login user
try {
    $handler = new login();
    $user = $handler->login($_POST);
} catch (Error $error) {
    // catch any errors created from users input
    // should display them in some better way.

    exit($error->getMessage());
} catch (Exception $error) {
    // catch any internal errors

    exit($error->getMessage());
}

// redirect user to starting page
header('Location: index.php'); # should display to user that they are successfully logged in