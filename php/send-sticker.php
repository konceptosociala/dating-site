<?php
	require 'config.php';

	if(
		isset($_POST['stickname']) &&
		isset($_POST['ant_userid']) &&
		isset($_POST['at_userid'])
	){
		$sticker = R::findOne('stickers', 'name = ?', [$_POST['stickname']]);
		
		if(isset($sticker)){
			$msg = R::dispense('messages');
			$msg->incoming_msg_id = $_POST['at_userid'];
			$msg->outgoing_msg_id = $_POST['ant_userid'];
			$msg->msg = '<img style="width: 40%" src="php/images/stickers/'.$sticker->img.'">';
			$msg->msg_type = "sticker";
			R::store($msg);
		}
	}
		
?>
