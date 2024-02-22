<?php

namespace View\Admin\Pages;

use Trait\View\Admin\AdminPagesTemplate;

class AdminPagesHome extends AdminPagesTemplate
{

    protected string $errorPath = '/';
    protected string $pageTitle = 'Admin Panel';
    protected function __construct()
    {
        parent::__construct();
    }

    protected function renderSettingsDashboard(): string
    {
        return '';
    }
}
