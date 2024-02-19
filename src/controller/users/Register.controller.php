<?php

namespace Controller\Users;

use Error;
use Model\Database;

/**
 * Class to register users.
 * 
 * New user are registered with the `register()` function.
 */
class Register
{

    protected array $PostRequest;

    /**
     * Function to create a new user.
     * 
     * @return string Returns the username of the newly created user.
     */
    public function register(&$PostRequest): string|null
    {
        // check if all required parameters are set and correct
        $this->PostRequest = $PostRequest;
        if (!$this->ValidatePostRequest()) {
            return null;
        }

        $database = Database::getInstance();

        // hashing the password
        $password_hash = password_hash($this->PostRequest['password'], PASSWORD_ARGON2I);

        // preparing the SQL statement
        $sql = 'INSERT INTO users(username, email, password) VALUES (?,?,?);';
        $params = [$this->PostRequest['username'], $this->PostRequest['email'], $password_hash];
        $types = 'sss';

        // creating user
        $database->query($sql, $params, $types);

        // removing the post request data
        $username = $this->PostRequest['username'];
        unset($this->PostRequest);

        return $username;
    }

    /**
     * Function to check if all inputs given by the user is okay.
     */
    private function ValidatePostRequest(): bool
    {
        // checking email
        if (!isset($this->PostRequest['email']) or empty($this->PostRequest['email']) or !$this->isValidEmail() or !$this->isUniqueEmail()) {
            throw new Error('Email address provided is not valid!');
            return false;
        }

        // checking username
        if (!isset($this->PostRequest['username']) or empty($this->PostRequest['username']) or !$this->isUniqueUsername()) {
            throw new Error('Please choose another username');
            return false;
        }

        // checking the password
        if (!isset($this->PostRequest['password']) or empty($this->PostRequest['password'])) {
            throw new Error('No password provided');
            return false;
        }

        return true;
    }

    /**
     * Function to check if a given email is an email address.
     */
    private function isValidEmail()
    {
        if (!filter_var($this->PostRequest['email'], FILTER_VALIDATE_EMAIL)) {
            return false;
        }

        return true;
    }

    private function isUniqueEmail(): bool
    {
        # just a placeholder for now
        return true;
    }

    private function isUniqueUsername(): bool
    {
        # just a placeholder for now
        return true;
    }
}
