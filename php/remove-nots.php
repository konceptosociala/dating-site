<?php
	include_once 'config.php';
	if(isset($_POST['adresat_id']) && isset($_POST['adresant_id'])){
		$nots = R::find('notifications', 'adresat_id = ? AND adresant_id = ?', [$_POST['adresat_id'], $_POST['adresant_id']]);
		R::trashAll($nots);
	}
?>
