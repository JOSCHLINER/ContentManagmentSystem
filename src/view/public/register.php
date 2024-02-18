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
    $user = $handler->register($_POST);
} catch (Error $error) {
    // catch any errors created from users input

    exit($error->getMessage());
} catch (Exception $error) {
    // catch any internal errors

    exit($error->getMessage());
}

echo 'User created successfully';
# should log in user too

# redirect doesn't work for now because of the echo statment before here
header('Location: index.php');