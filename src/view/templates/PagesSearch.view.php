<?php

namespace View\Templates;

use Controller\Error\ResponseMessages;
use Model\Database;
use Model\Includes;

/**
 * Class to search Pages.
 */
class PagesSearch { # just shows all pages for now.
    private array $results;
    private array $filterParams;
    private string $linkPath;
    public function __construct(array $filterParams, string $linkPath = '/wiki/') {
        $this->filterParams = $filterParams;
        $this->linkPath = $linkPath;
        $this->getResults();
    }

    public function render() {
?>
    <div class="container">
<?php
        if (empty($this->results)) {
            ResponseMessages::printMessage('No matching pages found', 'info', 'Please try another wording or phrase.');
        }

        foreach($this->results as $result) {
            $this->renderPreview($result);
        }
?>
    </div>
<?php
    }

    private function getResults() {
        $database = Database::getInstance();
        $sql = 'SELECT title, summary, article_id, username FROM articles a LEFT JOIN users u ON u.user_id = a.author_id;';
        $result = $database->queryUnsafe($sql);
    
        $this->results = array();
        while ($row = $result->fetch_assoc()) {
            $this->results[] = $row;
        }
    }
    

    private function renderPreview($row) {
?>
        <div class="container shadow p-3 mb-5 bg-body-tertiary rounded">
            <a href="<?= $this->linkPath . '/' . $row['article_id'] ?>" class="hidden_link">
            <div class="d-flex justify-content-between w-100">
                <h5><?= $row['title'] ?></h5>  
                <span>written by <i><?= $row['username'] ?></i></span>
            </div>
            <hr>

            <p>
            <?= Includes::getSecureParsedown()->text($row['summary']) ?>
            </p>
            </a>

        </div>
<?php
    }
}
