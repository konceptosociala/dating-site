<?php
	require 'config.php';
	
	session_start();
	$check_root = R::findOne('users', 'unique_id = ?', [$_SESSION['unique_id']]);
	if($check_root->unique_id != $_SESSION['unique_id']) {
		header("location: /");
	}
	
	if(isset($_FILES['upfile']) && isset($_POST['adresat'])){
		
		$img_name = $_FILES['upfile']['name'];
		$img_type = $_FILES['upfile']['type'];
		$tmp_name = $_FILES['upfile']['tmp_name'];
		$img_explode = explode('.',$img_name);
		$img_ext = end($img_explode);
		
		$extensions_p = ["jpeg", "png", "jpg"];
		$extensions_v = ["mp4", "webm"];
		$types_p = ["image/jpeg", "image/jpg", "image/png"];
		$types_v = ["video/mp4", "video/webm"];
		if(in_array($img_ext, $extensions_p) === true){
			if(in_array($img_type, $types_p) === true){
				$time = time();
				$new_img_name = $time.$img_name;
				if(move_uploaded_file($tmp_name,"images/".$new_img_name)){
					$msg = R::dispense('messages');
					$msg->incoming_msg_id = $_POST['adresat'];
					$msg->outgoing_msg_id = $_SESSION['unique_id'];
					$msg->msg = 'php/images/'.$new_img_name;
					$msg->msg_type = "image";
					R::store($msg);
					
					$nots = R::dispense('notifications');
					$nots->adresant_id = $_SESSION['unique_id'];
					$nots->adresat_id = $_POST['adresat'];
					R::store($nots);
				}				
			} else {
				echo '<script>alert("Wrong file type!");</script>';
			}
		} else if(in_array($img_ext, $extensions_v) === true) {
			if(in_array($img_type, $types_v) === true) {
				$time = time();
				$new_img_name = $time.$img_name;
				if(move_uploaded_file($tmp_name,"videos/".$new_img_name)){
					$msg = R::dispense('messages');
					$msg->incoming_msg_id = $_POST['adresat'];
					$msg->outgoing_msg_id = $_SESSION['unique_id'];
					$msg->msg = 'php/videos/'.$new_img_name;
					$msg->msg_type = "video";
					R::store($msg);
				}
			} else {
				echo '<script>alert("Wrong file type!");</script>';
			}
		} else {
			echo '<script>alert("Wrong file type!");</script>';
		}
	}

?>
