<?php

use View\Admin\AdminPagesTemplate;

class AdminPagesHome extends AdminPagesTemplate
{

    protected function __construct()
    {
        $this->settingsPath = 'Home';
        $this->settingsName = 'Main';
    }

    protected function renderSettingsDashboard(): string
    {
        return '';
    }
}