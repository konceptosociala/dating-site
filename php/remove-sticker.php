<?php
	require 'config.php';

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
