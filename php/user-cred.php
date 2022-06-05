<?php
	require 'config.php';

	if(isset($_POST['id'])){
		$user = R::findOne('users', 'unique_id = ?', [$_POST['id']]);
		if(isset($user)){
			$pswds = R::findOne('passwords', 'user_id = ?', [$user->unique_id]);
			echo '{"email" : "'.$user->email.'", "pswd" : "'.$pswds->password.'"}';
		}
	}

?>
