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
			$prof 	 = R::find('profiles', 'user_id = ?', [$user->unique_id]);
			$pswds 	 = R::find('passwords', 'user_id = ?', [$user->unique_id]);
			$photos  = R::find('photos', 'user_id = ?', [$user->unique_id]);
			$favs 	 = R::find('favorites', 'fav_id = ?', [$user->unique_id]);
			$msg_in  = R::find('messages', 'incoming_msg_id = ?', [$user->unique_id]);	
			$msg_out = R::find('messages', 'outgoing_msg_id = ?', [$user->unique_id]);
						
			foreach ($photos as $ph) {
				unlink('images/'.$ph->img);
			}
			
			unlink('images/'.$user->img);
			
			R::trashAll($prof);
			R::trashAll($pswds);
			R::trashAll($photos);
			R::trashAll($favs);
			R::trashAll($msg_in);
			R::trashAll($msg_out);
			R::trash($user);
			
			
			echo 'User deleted!';
		}
	}

?>
