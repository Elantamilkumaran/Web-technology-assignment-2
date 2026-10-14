-- Run once (phpMyAdmin -> Import, or: mysql -u root < portal.sql)
-- If you already imported the Question 2 database.sql, this only adds the "users" table.
CREATE DATABASE IF NOT EXISTS college_db;
USE college_db;

CREATE TABLE IF NOT EXISTS students (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  roll_no VARCHAR(20) NOT NULL UNIQUE,
  email VARCHAR(100) NOT NULL,
  course VARCHAR(100) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS student_files (
  id INT AUTO_INCREMENT PRIMARY KEY,
  student_id INT NOT NULL,
  category VARCHAR(20) NOT NULL,
  original_name VARCHAR(255) NOT NULL,
  file_path VARCHAR(255) NOT NULL,
  file_type VARCHAR(50) NOT NULL,
  file_size INT NOT NULL,
  uploaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE
);

-- Users who may log in to the portal (password stored as SHA-256 hash)
CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) NOT NULL UNIQUE,
  password CHAR(64) NOT NULL
);

INSERT IGNORE INTO users (username, password) VALUES ('admin', SHA2('admin123', 256));
