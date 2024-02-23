<?php
// initialize necessary services
require __DIR__ . '/../../model/Includes.model.php';
Model\Includes::initialize();

use View\Templates\Pages;
use Controller\Pages\PagesHandler;
use View\Templates\PagesSearch;

class Index extends Pages
{

    public function __construct()
    {
        $this->pageTitle = 'Home';

        parent::__construct();
    }

    protected function pageContent()
    {
?>

        <h1>All pages</h1>
        <p>
            Lorem ipsum dolor sit amet consectetur adipisicing elit. Similique adipisci esse nemo ipsa, modi error nesciunt, harum excepturi quod odio repellendus dolores autem qui? Asperiores porro possimus tenetur eaque veritatis?
        </p>

        <hr class="my-4">

<?php

        if (!isset($_GET['err']) or (isset($_GET['type']) and $_GET['type'] != 'error')) {
            try {
                $pagesFilter = new PagesSearch([]);
                $pagesFilter->render();
            } catch (Exception | Error $error) {
                var_dump($error);
                // throw new Error('Pages couldn\'t be fetched');
            }
        }
    }
}

$page = new Index();

try {
    ob_start();
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $page->handlePostRequest($_POST);
    } else {
        $page->renderPage();
    }
} catch (Error $error) {
    ob_end_clean();
    $page->errorRedirect('An error occurred when rendering the page!', $error->getMessage());
}

ob_end_flush();
