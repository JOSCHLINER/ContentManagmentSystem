<?php
// initialize necessary services
require __DIR__ . '/../../model/Includes.model.php';
Model\Includes::initialize();

use View\Templates\Pages;
use Controller\Pages\PagesHandler;

class Create extends Pages
{

    protected string $errorPath = '/create';
    public function __construct()
    {
        $this->pageTitle = 'Create Page';

        parent::__construct();
    }

    protected function pageContent()
    {

?>

        <div class="alert alert-info" role="alert">
            Markdown is enabled.
        </div>

        <form class="needs-validation" method="Post">
            <div class="mb-3">
                <label for="title" class="form-label">Title</label>
                <input type="text" class="form-control" id="title" name="title" aria-describedby="Title" maxlength="100" required>
                <div id="summaryMaxWordCount" class="form-text">At most 100 characters</div>
            </div>

            <div class="mb-3">
                <label for="summary" class="form-label">Summary</label>
                <textarea type="text" class="form-control" id="summary" name="summary" rows="5" maxlength="600" required></textarea>
                <div id="summaryMaxWordCount" class="form-text">At most 600 characters</div>
            </div>

            <div class="mb-3">
                <label for="content" class="form-label">Content</label>
                <textarea type="text" class="form-control" id="content" name="content" rows="5" minlength="100" required></textarea>
            </div>

            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="restricted_mode" name="restricted_mode">
                <label class="form-check-label" for="restricted_mode">Set page in restricted mode</label>
                <small id="restricted_mode" class="form-text text-muted d-block">Restricted Mode only allows you the author to edit the page.</small>
            </div>

            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="tos" name="tos" required>
                <label class="form-check-label" for="tos">Consent to the <a href="#">Terms and Conditions</a>.</label>
            </div>
            <button type="submit" class="btn btn-primary">Create Page</button>
        </form>

<?php

    }

    public function handlePostRequest(array &$Request)
    {
        // validating the post request
        if (!$this->validateRequest($Request)) {
            return false;
        }

        $handler = new PagesHandler();
        try {
            // trying to create the page
            $pageRestrictionMode = (isset($Request['restricted_mode'])) ? (($Request['restricted_mode'] == 'on') ? true : false) : false;

            $pageId = $handler->createPage($Request['content'], $Request['summary'], $Request['title'], $pageRestrictionMode);
            if (is_null($pageId)) {
                throw new Error();
            }
        } catch (Exception | Error $error) {
            // if the creation of the page fails

            throw new Error('Failed creating Page, please try again later!');
            return false;
        }

        http_response_code(303);
        header('Location: /wiki/' . $pageId);
    }

    private function validateRequest(&$Request): bool
    {
        if (!isset($Request['title']) or empty($Request['title'])) {
            throw new Error('Page needs to have a title!');
            return false;
        } elseif (strlen($Request['title']) > 100) {
            throw new Error('Title exceeds character limit of 100!');
            return false;
        } elseif (!isset($Request['summary']) or empty($Request['summary'])) {
            throw new Error('Page needs to have a title!');
            return false;
        } elseif (strlen($Request['summary']) > 600) {
            throw new Error('Summary exceeds character limit of 100!');
            return false;
        } elseif (!isset($Request['content']) or empty($Request['content'])) {
            throw new Error('Page can not be empty!');
            return false;
        } elseif (strlen($Request['content']) < 100) {
            throw new Error('Please give the page some more content. At least 100 characters are needed!');
            return false;
        } elseif (!isset($Request['tos']) or $Request['tos'] == 'off') {
            throw new Error('You have to agree to our terms of services if you want to create a page!');
            return false;
        }

        return true;
    }
}

$page = new Create();

try {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $page->handlePostRequest($_POST);
    } else {
        $page->renderPage();
    }
} catch (Error $error) {
    $page->errorRedirect('An error occurred when creating the page!', $error->getMessage());
}
