<?php
	session_start();
	include_once "config.php";
	$cnt = 0;

	if(isset($_POST['id'])){
		$badge = "";
		$msgs = R::getAll("SELECT * FROM notifications WHERE adresat_id = {$_SESSION['unique_id']};");
		if($msgs){
			while($cnt < count($msgs)){
				$cnt++;
			}
		}
		
		if($cnt > 0) $badge = '<i class="icon-chat"></i> Chat <span class="badge rounded-pill bg-danger">'.$cnt.'<span class="visually-hidden">unread messages</span></span>';
		else $badge = '<i class="icon-chat"></i> Chat';
		echo $badge;
	}
?>
