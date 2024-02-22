<?php

namespace View\Admin\Pages;

use Trait\View\Admin\AdminPagesTemplate;
use Controller\Pages\PagesHandler;
use Model\Page;
use Error;

class AdminPagesPagesEdit extends AdminPagesTemplate
{
    private ?Page $page = null;
    protected string $pageTitle = 'Edit Page';
    protected function __construct()
    {
        parent::__construct();
    }

    protected function renderSettingsDashboard(): string
    {
        // if we don't have a page
        // user gets redirects to Pages Overview
        if (is_null($this->page)) {

            // end buffering, making it possible to send out the headers
            ob_end_clean();

            // redirect user to the the view pages site
            http_response_code(303);
            header('Location: ' . $_SERVER['SERVER_NAME'] . '/admin/pages/view');
            exit(); # should add an errorcodes for user to see
        }

        # should make nicer once the backend is done
        return '<form method="POST" class="editform">
        <input name="id" type="hidden" value="' . $this->page->pageId . '">
        <input type="text" name="title" value="' . $this->page->pageTitle . '">
        <textarea name="content" rows=5>' . $this->page->pageContent . '</textarea>
        <input type="submit" name="edit"></form>
        <form method="POST">
        <input type="hidden" name="id" value="' . $this->page->pageId . '">
        <input name="delete" type="submit" value="Delete Page">
        </form>';
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
        if (isset($POSTRequest['delete'])) {
            // if the page should be deleted

            // check if a page id was provided
            if (empty($POSTRequest['id'])) {
                throw new Error('No page provided');
                return false;
            }

            // deleting the page
            $handler = new PagesHandler();
            return $handler->deletePage($POSTRequest['id']);
        } elseif (isset($POSTRequest['edit'])) {
            // if changes to the page are made

            return $this->editPage($POSTRequest);
        } else {
            throw new Error('Not a valid operation!');
            return false;
        }
    }

    private function editPage(array &$Request): bool
    {
        if (!$this->isValidPost($Request)) {
            return false;
        }

        $pageId = $this->getPageId($Request);

        // saving changes
        $handler = new PagesHandler();
        $handler->saveChanges($pageId, $Request['content'], $Request['summary'], $Request['title']);

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
        if (!isset($POSTRequest['id']) or !isset($POSTRequest['content']) or !isset($POSTRequest['title']) or !isset($POSTRequest['summary'])) {
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
