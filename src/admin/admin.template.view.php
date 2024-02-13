<?php

namespace View\Admin;


use Controller\Error\Pages\ErrorPages;
use Controller\Users\UserPrivileges;
use Exception;

class AdminPagesTemplate
{
    // variables used to render the page
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
        // load file with the template for the admin page with he right functions calls inside
        try {
            include __DIR__ . '/admin.template.php';
        } catch (Exception $error) {
            
        }
    }

    // function for creating the path to said setting
    protected function renderSettingsPath(): string
    {
        $renderedPath = '';

        $subLinks = explode('/', $this->settingsPath);
        $linkDepth = count($subLinks) - 1;

        foreach ($subLinks as $subLink) {
            $renderedPath .= '<a href="' . str_repeat('../', $linkDepth) . "$subLink\">$subLink</a> / ";
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

    public function handleGetRequest()
    {
    }

    public function handlePostRequest()
    {
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
// $page = AdminPagesTemplate::createInstance();
// $page->renderPage();