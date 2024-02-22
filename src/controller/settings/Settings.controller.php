<?php

namespace Controller\Settings;

use Model\Database;

class Settings
{

    protected static ?array $settings = null;
    protected string $settingsFile = '../../configuration/settings.ini';
    public function __construct()
    {
        // check if settings already have been loaded
        // to not overwrite local changes and because it is not necessary
        if (is_null(self::$settings)) {
            $this->loadSettingsFile();
        }
    }

    // function for loading in the setting file
    protected function loadSettingsFile(): bool
    {
        self::$settings = parse_ini_file($this->settingsFile, true);
        return true;
    }

    public function loadDatabase(): void
    {
        $database = self::$settings['Database']['name'];
        $host = self::$settings['Database']['host'];
        $port = self::$settings['Database']['port'];
        $user = self::$settings['Database']['user'];
        $password = self::$settings['Database']['password'];

        Database::loadSettings($database, $host, $user, $password, $port);
    }

    public function getAppName(): string {
        return isset(self::$settings['About']['app_name']) ? self::$settings['About']['app_name'] : '';
    }
}
