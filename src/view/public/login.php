<?php
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

        <div class="display-block shadow bg-body-tertiary rounded p-3 mb-5">
            <form class="submit_register_form d-block" method="Post">
                <div class="form-group pb-3">
                    <label for="usernameInput">Username</label>
                    <input type="text" class="form-control" id="usernameInput" name="username" aria-describedby="usernameHelp" placeholder="Enter username" maxlength="64" required>
                </div>

                <div class="form-group pb-3">
                    <label for="passwordInput">Password</label>
                    <input type="password" class="form-control" id="passwordInput" name="password" placeholder="Password" required>
                </div>

                <input type="submit" class="btn btn-primary" value="Login">
            </form>

            <div class="pt-5">
                Don't have an account?<a href="register.php" class="btn btn-link">Register</a>
            </div>
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
