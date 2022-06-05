<?php 
  session_start();
  if(!isset($_SESSION['unique_id'])){
    header("location: signup.php");
  }
	
  require 'php/config.php';

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
					<?php
										
						$countries = file_get_contents('countries.txt');
						$arr = explode("\n", $countries);
						for($i = 0; $i < count($arr) - 1; $i++) {
							echo '<option>'.$arr[$i].'</option>';
						}
							
					?>
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
			<?php
						
				$girls = R::getAll("SELECT * FROM users WHERE type = 'female'");
				if(empty($girls)) echo "<center><h3>You haven't created any accounts yet</h3></center>";
				for($i = 0; $i < count($girls); $i++) {	
					$acc = $girls[$i];					
					$prof = R::findOne('profiles', 'user_id = ?', [$acc['unique_id']]);
					$d1 = new DateTime(date('y-m-d'));
					$d2 = new DateTime($prof->birthday);
					$diff = $d2->diff($d1);
					
					if($acc['status'] == "Online"){
						$status = '<p class="card-text text-success">• Online</h5>';
					} else {
						$status = '<p class="card-text text-secondary">Offline</h5>';
					}		
											
					echo 
					'
					<div class="col-lg-3 col-md-6 col-sm-12 my-3">
						<div class="card mx-2">
							<div class="card-body">
								<div class="d-flex"><h5 class="card-title">'.$acc['name'].', '.$diff->y.'</h5></div>
								'.$status.'
							</div>
							<a title="View profile of '.$acc['name'].'" href="profile?id='.$acc['unique_id'].'"><div class="card-field" style="background-image: url(php/images/'.$acc['img'].')">
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

<?php include 'tml/footer.php' ?>
