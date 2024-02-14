<?php

namespace Trait\View\Admin;


use Controller\Error\HTTPResponse;
use Controller\Users\Authenticate;
use Exception;

/**
 * Class working as template for all admin pages.
 * An instance of this class can only be created through the static createInstance function of this class.
 */
class AdminPagesTemplate
{
    /** 
     * Variable for the name of the page.
     */
    protected string $settingsName = '';

    /**
     * Variable for the path to the site.
     * The names of the pages should be separated by a single space.
     */
    protected string $settingsPath = '';
    private function __construct()
    {
    }

    /**and the 
     * Function for creating the AdminPagesTemplate class.
     * 
     * @return null|AdminPagesTemplate Class will only be created if the user has the proper authorization.
     */
    public static function createInstance(): null | self
    {
        if (Authenticate::isAdministrator()) {
            return new static();
        }
        HTTPResponse::displayForbidden();
        return null;
    }

    /**
     * Function for rendering admin pages.
     */
    public function renderPage()
    {
        // load file with the template for the admin page with he right functions calls inside
        try {
            include __DIR__ . '/AdminPageTemplate.php';
        } catch (Exception $error) {
        }
    }

    /**
     * Function for creating the path set for the site.
     * 
     * @return string The rendered html code for the path.
     */
    protected function renderSettingsPath(): string
    {
        $renderedPath = '';

        $subLinks = explode('/', $this->settingsPath);
        $linkDepth = count($subLinks) - 1;

        // creating relative links to parent links
        foreach ($subLinks as $subLink) {
            $renderedPath .= '<a href="' . str_repeat('../', $linkDepth) . "$subLink\">$subLink</a> / ";
            $linkDepth--;
        }

        return substr($renderedPath, 0, -2);
    }

    /**
     * Function for rendering the name of the current page.
     */
    protected function renderSettingsName(): string
    {
        return $this->settingsName;
    }

    /**
     * Function for rendering the custom content on each page.
     * 
     * This function has to be overwritten in children of the AdminPagesTemplate class.
     */
    protected function renderSettingsDashboard(): string
    {
        return '';
    }

    /**
     * Handles potential GET request for the given site.
     */
    public function handleGetRequest()
    {
    }

    /**
     * Handel potential POST requests for the given site.
     */
    public function handlePostRequest()
    {
    }
}
