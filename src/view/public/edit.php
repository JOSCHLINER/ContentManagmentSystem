<?php
// initialize necessary services
require __DIR__ . '/../../model/Includes.model.php';
Model\Includes::initialize(false);

use Controller\Pages\PagesHandler;
use Model\Page;
use View\Templates\Pages;

/**
 * Class for the register page
 */
class Edit extends Pages
{

    private int $pageId;
    private Page $page;
    private PagesHandler $pageHandler;
    protected string $errorPath = '/';
    public function __construct(int $pageId)
    {
        $this->pageTitle = 'Edit';
        $this->pageId = $pageId;
        $this->errorPath = '/edit/' . $pageId;

        $this->pageHandler = new PagesHandler();
        $this->page = $this->pageHandler->getPage($pageId);

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
                <input type="text" class="form-control" id="title" name="title" aria-describedby="Title" maxlength="100" value="<?= $this->page->pageTitle ?>" required>
                <div id="summaryMaxWordCount" class="form-text">At most 100 characters</div>
            </div>

            <div class="mb-3">
                <label for="summary" class="form-label">Summary</label>
                <textarea type="text" class="form-control" id="summary" name="summary" rows="5" maxlength="600" required><?= $this->page->pageSummary ?></textarea>
                <div id="summaryMaxWordCount" class="form-text">At most 600 characters</div>
            </div>

            <div class="mb-3">
                <label for="content" class="form-label">Content</label>
                <textarea type="text" class="form-control" id="content" name="content" rows="5" minlength="100" required><?= $this->page->pageContent ?></textarea>
            </div>

            <!-- <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="tos" name="" required>
                <label class="form-check-label" for="tos">Consent to the <a href="#">Terms and Conditions</a>.</label>
            </div> -->
            <button type="submit" class="btn btn-primary">Save Changes</button>
        </form>

<?php
    }

    public function handlePostRequest(array &$Request)
    {
        if (!$this->validateRequest($Request)) {
            return;
        }

        $this->pageHandler->saveChanges($this->pageId, $Request['content'], $Request['summary'], $Request['title']);

        // redirecting user to the edited page
        http_response_code(303);
        header('Location: /wiki/' . $this->pageId . '?err=Changes saved successfully&type=success');
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
        }

        return true;
    }

    public static function extractPageId(string $requestURI)
    {
        $pathItems = explode('/', $requestURI);
        // var_dump($pathItems);
        return (int) $pathItems[2];
    }
}

// rendering page
try {

    // getting the right page
    $pageId = Edit::extractPageId($_SERVER['REQUEST_URI']);
    $page = new Edit($pageId);
} catch (Error $error) {
    header('Location: /index.php?err=Error!&msg=' . $error->getMessage());
    exit();
}

try {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $page->handlePostRequest($_POST);
    } else {
        $page->renderPage();
    }
} catch (Error $error) {
    $page->errorRedirect('An error occurred when trying to save the changes!', $error->getMessage());
}
