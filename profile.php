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
	
?>

<?php include_once "tml/header.php"; ?>
	
	<main class="bg-light">
		<div class="container-fluid p-3">
			<div class="row">
				<div class="col-lg-2 col-sm-0"></div>
				<div class="col-lg-2 col-md-6 col-sm-6 mb-md-0">
					<img class="img-fluid soft-shadow" src="php/images/<?php echo $acc->img; ?>">
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
							<p><a href=# class="link s-nav text-decoration-none" data-bs-toggle="modal" data-bs-target="#exampleModal">
								Settings
							</a></p>
							<p><a class="link s-nav text-decoration-none" href="php/logout.php?logout_id='.$acc['unique_id'].'">Log out</a></p>
						</div>
					</div>'
				;} ?>
			</div>
		</div>
		<!-- Modal -->
		<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
		  <div class="modal-dialog">
			<div class="modal-content">
			  <div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			  </div>
			  <div class="modal-body">
				...
			  </div>
			  <div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
				<button type="button" class="btn btn-primary">Save changes</button>
			  </div>
			</div>
		  </div>
		</div>
	</main>
	
<?php include 'tml/footer.php' ?>
