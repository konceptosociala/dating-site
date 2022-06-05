<?php
    session_start();
    include_once "config.php";
    
    $outgoing_id = $_SESSION['unique_id'];
    $thisuser = R::findOne('users', 'unique_id = ?', [$outgoing_id]);
    $users = R::getAll("SELECT * FROM users WHERE NOT unique_id = '{$_SESSION['unique_id']}' AND NOT type = '{$thisuser->type}' ORDER BY id DESC");
    $output = "";
    if(empty($users)){
        $output .= "No users are available to chat";
    } else {
        include_once "data.php";
    }
    echo $output;
?>
