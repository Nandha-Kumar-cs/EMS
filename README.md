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
├── empAPI/                   # Employee APIs split into separate files
│   ├── sessionCheck.php      # Session guard helper
│   ├── validateEmployee.php  # Employee validation helper
│   ├── getAll.php            # List all employees
│   ├── getEmployee.php       # Get single employee
│   ├── addEmployee.php       # Add employee
│   ├── updateEmployee.php    # Update employee
│   └── deleteEmployee.php    # Delete employee
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

## Employee API (empAPI)

The employee APIs are also available as separate files inside the `empAPI/` folder. Every endpoint returns JSON and needs a logged in session.

Base URL: `http://localhost/EMS/empAPI/`

| Endpoint | Method | Body / Query | Description |
| --- | --- | --- | --- |
| `getAll.php` | GET | – | List all employees |
| `getEmployee.php` | GET | `?id=1` | Get a single employee |
| `addEmployee.php` | POST | employee fields | Add a new employee |
| `updateEmployee.php` | POST | `employee_id` + employee fields | Update an employee |
| `deleteEmployee.php` | POST | `employee_id` | Delete an employee |

Employee fields used by add and update:

`employee_name`, `email`, `mobile`, `department`, `designation`, `salary`, `date_of_joining`

### Testing with Postman

**Step 1 – Login first (to get the session cookie)**

The APIs will return `401 Unauthorized access !` until you log in. Postman stores the `PHPSESSID` cookie automatically, so you only need to do this once.

- Method: `POST`
- URL: `http://localhost/EMS/api/auth.php`
- Body: `x-www-form-urlencoded`

| Key | Value |
| --- | --- |
| action | login |
| email | admin@gmail.com |
| password | your password |

Response:

```json
{
    "status": 200,
    "message": "Login successful !"
}
```

**Step 2 – Call the employee APIs**

Use `x-www-form-urlencoded` for all POST requests (the APIs read `$_POST`).

**Get all employees**

- Method: `GET`
- URL: `http://localhost/EMS/empAPI/getAll.php`

```json
{
    "status": 200,
    "result": [
        {
            "id": 1,
            "employee_name": "Nandhakumar",
            "email": "nandha@gmail.com",
            "mobile": "1234567890",
            "department": "Mechanical",
            "designation": "Qc",
            "salary": "125000.00",
            "date_of_joining": "2003-05-03"
        }
    ]
}
```

**Get single employee**

- Method: `GET`
- URL: `http://localhost/EMS/empAPI/getEmployee.php?id=1`

**Add employee**

- Method: `POST`
- URL: `http://localhost/EMS/empAPI/addEmployee.php`
- Body: `x-www-form-urlencoded`

| Key | Value |
| --- | --- |
| employee_name | Test User |
| email | testuser@gmail.com |
| mobile | 9876543210 |
| department | IT |
| designation | Developer |
| salary | 45000 |
| date_of_joining | 2024-01-15 |

Success response (`201`):

```json
{
    "status": 201,
    "message": "Employee added successfully !"
}
```

Validation / duplicate email response (`422`):

```json
{
    "status": 422,
    "errors": {
        "email": "Email already exists !"
    }
}
```

**Update employee**

- Method: `POST`
- URL: `http://localhost/EMS/empAPI/updateEmployee.php`
- Body: `x-www-form-urlencoded`
- Send `employee_id` along with all the employee fields.

| Key | Value |
| --- | --- |
| employee_id | 2 |
| employee_name | Test User Updated |
| email | testuser@gmail.com |
| mobile | 9876500000 |
| department | HR |
| designation | Manager |
| salary | 60000 |
| date_of_joining | 2024-02-20 |

Success response (`200`):

```json
{
    "status": 200,
    "message": "Employee updated successfully !"
}
```

**Delete employee**

- Method: `POST`
- URL: `http://localhost/EMS/empAPI/deleteEmployee.php`
- Body: `x-www-form-urlencoded`

| Key | Value |
| --- | --- |
| employee_id | 2 |

Success response (`200`):

```json
{
    "status": 200,
    "message": "Employee deleted successfully !"
}
```

### Status codes

| Code | When it happens |
| --- | --- |
| 200 | Request successful |
| 201 | Employee added |
| 401 | Not logged in (`Unauthorized access !`) |
| 404 | Employee not found |
| 405 | Wrong request method (`Invalid Request !!`) |
| 422 | Validation failed / invalid employee id / duplicate email |
| 500 | Database error (`Something went wrong, please try again !`) |

### Common mistakes while testing

- Getting `401` on every request – login through `api/auth.php` first and keep Postman cookies enabled.
- Getting `405` – the add, update and delete APIs accept `POST` only.
- Getting `422` with an empty body – select `x-www-form-urlencoded` in the Body tab, not `raw` JSON.
- `date_of_joining` must be in `YYYY-MM-DD` format and `mobile` must be 10 to 15 digits.

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
