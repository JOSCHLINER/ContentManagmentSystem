<?php

namespace View\Admin\Pages;

use Trait\View\Admin\AdminPagesTemplate;
use Controller\Settings\AdminSettings;


class AdminPagesSettings extends AdminPagesTemplate
{
    protected function __construct()
    {
        $this->settingsPath = 'Settings';
        $this->settingsName = 'General Settings View';
    }

    protected function renderSettingsDashboard(): string
    {
        $loadedSettings = AdminSettings::createInstance();
        $settings = $loadedSettings->retrieveSettings();

        foreach ($settings as $section => $sectionContent) {
            echo "<span>[$section]</span> <br>";
            foreach ($sectionContent as $key => $value) {
                echo "<span>$key : $value</span> <br>";
            }

        }

        return '';
    }

}

// $_SESSION['userPrivileges'] = 'a';
// $page = AdminPagesSettings::createInstance();
// $page->renderPage();
