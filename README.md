# Employee Management System (EMS)

A core PHP employee management system built with MySQLi (procedural style), jQuery AJAX, and Tailwind CSS.

## Features

- **Authentication**
  - User registration with validation (email format, duplicate check, password confirmation)
  - Password hashing with `password_hash()` / `password_verify()`
  - Session-based login, logout, and protected pages
- **Employee Management**
  - Add, edit, and delete employees
  - Server-side DataTables table with per-column footer search, sorting, and pagination (10 records per page)
  - Search by name or email
- **Dashboard**
  - Summary cards: total employees, total departments, total designations, total payroll
  - Recently added employees list
- **File Upload**
  - Upload profile images or supporting documents
  - JPG, PNG, and PDF only, maximum 2 MB
  - Files are renamed before storing and metadata is saved in the database

## Tech Stack

- Core PHP
- MySQL / MySQLi (procedural)
- jQuery + AJAX
- DataTables
- Tailwind CSS

## Requirements

- XAMPP (PHP 8+, MySQL, Apache) or equivalent
- Web browser

## Installation

1. Clone or copy the project into `C:\xampp8\htdocs\ems` (or your web root).
2. Start Apache and MySQL from the XAMPP control panel.
3. Create the database and tables in phpMyAdmin (see SQL below).
4. Update database credentials in `config/db.php` if they differ from the defaults:
   - Host: `localhost`
   - User: `root`
   - Password: (empty)
   - Database: `ems`
5. Open `http://localhost/ems/` in your browser.
6. Register a new account, then log in.

## Database Setup

Run these queries in phpMyAdmin (database: `ems`):

```sql
CREATE DATABASE IF NOT EXISTS ems;
USE ems;

CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(100) NOT NULL,
  email VARCHAR(100) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE employees (
  id INT AUTO_INCREMENT PRIMARY KEY,
  employee_name VARCHAR(100) NOT NULL,
  email VARCHAR(100) NOT NULL UNIQUE,
  mobile VARCHAR(15) NOT NULL,
  department VARCHAR(100) NOT NULL,
  designation VARCHAR(100) NOT NULL,
  salary DECIMAL(10, 2) NOT NULL,
  date_of_joining DATE NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE uploads (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  employee_id INT NOT NULL,
  original_name VARCHAR(255) NOT NULL,
  stored_name VARCHAR(255) NOT NULL,
  file_type VARCHAR(50) NOT NULL,
  file_size INT NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

## Folder Structure

```
ems/
├── index.php                 # Redirect based on login status
├── login.php                 # Login page
├── register.php              # Registration page
├── logout.php                # Destroy session and redirect
├── assets/js/                # jQuery AJAX files
│   ├── login.js
│   ├── register.js
│   ├── employees.js
│   ├── dashboard.js
│   └── uploads.js
├── api/                      # Backend endpoints (JSON)
│   ├── auth.php              # Register / login
│   ├── checkSession.php      # Session guard helper
│   ├── employee.php          # Employee CRUD + DataTables
│   ├── dashboard.php         # Summary stats
│   └── upload.php            # File upload / list / delete
├── config/
│   └── db.php                # MySQLi connection
├── pages/
│   ├── dashboard.php
│   ├── employees.php
│   └── uploads.php
├── uploads/                  # Uploaded files (renamed)
│   └── .htaccess             # Blocks PHP execution
└── README.md
```

## Security

- All database queries use MySQLi prepared statements (no PDO, no SQL injection).
- Passwords are hashed with `password_hash()` using `PASSWORD_DEFAULT`.
- Output is escaped with `htmlspecialchars()` when displaying data.
- Session IDs are regenerated on login and cookies are marked `httponly`.
- Uploaded files are renamed, validated by extension AND MIME type, size-limited to 2 MB, and stored in a folder where PHP execution is disabled.

## Notes

- Uploaded files are served directly from the `uploads/` folder via the Download button.
- The dashboard "Add Employee" button navigates to the employee management page.
- CDN dependencies (Tailwind, jQuery, DataTables) require an internet connection.
