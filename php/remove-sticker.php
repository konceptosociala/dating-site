<?php
	require 'config.php';
	
	session_start();
	$check_root = R::findOne('users', 'nickname = "root"');
	if($check_root->unique_id != $_SESSION['unique_id']) {
		header("location: /");
	}

	if(isset($_POST['stickname'])){
		$stick = R::findOne('stickers', 'name = ?', [$_POST['stickname']]);
		if(isset($stick)){
			$msgs = R::find('messages', 'msg_type = "sticker" AND msg = ?', ['php/images/stickers/'.$stick->img]);
			unlink('images/stickers/'.$stick->img);
			R::trashAll($msgs);
			
			R::trash($stick);
			echo 'Sticker deleted!';
		}
	}

?>
