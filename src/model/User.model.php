<?php

namespace Model;

/**
 * Object for storing all information associated with the user.
 */
class User
{
    public string $username;
    public int $userId;
    public string $authorization;
    public string $email;

    /**
     * Users hashed password, is only stored for login purposes.
     */
    public string $password;
}
