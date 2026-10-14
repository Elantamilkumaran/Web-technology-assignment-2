-- Run this file once (phpMyAdmin -> Import, or: mysql -u root < database.sql)
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

-- Metadata of every uploaded file, linked to a student
CREATE TABLE IF NOT EXISTS student_files (
  id INT AUTO_INCREMENT PRIMARY KEY,
  student_id INT NOT NULL,
  category VARCHAR(20) NOT NULL,        -- profile / id_proof / certificate / other
  original_name VARCHAR(255) NOT NULL,
  file_path VARCHAR(255) NOT NULL,
  file_type VARCHAR(50) NOT NULL,       -- MIME type
  file_size INT NOT NULL,
  uploaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE
);
