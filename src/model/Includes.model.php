<?php

namespace Model;
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

}