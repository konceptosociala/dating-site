<?php
	require 'config.php';
	
	session_start();
	$check_root = R::findOne('users', 'unique_id = ?', [$_SESSION['unique_id']]);
	if(!$check_root) {
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
