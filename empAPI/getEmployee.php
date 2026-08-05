<?php 
include './sessionCheck.php'; 
if($_SERVER['REQUEST_METHOD'] == 'GET'){
    require '../config/db.php' ;
    $req = $_GET ; 
    if(!isset($req['id'])){
        http_response_code(404);
        return json_encode([
            'status' => 404 , 
            'error' => 'Id is required !'
        ]);
    }

    $id = $req['id'] ; 
    $stmt = mysqli_prepare($conn , "SELECT * from employees where id = ? ");
    mysqli_stmt_bind_param($stmt , 'i' ,$id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $employee = mysqli_fetch_assoc($result); 
  
    http_response_code(200);
    return json_encode([
        'status' => 200 , 
        'result' =>  $employee
    ]);
    
}