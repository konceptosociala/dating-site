<?php 
  session_start();
  if(!isset($_SESSION['unique_id'])){
    header("location: signup.php");
  }
	
  $page_title = "Admin Panel";
	
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
					<a href="#dashboard" class="nav-link active nav-tab-db" id="dashboard-tab" data-bs-toggle="tab" data-bs-target="#dashboard-tab-pane" type="button" role="tab" aria-controls="dashboard-tab-pane" aria-selected="true">
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
			<div class="tab-pane fade show active" id="dashboard-tab-pane" role="tabpanel" aria-labelledby="dashboard-tab" tabindex="0">
				<h1 class="display-4 text-center m-3">Visitance</h1>
				<canvas class="my-4 w-100 chartjs-render-monitor" id="myChart" style="display: block; width: 1090px; height: 460px;" width="1090" height="460"></canvas>
			</div>
			<div class="tab-pane fade" id="clients-tab-pane" role="tabpanel" aria-labelledby="clients-tab" tabindex="1">
				<h1 class="display-4 text-center m-3">Clients</h1>
			</div>
			<div class="tab-pane fade" id="accounts-tab-pane" role="tabpanel" aria-labelledby="accounts-tab" tabindex="2">
				<h1 class="display-4 text-center m-3">Female accounts</h1>
				<div class="row">
					<div class="col-lg-6 col-md-12">
						<div class="input-group mb-3">
						    <span class="input-group-text" id="basic-addon1">Oleksandra, 21</span>
							<button class="btn btn-warning">Edit</button>
						</div>
						<div class="input-group mb-3">
						    <span class="input-group-text" id="basic-addon1">Oleksandra, 21</span>
							<button class="btn btn-warning">Edit</button>
						</div>
					</div>
				</div>
			</div>
			<div class="tab-pane fade" id="reviews-tab-pane" role="tabpanel" aria-labelledby="reviews-tab" tabindex="3">
				4
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
			case "#dashboard":
				$('#dashboard-tab-pane').attr("class", "tab-pane fade show active");
				$('#clients-tab-pane').attr("class", "tab-pane fade");
				$('#accounts-tab-pane').attr("class", "tab-pane fade");
				$('#reveiews-tab-pane').attr("class", "tab-pane fade");
				
				$('#dashboard-tab').attr("class", "nav-link active nav-tab-db");
				$('#clients-tab').attr("class", "nav-link nav-tab-cl");
				$('#accounts-tab').attr("class", "nav-link nav-tab-ac");
				$('#reveiews-tab').attr("class", "nav-link nav-tab-rv");
				break;
			
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
		}
	}

</script>
</body>
</html>
