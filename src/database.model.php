<?php

class Database
{

    #construct function; will retrieve required information to connect to said database
    private $hostname;
    private $database;
    private $username;
    private $password;

    public function __construct(string $database, string $hostname, string $username, string $password)
    {
        $this->database = $database;
        $this->hostname = $hostname;
        $this->username = $username;
        $this->password = $password;

        $this->connect();
    }

    # function for connecting to the database
    private $connection;
    private function connect()
    {
        try {
            $this->connection = new mysqli($this->hostname, $this->username, $this->password, $this->database);
            $this->testDatabaseConnection();
        } catch (Exception $error) {
            die('Failed to connect to database: ' . $error->getMessage() . $error);
        }
    }

    public function testDatabaseConnection()
    {
        if ($this->connection->connect_error) {
            throw new Exception('Connection error with database: ' . $this->connection->connect_error);
        }
    }

    # function to query the articles table
    public function queryConnection(string $sql, array $params = [], string $types = "")
    {
        $this->testDatabaseConnection();

        try {
            $prepared = ($this->connection)->prepare($sql);
            $prepared->bind_param($types, ...$params);
            $prepared->execute();

            $result = $prepared->get_result();

            $prepared->close();
        } catch (Exception $error) {
            throw new Exception('Execution of SQL statement failed: ' . $error->getMessage());
            return;
        }

        return $result;
    }

    public function queryConnectionUnsafe(string $sql)
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
        # closing connection if one exists and is active
        if ($this->connection instanceof mysqli) {
            $this->connection->close();
        }
    }
}
