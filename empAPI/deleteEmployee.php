<?php
include './sessionCheck.php';
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

    // employee should be available before deleting
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

    $stmt = mysqli_prepare($conn , "DELETE FROM employees WHERE id = ?");
    mysqli_stmt_bind_param($stmt , 'i' , $employee_id);

    if(mysqli_stmt_execute($stmt)){
        http_response_code(200);
        echo json_encode([
            'status' => 200 ,
            'message' => 'Employee deleted successfully !'
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
