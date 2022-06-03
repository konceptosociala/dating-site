<?php
	ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);
    session_start();
    include_once "config.php";
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $nickname = mysqli_real_escape_string($conn, $_POST['nickname']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    if(!empty($name) && !empty($nickname) && !empty($email) && !empty($password)){
        if(filter_var($email, FILTER_VALIDATE_EMAIL)){
			$with_mail = R::find( 'users', ' email = ? ', [ $email ] );			
            if(!empty($with_mail)){
                echo "$email - This email already exist!";
            } else {
				$with_nick = R::find( 'users', ' nickname = ? ', [ $nickname ] );			
				if(!empty($with_nick)){
					echo "$nickname - This nickname already exist!";
				} else if(strlen($nickname) > 20) {
					echo "$nickname - This nickname is too long!";
				} else {
					if(isset($_FILES['image'])){
						$img_name = $_FILES['image']['name'];
						$img_type = $_FILES['image']['type'];
						$tmp_name = $_FILES['image']['tmp_name'];
						
						$img_explode = explode('.',$img_name);
						$img_ext = end($img_explode);
		
						$extensions = ["jpeg", "png", "jpg"];
						if(in_array($img_ext, $extensions) === true){
							$types = ["image/jpeg", "image/jpg", "image/png"];
							if(in_array($img_type, $types) === true){
								$time = time();
								$new_img_name = $time.$img_name;
								if(move_uploaded_file($tmp_name,"images/".$new_img_name)){
									$ran_id = rand(time(), 100000000);
									$status = "Online";
									$encrypt_pass = md5($password);
									
									$users_t = R::dispense('users');
									$users_t->unique_id = $ran_id;
									$users_t->name = $name;
									$users_t->nickname = $nickname;
									$users_t->email = $email;
									$users_t->password = $encrypt_pass;
									$users_t->img = $new_img_name;
									$users_t->type = "male";
									$users_t->status = $status;
									
									$success_id = R::store($users_t);
									if(isset($success_id) && $success_id != 0){
										$select_sql2 = mysqli_query($conn, "SELECT * FROM users WHERE email = '{$email}'");
										if(mysqli_num_rows($select_sql2) > 0){
											$result = mysqli_fetch_assoc($select_sql2);
											$_SESSION['unique_id'] = $result['unique_id'];
											echo "success";
										}else{
											echo "This email address not Exist!";
										}
									}else{
										echo "Something went wrong. Please try again!";
									}
									
									$profile = R::dispense('profiles');
									
									$profile->user_id = $ran_id;
									$profile->birthday = $_POST['birthday'];
									$profile->country = "";
									$profile->haircolor = "";
									$profile->marital = "";
									$profile->about = "";
									$profile->wishes = "";
									
									R::store($profile);
								}
							}else{
								echo "Please upload an image file - jpeg, png, jpg";
							}
						}else{
							echo "Please upload an image file - jpeg, png, jpg";
						}
					}
				}
            }
        }else{
            echo "$email is not a valid email!";
        }
    }else{
        echo "All input fields are required!";
    }
?>
