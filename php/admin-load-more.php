<?php
	session_start();
    require "config.php";
    
    $from = $_POST['from'];
       
    $girls = R::getAll("SELECT * FROM users WHERE type = 'female' LIMIT 3 OFFSET {$from}");
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
		<div id="remove'.$acc['unique_id'].'" class="col-lg-4 col-md-6 col-sm-12 my-3 girl-card">
			<div class="card mx-2">
				<div class="card-body">
					<div class="d-flex"><h5 class="card-title">'.$acc['name'].', '.$diff->y.'</h5><button onclick="remove_user('.$acc['unique_id'].')" class="btn btn-close ms-auto"></button></div>
					<i>'.$acc['nickname'].'</i>
				</div>
				<a title="View profile of '.$acc['name'].'" href="profile?id='.$acc['unique_id'].'"><div class="card-field" style="border-radius: 0; background-image: url(php/images/'.$acc['img'].')">
					&nbsp;
				</div></a>
				<div class="d-flex">
					<button onclick="editor_id('.$acc['unique_id'].')" data-bs-toggle="modal" data-bs-target="#editModal" class="btn btn-warning flex-fill" style="border-radius: 0 0 0 5px">Edit</button>
					<button onclick="use_id('.$acc['unique_id'].')" data-bs-toggle="modal" data-bs-target="#useModal" class="btn btn-success flex-fill" style="border-radius: 0 0 5px 0">Use</button>
				</div>
			</div>
		</div>
		';
	}
?>
