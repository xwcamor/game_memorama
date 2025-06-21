
CREATE DATABASE IF NOT EXISTS memorama_db;
USE memorama_db;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);

-- Usuario por defecto
INSERT INTO users (username, password) VALUES ('user', '1234');
