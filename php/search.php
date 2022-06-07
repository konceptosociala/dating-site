<?php
    session_start();
    require "config.php";
    
    if($_POST['id'] != ''){
		
	} else {
		$date_to = date('Y');
		$date_to .= '-01-01';
		$date_from = date('Y-m-d');
    
		$query = "SELECT * FROM users INNER JOIN profiles ON profiles.user_id=users.unique_id WHERE type = 'female'";
		
		if($_POST['age-from'] != '' && $_POST['age-to'] != ''){
			$from = date('Y-m-d', strtotime($date_from. ' - '.$_POST['age-from'].' years'));
			$to   = date('Y-m-d', strtotime($date_to. ' - '.$_POST['age-to'].' years'));

			$query .= " AND birthday BETWEEN '".$to."' AND '".$from."'";	//...отнимать даты от СЕГОДНЯ, а я спать пошёл :)
		} else if(isset($_POST['age-from'])) {
			
		} else if(isset($_POST['age-to'])) {
			
		}
		
		$query .= ";";
		echo $query;
		$girls = R::getAll($query);
		var_dump($girls);
	}
    
    
   // echo '123';
?>
