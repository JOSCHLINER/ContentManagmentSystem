CREATE DATABASE test;
USE test;

CREATE TABLE users(
    id INT PRIMARY KEY AUTO_INCREMENT, 
    username VARCHAR(100) NOT NULL UNIQUE
);

INSERT INTO users(id, username)
VALUES(1, 'testuser');