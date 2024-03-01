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

    /**
     * Class responsible for initializing the services.
     * 
     * @param bool $loginRequired Parameter for setting if the user has to be logged in to visit the page. On by default.
     */
    public static function initialize(bool $loginRequired = true)
    {
        self::initializeAutoloader();
        self::checkSessionStatus();

        if ($loginRequired) {
            self::checkLoginStatus();
        }
    }

    /**
     * Function for initializing the Autoloader class. 
     */
    private static function initializeAutoloader()
    {
        // load and register the Autoloader
        include __DIR__ . '/Autoloader.model.php';
        Autoloader::register();
    }

    /**
     * Function checking if user is logged in.
     */
    private static function checkLoginStatus()
    {
        if (!Authenticate::isLoggedIn()) {
            http_response_code(403);
            header('Location: /register.php');
            exit();
        }
    }

    /**
     * Function for checking if a session is started.
     * 
     * If a session is started nothing happens, otherwise a session is started.
     */
    private static function checkSessionStatus()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * Function for getting a secured version of the Parsedown class.
     */
    public static function getSecureParsedown(): Parsedown
    {
        $parser = new Parsedown();
        $parser->setSafeMode(true);
        $parser->setMarkupEscaped(true);


        return $parser;
    }
}