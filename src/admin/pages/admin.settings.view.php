<?php
include __DIR__ . '/../admin.template.view.php';
include __DIR__ . '/../../configuration/settings.admin.controller.php';

use View\Admin\AdminPagesTemplate;
use Controller\Configuration\Settings\AdminSettings;

class AdminPagesSettingsView extends AdminPagesTemplate
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

$_SESSION['userPrivileges'] = 'a';
$page = AdminPagesSettingsView::createInstance();
$page->renderPage();
