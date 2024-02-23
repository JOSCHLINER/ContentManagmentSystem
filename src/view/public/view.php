<?php
// initialize necessary services
require __DIR__ . '/../../model/Includes.model.php';
Model\Includes::initialize();

use View\Render\PageRenderer;
use View\Templates\Pages;

/**
 * Class for viewing pages.
 */
class View extends Pages
{

    protected string $errorPath = '/';
    private PageRenderer $pageRender;
    public function __construct(int $title)
    {
        $this->pageRender = new PageRenderer($title);
        $this->pageTitle = $this->pageRender->pageTitle();

        parent::__construct();
    }

    protected function pageContent()
    {

?>

        <div class="view_header">
            <div class="d-flex justify-content-between">
                <h1> <?= $this->pageRender->pageTitle() ?></h1>
                <a href="/edit/<?= $this->pageRender->pageId() ?>">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                        <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                        <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                    </svg>
                    <span style="font-size:small">Edit</span>
                </a>
            </div>

            <div class="d-flex justify-content-center">
                <div class="d-flex justify-content-between" style="width: 98%">
                    <span>written by <u> <?= $this->pageRender->pageAuthor() ?> </u> </span>
                    <span>created on <?= $this->pageRender->pageCreationDate() ?> </span>
                </div>
            </div>

        </div>
        <hr>
        <b> <?= $this->pageRender->renderMarkdownSummary() ?> </b>
        <?= $this->pageRender->renderMarkdownMainContent() ?>

<?php

    }

    public static function extractPageId(string $requestURI)
    {
        $pathItems = explode('/', $requestURI);

        return (int) $pathItems[2];
    }
}

// rendering page
try {

    // getting the right page
    $pageId = View::extractPageId($_SERVER['REQUEST_URI']);
    $page = new View($pageId);
} catch (Error $error) {
    header('Location: /index.php?err=The page you tried to view does not exist!&msg=' . $error->getMessage());
    exit();
}



try {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $page->handlePostRequest($_POST);
    } else {
        $page->renderPage();
    }
} catch (Error $error) {
    $page->errorRedirect('An error occurred when creating the page!', $error->getMessage());
}

?>