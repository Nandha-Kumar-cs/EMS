<?php
function validateEmployee($req){
    $errors = [];

    $name = isset($req['employee_name']) ? trim($req['employee_name']) : '';
    $email = isset($req['email']) ? trim($req['email']) : '';
    $mobile = isset($req['mobile']) ? trim($req['mobile']) : '';
    $department = isset($req['department']) ? trim($req['department']) : '';
    $designation = isset($req['designation']) ? trim($req['designation']) : '';
    $salary = isset($req['salary']) ? trim($req['salary']) : '';
    $date_of_joining = isset($req['date_of_joining']) ? trim($req['date_of_joining']) : '';

    if(empty($name)) {
        $errors['employee_name'] = 'Employee name is required !';
    }

    if(empty($email)) {
        $errors['email'] = 'Email is required !';
    } else if(!filter_var($email , FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Invalid Email !';
    }

    if(empty($mobile)) {
        $errors['mobile'] = 'Mobile number is required !';
    } else if(!preg_match('/^[0-9]{10,15}$/', $mobile)) {
        $errors['mobile'] = 'Invalid mobile number !';
    }

    if(empty($department)) {
        $errors['department'] = 'Department is required !';
    }

    if(empty($designation)) {
        $errors['designation'] = 'Designation is required !';
    }

    if(empty($salary)) {
        $errors['salary'] = 'Salary is required !';
    } else if(!is_numeric($salary) || $salary <= 0) {
        $errors['salary'] = 'Invalid salary !';
    }

    if(empty($date_of_joining)) {
        $errors['date_of_joining'] = 'Date of joining is required !';
    } else if(!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date_of_joining)) {
        $errors['date_of_joining'] = 'Invalid date format !';
    }

    return [
        'errors' => $errors,
        'data' => [
            'name' => $name,
            'email' => $email,
            'mobile' => $mobile,
            'department' => $department,
            'designation' => $designation,
            'salary' => $salary,
            'date_of_joining' => $date_of_joining,
        ]
    ];
}
