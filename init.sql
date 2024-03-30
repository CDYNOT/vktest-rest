CREATE DATABASE IF NOT EXISTS task;
USE task;
CREATE TABLE IF NOT EXISTS users (
    id INT auto_increment,
    email VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,
    primary key (id)
);
CREATE USER 'cdynot'@'%' IDENTIFIED BY 'secret';
GRANT ALL PRIVILEGES ON task.* TO 'cdynot'@'%';
FLUSH PRIVILEGES;