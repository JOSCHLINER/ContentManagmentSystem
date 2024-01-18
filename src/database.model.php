<?php

class DatabaseConnection
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

            if ($this->connection->connect_error) {
                throw new Exception('Connection error with database: ' . $this->connection->connect_error);
            }
        } catch (Exception $error) {
            die('Failed to connect to database: ' . $error->getMessage() . $error);
        }
    }

    # function to query the articles table
    public function query(string $sql, array $params = [], string $types = "")
    {
        if ($this->connection->connect_error) {
            die('Connection error with database: ' . $this->connection->connect_error);
        }

        try {
            $prepared = ($this->connection)->prepare($sql);
        } catch (Exception $error) {
            throw new Exception('SQL statement preparation failed: ' . $error->getMessage());
            return null;
        }

        try {
            $prepared->bind_param($types, ...$params);
            $prepared->execute();
            
            $result = $prepared->get_result();

            $prepared->close();
        } catch (Exception $error) {
            throw new Exception('Execution of SQL statement failed: ' . $error->getMessage());
            return null;
        }

        return $result;
    }

    public function _unsafe_query(string $sql) {
        try {
            if ($this->connection->connect_error) {
                throw new Exception('Connection to Database lost: ' . $this->connection->connect_error);
            }

            $result = $this->connection -> query($sql);
        }   catch (Exception $error) {
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
