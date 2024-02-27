<?php

namespace Model;

use mysqli;
use Error;
use Exception;

/**
 * Class handling the database.
 * 
 * This class follows the singleton pattern. 
 * The settings for the database have to be set before the class can be constructed.
 * ```php
 * Database::loadSettings();
 * Database::getInstance();
 * ```
 */
class Database
{
    private function __construct()
    {
        if ($this->settingsAreLoaded()) {
            $this->connect();
        } else {
            throw new Error('The database settings were not loaded before initialization of the database class');
        }
    }

    /**
     * Function for connecting to the database
     */
    private mysqli $connection;
    private function connect(): void
    {
        try {
            $this->connection = new mysqli(self::$hostname, self::$username, self::$password, self::$database, self::$port);
            $this->testDatabaseConnection();
        } catch (Exception $error) {
            throw new Error('Failed to connect to database: ' . $error->getMessage() . $error);
        }
    }

    /**
     * Function for getting an instance of this class. 
     * 
     * This function follows the singelton pattern, ensuring that all   
     */
    private static self $instance;
    public static function getInstance(): self
    {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private static string $hostname;
    private static string $database;
    private static string $username;
    private static string $password;
    private static int $port;

    /**
     * Function for loading the settings for the database.
     * 
     * Needs to be called before a connection can be made. 
     */
    public static function loadSettings(string $database, string $host, string $username, string $password, int $port = 3306): void
    {
        self::$database = $database;
        self::$hostname = $host;
        self::$username = $username;
        self::$password = $password;
        self::$port = $port;
    }

    // function for checking if the settings have been loaded
    public function settingsAreLoaded(): bool
    {
        return isset(self::$hostname);
    }

    // function for closing the database connection
    private function closeDatabaseConnection(): void
    {
        if ($this->connection instanceof mysqli) {
            $this->connection->close();
        }
    }

    /**
     * Function for testing the database connection
     */
    public function testDatabaseConnection(): void
    {
        if ($this->connection->connect_error) {
            throw new Error('Connection error with database: ' . $this->connection->connect_error);
        }
    }

    /**
     * Function for safe querying of the database.
     */
    public function query(string $sql, array $params = [], string $types = ""): mixed
    {
        try {
            $this->testDatabaseConnection();

            $prepared = ($this->connection)->prepare($sql);
            $prepared->bind_param($types, ...$params);

            if (!$prepared->execute()) {
                $error = $prepared->error;
                $errornumber = $prepared->errno;
                throw new Error('Prepare Statement Failed' . $error . ', ' . $errornumber);
            }

            $result = $prepared->get_result();

            $prepared->close();
        } catch (Error $error) {
            throw new Error('Failed to query database ' . $error->getMessage());
            return null;
        }

        return $result;
    }

    /**
     * Function for unsafe querying of the database.
     * 
     * ***Should not be used with provided data!***
     */
    public function queryUnsafe(string $sql): mixed
    {
        try {
            $this->testDatabaseConnection();
            $result = $this->connection->query($sql);
        } catch (Error $error) {
            throw new Error('Failed to query database');
        }

        return $result;
    }

    public function __destruct()
    {
        $this->closeDatabaseConnection();
    }
}
