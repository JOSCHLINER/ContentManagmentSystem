<?php

namespace Controller\Pages;

use Model\Database;
use Model\Page;
use Error;

/**
 * Class to save changes made to Pages.
 */
class PagesHandler
{

    /**
     * Function for saving changes to an already existing page.
     */
    public function saveChanges(int $articleId, string $content, string $title): bool
    {

        // check if user owns the site
        $page = $this->getPage($articleId);
        $user = unserialize($_SESSION['user']);
        if ($page->authorId != $user->userId) {
            throw new Error('Only the page owner can edit the page');
            return false;
        }

        $content = htmlspecialchars($content);
        $title = htmlspecialchars($title);

        $sql = 'UPDATE articles SET content = ?, title = ?, created_date = CURRENT_TIMESTAMP WHERE article_id = ?;';
        $types = 'ssi';

        $database = Database::getInstance();
        $result = $database->query($sql, [$content, $title, $articleId], $types);

        return true;
    }


    /**
     * Function to delete a page by id.
     */
    public function deletePage(int $articleId): bool
    {

        // check if user owns the site
        $page = $this->getPage($articleId);
        $user = unserialize($_SESSION['user']);
        if ($page->authorId != $user->userId) {
            throw new Error('Only the page owner can delete the page');
            return false;
        }

        // delete the article
        $database = Database::getInstance();
        $sql = 'DELETE FROM articles WHERE article_id = ?;';
        $types = 'i';
        $result = $database->query($sql, [$articleId], $types);

        return true;
    }

    /**
     * Function to get a page by id.
     */
    public function getPage(int $pageId): Page|null
    {

        $sql = 'SELECT article_id, author_id, username, title, content, created_date FROM articles a LEFT JOIN users u ON a.author_id = u.user_id WHERE article_id = ?;';
        $type = 'i';

        $database = Database::getInstance();
        $result = $database->query($sql, [$pageId], $type);

        // check if we got a result back
        $result = $result->fetch_assoc();
        if (isset($result['article_id'])) {

            // create new Page Object
            $page = new Page();

            // fill the values in the Page Object
            $page->pageId = $pageId;
            $page->pageTitle = $result['title'];
            $page->pageContent = $result['content'];
            $page->creationDate = $result['created_date'];
            $page->pageAuthor = $result['username'];
            $page->authorId = $result['author_id'];

            return $page;
        }

        throw new Error('Page not found!');
        return null;
    }

    /**
     * Function to create a page.
     * 
     * @return int Returns the id of the created page.
     */
    public function createPage(string $content, string $title): int|null
    {

        // escape user created html to mitigate XSS attacks
        $content = htmlspecialchars($content);
        $title = htmlspecialchars($title);

        $database = Database::getInstance();

        // creating page in database
        $sql = 'INSERT INTO articles(author_id, title, content) VALUES(?, ?, ?);';
        $types = 'iss';
        $database->query($sql, [unserialize($_SESSION['user'])->userId, $title, $content], $types);

        return $this->getArticleIdByTitle($title);
    }

    private function getArticleIdByTitle(string $title): int|null
    {
        $database = Database::getInstance();

        $sql = 'SELECT article_id FROM articles WHERE title = ?;';
        $types = 's';
        $result = $database->query($sql, [$title], $types)->fetch_assoc();

        if (!isset($result['article_id'])) {
            throw new Error('Page not found!');
            return null;
        }

        return (int) $result['article_id'];
    }
}
