<?php

namespace View\Admin\Pages;

use Trait\View\Admin\AdminPagesTemplate;
use Controller\Settings\AdminSettings;


class AdminPagesSettings extends AdminPagesTemplate
{
    protected string $errorPath = '/admin/home';
    protected string $pageTitle = 'Settings';
    protected function __construct()
    {
        parent::__construct();
    }

    protected function renderSettingsDashboard(): string
    {
        $loadedSettings = AdminSettings::createInstance();
        $settings = $loadedSettings->retrieveSettings();

?>
        <h1>Settings</h1>


        <div class="container mt-5">
            <div class="w-100 d-flex justify-content-end">
                <a href="/admin/settings/edit">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                        <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                        <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                    </svg>
                    <span style="font-size:small">Edit</span>
                </a>
            </div>

            <table class="blocktable table table-bordered table-striped table-hover mt-1">
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
        </div>
<?php

        return '';
    }
}

// $_SESSION['userPrivileges'] = 'a';
// $page = AdminPagesSettings::createInstance();
// $page->renderPage();
