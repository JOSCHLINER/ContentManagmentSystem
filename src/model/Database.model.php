<?php

namespace Model;

use mysqli;
use Exception;

class Database
{
    private function __construct()
    {
        if ($this->settingsAreLoaded()) {
            $this->connect();
        } else {
            throw new Exception('The database settings were not loaded before initialization of the database class');
        }
    }

    // function for connecting to the database
    private mysqli $connection;
    private function connect(): void
    {
        try {
            $this->connection = new mysqli(self::$hostname, self::$username, self::$password, self::$database, self::$port);
            $this->testDatabaseConnection();
        } catch (Exception $error) {
            die('Failed to connect to database: ' . $error->getMessage() . $error);
        }
    }

    // function for getting an instance of this class, by doing it this way we ensure that all code can access the same database connection effortless
    private static self $instance;
    public static function getInstance(): self
    {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    // function for loading all the settings to connect to the database
    private static string $hostname;
    private static string $database;
    private static string $username;
    private static string $password;
    private static int $port;
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

    // function for testing the connection to the database
    public function testDatabaseConnection(): void
    {
        if ($this->connection->connect_error) {
            throw new Exception('Connection error with database: ' . $this->connection->connect_error);
        }
    }

    // function for querying the database with user provided inputs
    public function query(string $sql, array $params = [], string $types = ""): mixed
    {
        try {
            $this->testDatabaseConnection();

            $prepared = ($this->connection)->prepare($sql);
            $prepared->bind_param($types, ...$params);

            if (!$prepared->execute())
            {
                $error = $prepared->error;
                $errornumber = $prepared->errno;
                throw new Exception('Prepare Statement Failed' . $error . ', ' . $errornumber);
            }

            $result = $prepared->get_result();

            $prepared->close();
        } catch (Exception $error) {
            throw new Exception('Failed to query database ' . $error->getMessage());
            return null;
        }

        return $result;
    }

    // function for unsafe querying of the database
    public function queryUnsafe(string $sql): mixed
    {
        try {
            $this->testDatabaseConnection();
            $result = $this->connection->query($sql);
        } catch (Exception $error) {
            die('Failed to query database' . $error->getMessage());
        }

        return $result;
    }

    public function __destruct()
    {
        $this->closeDatabaseConnection();
    }
}
