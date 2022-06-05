<?php 
ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);
	include 'php/config.php';

	session_start();
	if(!isset($_SESSION['unique_id'])){
		header("location: signup.php");
	}
	
	$acc_type = "";
	
	$vtor = R::findOne('users', 'unique_id = ?', [$_SESSION['unique_id']]);
	
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
				$prof = R::findOne('profiles', 'user_id = ?', [$_GET['id']]);
			} else {
				header("location: /");
			}
		}
	}
	
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
	
?>

<?php include_once "tml/header.php"; ?>
	
	<main class="bg-light">
		<div class="container-fluid p-3">
			<div class="row">
				<div class="col-lg-2 col-sm-0"></div>
				<form class="col-lg-2 col-md-6 col-sm-6 mb-md-0" id="chavatar" action="/profile" method="POST" enctype="multipart/form-data" autocomplete="off">
					<label for="chavatar-input"><img <?php if($acc_type == 'visitor') echo 'data-bs-toggle="tooltip" style="cursor: pointer" title="Click to change avatar"'; ?> class="img-fluid soft-shadow" src="php/images/<?php echo $acc->img; ?>"></label>
					<?php if($acc_type == 'visitor') echo '<input type="file" id="chavatar-input" name="chavatar-input" class="d-none">'; ?>
				</form>
				<div class="d-flex flex-column col-lg-5 col-sm-6">
					<div class="container">
						<h1><?php echo $acc->nickname; ?> <?php if($acc_type == 'person') echo '<a href=?favorite='.$acc->unique_id.' class="text-decoration-none icon-star-empty text-warning" title="Add to favorites"></a>'; ?></h1>
						<p>Profile ID: <?php echo $acc->unique_id; ?></p>
					</div>
					<div class="container-fluid soft-shadow p-0 mb-4 bg-white">
						<div class="d-flex flex-wrap">
							<div class="col-12 container p-3 bg-light">
								<h4><b>Bio</b></h4>
							</div>
							<div class="col-lg-4 col-sm-6">
								<div class="container py-2">
									<b>Name</b>
									<p><?php echo $acc->name; ?></p>
								</div>
							</div>
							<div class="col-lg-4 col-sm-6">
								<div class="container py-2">
									<b>Birthday</b>
									<p><?php echo $prof->birthday; ?></p>
								</div>
							</div>
							<div class="col-lg-4 col-sm-6">
								<div class="container py-2">
									<b>Marital status</b>
									<p><?php if($prof->marital != '') echo $prof->marital; else echo '-'; ?></p>
								</div>
							</div>
							<div class="col-lg-4 col-sm-6">
								<div class="container py-2">
									<b>Country</b>
									<p><?php if($prof->country != '') echo $prof->country; else echo '-'; ?></p>
								</div>
							</div>
							<div class="col-lg-4 col-sm-6">
								<div class="container py-2">
									<b>Color of hair</b>
									<p><?php if($prof->haircolor != '') echo $prof->haircolor; else echo '-'; ?></p>
								</div>
							</div>
							<div class="col-lg-4 col-sm-6">
								<div class="container py-2">
									<b>Email</b>
									<p><?php if($acc->confirm == true) echo 'Confirmed'; else echo 'Not confirmed'; ?></p>
								</div>
							</div>
						</div>
					</div>
					<div class="container-fluid soft-shadow p-0 mb-4 bg-white">
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
					<div class="container-fluid soft-shadow p-0 mb-4 bg-white">
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
					'<div class="col-lg-3 col-sm-12 d-flex flex-column">
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
							<div class="">
								<label>Photos</label>
								<?php 
								
								$photos = R::find('photos', 'user_id = ?', [$_SESSION['unique_id']]);
								foreach($photos as $photo) {
									echo 'SAS| ';
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
	</main>
	
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script> 
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
<script>
	const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
	const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))
	
	document.getElementById("chavatar-input").onchange = function() {
		document.getElementById("chavatar").submit();
	};
		
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
                alert(response);
			}
		});
    });
</script>
</body>
</html>
