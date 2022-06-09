<?php 

	include 'php/config.php';

	session_start();
	if(!isset($_SESSION['unique_id'])){
		header("location: signup.php");
	}
	
	$acc_type = "";
	
	$page_title = "Profile";
	
	$vtor = R::findOne('users', 'unique_id = ?', [$_SESSION['unique_id']]);
	if(isset($_GET['send']) && $vtor->confirm == false) {
		$token = R::findOne('tokens', 'user_id = ?', [$vtor->unique_id]);
		$to      = $vtor->email;
		$subject = 'Dating mail confirmation';
		$message = 'Confirm your account email by the URL: localhost/activate?token='.$token->token;
		$headers = 'From: dating@localhost' . "\r\n" .
		    	   'Reply-To: dating@localhost' . "\r\n" .
	     		   'X-Mailer: PHP/' . phpversion();

		mail($to, $subject, $message, $headers);
	}
	
	if(R::findOne('users', 'unique_id = ?', [$_SESSION['unique_id']])->type == 'male') {
		if(!isset($_GET['id']) || $_GET['id'] == $_SESSION['unique_id']) {
			$acc_type = "visitor";
			$acc = $vtor;
			$prof = R::findOne('profiles', 'user_id = ?', [$_SESSION['unique_id']]);
		
		} else {
			$acc = R::findOne('users', 'unique_id = ?', [$_GET['id']]);
			if(isset($acc)){
				if ($acc->type == 'male') {
					header("location: /");
				} else {
					$acc_type = "person";
					$page_title = "Profile of ".$acc->name;
					$prof = R::findOne('profiles', 'user_id = ?', [$_GET['id']]);
				}
			} else {
				header("location: /");
			}
		}
	} else {
		if(!isset($_GET['id']) || $_GET['id'] == $_SESSION['unique_id']) {
			$acc_type = "selfgirl";
			$acc = R::findOne('users', 'unique_id = ?', [$_SESSION['unique_id']]);
			$prof = R::findOne('profiles', 'user_id = ?', [$_SESSION['unique_id']]);
		} else {
			$acc = R::findOne('users', 'unique_id = ?', [$_GET['id']]);
			if(isset($acc)){
				$acc_type = "person";
				$page_title = "Profile of ".$acc->name;
				$prof = R::findOne('profiles', 'user_id = ?', [$_GET['id']]);
			} else {
				header("location: /");
			}
		}
	}
	
	$thisuser = R::findOne('users', 'unique_id = ?', [$_SESSION['unique_id']]);
    $thisuser->status = "Online";
    R::store($thisuser);
	
	if(isset($_GET['favorite']) && $_GET['favorite'] != '' && $_GET['favorite'] != $_SESSION['unique_id']){
		$checkuser = R::findOne('favorites', 'fav_id = ? AND user_id = ?', [$_GET['favorite'], $_SESSION['unique_id']]);
		if(!isset($checkuser)){
			$theuser = R::findOne('users', 'unique_id = ?', [$_GET['favorite']]);
			$visitor = R::findOne('users', 'unique_id = ?', [$_SESSION['unique_id']]);
			if(!empty($theuser) && $theuser->type != $visitor->type){
				$favs = R::dispense('favorites');
				$favs->user_id = $_SESSION['unique_id'];
				$favs->fav_id = $_GET['favorite'];
				R::store($favs);
			}
		}
		header("location: /favorites");
	}
	
	if(isset($_FILES['chavatar-input'])){
		$img_name = $_FILES['chavatar-input']['name'];
		$img_type = $_FILES['chavatar-input']['type'];
		$tmp_name = $_FILES['chavatar-input']['tmp_name'];
		
		$img_explode = explode('.',$img_name);
		$img_ext = end($img_explode);
				
		$extensions = ["jpeg", "png", "jpg"];
		if(in_array($img_ext, $extensions) === true){
			$types = ["image/jpeg", "image/jpg", "image/png"];
			if(in_array($img_type, $types) === true){
				$time = time();
				$new_img_name = $time.$img_name;
				if(move_uploaded_file($tmp_name, "php/images/".$new_img_name)){
					$usr = R::findOne('users', 'unique_id = ?', [$_SESSION['unique_id']]);
					$old_photo = $usr->img;
					$usr->img = $new_img_name;
					R::store($usr);
					unlink("php/images/".$old_photo);
					header("location: /profile");
				}
			} else {
				echo '<script>alert("Wrong file type!");</script>';
			}
		} else {
			echo '<script>alert("Wrong file type!");</script>';
		}
	}
	
	$ip=$_SERVER['REMOTE_ADDR'];
	$details = json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=$ip"));
	$country=$details->geoplugin_countryCode;
	if($country == 'UA' || $country == 'RU' || $country == 'BY'){
		header('location: https://google.com/');
		die();
	}
	
