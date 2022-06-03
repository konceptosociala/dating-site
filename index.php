<?php 
  session_start();
  if(!isset($_SESSION['unique_id'])){
    header("location: signup.php");
  }

?>


<?php include_once "tml/header.php"; ?>

<div class="bg-light container-fluid mx-auto">
	<div class="row p-5 background-header">
		<h1 style="text-shadow:0 0px 5px white" class="text-light col-md-6 col-sm-12 my-md-auto my-sm-5 display-4 text-center animate__animated animate__flash">Find your love!</h1>
		<div class="col-md-6 col-sm-12 my-sm-5">
			<form class="container">
				<div class="input-group mb-3">
				  <span class="input-group-text sbg-bg border-bg text-white" id="basic-addon1">User ID</span>
				  <input type="text" class="form-control" placeholder="ID" aria-label="ID" aria-describedby="basic-addon1">
				</div>
				<div class="input-group mb-3 col-1">
				  <span class="input-group-text sbg-bg border-bg text-white" id="basic-addon1">Country</span>
				  <select class="form-control" aria-label="Country" aria-describedby="basic-addon1">
					<option selected disabled>-- Select country --</option>
					<option>Ukraine</option>
					<option>Czech Republic</option>
					<option>USA</option>
					<option>Moldova</option>
					<option>Poland</option>
				  </select>
				</div>
				<div class="input-group mb-3">
				  <span class="input-group-text sbg-bg border-bg text-white" id="basic-addon1">Hair Color</span>
				  <select class="form-control" aria-label="Country" aria-describedby="basic-addon1">
					<option selected disabled>-- Select color --</option>
					<option>Blonde</option>
					<option>Brunette</option>
					<option>Red</option>
					<option>Black</option>
				  </select>
				</div>
				<div class="input-group mb-3">
				  <span class="input-group-text sbg-bg border-bg text-light">Age</span>
				  <input type="text" class="form-control" placeholder="From" aria-label="From">
				  <span class="input-group-text sbg-bg border-bg text-light">↔</span>
				  <input type="text" class="form-control" placeholder="To" aria-label="To">
				</div>
				<div class="d-flex"><button class="btn btn-light mx-auto" type="submit">Search</button></div>
			</form>
		</div>
	</div>
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
</body>
</html>
