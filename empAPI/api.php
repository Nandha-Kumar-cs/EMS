<?php 
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    require '../config/db.php';
    if(session_status() == PHP_SESSION_NONE){
        session_start();
    }
    if(!isset($_SESSION['userAccountId'])){
        http_response_code(401);
        echo json_encode([
            'status' => 401,
            'message' => 'Unauthorized access !'
        ]);
        exit;
    }

    $req = $_POST ; 
    if(!isset($req['action'])){
        http_response_code(422);
        echo json_encode([
            'status' => 422, 
            'error'  => 'Invalid action !!'
        ]);
        exit;
    }


    if($req['action'] == '')


}