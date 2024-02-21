<?php

namespace View\Admin\Pages;

use Trait\View\Admin\AdminPagesTemplate;
use Controller\Pages\PagesHandler;
use Model\Page;
use Error;

class AdminPagesPagesEdit extends AdminPagesTemplate
{
    private ?Page $page = null;
    protected function __construct()
    {
        $this->settingsPath = 'Pages';
        $this->settingsName = 'Page edit';
    }

    protected function renderSettingsDashboard(): string
    {

        // if we don't have a page
        // user gets redirects to Pages Overview
        if (is_null($this->page)) {

            // end buffering, making it possible to send out the headers
            ob_end_clean();

            // redirect user to the the view pages site
            http_response_code(404);
            header('Location: ' . $_SERVER['SERVER_NAME'] . '/admin/pages/view');
            exit; # should add an errorcodes for user to see
        }
        return '<form method="POST"><input name="id" value="' . $this->page->pageId . '"><input type="text" name="title" value="' . $this->page->pageTitle . '"><textarea name="content">' . $this->page->pageContent . '</textarea><input type="submit"></form>';
    }

    public function handleGetRequest(array &$GETRequest): bool
    {
        // check if the user tries to edit a page
        // if not, return False
        $pageId = $this->getPageId($GETRequest);
        if (is_null($pageId)) {
            throw new Error('Page not found!');
            return false;
        }

        $handler = new PagesHandler();

        // getting the content of the page
        $page = $handler->getPage($pageId);
        if (!is_null($page)) {

            $this->page = $page;
            return true;
        }

        throw new Error('Page couldn\'t be found');
        return false;
    }

    public function handlePostRequest(array &$POSTRequest): bool
    {

        if (!$this->isValidPost($POSTRequest)) {
            return false;
        }

        $pageId = $this->getPageId($POSTRequest);

        // saving changes
        $handler = new PagesHandler();
        $handler->saveChanges($pageId, $POSTRequest['content'], $POSTRequest['title']);

        // getting the content of the page
        $page = $handler->getPage($pageId);
        if (!is_null($page)) {

            $this->page = $page;
            return true;
        }

        throw new Error('Page couldn\'t be saved, try again later!');
        return false;
    }

    /**
     * Checks if a Post request has all the necessary fields.
     * 
     * @return bool Returns `true` if the Post request is valid, otherwise `false`.
     */
    private function isValidPost(&$POSTRequest): bool
    {
        if (!isset($POSTRequest['id']) or !isset($POSTRequest['content']) or !isset($POSTRequest['title'])) {
            throw new Error('Invalid Post Request!');
            return false;
        }

        return true;
    }

    /**
     * Function to get the article if from a request, Get or Post.
     * 
     * @return int|null Returns the page id if one exists, otherwise null.
     */
    private function getPageId(array &$Request): int|null
    {
        if (isset($Request['id']) and is_numeric($Request['id'])) {
            return (int) $Request['id'];
        }

        throw new Error('Page doesn\'t exist');
        return null;
    }
}
