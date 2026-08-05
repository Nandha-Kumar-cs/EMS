<?php
include './sessionCheck.php';
include './validateEmployee.php';
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    // mysqli should return false on error instead of stopping the script
    mysqli_report(MYSQLI_REPORT_OFF);
    require '../config/db.php' ;
    $req = $_POST ;

    $validated = validateEmployee($req);
    $errors = $validated['errors'];
    $data = $validated['data'];

    // email should not be used by another employee
    if(count($errors) == 0){
        $check_stmt = mysqli_prepare($conn , "SELECT id FROM employees WHERE email = ?");
        mysqli_stmt_bind_param($check_stmt , 's' , $data['email']);
        mysqli_stmt_execute($check_stmt);
        mysqli_stmt_store_result($check_stmt);
        if(mysqli_stmt_num_rows($check_stmt) > 0){
            $errors['email'] = 'Email already exists !';
        }
        mysqli_stmt_close($check_stmt);
    }

    if(count($errors) != 0){
        http_response_code(422);
        echo json_encode([
            'status' => 422 ,
            'errors' => $errors
        ]);
        exit;
    }

    $stmt = mysqli_prepare($conn , "INSERT INTO employees (employee_name, email, mobile, department, designation, salary, date_of_joining) VALUES (?, ?, ?, ?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt , 'sssssds' , $data['name'], $data['email'], $data['mobile'], $data['department'], $data['designation'], $data['salary'], $data['date_of_joining']);

    if(mysqli_stmt_execute($stmt)){
        http_response_code(201);
        echo json_encode([
            'status' => 201 ,
            'message' => 'Employee added successfully !'
        ]);
    } else {
        http_response_code(500);
        echo json_encode([
            'status' => 500 ,
            'message' => 'Something went wrong, please try again !'
        ]);
    }
    mysqli_stmt_close($stmt);

}else {
    http_response_code(405);
    echo json_encode([
        'status' => 405,
        'error'  => 'Invalid Request !!'
    ]);
}
