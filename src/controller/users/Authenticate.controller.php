<?php
namespace Controller\Users;


use Controller\Users\Users;

class Authenticate extends Users
{
    public static function privilegesAreSet()
    {
        if (!isset($_SESSION['userPrivileges'])) {
            $_SESSION['userPrivileges'] = 'n';
        }  
        return true;
    } 

    public static function isLoggedIn(): bool
    {
        return self::privilegesAreSet() and $_SESSION['userPrivileges'] != 'n';
    }

    public static function isModeratorOrGreater(): bool
    {
        return self::privilegesAreSet() and ($_SESSION['userPrivileges'] == 'm' or self::isAdministrator());
    }

    public static function isAdministrator(): bool
    {
        return self::privilegesAreSet() and $_SESSION['userPrivileges'] == 'a';
    }
}
