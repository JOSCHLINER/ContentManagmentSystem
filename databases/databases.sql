-- creating the database and the tables alongside it
CREATE DATABASE IF NOT EXISTS contentmanagment;
USE contentmanagment;

CREATE TABLE IF NOT EXISTS users(
    user_id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(16) UNIQUE NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    privileges ENUM('a', 'u', 'm', 'n') DEFAULT 'u' NOT NULL
);

CREATE TABLE IF NOT EXISTS articles(
    article_id INT AUTO_INCREMENT PRIMARY KEY,
    author_id INT NOT NULL, 
    content TEXT NOT NULL,
    created_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (author_id) REFERENCES users(user_id)
);

-- Creating the user for the content managment system to use
CREATE USER IF NOT EXISTS 'cmswebbpage'@'localhost'
IDENTIFIED WITH authentication_plugin BY 'secure_password';

GRANT INSERT, UPDATE, DELETE, SELECT ON contentmanagment.* TO 'cmswebbpage'@'localhost' WITH GRANT OPTION;
FLUSH PRIVILEGES;