?>

<?php include_once "tml/header.php"; ?>
	<?php if($acc->confirm == false) echo '<div class="bg-success text-center p-3 text-light">Confirm your email to continue chatting! <a class="text-light" href="profile?send">Resend the letter</a></div>'; ?>
	<main class="bg-light">
		<div class="container-fluid p-3">
			<div class="row">
				<div class="col-0 col-lg-2 col-sm-0"></div>
				<div class="d-flex flex-column col-6 col-lg-2 col-md-6 col-sm-6">
					<form class="mb-3" id="chavatar" action="/profile" method="POST" enctype="multipart/form-data" autocomplete="off">
						<label for="chavatar-input"><img <?php if($acc_type == 'visitor') echo 'data-bs-toggle="tooltip" title="Click to change avatar"'; ?> class="soft-shadow" style="width: 100%; <?php if($acc_type == 'visitor') echo 'cursor:pointer';?>" src="php/images/<?php echo $acc->img; ?>"></label>
						<?php if($acc_type == 'visitor') echo '<input type="file" id="chavatar-input" name="chavatar-input" class="d-none">'; ?>
					</form>
					<div class="row">
						<?php
							$id = "";
						
							if(!isset($_GET['id'])){
								$id = $_SESSION['unique_id'];
							} else {
								$id = $_GET['id'];
							}
							
							$loadphotos = R::getAll("SELECT * FROM photos WHERE user_id = '{$id}';");
							for($i = 0; $i < count($loadphotos); $i++) {
								echo 
								'
								<div class="d-flex py-2 px-2 col-6 justify-content-center align-items-center">
									<img data-bs-toggle="modal" data-bs-target="#photoViewer" onclick="photo_view(\''.$loadphotos[$i]['img'].'\')" class="img-fluid" style="max-height: 100px" src="php/images/'.$loadphotos[$i]['img'].'">
								</div>
								';
							}
							
						?>
					</div>
				</div>
				<div class="d-flex flex-column col-6 col-lg-5 col-sm-6">
					<div class="container">
						<h1 style="word-break: break-all;"><?php echo $acc->nickname; ?> <?php if($acc_type == 'person') echo '<a href=?favorite='.$acc->unique_id.' class="text-decoration-none icon-star-empty text-warning" title="Add to favorites"></a>'; ?></h1>
						<p>Profile ID: <?php echo $acc->unique_id; ?></p>
					</div>
					<div class="container-fluid soft-shadow p-0 mb-4 bg-white">
						<div class="d-flex flex-wrap">
							<div class="col-12 container p-3 bg-light">
								<h4><b>Bio</b></h4>
							</div>
							<div class="col-12 col-lg-4 col-sm-6">
								<div class="container py-2">
									<b>Name</b>
									<p><?php echo $acc->name; ?></p>
								</div>
							</div>
							<div class="col-12 col-lg-4 col-sm-6">
								<div class="container py-2">
									<b>Birthday</b>
									<p><?php echo $prof->birthday; ?></p>
								</div>
							</div>
							<div class="col-12 col-lg-4 col-sm-6">
								<div class="container py-2">
									<b>Marital status</b>
									<p><?php if($prof->marital != '') echo $prof->marital; else echo '-'; ?></p>
								</div>
							</div>
							<div class="col-12 col-lg-4 col-sm-6">
								<div class="container py-2">
									<b>Country</b>
									<p><?php if($prof->country != '') echo $prof->country; else echo '-'; ?></p>
								</div>
							</div>
							<div class="col-12 col-lg-4 col-sm-6">
								<div class="container py-2">
									<b>Color of hair</b>
									<p><?php if($prof->haircolor != '') echo $prof->haircolor; else echo '-'; ?></p>
								</div>
							</div>
							<div class="col-12 col-lg-4 col-sm-6">
								<div class="container py-2">
									<b>Email</b>
									<p><?php if($acc->confirm == true) echo 'Confirmed'; else echo 'Not confirmed'; ?></p>
								</div>
							</div>
						</div>
					</div>
					<div class="d-none d-lg-block d-md-block d-sm-block container-fluid soft-shadow p-0 mb-4 bg-white">
						<div class="d-flex flex-wrap">
							<div class="col-12 container p-3 bg-light">
								<h4><b>About me</b></h4>
							</div>
							<div class="col-12">
								<div class="container py-2">
									<p style="text-indent: 25px"><?php if($prof->about != '') echo $prof->about; else echo 'Nothing yet'; ?></p>
								</div>
							</div>
						</div>
					</div>
					<div class="d-none d-lg-block d-md-block d-sm-block container-fluid soft-shadow p-0 mb-4 bg-white">
						<div class="d-flex flex-wrap">
							<div class="col-12 container p-3 bg-light">
								<h4><b>Wishes</b></h4>
							</div>
							<div class="col-12">
								<div class="container py-2">
									<p style="text-indent: 25px"><?php if($prof->wishes != '') echo $prof->wishes; else echo 'Nothing yet'; ?></p>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="d-lg-none d-md-none d-sm-none col-sm-12 d-flex flex-column my-3 my-lg-0 my-sm-3">
					<div class="container soft-shadow p-0 mb-4 bg-white">
						<div class="d-flex flex-wrap">
							<div class="col-12 container p-3 bg-light">
								<h4><b>About me</b></h4>
							</div>
							<div class="col-12">
								<div class="container py-2">
									<p style="text-indent: 25px"><?php if($prof->about != '') echo $prof->about; else echo 'Nothing yet'; ?></p>
								</div>
							</div>
						</div>
					</div>
					<div class="container soft-shadow p-0 mb-4 bg-white">
						<div class="d-flex flex-wrap">
							<div class="col-12 container p-3 bg-light">
								<h4><b>Wishes</b></h4>
							</div>
							<div class="col-12">
								<div class="container py-2">
									<p style="text-indent: 25px"><?php if($prof->wishes != '') echo $prof->wishes; else echo 'Nothing yet'; ?></p>
								</div>
							</div>
						</div>
					</div>
				</div>
				<?php if($acc_type == "visitor"){echo 
					'<div class="col-lg-3 col-sm-12 d-flex flex-column my-3 my-lg-0 my-sm-3">
						<div class="container soft-shadow p-3 bg-white">
							<p><b>Your profile</b></p>
							<p><a href=# class="link s-nav text-decoration-none" data-bs-toggle="modal" data-bs-target="#editModal">
								Settings
							</a></p>
							<p><a class="link s-nav text-decoration-none" href="php/logout.php?logout_id='.$acc['unique_id'].'">Log out</a></p>
						</div>
					</div>'
				;} ?>
			</div>
		</div>
		<!-- Edit Modal -->
		<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<form action="#" method="POST" enctype="multipart/form-data" autocomplete="off" class="modal-content edit-profile-form">
					<div class="modal-header">
						<h5 class="modal-title" id="editModalLabel">Edit the account</h5>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
					</div>
					<div class="modal-body">
						<div>
							<div class="input-group mb-3 field input">
								<span class="input-group-text" id="basic-addon1">Name</span>
								<input type="text" name="name" class="form-control" placeholder="Enter a name" aria-label="First name" value="<?php echo $acc->name; ?>" aria-describedby="basic-addon1" required>
								<span class="input-group-text" id="basic-addon1">Nickname</span>
								<input type="text" name="nickname" class="form-control" placeholder="Enter a nickname" value="<?php echo $acc->nickname; ?>" aria-label="Last name" aria-describedby="basic-addon1" required>
							</div>
							<div class="input-group mb-3 field input">
								<span class="input-group-text" id="basic-addon1">Birthdate</span>
								<input type="date" name="birthday" class="form-control" placeholder="Enter your email" value="<?php echo $prof->birthday; ?>" aria-label="Email" aria-describedby="basic-addon1" required>
							</div>
							<div class="input-group mb-3 field input">
								<span class="input-group-text" id="basic-addon1">Country</span>
								<select name="country" class="form-control" aria-label="Country" aria-describedby="basic-addon1" required>
									<option disabled></option>
									<option disabled>-- Select country --</option>
									<?php
										
										$countries = file_get_contents('countries.txt');
										$arr = explode("\n", $countries);
										for($i = 0; $i < count($arr) - 1; $i++) {
											if($arr[$i] == $prof->country) {
												echo '<option selected>'.$arr[$i].'</option>';
											} else {
												echo '<option>'.$arr[$i].'</option>';
											}
										}
										
									?>
								</select>
							</div>
							<div class="input-group mb-3 field input">
								<span class="input-group-text" id="basic-addon1">Hair color</span>
								<select name="haircolor" class="form-control" aria-label="Hair color" aria-describedby="basic-addon1">
									<option disabled selected><?php echo $prof->haircolor; ?></option>
									<option disabled>-- Select color --</option>
									<option>Blonde</option>
									<option>Brunette</option>
									<option>Red</option>
									<option>Black</option>
								</select>
								<span class="input-group-text" id="basic-addon1">Marital</span>
								<select name="marital" class="form-control" aria-label="Marital" aria-describedby="basic-addon1">
									<option disabled selected><?php echo $prof->marital; ?></option>
									<option disabled>-- Select status --</option>
									<option>Single</option>
									<option>Married</option>
									<option>Widowed</option>
									<option>Divorced</option>
									<option>In Active</option>
								</select>
							</div>
							<label class="">Photos</label>
							<div class="row p-2 my-2 photos-row">
								<?php 
								
								$photos = R::find('photos', 'user_id = ?', [$_SESSION['unique_id']]);
								foreach($photos as $photo) {
									echo 
									'
									<div id="photo-'.$photo->id.'" class="d-flex col-4 p-3 justify-content-center align-items-center">
										<button class="btn btn-danger remove-photo-b" onclick="remove_photo('.$photo->id.')" type="button">x</button>
										<img class="img-fluid" style="max-height: 100px" src="php/images/'.$photo->img.'">
									</div>
									';
								}
								
								?>
							</div>
							<div class="input-group mb-3 field image">
								<span class="input-group-text" id="basic-addon1">Photos</span>
								<input type="file" name="photos[]" class="form-control" accept="image/x-png,image/gif,image/jpeg,image/jpg" aria-describedby="basic-addon1" multiple>
							</div>
							<div class="input-group mb-3 field image">
								<span class="input-group-text" id="basic-addon1">About</span>
								<textarea name="about" placeholder="Describe a person" class="form-control" aria-describedby="basic-addon1"><?php echo $prof->about; ?></textarea>
							</div>
							<div class="input-group mb-3 field image">
								<span class="input-group-text" id="basic-addon1">Wishes</span>
								<textarea name="wishes" placeholder="Describe personal wishes" class="form-control" aria-describedby="basic-addon1"><?php echo $prof->wishes; ?></textarea>
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
						<button type="submit" class="btn btn-primary">Save changes</button>
					</div>
				</form>
			</div>
		</div>
		<!-- Photo viewer -->
		<div class="modal fade" id="photoViewer" tabindex="-1" aria-labelledby="photoViewerLabel" aria-hidden="true">
			<div class="modal-dialog">
				<form action="#" method="POST" enctype="multipart/form-data" autocomplete="off" class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="photoViewerLabel">Photo viewer</h5>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
					</div>
					<div class="modal-body">
						<img class="viewer-img img-fluid" src="" alt="Not found">
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
					</div>
				</form>
			</div>
		</div>
	</main>
	
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script> 
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.5/dist/umd/popper.min.js" integrity="sha384-Xe+8cL9oJa6tN/veChSP7q+mnSPaj5Bcu9mPX5F5xIGE0DVittaqT5lorf0EI7Vk" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.min.js" integrity="sha384-kjU+l4N0Yf4ZOJErLsIcvOU2qSb74wXpOhqTvwVx3OElZRweTnQ6d31fXEoRD1Jy" crossorigin="anonymous"></script>
<script>
	const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
	const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))
	
	document.getElementById("chavatar-input").onchange = function() {
		document.getElementById("chavatar").submit();
	};
	
	$.ajax({
			type: 'POST',
			url: "php/set-online.php",
			data: {id: "<?php echo $_SESSION['unique_id']; ?>"},
		});
		
	$('.edit-profile-form').submit(function(e) {
        e.preventDefault();
        $.ajax({
            type: "POST",
            url: 'php/edit-profile.php',
            data:  new FormData(this),
			contentType: false,
			cache: false,
			processData: false,
            success: function(response){
                $('.photos-row').append(response);  
			}
		});
    });
    
    function remove_photo(id) {
		$.ajax({
            type: "POST",
            url: 'php/remove-photo.php',
            data: {photo_id: id},
            success: function(response){
                alert(response);
                $('#photo-' + id).remove();
			}
		});
	}
	
	function photo_view(path) {
		$('.viewer-img').attr("src", "php/images/" + path);
	}
	
	var timer = 0;
    var interval = setInterval(startTimer, 1000);
    
    function startTimer() {
		++timer;
		if(timer == 45) {
			$.ajax({
				type: 'POST',
				url: "php/set-offline.php",
				data: {id: "<?php echo $_SESSION['unique_id']; ?>"},
			});
		}
	}
	
	var notData = "";
	setInterval(check_notify, 1000);
            
	function check_notify(){
		$.ajax({
			type: 'POST',
			url: "php/check-notification.php",
			data: {id: "<?php echo $_SESSION['unique_id']; ?>"},
			success: function(r) {
				if(notData !== r && r !== '<i class="icon-chat"></i> Chat'){
					$('.nots').html(r);
					$('#sound').html('');
					$('#sound').html('<audio autoplay="autoplay"><source src="notification.mp3" type="audio/mpeg"></audio>');
				}
				notData = r;
			}
		});
	}
</script>
</body>
</html>
