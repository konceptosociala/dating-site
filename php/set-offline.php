<?php
	
	require 'config.php';

	if(isset($_POST['id'])){
		$user = R::findOne('users', 'unique_id = ?', [$_POST['id']]);
		if($user->type == 'male'){
			$user->status = "Offline";
			R::store($user);
		}
	}
?>
