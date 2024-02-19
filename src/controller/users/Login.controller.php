<?php

namespace Controller\Users;

use Error;
use Model\User;

/**
 * Class to login a user.
 * 
 * User is logged in with the `login()` function.
 */
class Login
{
    private User $user;

    /**
     * Function to log in a user.
     * 
     * @param array &$PostRequest The post request containing the user's username and password.
     */
    public function login(array &$PostRequest): User|null
    {
        // validate the post request
        $this->validatePostRequest($PostRequest);

        // get the user information 
        $this->getUserInformation($PostRequest['username']);

        // check password
        if (!$this->checkPassword($PostRequest['password'])) {
            throw new Error('Username or password provided is incorrect!');
            return null;
        }

        return $this->user;
    }

    /**
     * Function to get the user information from the database.
     * 
     * Stores the data in `$this->user`.
     */
    private function getUserInformation(string $username)
    {

        // catch potential Errors and return own error
        // this is in order to not reveal any sensitive information about the user
        try {
            $this->user = UsersHandler::getUser($username);
        } catch (Error $error) {
            throw new Error('Username or password provided is incorrect!');
        }
    }

    /**
     * Function to check compare a given password to the users password.
     * 
     * @return bool Returns true if the password matches the user'ss stored, otherwise false.
     */
    private function checkPassword(string $password): bool{
        $isSamePassword = password_verify($password, $this->user->password);
        
        // remove password from user data
        unset($this->user->password);
        return $isSamePassword;
    }

    /**
     * Function to validate that post request is correct.
     * 
     * @param &$PostRequest Reference to post request data, reference is used to minimize space needed.
     */
    private function validatePostRequest(&$PostRequest): bool
    {
        // checking username
        if (!isset($PostRequest['username']) or empty($PostRequest['username'])) {
            throw new Error('Please enter a username.');
            return false;
        }

        // checking the password
        if (!isset($PostRequest['password']) or empty($PostRequest['password'])) {
            throw new Error('No password provided!');
            return false;
        }

        return true;
    }
}
