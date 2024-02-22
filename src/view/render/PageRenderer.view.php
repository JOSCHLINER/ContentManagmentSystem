<?php

namespace View\Render;

use Controller\Pages\PagesHandler;
use Libraries\Parsedown;
use Model\Page;
use Model\Includes;

/**
 * Class to creating pages rendering sites.
 */
class PageRenderer {

     private int $pageId;
     private Page $page;
     private Parsedown $parser;
     public function __construct(int $pageId) {
          $this->pageId = $pageId;

          $handler = new PagesHandler();
          $this->page = $handler->getPage($this->pageId);

          $this->parser = Includes::getSecureParsedown();
     }

     public function renderMarkdownMainContent() {
          return $this->parser->text($this->page->pageContent);
     }

     public function renderMarkdownSummary() {
          return $this->parser->text($this->page->pageSummary);
     }

     public function pageTitle(): string {
          return $this->page->pageTitle;
     }

     public function pageAuthor(): string {
          return $this->page->pageAuthor;
     }

     public function pageCreationDate(): string {
          return $this->page->creationDate;
     }

}