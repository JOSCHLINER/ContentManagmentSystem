<?php

namespace Model;

use Libraries\Parsedown;
use Controller\Settings\Settings;
use Controller\Users\Authenticate;

/**
 * Class for view pages. Initializes important services.
 * 
 * Services started:
 * - Autoloader
 * - Session
 */
class Includes
{

    public static function initialize(bool $loginRequired = true)
    {
        self::initializeAutoloader();
        self::checkSessionStatus();

        if ($loginRequired) {
            self::checkLoginStatus();
        }

        //load Settings into database
        $settings = new Settings();
        $settings->loadDatabase();
    }

    private static function initializeAutoloader()
    {
        // load and register the Autoloader
        include __DIR__ . '/Autoloader.model.php';
        Autoloader::register();
    }

    private static function checkLoginStatus()
    {
        if (!Authenticate::isLoggedIn()) {
            http_response_code(403);
            header('Location: register.php');
        }
    }

    private static function checkSessionStatus()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    public static function getSecureParsedown(): Parsedown
    {
        $parser = new Parsedown();
        $parser->setSafeMode(true);
        $parser->setMarkupEscaped(true);


        return $parser;
    }
}