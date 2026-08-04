<?php 
function checkSession(){
    if(session_status() == PHP_SESSION_NONE){
        session_start();
    }
    if(!isset($_SESSION['userAccountId'])){
        header('Location: ../login.php');
        exit;
    }else {
        $session_user_id = $_SESSION['userAccountId']; 
        return $session_user_id ; 
    }
}
