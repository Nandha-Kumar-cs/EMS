<?php 
include './sessionCheck.php'; 
if($_SERVER['REQUEST_METHOD'] == 'GET') {
    require '../config/db.php';
    $stmt = mysqli_prepare($conn , "SELECT * from employees");
    mysqli_stmt_execute($stmt); 
    $result = mysqli_stmt_get_result($stmt);
    $employee = [] ; 
    while ($row = mysqli_fetch_assoc($result)){
        $employee[] = $row ; 
    }
    http_response_code(200);
    echo json_encode([
        'status' => 200 ,
        'result' => $employee
    ]);
}
