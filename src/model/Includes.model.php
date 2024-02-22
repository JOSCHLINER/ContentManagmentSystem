<?php

namespace Model;

use Libraries\Parsedown;

/**
 * Class for view pages. Initializes important services.
 * 
 * Services started:
 * - Autoloader
 * - Session
 */
class Includes {

    public static function initialize() {
        self::initializeAutoloader();
        self::checkSessionStatus();

    }

    private static function initializeAutoloader() {
        // load and register the Autoloader
        include __DIR__ . '/Autoloader.model.php';
        Autoloader::register();
    }

    private static function checkSessionStatus() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    public static function getSecureParsedown(): Parsedown {
        $parser = new Parsedown();
        $parser->setSafeMode(true);

        return $parser;
    }

}