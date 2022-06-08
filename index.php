<?php 
  session_start();
  if(!isset($_SESSION['unique_id'])){
    header("location: signup.php");
  }
	
  require 'php/config.php';
  $thisuser = R::findOne('users', 'unique_id = ?', [$_SESSION['unique_id']]);
  $thisuser->status = "Online";
  R::store($thisuser);
  
  $page_title = "Find your love!";

?>


<?php include_once "tml/header.php"; ?>

<div class="bg-light container-fluid mx-auto">
	<div class="row p-5 background-header">
		<h1 style="text-shadow:0 0px 5px white" class="text-light col-md-6 col-sm-12 my-md-auto my-sm-5 display-4 text-center animate__animated animate__flash">Find your love!</h1>
		<div class="col-md-6 col-sm-12 my-sm-5">
			<form id="search-girls" class="container">
				<div class="input-group mb-3">
				  <span class="input-group-text sbg-bg border-bg text-white" id="basic-addon1">User ID</span>
				  <input name="id" type="text" class="form-control" placeholder="ID" aria-label="ID" aria-describedby="basic-addon1">
				</div>
				<div class="input-group mb-3 col-1">
				  <span class="input-group-text sbg-bg border-bg text-white" id="basic-addon1">Country</span>
				  <select name="country" class="form-control" aria-label="Country" aria-describedby="basic-addon1">
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
				  <select name="haircolor" class="form-control" aria-label="Country" aria-describedby="basic-addon1">
					<option selected disabled>-- Select color --</option>
					<option>Blonde</option>
					<option>Brunette</option>
					<option>Red</option>
					<option>Black</option>
				  </select>
				</div>
				<div class="input-group mb-3">
				  <span class="input-group-text sbg-bg border-bg text-light">Age</span>
				  <input name="age-from" type="number" class="form-control" placeholder="From" aria-label="From">
				  <span class="input-group-text sbg-bg border-bg text-light">↔</span>
				  <input name="age-to" type="number" class="form-control" placeholder="To" aria-label="To">
				</div>
				<div class="d-flex"><button class="btn btn-light mx-auto submit-search" type="submit">Search</button></div>
			</form>
		</div>
	</div>
	<div class="container mt-3">
		<div class="row girls-row">
			<?php
						
				$girls = R::getAll("SELECT * FROM users WHERE type = 'female' LIMIT 0, 3");
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
		<center><button class="btn btn-primary m-3" id="show-more">Show more</button></center>
	</div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script> 
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
<script>
	const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
	const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))
	
	var girl_count = 3;
	var is_search = false;
	
	$('#search-girls').submit(function(e) {
        e.preventDefault();
        $.ajax({
            type: "POST",
            url: 'php/search.php',
            data:  new FormData(this),
			contentType: false,
			cache: false,
			processData: false,
            success: function(response){
                $('.girls-row').html(response);
			}
		});
		is_search = true;
		girl_count = 3;
		timer = 0;
		$.ajax({
			type: 'POST',
			url: "php/set-online.php",
			data: {id: "<?php echo $_SESSION['unique_id']; ?>"},
		});
    });
    
    $('#show-more').click(function() {
		if(is_search == false){
			$.ajax({
				type: "POST",
				url: 'php/load-more.php',
				data:  {from: girl_count},
				success: function(response){
					$('.girls-row').append(response);
				}
			});
			girl_count += 3;
			timer = 0;
			$.ajax({
				type: 'POST',
				url: "php/set-online.php",
				data: {id: "<?php echo $_SESSION['unique_id']; ?>"},
			});
		} else {
			$.ajax({
				type: "POST",
				url: 'php/load-more-search.php',
				data:  {from: girl_count, key: $('.key').attr("value")},
				success: function(response){
					$('.girls-row').append(response);
				}
			});
			girl_count += 3;
			timer = 0;
			$.ajax({
				type: 'POST',
				url: "php/set-online.php",
				data: {id: "<?php echo $_SESSION['unique_id']; ?>"},
			});
		}
		
		
    });
    
    var timer = 0;
    var interval = setInterval(startTimer, 1000);
    
    function startTimer() {
		++timer;
		if(timer == 120) {
			clearInterval(interval);
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
