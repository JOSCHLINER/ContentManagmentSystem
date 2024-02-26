<?php

namespace View\Admin\Pages;

use Trait\View\Admin\AdminPagesTemplate;
use Controller\Settings\AdminSettings;

/**
 * Class for the admin/settings/edit page. Displays and saves settings.
 */
class AdminPagesSettingsEdit extends AdminPagesTemplate
{

    protected string $errorPath = '/admin/settings';
    protected string $pageTitle = 'Edit Settings';
    private AdminSettings $settingsInstance;
    protected function __construct()
    {
        $this->settingsInstance = AdminSettings::createInstance();

        parent::__construct();
    }


    protected function renderSettingsDashboard(): string
    {
        $settings = $this->settingsInstance->retrieveSettings();

        // printing out a table with all settings in the appropriate sections.
?>
        <h1>Settings</h1>


        <div class="container mt-5">
            <form method="Post">
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
                                <td><input class="form-control" name="<?= $section ?>[<?= $key ?>]" type="text" value="<?= $value ?>"></td>
                            </tr>

                    <?php
                        }
                    }
                    ?>

                </table>
                <div class="d-flex justify-content-end">
                    <input type="submit" value="Save Settings">
                </div>
            </form>

    <?php

        return '';
    }

    public function handlePostRequest(array &$POSTRequest): bool
    {
        // go over each settings section
        foreach ($POSTRequest as $section => $sectionContent) {

            foreach ($sectionContent as $key => $value) {
                // changing the settings locally
                $this->settingsInstance->editSettingsUnsaved($section, $key, $value);
            }
        }

        // writing the local saved settings
        return $this->settingsInstance->writeSettings();
    }
}
