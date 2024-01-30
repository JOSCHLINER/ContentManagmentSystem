<?php

class Settings {

    protected static array $settings;
    protected string $settingsFile;
    public function __construct()
    {
        $this->settingsFile = 'settings.ini';
        $this->loadSettingsFile();
    }

    // function for loading in the setting file
    protected function loadSettingsFile():bool {
        self::$settings = parse_ini_file($this->settingsFile, true);
        return true;
    }

} 