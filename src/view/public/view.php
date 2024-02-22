<?php
// initialize necessary services
require __DIR__ . '/../../model/Includes.model.php';
Model\Includes::initialize();

use View\Render\ViewPage;
use View\Templates\Pages;

/**
 * Class for viewing pages.
 */
class View extends Pages
{

    protected string $errorPath = '/';
    private ViewPage $pageRender;
    public function __construct(int $title)
    {
        $this->pageRender = new ViewPage($title);
        $this->pageTitle = $this->pageRender->pageTitle();

        parent::__construct();
    }

    protected function pageContent()
    {

?>

        <div class="view_header">
            <h1> <?= $this->pageRender->pageTitle() ?></h1>
            <div class="d-flex justify-content-center">
                <div class="d-flex justify-content-between" style="width: 98%">
                    <span>written by <u> <?= $this->pageRender->pageAuthor() ?> </u> </span>
                    <span>created on <?= $this->pageRender->pageCreationDate() ?> </span>
                </div>
            </div>

        </div>
        <hr>
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