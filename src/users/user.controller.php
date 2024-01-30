<?php
namespace Controller\Users;

use Model\Database\Database;

class Users
{

    private $databaseConnection;
    public function __construct() {
        $this->databaseConnection = Database::getInstance();
    }

    public function register(string $username, string $password, string $email): bool
    {
        if (!$this->validateEmail($email)) {
            return false;
        }

        # hashing password
        $password_hash = password_hash($password, PASSWORD_ARGON2I);

        # creating user in database
        $sql = 'INSERT INTO users(username, email, password) VALUES (?,?,?);';
        $params = [$username, $email, $password_hash];
        $types = 'sss';

        $this->databaseConnection->query($sql, $params, $types);
    }

    public function login(string $username, string $password): bool
    {
        $sql = 'SELECT password, privileges, user_id FROM users WHERE username = ?;';
        $params = [$username];
        $types = 's';

        $result = $this->databaseConnection->query($sql, $params, $types);

        $row = $result->fetch_assoc();

        # check password
        if (!password_verify($password, $row['password'])) {
            return false;
        }

        $_SESSION['username'] = $username;
        $_SESSION['user_id'] = $row['user_id'];
        $_SESSION['userPrivileges'] = $row['privileges'];

        return true;
    }

    public function logout(): void
    {
        $_SESSION['userPrivileges'] = 'n';
        unset($_SESSION['username']);
        unset($_SESSION['user_id']);
    }

    private function validateEmail($email): bool
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return false;
        }

        return true;
    }
}
