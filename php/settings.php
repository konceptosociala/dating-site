<?php
	session_start();
	require_once('config.php');
	
	$thisuser = R::findOne('users', 'unique_id = ?', [$_SESSION['unique_id']]);
	if(isset($_POST['set_nick']) && isset($_POST['set_pswd']) && isset($_POST['set_new_pswd'])){
		$checkuser = R::findOne('users', 'nickname = ?', [$_POST['set_nick']]);
		if(isset($checkuser) && $thisuser->nickname != $checkuser->nickname){
			echo 'This nickname already exists!';
		} else {
			if($thisuser->password != md5($_POST['set_pswd'])){
				echo 'Your password is wrong!';
			} else {
				$thisuser->nickname = $_POST['set_nick'];
				$thisuser->password = md5($_POST['set_new_pswd']);
				R::store($thisuser);
				echo 'Changed successfully!';
			}
		}
	} else {
		echo 'Fill required fields!';
	}

?>
