<?php

namespace Model;

/** 
 * Class storing all information needed to connect to a database.
 */
class ConnectionSettings
{
    public string $host;
    public string $username;
    public string $password;
    public int $port;

    public string $database;
}