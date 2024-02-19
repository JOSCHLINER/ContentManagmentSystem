<?php

namespace Controller\Users;

use Model\Database;
use Model\User;
use Error;

/**
 * Class to handle the User Model.
 */
class UsersHandler
{

    /**
     * Function to get the user information back.
     */
    public static function getUser(string $username): User|null
    {
        $database = Database::getInstance();

        $sql = 'SELECT * FROM users WHERE username = ?;';
        $types = 's';

        // getting user data
        $userData = $database->query($sql, [$username], $types)->fetch_assoc();

        if (!isset($userData['username'])) {
            throw new Error('User can not be found!');
            return null;
        }

        // loading data
        $user = new User();
        $user->username = $username;
        $user->userId = $userData['user_id'];
        $user->authorization = $userData['privileges'];
        $user->email = $userData['email'];
        $user->password = $userData['password'];

        return $user;
    }
}
