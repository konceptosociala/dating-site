<?php 
  session_start();
  if(!isset($_SESSION['unique_id'])){
    header("location: signup.php");
  }
	
  $page_title = "Admin Panel";
  
  require 'php/config.php'
	
?>


<?php include_once "tml/noheader.php"; ?>
<section class="row">
    <header class="col-3">
		<div class="p-4 bg-light">
			<a href="/admin" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto link-dark text-decoration-none">
				<span class="fs-4"><i class="icon-wrench"></i> Admin Panel</span>
			</a>
			<hr>
			<ul class="nav nav-pills flex-column" style="height: 100vh !important">
				<li class="nav-item" role="presentation">
					<a href="#dashboard" class="nav-link nav-tab-db" id="dashboard-tab" data-bs-toggle="tab" data-bs-target="#dashboard-tab-pane" type="button" role="tab" aria-controls="dashboard-tab-pane" aria-selected="true">
						<i class="icon-gauge-1"></i> Dashboard
					</a>
				</li>
				<li class="nav-item" role="presentation">
					<a href="#clients" class="nav-link nav-tab-cl" id="clients-tab" data-bs-toggle="tab" data-bs-target="#clients-tab-pane" type="button" role="tab" aria-controls="clients-tab-pane" aria-selected="true">
						<i class="icon-male"></i> Clients
					</a>
				</li>
				<li class="nav-item" role="presentation">
					<a href="#accounts" class="nav-link nav-tab-ac" id="accounts-tab" data-bs-toggle="tab" data-bs-target="#accounts-tab-pane" type="button" role="tab" aria-controls="accounts-tab-pane" aria-selected="true">
						<i class="icon-female"></i> Accounts
					</a>
				</li>
				<li class="nav-item" role="presentation">
					<a href="#reviews" class="nav-link nav-tab-rv" id="reviews-tab" data-bs-toggle="tab" data-bs-target="#reviews-tab-pane" type="button" role="tab" aria-controls="reviews-tab-pane" aria-selected="true">
						<i class="icon-commenting"></i> Reviews
					</a>
				</li> 
			</ul>
		</div>
    </header>

	<main class="col-9">
		<div class="tab-content" id="myTabContent">
			<div class="tab-pane fade" id="dashboard-tab-pane" role="tabpanel" aria-labelledby="dashboard-tab" tabindex="0">
				<h1 class="display-4 text-center m-3">Visitance</h1>
				<canvas class="my-4 w-100 chartjs-render-monitor" id="myChart" style="display: block; width: 1090px; height: 460px;" width="1090" height="460"></canvas>
			</div>
			<div class="tab-pane fade" id="clients-tab-pane" role="tabpanel" aria-labelledby="clients-tab" tabindex="1">
				<h1 class="display-4 text-center m-3">Clients</h1>
			</div>
			<div class="tab-pane fade" id="accounts-tab-pane" role="tabpanel" aria-labelledby="accounts-tab" tabindex="2">
				<h1 class="display-4 text-center m-3">Female accounts</h1>
				<center><button class="btn btn-success btn-lg mb-4" data-bs-toggle="modal" data-bs-target="#createModal">+ Create</button></center>
				<div class="row">
					<div class="col-12">
						
						<?php
						
						$girls = R::getAll("SELECT * FROM users WHERE type = 'female'");
						if(empty($girls)) echo "<center><h3>You haven't created any accounts yet</h3></center>";
						for($i = 0; $i < count($girls); $i++) {														
							/*echo 
							'
							<div class="col-lg-3 col-md-6 col-sm-12 my-3">
								<div class="card mx-2">
									<div class="card-body">
										<div class="d-flex"><h5 class="card-title">'.$acc->name.', '.$diff->y.'</h5><a href="?remove-favorite='.$acc->unique_id.'" class="btn btn-close ms-auto"></a></div>
										'.$status.'
									</div>
									<a title="View profile of '.$acc->name.'" href="profile?id='.$acc->unique_id.'"><div class="card-field" style="background-image: url(php/images/'.$acc->img.')">
										&nbsp;
									</div></a>
									<a href="chat?id='.$acc->unique_id.'" class="btn btn-success" style="border-radius: 0 0 5px 5px">Chat</a>
								</div>
							</div>
							';*/
						}
						
						?>
						
					</div>
				</div>
			</div>
			<div class="tab-pane fade" id="reviews-tab-pane" role="tabpanel" aria-labelledby="reviews-tab" tabindex="3">
				4
			</div>
		</div>
		<!-- Create Modal -->
		<div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<form action="#" method="POST" enctype="multipart/form-data" autocomplete="off" class="modal-content create-form">
					<div class="modal-header">
						<h5 class="modal-title" id="createModalLabel">Create new account</h5>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
					</div>
					<div class="modal-body">
						<div>
							<div class="input-group mb-3 field input">
								<span class="input-group-text" id="basic-addon1">Name</span>
								<input type="text" name="name" class="form-control" placeholder="Enter a name" aria-label="First name" aria-describedby="basic-addon1" required>
								<span class="input-group-text" id="basic-addon1">Nickname</span>
								<input type="text" name="nickname" class="form-control" placeholder="Enter a nickname" aria-label="Last name" aria-describedby="basic-addon1" required>
							</div>
							<div class="input-group mb-3 field input">
								<span class="input-group-text" id="basic-addon1">Birthdate</span>
								<input type="date" name="birthday" class="form-control" placeholder="Enter your email" aria-label="Email" aria-describedby="basic-addon1" required>
							</div>
							<div class="input-group mb-3 field input">
								<span class="input-group-text" id="basic-addon1">Country</span>
								<select name="country" class="form-control" aria-label="Country" aria-describedby="basic-addon1" required>
									<option disabled selected></option>
									<option disabled>-- Select country --</option>
									<?php
										
										$countries = file_get_contents('countries.txt');
										$arr = explode("\n", $countries);
										for($i = 0; $i < count($arr) - 1; $i++) {
											echo '<option>'.$arr[$i].'</option>';
										}
										
									?>
								</select>
							</div>
							<div class="input-group mb-3 field input">
								<span class="input-group-text" id="basic-addon1">Hair color</span>
								<select name="haircolor" class="form-control" aria-label="Hair color" aria-describedby="basic-addon1">
									<option disabled selected></option>
									<option disabled>-- Select color --</option>
									<option>Blonde</option>
									<option>Brunette</option>
									<option>Red</option>
									<option>Black</option>
								</select>
								<span class="input-group-text" id="basic-addon1">Marital</span>
								<select name="marital" class="form-control" aria-label="Marital" aria-describedby="basic-addon1">
									<option disabled selected></option>
									<option disabled>-- Select status --</option>
									<option>Single</option>
									<option>Married</option>
									<option>Widowed</option>
									<option>Divorced</option>
									<option>In Active</option>
								</select>
							</div>
							<div class="input-group mb-3 field image">
								<span class="input-group-text" id="basic-addon1">Photos</span>
								<input type="file" name="photos[]" class="form-control" accept="image/x-png,image/gif,image/jpeg,image/jpg" aria-describedby="basic-addon1" multiple required>
							</div>
							<div class="input-group mb-3 field image">
								<span class="input-group-text" id="basic-addon1">About</span>
								<textarea name="about" placeholder="Describe a person" class="form-control" aria-describedby="basic-addon1"></textarea>
							</div>
							<div class="input-group mb-3 field image">
								<span class="input-group-text" id="basic-addon1">Wishes</span>
								<textarea name="wishes" placeholder="Describe personal wishes" class="form-control" aria-describedby="basic-addon1"></textarea>
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
		<!-- Edit Modal -->
		<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
		  <div class="modal-dialog">
			<div class="modal-content">
			  <div class="modal-header">
				<h5 class="modal-title" id="editModalLabel">Edit account</h5>
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
</section>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script> 
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/feather-icons@4.28.0/dist/feather.min.js" integrity="sha384-uO3SXW5IuS1ZpFPKugNNWqTZRRglnUJK6UAZ/gxOX80nxEkN9NcGZTftn6RzhGWE" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4/dist/Chart.min.js" integrity="sha384-zNy6FEbO50N+Cg5wap8IKA4M/ZnLJgzc6w2NqACZaK0u0FXfOWRRJOnQtpZun8ha" crossorigin="anonymous">
</script><script src="dashboard/dashboard.js"></script>
<script>
	process_hash();

	$('.nav-tab-db').click(function() {
		window.location.hash = "dashboard";
	});
	
	$('.nav-tab-cl').click(function() {
		window.location.hash = "clients";
	});
	
	$('.nav-tab-ac').click(function() {
		window.location.hash = "accounts";
	});
	
	$('.nav-tab-rv').click(function() {
		window.location.hash = "reviews";
	});
	
	$(window).bind( 'hashchange', function(e) {
		process_hash();
	});	
		
	function process_hash() {
		switch(window.location.hash) {			
			case "#clients":
				$('#dashboard-tab-pane').attr("class", "tab-pane fade");
				$('#clients-tab-pane').attr("class", "tab-pane fade show active");
				$('#accounts-tab-pane').attr("class", "tab-pane fade");
				$('#reviews-tab-pane').attr("class", "tab-pane fade");
				
				$('#dashboard-tab').attr("class", "nav-link nav-tab-db");
				$('#clients-tab').attr("class", "nav-link nav-tab-cl active");
				$('#accounts-tab').attr("class", "nav-link nav-tab-ac");
				$('#reveiews-tab').attr("class", "nav-link nav-tab-rv");
				break;
			
			case "#accounts":
				$('#dashboard-tab-pane').attr("class", "tab-pane fade");
				$('#clients-tab-pane').attr("class", "tab-pane fade");
				$('#accounts-tab-pane').attr("class", "tab-pane fade show active");
				$('#reviews-tab-pane').attr("class", "tab-pane fade");
				
				$('#dashboard-tab').attr("class", "nav-link nav-tab-db");
				$('#clients-tab').attr("class", "nav-link nav-tab-cl");
				$('#accounts-tab').attr("class", "nav-link nav-tab-ac active");
				$('#reveiews-tab').attr("class", "nav-link nav-tab-rv");
				break;
			
			case "#reviews":
				$('#dashboard-tab-pane').attr("class", "tab-pane fade");
				$('#clients-tab-pane').attr("class", "tab-pane fade");
				$('#accounts-tab-pane').attr("class", "tab-pane fade");
				$('#reviews-tab-pane').attr("class", "tab-pane fade show active");
				
				$('#dashboard-tab').attr("class", "nav-link nav-tab-db");
				$('#clients-tab').attr("class", "nav-link nav-tab-cl");
				$('#accounts-tab').attr("class", "nav-link nav-tab-ac");
				$('#reveiews-tab').attr("class", "nav-link nav-tab-rv active");
				break;		
				
			default:
				$('#dashboard-tab-pane').attr("class", "tab-pane fade show active");
				$('#clients-tab-pane').attr("class", "tab-pane fade");
				$('#accounts-tab-pane').attr("class", "tab-pane fade");
				$('#reveiews-tab-pane').attr("class", "tab-pane fade");
				
				$('#dashboard-tab').attr("class", "nav-link active nav-tab-db");
				$('#clients-tab').attr("class", "nav-link nav-tab-cl");
				$('#accounts-tab').attr("class", "nav-link nav-tab-ac");
				$('#reveiews-tab').attr("class", "nav-link nav-tab-rv");
				break;
		}
	}
	
	$('.create-form').submit(function(e) {
        e.preventDefault();
        $.ajax({
            type: "POST",
            url: 'php/create-form.php',
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
