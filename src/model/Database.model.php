<?php

namespace Model;

use Controller\Settings\SensibleSettingsLoader;
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
        // loading the credentials and other information
        (new SensibleSettingsLoader())->loadDatabaseSettings();

        // connecting to the database
        $this->connect();
    }

    /**
     * Function for connecting to the database
     */
    private mysqli $connection;
    private function connect(): void
    {
        try {
            $this->connection = new mysqli(self::$settings->host, self::$settings->username, self::$settings->password, self::$settings->database, self::$settings->port);
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

    private static ConnectionSettings $settings;
    /**
     * Function for loading the settings for the database.
     * 
     * Needs to be called before a connection can be made. 
     */
    public static function loadSettings($settings): void
    {
        self::$settings = $settings;
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
