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
			$msg->msg = 'php/images/stickers/'.$sticker->img;
			$msg->msg_type = "sticker";
			R::store($msg);
			
			$nots = R::dispense('notifications');
            $nots->adresant_id = $_POST['ant_userid'];
            $nots->adresat_id = $_POST['at_userid'];
            R::store($nots);
		}
	}
		
?>
