<?
// initialize necessary services
require __DIR__ . '/../../model/Includes.model.php';
Model\Includes::initialize();


use Controller\Error\HTTPResponse;
use Controller\Users\Register;
use Controller\Settings\Settings;

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

    header('Location: index.php?err=' . $error->getMessage() . '&type=warning');
    exit($error->getMessage());
} catch (Exception $error) {
    // catch any internal errors

    exit($error->getMessage());
}

// redirecting user to the login page
// easier to let user login that it is to login the user directly
header('Location: index.php?err=User created Successfully&type=success');