<?php

namespace View\Admin\Pages;

use Controller\Pages\PagesHandler;
use Trait\View\Admin\AdminPagesTemplate;
use Error;

/**
 * Class working as template for all admin pages.
 * An instance of this class can only be created through the static createInstance function of this class.
 */
class AdminPagesPagesCreate extends AdminPagesTemplate
{
    protected string $settingsName = 'Pages Create';
    protected string $settingsPath = 'Page edit';
    protected function __construct()
    {
    }

    protected function renderSettingsDashboard(): string
    {
        return '<form method="POST"><input type="text" name="title"><textarea name="content"></textarea><input type="submit" value="Create Page"></form>';
    }

    public function handlePostRequest(array &$POSTRequest): bool
    {
        // validating the post request
        if (!$this->validateRequest($POSTRequest)) {
            return false;
        }

        // trying to create the page
        $handler = new PagesHandler();
        try {
            $pageId = $handler->createPage($POSTRequest['content'], $POSTRequest['title']);
            if (is_null($pageId)) {
                throw new Error();
            }

        }   catch(Error $error) {      
            // if the creation of the page fails
            
            throw new Error('Failed creating Page, please try again later!');
        }

        http_response_code(303);
        header('Location: /admin/pages/edit?id=' . $pageId);
        return true;
    }

    private function validateRequest(&$Request): bool {
        if (!isset($Request['title']) or empty($Request['title'])) {
            throw new Error('Page needs to have a title!');
            return false;
        }

        elseif(!isset($Request['content']) or empty($Request['content'])) {
            throw new Error('Page can not be empty!');
            return false;
        } 
        
        return true;
    }
}
