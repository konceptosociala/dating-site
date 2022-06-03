<?php 
  session_start();
  if(!isset($_SESSION['unique_id'])){
    header("location: signup.php");
  }
	$page_title = "Favorites"
?>


<?php include_once "tml/header.php"; ?>


<div class="bg-light container-fluid mx-auto">
	<div class="d-flex justify-content-center p-4"><h1 class="display-4">Favorites</h1></div>
	<div class="container mt-3">
		<div class="row">
			<div class="col-lg-3 col-md-6 col-sm-12 my-3">
				<div class="card mx-2">
					<div class="card-body">
						<h5 class="card-title">Helena, 21</h5>
						<p class="card-text text-secondary">Offline</h5>
					</div>
					<div class="card-field" style="background-image: url('https://images.pexels.com/photos/733872/pexels-photo-733872.jpeg?auto=compress&cs=tinysrgb&dpr=1&w=500')">
						&nbsp;
					</div>
				</div>
			</div>
			<div class="col-lg-3 col-md-6 col-sm-12 my-3">
				<div class="card mx-2">
					<div class="card-body">
						<h5 class="card-title">Oleksandra, 19</h5>
						<p class="card-text text-success">• Online</h5>
					</div>
					<div class="card-field" style="background-image: url('https://images.pexels.com/photos/733872/pexels-photo-733872.jpeg?auto=compress&cs=tinysrgb&dpr=1&w=500')">
						&nbsp;
					</div>
				</div>
			</div>
			<div class="col-lg-3 col-md-6 col-sm-12 my-3">
				<div class="card mx-2">
					<div class="card-body">
						<h5 class="card-title">Oleksandra, 19</h5>
						<p class="card-text text-success">• Online</h5>
					</div>
					<div class="card-field" style="background-image: url('https://images.pexels.com/photos/733872/pexels-photo-733872.jpeg?auto=compress&cs=tinysrgb&dpr=1&w=500')">
						&nbsp;
					</div>
				</div>
			</div>
			<div class="col-lg-3 col-md-6 col-sm-12 my-3">
				<div class="card mx-2">
					<div class="card-body">
						<h5 class="card-title">Oleksandra, 19</h5>
						<p class="card-text text-success">• Online</h5>
					</div>
					<div class="card-field" style="background-image: url('https://images.pexels.com/photos/733872/pexels-photo-733872.jpeg?auto=compress&cs=tinysrgb&dpr=1&w=500')">
						&nbsp;
					</div>
				</div>
			</div>
			<div class="col-lg-3 col-md-6 col-sm-12 my-3">
				<div class="card mx-2">
					<div class="card-body">
						<h5 class="card-title">Oleksandra, 19</h5>
						<p class="card-text text-success">• Online</h5>
					</div>
					<div class="card-field" style="background-image: url('https://images.pexels.com/photos/733872/pexels-photo-733872.jpeg?auto=compress&cs=tinysrgb&dpr=1&w=500')">
						&nbsp;
					</div>
				</div>
			</div>
			<div class="col-lg-3 col-md-6 col-sm-12 my-3">
				<div class="card mx-2">
					<div class="card-body">
						<h5 class="card-title">Oleksandra, 19</h5>
						<p class="card-text text-success">• Online</h5>
					</div>
					<div class="card-field" style="background-image: url('https://images.pexels.com/photos/733872/pexels-photo-733872.jpeg?auto=compress&cs=tinysrgb&dpr=1&w=500')">
						&nbsp;
					</div>
				</div>
			</div>
		</div>
	</div>
</div>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script> 
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
<script src="/javascript/emojiarea/jquery.emojiarea.js"></script>
</body>
</html>
