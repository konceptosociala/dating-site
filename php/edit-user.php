<?php
	session_start();
	require 'config.php';
	
	$check_root = R::findOne('users', 'nickname = "root"');
	if($check_root->unique_id != $_SESSION['unique_id']) {
		header("location: /");
	}

	if(
		isset($_POST['hidden-id']) &&
		isset($_POST['name']) &&
		isset($_POST['nickname']) &&
		isset($_POST['birthday']) &&
		isset($_POST['country'])
	){	
		$with_nick = R::find( 'users', ' nickname = ? AND NOT unique_id = ?', [$_POST['nickname'], $_POST['hidden-id']] );	
		
		$d1 = new DateTime(date('y-m-d'));
		$d2 = new DateTime($_POST['birthday']);
		$diff = $d2->diff($d1);
						
		if(!empty($with_nick)){
			echo '<script>alert("'.$_POST['nickname'].' - This nickname already exist!")</script>';
		} else if(strlen($_POST['nickname']) > 20) {
			echo '<script>alert("'.$_POST['nickname'].' - This nickname is too long!")</script>';
		} else if($diff->y < 18){
			echo '<script>alert("Entered date is invalid!")</script>';
		} else {	
			if($_FILES['photos']['name'][0]){	
				$img_name = $_FILES['photos']['name'];
				$img_type = $_FILES['photos']['type'];
				
				$photo_count = count(R::find('photos', 'user_id = ?', [$_POST['hidden-id']]));				
				$tmp_name = $_FILES['photos']['tmp_name'];
				
				if($photo_count + count($_FILES['photos']['name']) > 9) {
					echo '<script>alert("Max count of photos is 9!")</script>';
				} else {
					for($i = 0; $i < count($img_name); $i++){
						$img_explode = explode('.',$img_name[$i]);
						$img_ext = end($img_explode);
						
						$extensions = ["jpeg", "png", "jpg"];
						if(in_array($img_ext, $extensions) === true){
							$types = ["image/jpeg", "image/jpg", "image/png"];
							if(in_array($img_type[$i], $types) === true){
								$time = time();
								$new_img_name = $time.$img_name[$i];
								if(move_uploaded_file($tmp_name[$i],"images/".$new_img_name)){
									$photos = R::dispense('photos');
									$photos->img = $new_img_name;
									$photos->user_id = $_POST['hidden-id'];
									R::store($photos);
									echo 
									'
									<div id="photo-'.$photos->id.'" class="d-flex col-4 p-3 justify-content-center align-items-center">
										<button class="btn btn-danger remove-photo-b" onclick="remove_photo('.$photos->id.')" type="button">x</button>
										<img class="img-fluid" style="max-height: 100px" src="php/images/'.$photos->img.'">
									</div>
									';
									
								}
							} else {
								echo '<script>alert("Wrong file type!");</script>';
							}
						} else {
							echo '<script>alert("Wrong file type!");</script>';
						}
					}
				}
			} 
		
			$user = R::findOne('users', 'unique_id = ?', [$_POST['hidden-id']]);
			$prof = R::findOne('profiles', 'user_id = ?', [$user->unique_id]);
								
			$user->name = $_POST['name'];
			$user->nickname = $_POST['nickname'];
			if(isset($_POST['is-online']) && $_POST['is-online'] == 'on'){
				$user->status = "Online";
			} else {
				$user->status = "Offline";
			}
			
			if($_FILES['avatar']['name']){
				$types = ["image/jpeg", "image/jpg", "image/png"];
				if(in_array($_FILES['avatar']['type'], $types) === true){
					$time = time();
					$new_av_name = $time.$_FILES['avatar']['name'];
					if(move_uploaded_file($_FILES['avatar']['tmp_name'],"images/".$new_av_name)){
						$user->img = $new_av_name;
					}
				} else {
					echo '<script>alert("Wrong file type!");</script>';
				}
			} 
			
			R::store($user);
							
			$prof->birthday = $_POST['birthday'];
			$prof->country = $_POST['country'];
			if(isset($_POST['haircolor'])) $prof->haircolor = $_POST['haircolor'];
			if(isset($_POST['marital'])) $prof->marital = $_POST['marital'];								
			if(isset($_POST['about'])) $prof->about = $_POST['about'];
			if(isset($_POST['wishes'])) $prof->wishes = $_POST['wishes'];
								
			R::store($prof);
		}
	} else {
		echo '<script>alert("Fill required fields!");</script>';
	}

?>

