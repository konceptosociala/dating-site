<?php
    session_start();
    require "config.php";
    
    if($_POST['id'] != ''){
		$girls = R::getAll("SELECT * FROM users INNER JOIN profiles ON profiles.user_id=users.unique_id WHERE type = 'female' AND unique_id = '{$_POST['id']}';");
		var_dump($girls);
	} else {
		$date_to = date('Y');
		$date_to .= '-01-01';
		$date_from = date('Y-m-d');
    
		$query = "SELECT * FROM users INNER JOIN profiles ON profiles.user_id=users.unique_id WHERE type = 'female'";
		
		if($_POST['age-from'] != '' && $_POST['age-to'] != ''){
			$from = date('Y-m-d', strtotime($date_from. ' - '.$_POST['age-from'].' years'));
			$to   = date('Y-m-d', strtotime($date_to. ' - '.$_POST['age-to'].' years'));

			$query .= " AND birthday BETWEEN '".$to."' AND '".$from."'";
		} else if($_POST['age-from'] != '') {
			$from = date('Y-m-d', strtotime($date_from. ' - '.$_POST['age-from'].' years'));
			$to   = date('Y-m-d', strtotime($date_to. ' - 100 years'));

			$query .= " AND birthday BETWEEN '".$to."' AND '".$from."'";
		} else if($_POST['age-to'] != '') {
			$from = date('Y-m-d', strtotime($date_from. ' - 18 years'));
			$to   = date('Y-m-d', strtotime($date_to. ' - '.$_POST['age-to'].' years'));

			$query .= " AND birthday BETWEEN '".$to."' AND '".$from."'";
		}
		
		if(isset($_POST['country']) && $_POST['country'] != '-- Select country --'){
			$query .= " AND country = '".$_POST['country']."'";
		}
		
		if(isset($_POST['haircolor']) && $_POST['haircolor'] != '-- Select color --'){
			$query .= " AND haircolor = '".$_POST['haircolor']."'";
		}
		
		$query .= ";";
		$girls = R::getAll($query);
		var_dump($girls);
	}
    
    
   // echo '123';
?>
