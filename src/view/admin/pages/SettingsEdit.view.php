<?php

namespace View\Admin\Pages;

use Trait\View\Admin\AdminPagesTemplate;
use Controller\Settings\AdminSettings;

/**
 * Class for the admin/settings/edit page. Displays and saves settings.
 */
class AdminPagesSettingsEdit extends AdminPagesTemplate
{

    private AdminSettings $settingsInstance;
    protected function __construct()
    {
        $this->settingsPath = 'Home Settings';
        $this->settingsName = 'Edit Settings';

        $this->settingsInstance = AdminSettings::createInstance();
    }


    protected function renderSettingsDashboard(): string
    {
        $settings = $this->settingsInstance->retrieveSettings();

        // printing out a table with all settings in the appropriate sections.
?>
        <form method="POST">
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
                            <td><input name="<?= $section ?>[<?= $key ?>]" type="text" value="<?= $value ?>"></td>
                        </tr>

                <?php
                    }
                }
                ?>

            </table>
            <input type="submit" value="Save Settings">
        </form>

<?php

        return '';
    }

    public function handlePostRequest(array $POSTRequest): bool
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
