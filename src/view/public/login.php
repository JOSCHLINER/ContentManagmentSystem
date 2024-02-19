<?
// initialize necessary services
require __DIR__ . '/../../model/Includes.model.php';
Model\Includes::initialize();


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
    # should display them in some better way.

    exit($error->getMessage());
} catch (Exception $error) {
    // catch any internal errors

    exit($error->getMessage());
}

// store user data in session
$_SESSION['user'] = serialize($user);

// redirect user to starting page
header('Location: index.php'); # should display to user that they are successfully logged in