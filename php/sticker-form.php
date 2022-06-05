<?php
	require 'config.php';

	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);

	if(isset($_POST['sticker-name']) && isset($_FILES['sticker-file'])){		
		if(!preg_match('/[^A-Za-z0-9.#\\-$]/', $_POST['sticker-name'])){
			$checksticker = R::findOne('stickers', 'name = ?', [$_POST['sticker-name']]);
			if(!isset($checksticker)){
				$img_name = $_FILES['sticker-file']['name'];
				$img_type = $_FILES['sticker-file']['type'];
				$tmp_name = $_FILES['sticker-file']['tmp_name'];
						
				$img_explode = explode('.',$img_name);
				$img_ext = end($img_explode);
				
				$extensions = ["jpeg", "png", "jpg"];
				if(in_array($img_ext, $extensions) === true){
					$types = ["image/jpeg", "image/jpg", "image/png"];
					if(in_array($img_type, $types)){
						$time = time(); 
						$new_img_name = $time.$img_name;
						if(move_uploaded_file($tmp_name,"images/stickers/".$new_img_name)){
							$sticker = R::dispense('stickers');
							$sticker->name = $_POST['sticker-name'];
							$sticker->img = $new_img_name;
							R::store($sticker);
							echo 
							'
							<div id="remove-stick-'.$sticker['name'].'" class="col-lg-4 col-md-6 col-sm-12 my-3 stick-card">
								<div class="card mx-2">
									<div class="card-body">
										<div class="d-flex"><h5 class="card-title">'.$sticker['name'].'</h5><button onclick="remove_sticker(\''.$sticker['name'].'\')" class="btn btn-close ms-auto"></button></div>
									</div>
									<div class="card-field p-3" style="border-radius: 0;">
										<img src="php/images/stickers/'.$sticker['img'].'" class="img-fluid">
									</div>
								</div>
							</div>
							';
						}
					} else {
						echo '<script>alert("Wrong file type!")</script>';
					}
					
				} else {
					echo '<script>alert("Wrong file type!")</script>';
				}			
			} else {
				echo '<script>alert("Sticker with such name already exists!")</script>';
			}
		} else {
			echo '<script>alert("Invalid characters!")</script>';
		}
	}

?>
