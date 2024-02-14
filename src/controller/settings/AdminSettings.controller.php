<?php

namespace Controller\S\Settings;

use Controller\Settings\Settings;
use Controller\Users\Authenticate;
use Controller\Error\HTTPResponse;

class AdminSettings extends Settings
{

    private function __construct()
    {
        parent::__construct();
    }

    // function for creating this class; this class can only be created if the user has the proper authorization
    public static function createInstance(): null | self
    {
        if (Authenticate::isAdministrator()) {
            return new self();
        }
        HTTPResponse::displayForbidden();
        return null;
    }

    // function for editing the settings in the configuration file
    public function editSettings(mixed $section, mixed $changeKey, mixed $changeValue): void
    {
        self::$settings[$section][$changeKey] = $changeValue;

        $newIniContents = '';
        foreach (self::$settings as $section => $section_content) {
            $newIniContents .= "[$section]" . PHP_EOL;
            foreach ($section_content as $key => $value) {
                $newIniContents .= "$key = $value" . PHP_EOL;
            }
            $newIniContents .= PHP_EOL;
        }
        // these changes should be logged
        file_put_contents(__DIR__ . '/' . $this->settingsFile, $newIniContents);
        $this->loadSettingsFile();
    }

    public function retrieveSettings(): array
    {
        return self::$settings;
    }
}