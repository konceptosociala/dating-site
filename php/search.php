<?php
    session_start();
    require "config.php";
    include "vigener.php";
    
    $query_send = "";
    
    if($_POST['id'] != ''){
		$girls = R::getAll("SELECT * FROM users INNER JOIN profiles ON profiles.user_id=users.unique_id WHERE type = 'female' AND unique_id = '{$_POST['id']}';");
		if(empty($girls)) echo "<center><h3>No users found!</h3></center>";
		for($i = 0; $i < count($girls); $i++) {	
			$acc = $girls[$i];					
			$prof = R::findOne('profiles', 'user_id = ?', [$acc['unique_id']]);
			$d1 = new DateTime(date('y-m-d'));
			$d2 = new DateTime($prof->birthday);
			$diff = $d2->diff($d1);
			
			if($acc['status'] == "Online"){
				$status = '<p class="card-text text-success">• Online</h5>';
			} else {
				$status = '<p class="card-text text-secondary">Offline</h5>';
			}		
											
			echo 
			'
			<div class="col-lg-3 col-md-6 col-sm-12 my-3">
				<div class="card mx-2">
					<div class="card-body">
						<div class="d-flex"><h5 class="card-title">'.$acc['name'].', '.$diff->y.'</h5></div>
						'.$status.'
					</div>
					<a title="View profile of '.$acc['name'].'" href="profile?id='.$acc['unique_id'].'"><div class="card-field" style="background-image: url(php/images/'.$acc['img'].')">
						&nbsp;
					</div></a>
				</div>
			</div>
			';			
		}
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
		
		$query_send = $query;		
		$query .= " LIMIT 3 OFFSET 0;";
		$girls = R::getAll($query);
		if(empty($girls)) echo "<center><h3>No users found!</h3></center>";
		for($i = 0; $i < count($girls); $i++) {	
			$acc = $girls[$i];					
			$prof = R::findOne('profiles', 'user_id = ?', [$acc['unique_id']]);
			$d1 = new DateTime(date('y-m-d'));
			$d2 = new DateTime($prof->birthday);
			$diff = $d2->diff($d1);
			
			if($acc['status'] == "Online"){
				$status = '<p class="card-text text-success">• Online</h5>';
			} else {
				$status = '<p class="card-text text-secondary">Offline</h5>';
			}		
											
			echo 
			'
			<div class="col-lg-3 col-md-6 col-sm-12 my-3">
				<div class="card mx-2">
					<div class="card-body">
						<div class="d-flex"><h5 class="card-title">'.$acc['name'].', '.$diff->y.'</h5></div>
						'.$status.'
					</div>
					<a title="View profile of '.$acc['name'].'" href="profile?id='.$acc['unique_id'].'"><div class="card-field" style="background-image: url(php/images/'.$acc['img'].')">
						&nbsp;
					</div></a>
				</div>
			</div>
			';
		}
				
		echo '<div class="key fixed-top" value="'.Encipher($query_send, 'datingkey').'"></div>';
	}
    
?>
