<?php

namespace View\Templates;

use Controller\Error\ResponseMessages;
use Controller\Settings\Settings;
use Controller\Users\UsersHandler;

/**
 * Class functioning as a template for all pages.
 * 
 * Example of how a new page should be build with this class:
 * ```php
 * class NewPage extends Pages
 * {
 *  protected string $errorPath = '/path/page/redirects/to/on/error';
 * 
 *  protected function pageContent()
 *  {
 *  // content of the page
 *  }
 * 
 *  public function handlePostRequest()
 *  {
 *  // what should happen if a post request is made.
 *  }
 * }
 * ```
 * 
 * The page should be build as:
 * ```php
 * try {
 *   if ($_SERVER['REQUEST_METHOD'] == 'POST') {
 *      $page->handlePostRequest($_POST);
 * } else {
 *     $page->renderPage();
 * }
 * } catch (Error $error) {
 *     $page->errorRedirect('An error occurred', $error->getMessage());
 * }
 * ```
 */
class Pages
{

  public readonly string $appName;
  protected string $pageTitle;

  /**
   * The Path of the page where errors get displayed if they occur. 
   * 
   * Pages should redirect to themselves not get an infinite redirect.
   * Static Pages are exempt from this rule.
   */
  protected string $errorPath = '/';
  public function __construct()
  {
    $this->appName = (new Settings)->getAppName();
  }


  /**
   * Function to render the page with the template for all pages.
   */
  public function renderPage()
  {
?>

    <!DOCTYPE html>
    <html lang="en">

    <head>
      <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
      <link rel="stylesheet" href="/static/css/style.css">


      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title> <?= $this->appName ?> - <?= $this->pageTitle ?></title>
    </head>

    <body class="d-flex flex-column min-vh-100">
      <header>
        <nav class="navbar bg-body-tertiary">
          <div class="container-fluid">
            <a class="navbar-brand" href="/"><?= $this->appName ?></a>
            <ul class="nav justify-content-end">
              <li class="nav-item">
                <a class="nav-link" id="username" href="#"><?= UsersHandler::getUsername() ?></a>
              </li>
              <li class="nav-item">
                <a class="active btn btn-primary" aria-current="page" href="/create">Create Page</a>
              </li>
            </ul>
          </div>
        </nav>
      </header>
      <main class="container py-4">
        <?= ResponseMessages::displayMessage() ?>
        <?= $this->pageContent() ?>
      </main>

      <footer class="d-flex flex-wrap justify-content-between align-items-center py-3 border-top mt-auto">

        <div class="col-md-4 d-flex align-items-center">
          <a href="/" class="mb-3 me-2 mb-md-0 text-muted text-decoration-none lh-1">
            <img src="/static/images/favicon.svg" alt="">
          </a>
          <div>
            <span class="text-muted d-block">© 2024 Joschliner</span>
            <p class="text-muted d-flex">Project distributed under GNU General Public License v3.0</p>
          </div>
        </div>

        <ul class="nav col-md-4 justify-content-end list-unstyled d-flex">
          <li class="ms-3 justify-content-center">
            <a class="btn btn-outline-secondary link-underline link-underline-opacity-0 justify-content-center d-flex" href="https://github.com/JOSCHLINER/Markdown-CMS" target="_blank">
              Visit on GitHub
              <svg class="m-1" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                <path d="M8 0C3.58 0 0 3.58 0 8c0 3.54 2.29 6.53 5.47 7.59.4.07.55-.17.55-.38 0-.19-.01-.82-.01-1.49-2.01.37-2.53-.49-2.69-.94-.09-.23-.48-.94-.82-1.13-.28-.15-.68-.52-.01-.53.63-.01 1.08.58 1.23.82.72 1.21 1.87.87 2.33.66.07-.52.28-.87.51-1.07-1.78-.2-3.64-.89-3.64-3.95 0-.87.31-1.59.82-2.15-.08-.2-.36-1.02.08-2.12 0 0 .67-.21 2.2.82.64-.18 1.32-.27 2-.27s1.36.09 2 .27c1.53-1.04 2.2-.82 2.2-.82.44 1.1.16 1.92.08 2.12.51.56.82 1.27.82 2.15 0 3.07-1.87 3.75-3.65 3.95.29.25.54.73.54 1.48 0 1.07-.01 1.93-.01 2.2 0 .21.15.46.55.38A8.01 8.01 0 0 0 16 8c0-4.42-3.58-8-8-8"></path>
              </svg>
            </a>
          </li>
        </ul>

      </footer>


    </body>

    </html>

  <?php
  }

  /**
   * Function to only render the item in the `pageContent` function
   */
  public function renderOnlyContent()
  {
  ?>

    <!DOCTYPE html>
    <html lang="en">

    <head>
      <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
      <link rel="stylesheet" href="/static/css/style.css">


      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title> <?= $this->appName ?> - <?= $this->pageTitle ?></title>
    </head>

    <body class="min-vh-100">
      <div class="m-5">
        <?= ResponseMessages::displayMessage() ?>
      </div>

      <main class="d-flex align-items-center justify-content-center" style="height:100vh">

        <?= $this->pageContent() ?>
      </main>

    </body>

    </html>

<?php
  }

  public function errorRedirect(string $error, string $message = '')
  {
    http_response_code(400);
    header('Location: ' . $this->errorPath  . "?err=$error&msg=$message&type=error");
    die();
  }

  protected function pageContent()
  {
  }

  public function handlePostRequest(array &$Request)
  {
  }
}
