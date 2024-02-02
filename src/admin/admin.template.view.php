<?php

namespace View\Admin;

require __DIR__ . '/../error/errorpages.model.php';
require __DIR__ . '/../users/usersauthenticate.controller.php';

use Controller\Error\Pages\ErrorPages;
use Controller\Users\UserPrivileges;

class AdminPagesTemplate
{

    protected string $settingsName = '';
    protected string $settingsPath = '';
    private function __construct()
    {
    }

    // function for creating this class; this class can only be created if the user has the proper authorization
    public static function createInstance(): null | self
    {
        if (UserPrivileges::isAdministrator()) {
            return new static();
        }
        ErrorPages::displayForbidden();
        return null;
    }

    // function for rendering any adminpage
    public function renderPage()
    {
?>
        <!DOCTYPE html>
        <html lang="en">

        <head>
            <link rel="stylesheet" href="../style.admin.css">

            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Document</title>
        </head>

        <body>
            <div class="container">
                <nav class="sidebar">
                    <label for="navigation-list" class="sidebar-list-label">Main</label>
                    <ul id="navigation-list">
                        <li class="navigation-item"><a href="">Home</a></li>
                        <li class="navigation-item"><a href="">Pages</a></li>
                        <li class="navigation-item"><a href="">Settings</a></li>
                    </ul>
                </nav>

                <div class="settings">
                    <nav class="settings-path">
                        <!-- Path to setting -->
                        <?php echo $this->renderSettingsPath(); ?>
                    </nav>
                    <div class="dashboard-container">
                        <span class="dashboard-name">
                            <!-- Settings name -->
                            <?php echo $this->renderSettingsName(); ?>
                        </span>
                        <main class="dashboard">
                            <!-- here are the settings placed -->
                            <?php echo $this->renderSettingsDashboard(); ?>
                        </main>
                    </div>
                </div>
            </div>
        </body>

        </html>

<?php

    }

    // function for giving the path to said setting
    protected function renderSettingsPath(): string
    {
        $renderedPath = '';

        $sublinks = explode('/', $this->settingsPath);
        $linkDepth = count($sublinks) - 1;

        foreach ($sublinks as $sublink) {
            $renderedPath .= '<a href="' . str_repeat('../', $linkDepth) . "$sublink\">$sublink</a> / ";
            $linkDepth--; 

        }

        return substr($renderedPath, 0, -2);
    }

    // function for showing the name of the settings site you are on
    protected function renderSettingsName(): string
    {
        return $this->settingsName;
    }

    // function for rendering all of the settings on each page; should be overwritten by new function in extended class 
    protected function renderSettingsDashboard(): string
    {
        return '';
    }
}


/* This class should be used in the following way:

class example extends AdminPagesTemplate {
    protected function __construct()
    {
        $this->settingsPath = 'myPath';
        $this->settingsName = 'myName';
    }

    protected function renderSettingsDashboard(): string
    {
        # A random function
        return '';
    }
}*/

// $_SESSION['userPrivileges'] = 'a';
// $page = AdminPages::createInstance();
// $page->renderPage();
