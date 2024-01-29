<?php

class Settings {

    static array $settings;
    public function __construct()
    {
        $this->loadSettingsFile();
    }

    private function loadSettingsFile():bool {
        self::$settings = parse_ini_file('settings.ini', false);
        return true;
    }

} 