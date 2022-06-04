<?php

include 'php/config.php';

if(isset($_GET['token'])){
	$confirm = R::findOne('tokens', 'token = ?', [$_GET['token']]);
	if(isset($confirm)){
		$user = R::findOne('users', 'unique_id = ?', [$confirm->user_id]);
		$user->confirm = true;
		R::store($user);
		R::trash($confirm);
		echo '<script>alert("Email confirmed!");</script>';
		header("location: /");
	}
} else {
	header("location: /");
}

?>
