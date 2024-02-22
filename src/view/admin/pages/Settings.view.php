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

        parent::__construct();
    }

    protected function renderSettingsDashboard(): string
    {
        $loadedSettings = AdminSettings::createInstance();
        $settings = $loadedSettings->retrieveSettings();

?>
        <table class="blocktable">
            <tr>
                <th>Sectionname</th>
                <th>Settingsname</th>
                <th>Settingsvalue</th>
            </tr>

            <?php foreach ($settings as $section => $sectionContent) { ?>

                <tr>
                    <td><?= $section ?></td>
                    <td></td>
                    <td></td>
                </tr>

                <?php foreach ($sectionContent as $key => $value) { ?>
                    <tr>
                        <td></td>
                        <td><?= $key ?></td>
                        <td><?= $value ?></td>
                    </tr>

            <?php
                }
            }
            ?>

        </table>

<?php

        return '';
    }
}

// $_SESSION['userPrivileges'] = 'a';
// $page = AdminPagesSettings::createInstance();
// $page->renderPage();
