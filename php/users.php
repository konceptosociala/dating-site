<?php
    session_start();
    include_once "config.php";
    ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);
    
    $outgoing_id = $_SESSION['unique_id'];
    $users = R::getAll("SELECT * FROM users WHERE NOT unique_id = '{$_SESSION['unique_id']}' AND type = 'female' ORDER BY id DESC");
    $output = "";
    if(empty($users)){
        $output .= "No users are available to chat";
    } else {
        include_once "data.php";
    }
    echo $output;
?>
