<?php
include './sessionCheck.php';
include './validateEmployee.php';
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    // mysqli should return false on error instead of stopping the script
    mysqli_report(MYSQLI_REPORT_OFF);
    require '../config/db.php' ;
    $req = $_POST ;

    $employee_id = isset($req['employee_id']) ? trim($req['employee_id']) : '';

    if(empty($employee_id) || !is_numeric($employee_id)){
        http_response_code(422);
        echo json_encode([
            'status' => 422 ,
            'message' => 'Invalid employee !'
        ]);
        exit;
    }

    // employee should be available before updating
    $check_stmt = mysqli_prepare($conn , "SELECT id FROM employees WHERE id = ?");
    mysqli_stmt_bind_param($check_stmt , 'i' , $employee_id);
    mysqli_stmt_execute($check_stmt);
    mysqli_stmt_store_result($check_stmt);
    $employee_count = mysqli_stmt_num_rows($check_stmt);
    mysqli_stmt_close($check_stmt);

    if($employee_count == 0){
        http_response_code(404);
        echo json_encode([
            'status' => 404 ,
            'message' => 'Employee not found !'
        ]);
        exit;
    }

    $validated = validateEmployee($req);
    $errors = $validated['errors'];
    $data = $validated['data'];

    // email should not be used by another employee
    if(count($errors) == 0){
        $email_stmt = mysqli_prepare($conn , "SELECT id FROM employees WHERE email = ? AND id != ?");
        mysqli_stmt_bind_param($email_stmt , 'si' , $data['email'], $employee_id);
        mysqli_stmt_execute($email_stmt);
        mysqli_stmt_store_result($email_stmt);
        if(mysqli_stmt_num_rows($email_stmt) > 0){
            $errors['email'] = 'Email already exists !';
        }
        mysqli_stmt_close($email_stmt);
    }

    if(count($errors) != 0){
        http_response_code(422);
        echo json_encode([
            'status' => 422 ,
            'errors' => $errors
        ]);
        exit;
    }

    $stmt = mysqli_prepare($conn , "UPDATE employees SET employee_name = ?, email = ?, mobile = ?, department = ?, designation = ?, salary = ?, date_of_joining = ? WHERE id = ?");
    mysqli_stmt_bind_param($stmt , 'sssssdsi' , $data['name'], $data['email'], $data['mobile'], $data['department'], $data['designation'], $data['salary'], $data['date_of_joining'], $employee_id);

    if(mysqli_stmt_execute($stmt)){
        http_response_code(200);
        echo json_encode([
            'status' => 200 ,
            'message' => 'Employee updated successfully !'
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
