<?php
	require 'config.php';
	
	session_start();
	$check_root = R::findOne('users', 'nickname = "root"');
	if($check_root->unique_id != $_SESSION['unique_id']) {
		header("location: /");
	}

	if(isset($_POST['photo_id'])){
		$photo = R::findOne('photos', 'id = ?', [$_POST['photo_id']]);
		if($photo){
			unlink('images/'.$photo->img);
			R::trash($photo);
			echo 'Photo deleted!';
		}
	}

?>
