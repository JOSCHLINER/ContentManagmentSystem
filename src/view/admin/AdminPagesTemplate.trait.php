<?php

namespace Trait\View\Admin;

use View\Templates\Pages;
use Controller\Error\HTTPResponse;
use Controller\Users\Authenticate;

/**
 * Class working as template for all admin pages.
 * An instance of this class can only be created through the static createInstance function of this class.
 * Example of a child:
 * ```
 * class AdminPagesSOMENAME extends AdminPagesTemplate
 * {
 *     protected string $settingsName = 'some name';
 *    protected string $settingsPath = 'some path';
 *    protected function __construct()
 *    {
 *   }
 *
 *    protected function renderSettingsDashboard(): string
 *    {
 *        return '';
 *    }
 *}
 * ```
 */
class AdminPagesTemplate extends Pages
{
    protected function __construct()
    {
        $this->pageTitle = isset($this->pageTitle) ? $this->pageTitle : 'Admin Panel';
        parent::__construct();
    }

    /**
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
    protected function pageContent()
    {
        return $this->renderSettingsDashboard();
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
    public function handleGetRequest(array &$GETRequest): bool
    {
        return true;
    }
}
