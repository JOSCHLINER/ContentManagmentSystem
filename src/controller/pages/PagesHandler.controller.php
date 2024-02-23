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
    public function saveChanges(int $articleId, string $content, string $summary, string $title): bool
    {

        // check if the page is in restricted mode
        // in which case only the owner can edit the page
        $page = $this->getPage($articleId);
        $user = unserialize($_SESSION['user']);
        if ($page->restricted == true and $page->authorId != $user->userId) {
            throw new Error('This page is in restricted mode, meaning only the owner can make changes to it.');
            return false;
        }

        $content = htmlspecialchars($content);
        $title = htmlspecialchars($title);

        $sql = 'UPDATE articles SET content = ?, summary = ?, title = ?, created_date = CURRENT_TIMESTAMP WHERE article_id = ?;';
        $types = 'sssi';

        $database = Database::getInstance();
        $result = $database->query($sql, [$content, $summary, $title, $articleId], $types);

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

        $sql = 'SELECT article_id, author_id, username, title, content, summary, created_date FROM articles a LEFT JOIN users u ON a.author_id = u.user_id WHERE article_id = ?;';
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
            $page->pageSummary = $result['summary'];
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
    public function createPage(string $content, string $summary, string $title): int|null
    {

        // escape user created html to mitigate XSS attacks
        $content = htmlspecialchars($content);
        $summary = htmlspecialchars($summary);
        $title = htmlspecialchars($title);

        $database = Database::getInstance();

        // creating page in database
        $sql = 'INSERT INTO articles(author_id, title, content, summary) VALUES(?, ?, ?, ?);';
        $types = 'isss';
        $database->query($sql, [unserialize($_SESSION['user'])->userId, $title, $content, $summary], $types);

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
