<?php

namespace View\Admin\Pages;

use Trait\View\Admin\AdminPagesTemplate;

class AdminPagesHome extends AdminPagesTemplate
{

    protected function __construct()
    {
        $this->settingsPath = 'Home';
        $this->settingsName = 'Main';

        parent::__construct();
    }

    protected function renderSettingsDashboard(): string
    {
        return '';
    }
}
