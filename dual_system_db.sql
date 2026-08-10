CREATE DATABASE IF NOT EXISTS dual_system_db DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE dual_system_db;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    fullname VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    role ENUM('establishment', 'institution', 'student', 'admin') DEFAULT 'establishment',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS partnerships (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    partner_name VARCHAR(255) NOT NULL,
    type ENUM('สถานประกอบการ', 'สถานศึกษา') NOT NULL,
    details TEXT,
    status VARCHAR(50) DEFAULT 'รอดำเนินการ',
    created_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;