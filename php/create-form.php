<?php
	require 'config.php';

	function generateMail() {
		$characters = 'abcdefghijklmnopqrstuvwxyz';
		$charactersLength = strlen($characters);
		$name = '';
		$domain = '';
		for ($i = 0; $i < 8; $i++) {
			$name .= $characters[rand(0, $charactersLength - 1)];
		}
		
		for ($i = 0; $i < 6; $i++) {
			$domain .= $characters[rand(0, $charactersLength - 1)];
		}
		return $name.'@'.$domain.'.com';
	}
	
	function generatePassword($len = 10) {
		$characters = 'abcdefghijklmnopqrstuvwxyz';
		$charactersLength = strlen($characters);
		$pass = '';
		for ($i = 0; $i < $len; $i++) {
			$pass .= $characters[rand(0, $charactersLength - 1)];
		}
		return $pass;
	}

	if(
		isset($_POST['name']) &&
		isset($_POST['nickname']) &&
		isset($_POST['birthday']) &&
		isset($_POST['country']) && 
		isset($_FILES['photos'])
	){
		//echo 'check error';
		
		$img_name = $_FILES['photos']['name'];
		$img_type = $_FILES['photos']['type'];
		$tmp_name = $_FILES['photos']['tmp_name'];
		
		$ran_id = rand(time(), 100000000);
		$with_nick = R::find( 'users', ' nickname = ? ', [$_POST['nickname']] );	
		
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
							if($i == 0) {
								$passwd = generatePassword();
								$email = generateMail();
								
								$girl = R::dispense('users');
								$prof = R::dispense('profiles');
								$pswds = R::dispense('passwords');
								
								$girl->name = $_POST['name'];
								$girl->nickname = $_POST['nickname'];
								$girl->unique_id = $ran_id;
								$girl->type = "female";
								$girl->confirm = true;
								$girl->status = "Online";
								$girl->email = $email;
								$girl->password = md5($passwd);
								$girl->img = $new_img_name;
								R::store($girl);
								
								$pswds->user_id = $ran_id;
								$pswds->password = $passwd;
								R::store($pswds);
								
								$prof->user_id = $ran_id;
								$prof->birthday = $_POST['birthday'];
								$prof->country = $_POST['country'];
								
								if(isset($_POST['haircolor'])){
									$prof->haircolor = $_POST['haircolor'];
								}
								
								if(isset($_POST['marital'])){
									$prof->marital = $_POST['marital'];
								}
								
								if(isset($_POST['about'])){
									$prof->about = $_POST['about'];
								}
								
								if(isset($_POST['wishes'])){
									$prof->wishes = $_POST['wishes'];
								}
								
								R::store($prof);
								
								echo 
								'
								<div id="remove'.$girl->unique_id.'" class="col-lg-4 col-md-6 col-sm-12 my-3 girl-card">
									<div class="card mx-2">
										<div class="card-body">
											<div class="d-flex"><h5 class="card-title">'.$girl->name.', '.$diff->y.'</h5><button onclick="remove_user('.$girl->unique_id.')" class="btn btn-close ms-auto"></button></div>
											<i>'.$girl->nickname.'</i>
										</div>
										<a title="View profile of '.$girl->name.'" href="profile?id='.$girl->unique_id.'"><div class="card-field" style="border-radius: 0; background-image: url(php/images/'.$girl->img.')">
											&nbsp;
										</div></a>
										<div class="d-flex">
											<button onclick="editor_id('.$girl->unique_id.')" data-bs-toggle="modal" data-bs-target="#editModal" class="btn btn-warning flex-fill" style="border-radius: 0 0 0 5px">Edit</button>
											<button onclick="use_id('.$girl->unique_id.')" data-bs-toggle="modal" data-bs-target="#useModal" class="btn btn-success flex-fill" style="border-radius: 0 0 5px 0">Use</button>
										</div>
									</div>
								</div>
								';
								
							} else {
								$photos = R::dispense('photos');
								$photos->img = $new_img_name;
								$photos->user_id = $ran_id;
								R::store($photos);
							}
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

?>
