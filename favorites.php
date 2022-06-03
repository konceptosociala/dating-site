<?php ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);
  session_start();
  if(!isset($_SESSION['unique_id'])){
    header("location: signup.php");
  }
	$page_title = "Favorites"
?>


<?php include_once "tml/header.php"; ?>


<div class="bg-light container-fluid mx-auto" style="min-height: 100% !important">
	<div class="d-flex justify-content-center p-4"><h1 class="display-4">Favorites</h1></div>
	<div class="container mt-3">
		<div class="row">
			<?php 
			
			require 'php/config.php';
			
			if(isset($_GET['remove-favorite'])){
				$deluser = R::findOne('favorites', 'fav_id = ?', [$_GET['remove-favorite']]);
				if(isset($deluser)){
					R::trash($deluser);
				}
			}
			
			$favs = R::getAll("SELECT * FROM favorites WHERE user_id = {$_SESSION['unique_id']}");
			if(empty($favs)) echo "<center><h3>You haven't any favorites</h3></center>";
			for($i = 0; $i < count($favs); $i++) {
				$acc  = R::findOne('users', 'unique_id = ?', [$favs[$i]['fav_id']]);
				$prof  = R::findOne('profiles', 'user_id = ?', [$favs[$i]['fav_id']]);
				
				$d1 = new DateTime(date('y-m-d'));
				$d2 = new DateTime($prof->birthday);

				$diff = $d2->diff($d1);
				
				if($acc->status == "Online"){
					$status = '<p class="card-text text-success">• Online</h5>';
				} else {
					$status = '<p class="card-text text-secondary">Offline</h5>';
				}
				
				echo 
				'
				<div class="col-lg-3 col-md-6 col-sm-12 my-3">
					<div class="card mx-2">
						<div class="card-body">
							<div class="d-flex"><h5 class="card-title">'.$acc->name.', '.$diff->y.'</h5><a href="?remove-favorite='.$acc->unique_id.'" class="btn btn-outline-danger px-2 py-1 ms-auto">X</a></div>
							'.$status.'
						</div>
						<a title="Start chatting with '.$acc->name.'" href="chat?id='.$acc->unique_id.'"><div class="card-field" style="background-image: url(php/images/'.$acc->img.')">
							&nbsp;
						</div></a>
					</div>
				</div>
				
				';
			}
			
			?>
		</div>
	</div>
</div>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script> 
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
<script src="/javascript/emojiarea/jquery.emojiarea.js"></script>
</body>
</html>
