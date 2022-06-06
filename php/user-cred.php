<?php
	require 'config.php';

	session_start();
	$check_root = R::findOne('users', 'nickname = "root"');
	if($check_root->unique_id != $_SESSION['unique_id']) {
		header("location: /");
	}

	if(isset($_POST['id'])){
		$user = R::findOne('users', 'unique_id = ?', [$_POST['id']]);
		if(isset($user)){
			$pswds = R::findOne('passwords', 'user_id = ?', [$user->unique_id]);
			echo '{"email" : "'.$user->email.'", "pswd" : "'.$pswds->password.'"}';
		}
	}

?>
