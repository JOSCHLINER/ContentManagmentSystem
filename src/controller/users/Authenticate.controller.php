<?php
namespace Controller\Users;


class Authenticate
{
    public static function isLoggedIn()
    {
        return isset($_SESSION['user']);
    } 

    public static function isModeratorOrGreater(): bool
    {
        return self::isLoggedIn() and (unserialize($_SESSION['user'])->authorization == 'm' or self::isAdministrator());
    }

    public static function isAdministrator(): bool
    {
        return self::isLoggedIn() and unserialize($_SESSION['user'])->authorization == 'u'; # should be changed to a for administrator later
    }
}
