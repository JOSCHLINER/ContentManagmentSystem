<?php

namespace Controller\Settings;

use Controller\Settings\Settings;
use Error;
use Model\Cache;
use Model\ConnectionSettings;
use Model\Database;

class SensibleSettingsLoader extends Settings
{

    private static ?array $sensibleSettings = null;
    private string $sensibleSettingsFile = '../../configuration/sensible_settings.ini';
    public function __construct()
    {

        // Check if the settings have already been loaded in. 
        // This could happen if there are multiple instances of this class
        if (is_null(self::$sensibleSettings)) {
            $this->loadSensibleSettings();
        }

        // constructing the normal settings 
        parent::__construct();
    }

    private function loadSensibleSettings()
    {
        self::$sensibleSettings = parse_ini_file($this->sensibleSettingsFile, true);
        return true;
    }

    public function loadDatabaseSettings(): void
    {
        Database::loadSettings($this->loadConnectionSettings('Database'));
    }

    public function loadCacheSettings(): void
    {
        Cache::loadSettings($this->loadConnectionSettings('Cache'));
    }

    /**
     * Function load a `ConnectionSettings` from the name of the section in the ini file.
     */
    private function loadConnectionSettings(string $sectionName): ConnectionSettings
    {
        $settings = new ConnectionSettings();

        try {
            // getting the unsensible settings
            $settings->host = self::$settings[$sectionName]['host'];
            $settings->port = self::$settings[$sectionName]['port'];
            $settings->username = self::$settings[$sectionName]['user'];
            $settings->database = isset(self::$settings[$sectionName]['name']) ? self::$settings[$sectionName]['name'] : '';

            // getting the sensible settings
            $settings->password = self::$sensibleSettings[$sectionName]['password'];
        } catch (Error $error) {

            // without the settings loaded in the application can not work
            error_log('The settings are not properly configured! Error when loading settings from:' . $sectionName . $error->getMessage());
            die('Settings misconfigured, check logs!');
        }

        return $settings;
    }
}
