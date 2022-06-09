<?php
	session_start();
    require "config.php";
    
    $from = $_POST['from'];
       
    $girls = R::getAll("SELECT * FROM users WHERE type = 'male' LIMIT 12 OFFSET {$from}");
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
		<div class="col-lg-4 col-md-6 col-sm-12 my-3  client-card">
			<div class="card mx-2">
				<div class="card-body">
					<div class="d-flex"><h5 class="card-title">'.$acc['name'].', '.$diff->y.'</h5></div>
					'.$acc['nickname'].'
				</div>
				<a title="View profile of '.$acc['name'].'" href="profile?id='.$acc['unique_id'].'"><div class="card-field" style="background-image: url(php/images/'.$acc['img'].'); border-radius: 0">
					&nbsp;
				</div></a>
				<div class="d-flex">
					<input disabled class="form-control" value="'.$acc['email'].'" style=" border-radius: 0 0 5px 5px">
				</div>
			</div>
		</div>
		';
	}
?>
