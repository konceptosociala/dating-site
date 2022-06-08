<?php
	require 'config.php';

	session_start();
	$check_root = R::findOne('users', 'nickname = "root"');
	if($check_root->unique_id != $_SESSION['unique_id']) {
		header("location: /");
	}

	$photo_div = "";

	if(isset($_POST['id'])){
		$user = R::findOne('users', 'unique_id = ?', [$_POST['id']]);
		if(isset($user)){
			$profile = R::findOne('profiles', 'user_id = ?', [$user->unique_id]);
			$photos = R::find('photos', 'user_id = ?', [$user->unique_id]);
			foreach($photos as $photo) {
				$photo_div .= '<div id=\"photo-'.$photo->id.'\" class=\"d-flex col-4 p-3 justify-content-center align-items-center\"><button class=\"btn btn-danger remove-photo-b\" onclick=\"remove_photo('.$photo->id.')\" type=\"button\">x</button><img class=\"img-fluid\" style=\"max-height: 100px\" src=\"php/images/'.$photo->img.'\"></div>';
			}
			
			$countries = file_get_contents('../countries.txt');
			$arr = explode("\n", $countries);
			$countries = "<option></option>";
			for($i = 0; $i < count($arr) - 1; $i++) {
				if($arr[$i] != $profile->country){
					$countries .= '<option>'.$arr[$i].'</option>';
				} else {
					$countries .= '<option selected>'.$arr[$i].'</option>';
				}
			}
						
			echo 
			'
				{
					"hidden_id"	: "'.$user->unique_id.'",
					"name" 		: "'.$user->name.'",
					"nickname" 	: "'.$user->nickname.'",
					"birthday"	: "'.$profile->birthday.'",
					"country"	: "'.$countries.'",
					"haircolor" : "'.$profile->haircolor.'",
					"marital"   : "'.$profile->marital.'",
					"about" 	: "'.$profile->about.'",
					"wishes" 	: "'.$profile->wishes.'",
					"photo_div" : "'.$photo_div.'"
				}
			';
		}
	}

?>
