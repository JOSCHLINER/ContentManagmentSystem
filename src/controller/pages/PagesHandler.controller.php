<?php

namespace Controller\Pages;

use Model\Database;
use Model\Page;

/**
 * Class to save changes made to Pages.
 */
class PagesHandler
{

    /**
     * Function for saving changes to an already existing page.
     */
    public function saveChanges(int $articleId, string $content): bool {
        $sql = 'UPDATE articles SET content = ?, created_date = CURRENT_TIMESTAMP WHERE article_id = ?;';
        $types = 'si';

        $database = Database::getInstance();
        $result = $database->query($sql, [$content, $articleId], $types);

        return true;
    }

    /**
     * Function to get a page by id.
     */
    public function getPage(int $pageId): Page|null {

        $sql = 'SELECT * FROM articles WHERE article_id = ?';
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
            $page->pageContent = $result['content'];
            $page->creationDate = $result['created_date'];
            $page->authorId = $result['author_id'];

            return $page;
        }

        return null;
    }
}