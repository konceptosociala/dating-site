<?php
	require 'config.php';

	if(isset($_POST['stickname'])){
		$stick = R::findOne('stickers', 'name = ?', [$_POST['stickname']]);
		if(isset($stick)){
			R::trash($stick);
			echo 'Sticker deleted!';
		}
	}

?>
