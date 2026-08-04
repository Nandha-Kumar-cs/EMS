<?php 
session_start();
if(!isset($_SESSION['userAccountId'])){
    header('Location: ./login.php');
}else {
    header('Location: ./pages/dashboard.php');
}


