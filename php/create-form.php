<?php
	require 'config.php';

	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);

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
		isset($_POST['country'])
	){
		//echo 'check error';
		
		$img_name = $_FILES['photos']['name'];
		$img_type = $_FILES['photos']['type'];
		$tmp_name = $_FILES['photos']['tmp_name'];
		
		$ran_id = rand(time(), 100000000);
		
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
							
						} else {
							$photos = R::dispense('photos');
							$photos->img = $new_img_name;
							$photos->user_id = $ran_id;
							R::store($photos);
						}
					}
				}
			}
		}
	}

?>
