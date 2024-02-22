<?
// initialize necessary services
require __DIR__ . '/../../model/Includes.model.php';
Model\Includes::initialize(false);

use View\Templates\Pages;
use Controller\Users\Login;

/**
 * Class for the login page.
 */
class LoginPage extends Pages
{

    protected string $errorPath = '/login.php';
    public function __construct()
    {
        $this->pageTitle = 'Login';

        parent::__construct();
    }

    protected function pageContent()
    {
?>

        <form method="Post">
            <div class="form-group">
                <label for="exampleInputEmail1">Username</label>
                <input type="text" class="form-control" id="exampleInputEmail1" name="username" aria-describedby="emailHelp" placeholder="Enter username">
            </div>

            <div class="form-group">
                <label for="exampleInputPassword1">Password</label>
                <input type="password" class="form-control" id="exampleInputPassword1" name="password" placeholder="Password">
            </div>

            <input type="submit" class="btn btn-primary" value="Login">
        </form>

        <div class="d-block">
            <p>Don't have an account? <a href="register.php" class="btn btn-link">Register</a></p>
        </div>

<?php
    }

    public function handlePostRequest(array &$Request)
    {

        // try to login user
        $handler = new login();
        $user = $handler->login($_POST);

        // store user data in session
        $_SESSION['user'] = serialize($user);

        // redirect user to starting page
        header('Location: index.php?err=Logged in successfully as ' . $user->username . '.&type=success'); # should display to user that they are successfully logged in
    }
}

// rendering page
$page = new LoginPage();

try {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $page->handlePostRequest($_POST);
    } else {
        $page->renderOnlyContent();
    }
} catch (Error $error) {
    $page->errorRedirect('An error occurred when trying to log in!', $error->getMessage());
}