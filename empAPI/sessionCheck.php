<?php 
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