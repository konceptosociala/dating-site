<?php 
    session_start();
    if(isset($_SESSION['unique_id'])){
        include_once "config.php";
        $outgoing_id = $_SESSION['unique_id'];
        $incoming_id = mysqli_real_escape_string($conn, $_POST['incoming_id']);
        $message = mysqli_real_escape_string($conn, $_POST['message']);
        if(!empty($message)){
            $msg_t = R::dispense( 'messages' );
            $msg_t->incoming_msg_id = $incoming_id;
            $msg_t->outgoing_msg_id = $outgoing_id;
            $message = str_replace("\'", "'", $message);
            $msg_t->msg = str_replace('\r\n', "\r\n", $message);
            $msg_t->msg_type = "text";
            R::store($msg_t);
            
            $nots = R::dispense('notifications');
            $nots->adresant_id = $outgoing_id;
            $nots->adresat_id = $incoming_id;
            R::store($nots);
        }
    }else{
        header("location: ../login");
    }


    
?>
