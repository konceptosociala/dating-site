<?php 
ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);
	include 'php/config.php';

	session_start();
	if(!isset($_SESSION['unique_id'])){
		header("location: signup.php");
	}
	
	$acc_type = "";
	
	if(R::findOne('users', 'unique_id = ?', [$_SESSION['unique_id']])->type == 'male') {
		if(!isset($_GET['id']) || $_GET['id'] == $_SESSION['unique_id']) {
			$acc_type = "visitor";
			$acc = R::findOne('users', 'unique_id = ?', [$_SESSION['unique_id']]);
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
			header("location: /");
		} else {
			$acc = R::findOne('users', 'unique_id = ?', [$_GET['id']]);
			if(isset($acc)){
				if ($acc->type == 'female') {
					header("location: /");
				} else {
					$acc_type = "person";
					$prof = R::findOne('profiles', 'user_id = ?', [$_GET['id']]);
				}
			} else {
				header("location: /");
			}
		}
	}
	
	if(isset($_GET['favorite']) && $_GET['favorite'] != '' && $_GET['favorite'] != $_SESSION['unique_id']){
		$checkuser = R::findOne('favorites', 'fav_id = ?', [$_GET['favorite']]);
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
	
?>

<?php include_once "tml/header.php"; ?>
	
	<main class="bg-light">
		<div class="container-fluid p-3">
			<div class="row">
				<div class="col-lg-2 col-sm-0"></div>
				<div class="col-lg-2 col-md-6 col-sm-6 mb-md-0">
					<img class="img-fluid soft-shadow" src="https://i.pinimg.com/736x/28/42/c9/2842c9d941fc16ca9e0f34d148c1c33c.jpg">
				</div>
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
							<p><a class="link s-nav text-decoration-none" href=#>Settings</a></p>
							<p><a class="link s-nav text-decoration-none" href=#>Log out</a></p>
						</div>
					</div>'
				;} ?>
			</div>
		</div>
	</main>
	
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
  </body>
</html>
