<?php 
	session_start();
	if(!isset($_SESSION['unique_id'])){
		header("location: signup.php");
	}
	
	$page_title = "Admin Panel";
  
	require 'php/config.php';
	
	$check_root = R::findOne('users', 'nickname = "root"');
	if($check_root->unique_id != $_SESSION['unique_id']) {
		header("location: /");
	}
	
	$check_root->status = "Online";
	R::store($check_root);
	
	$ip=$_SERVER['REMOTE_ADDR'];
	$details = json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=$ip"));
	$country=$details->geoplugin_countryCode;
	if($country == 'UA' || $country == 'RU' || $country == 'BY'){
		header('location: https://google.com/');
		die();
	}
	
?>


<?php include_once "tml/noheader.php"; ?>
<section class="row">
    <header class="col-5 col-lg-3 col-md-4 col-sm-5">
		<div class="p-4 bg-light">
			<a href="/admin" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto link-dark text-decoration-none">
				<span class="fs-4"><i class="icon-wrench"></i> Admin Panel</span>
			</a>
			<hr>
			<ul class="nav nav-pills flex-column" style="height: 100vh !important">
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
					<a href="#stickers" class="nav-link nav-tab-st" id="reviews-tab" data-bs-toggle="tab" data-bs-target="#stickers-tab-pane" type="button" role="tab" aria-controls="stickers-tab-pane" aria-selected="true">
						<i class="icon-sticky-note"></i> Stickers
					</a>
				</li> 
			</ul>
		</div>
    </header>

	<main class="col-7 col-lg-9 col-md-8 col-sm-7">
		<div class="tab-content container-fluid" id="myTabContent">
			<div class="tab-pane fade" id="clients-tab-pane" role="tabpanel" aria-labelledby="clients-tab" tabindex="0">
				<h1 class="display-4 text-center m-3">Clients</h1>
				<form class="search-client-form input-group mb-3">
					<span class="input-group-text" id="basic-addon1">Search</span>
					<input type="text" class="form-control" name="search-client" placeholder="ID or Username" aria-label="Username" aria-describedby="basic-addon1">
				</form>
				<div class="row clients-row">
					<?php
					
					$girls = R::getAll("SELECT * FROM users WHERE type = 'male' LIMIT 0, 12");
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
						<div class="col-lg-4 col-md-6 col-sm-12 my-3 client-card">
							<div class="card mx-2">
								<div class="card-body">
									<div class="d-flex"><h5 class="card-title">'.$acc['name'].', '.$diff->y.'</h5></div>
									'.$acc['nickname'].'
								</div>
								<a title="View profile of '.$acc['name'].'" href="profile?id='.$acc['unique_id'].'"><div class="card-field" style="background-image: url(php/images/'.$acc['img'].'); border-radius: 0">
									&nbsp;
								</div></a>
								<div class="d-flex">
									<input disabled class="form-control" value="'.$acc['email'].'" style=" border-radius: 0 0 5px 5px">
								</div>
							</div>
						</div>
						';
					}
					
					?>
				</div>
				<center><button class="btn btn-primary m-3" id="show-more-clients">Show more</button></center>
			</div>
			<div class="tab-pane fade" id="accounts-tab-pane" role="tabpanel" aria-labelledby="accounts-tab" tabindex="1">
				<h1 class="display-4 text-center m-3">Female accounts</h1>
				<center><button class="btn btn-info btn-lg mb-4" data-bs-toggle="modal" data-bs-target="#searchModal"><i class="icon-search"></i> Search</button>
				<button class="btn btn-success btn-lg mb-4" data-bs-toggle="modal" data-bs-target="#createModal">+ Create</button></center>
				<div class="row girl-cards-div">
						
						<?php
						
							$girls = R::getAll("SELECT * FROM users WHERE type = 'female' LIMIT 12;");
							if(empty($girls)) echo "<center><h3>You haven't created any accounts yet</h3></center>";
							for($i = 0; $i < count($girls); $i++) {	
								$acc = $girls[$i];					
								$prof = R::findOne('profiles', 'user_id = ?', [$acc['unique_id']]);
								$d1 = new DateTime(date('y-m-d'));
								$d2 = new DateTime($prof->birthday);

								$diff = $d2->diff($d1);	
														
								echo 
								'
								<div id="remove'.$acc['unique_id'].'" class="col-lg-4 col-md-6 col-sm-12 my-3 girl-card">
									<div class="card mx-2">
										<div class="card-body">
											<div class="d-flex"><h5 class="card-title">'.$acc['name'].', '.$diff->y.'</h5><button onclick="remove_user('.$acc['unique_id'].')" class="btn btn-close ms-auto"></button></div>
											<i>'.$acc['nickname'].'</i>
										</div>
										<a title="View profile of '.$acc['name'].'" href="profile?id='.$acc['unique_id'].'"><div class="card-field" style="border-radius: 0; background-image: url(php/images/'.$acc['img'].')">
											
										</div></a>
										<div class="d-flex">
											<button onclick="editor_id('.$acc['unique_id'].')" data-bs-toggle="modal" data-bs-target="#editModal" class="btn btn-warning flex-fill" style="border-radius: 0 0 0 5px">Edit</button>
											<button onclick="use_id('.$acc['unique_id'].')" data-bs-toggle="modal" data-bs-target="#useModal" class="btn btn-success flex-fill" style="border-radius: 0 0 5px 0">Use</button>
										</div>
									</div>
								</div>
								';
							}	
						
						?>						
				</div>
				<center><button class="btn btn-primary m-3" id="show-more">Show more</button></center>
			</div>
			<div class="tab-pane fade" id="stickers-tab-pane" role="tabpanel" aria-labelledby="reviews-tab" tabindex="2">
				<h1 class="display-4 text-center m-3">Stickers</h1>
				<center><button class="btn btn-success btn-lg mb-4" data-bs-toggle="modal" data-bs-target="#stickersModal">+ Add</button></center>
				<div class="row stickers-div">
						<?php
						
							$sticks = R::getAll("SELECT * FROM stickers");
							if(empty($sticks)) echo "<center><h3>Stickers not found!</h3></center>";
							for($i = 0; $i < count($sticks); $i++) {	
								$stick = $sticks[$i];					
														
								echo 
								'
								<div id="remove-stick-'.$stick['name'].'" class="col-lg-4 col-md-6 col-sm-12 my-3 girl-card">
									<div class="card mx-2">
										<div class="card-body">
											<div class="d-flex"><h5 class="card-title">'.$stick['name'].'</h5><button onclick="remove_sticker(\''.$stick['name'].'\')" class="btn btn-close ms-auto"></button></div>
										</div>
										<div class="card-field p-3" style="border-radius: 0;">
											<center><img style="object-fit: containt; max-height: 275px !important" src="php/images/stickers/'.$stick['img'].'" class="img-fluid"></center>
										</div>
									</div>
								</div>
								';
							}	
						
						?>
				</div>
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
									<option selected></option>
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
								<span class="input-group-text" id="basic-addon1">Avatar</span>
								<input type="file" name="photos" class="form-control" accept="image/x-png,image/gif,image/jpeg,image/jpg" aria-describedby="basic-addon1" required>
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
				<form action="#" method="POST" enctype="multipart/form-data" autocomplete="off" class="modal-content edit-user-form">
					<div class="modal-header">
						<h5 class="modal-title" id="editModalLabel">Edit the account</h5>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
					</div>
					<div class="modal-body">
						<div>
							<div class="input-group mb-3 field input">
								<input id="f-hidden-id" name="hidden-id" value="" hidden>
								<span class="input-group-text" id="basic-addon1">Name</span>
								<input id="f-name" type="text" name="name" class="form-control" placeholder="Enter a name" aria-label="First name" value="" aria-describedby="basic-addon1" required>
								<span class="input-group-text" id="basic-addon1">Nickname</span>
								<input id="f-nickname" type="text" name="nickname" class="form-control" placeholder="Enter a nickname" value="" aria-label="Last name" aria-describedby="basic-addon1" required>
							</div>
							<div class="input-group mb-3 field input">
								<span class="input-group-text" id="basic-addon1">Birthdate</span>
								<input id="f-birthday" type="date" name="birthday" class="form-control" placeholder="Enter your email" value="" aria-label="Email" aria-describedby="basic-addon1" required>
							</div>
							<div class="input-group mb-3 field input">
								<span class="input-group-text" id="basic-addon1">Country</span>
								<select id="f-country" name="country" class="form-control" aria-label="Country" aria-describedby="basic-addon1" required>
									<option disabled></option>
									<option disabled>-- Select country --</option>
								</select>
							</div>
							<div class="input-group mb-3 field input">
								<span class="input-group-text" id="basic-addon1">Hair color</span>
								<select name="haircolor" class="form-control" aria-label="Hair color" aria-describedby="basic-addon1">
									<option id="f-haircolor" disabled selected></option>
									<option disabled>-- Select color --</option>
									<option>Blonde</option>
									<option>Brunette</option>
									<option>Red</option>
									<option>Black</option>
								</select>
								<span class="input-group-text" id="basic-addon1">Marital</span>
								<select name="marital" class="form-control" aria-label="Marital" aria-describedby="basic-addon1">
									<option id="f-marital" disabled selected></option>
									<option disabled>-- Select status --</option>
									<option>Single</option>
									<option>Married</option>
									<option>Widowed</option>
									<option>Divorced</option>
									<option>In Active</option>
								</select>
							</div>
							<div class="input-group mb-3 field image">
								<span class="input-group-text" id="basic-addon1">Avatar</span>
								<input type="file" name="avatar" id="f-avatar-in" class="form-control" accept="image/x-png,image/gif,image/jpeg,image/jpg" aria-describedby="basic-addon1">
							</div>
							<label class="">Photos</label>
							<div class="row p-2 my-2 photos-row">

							</div>
							<div class="input-group mb-3 field image">
								<span class="input-group-text" id="basic-addon1">Photos</span>
								<input type="file" name="photos[]" id="f-photos-in" class="form-control" accept="image/x-png,image/gif,image/jpeg,image/jpg" aria-describedby="basic-addon1" multiple>
							</div>
							<div class="input-group mb-3 field image">
								<span class="input-group-text" id="basic-addon1">About</span>
								<textarea id="f-about" name="about" placeholder="Describe a person" class="form-control" aria-describedby="basic-addon1"></textarea>
							</div>
							<div class="input-group mb-3 field image">
								<span class="input-group-text" id="basic-addon1">Wishes</span>
								<textarea id="f-wishes" name="wishes" placeholder="Describe personal wishes" class="form-control" aria-describedby="basic-addon1"></textarea>
							</div>
							<label>Online</label>
							<input type="checkbox" name="is-online" class="" aria-describedby="basic-addon1">
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
						<button type="submit" class="btn btn-primary">Save changes</button>
					</div>
				</form>
			</div>
		</div>
		<!-- Use Modal -->
		<div class="modal fade" id="useModal" tabindex="-1" aria-labelledby="useModalLabel" aria-hidden="true">
		  <div class="modal-dialog">
			<div class="modal-content">
			  <div class="modal-header">
				<h5 class="modal-title" id="useModalLabel">Use account</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			  </div>
			  <div class="modal-body">
				<div class="input-group mb-3 field input">
					<span class="input-group-text" id="basic-addon1">Email</span>
					<input type=text id="acc_email" class="form-control" disabled aria-label="Email" aria-describedby="basic-addon1" required>
				</div>
				<div class="input-group mb-3 field input">
					<span class="input-group-text" id="basic-addon1">Password</span>
					<input type=text id="acc_pswd" class="form-control" disabled aria-label="Email" aria-describedby="basic-addon1" required>
				</div>
			  </div>
			  <div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
			  </div>
			</div>
		  </div>
		</div>
		<!-- Search Modal -->
		<div class="modal fade" id="searchModal" tabindex="-1" aria-labelledby="searchModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<form action="#" method="POST" enctype="multipart/form-data" autocomplete="off" class="modal-content find-form">
					<div class="modal-header">
						<h5 class="modal-title" id="searchModalLabel">Search</h5>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
					</div>
					<div class="modal-body">
						<div>
							<input type="text" name="search-field" class="form-control" placeholder="Enter the nickname or ID" aria-label="First name" aria-describedby="basic-addon1" required>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
						<button type="submit" class="btn btn-success">Search</button>
					</div>
				</form>
			</div>
		</div>
		<!-- Stickers Modal -->
		<div class="modal fade" id="stickersModal" tabindex="-1" aria-labelledby="stickersModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<form action="#" method="POST" enctype="multipart/form-data" autocomplete="off" class="modal-content sticker-form">
					<div class="modal-header">
						<h5 class="modal-title" id="stickersModalLabel">Stickers</h5>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
					</div>
					<div class="modal-body">
						<div class="input-group mb-3 field input">
							<span class="input-group-text" id="basic-addon1">Name</span>
							<input type=text name="sticker-name" class="form-control" aria-label="Name" aria-describedby="basic-addon1" required>
						</div>
						<div class="input-group mb-3 field input">
							<span class="input-group-text" id="basic-addon1">Image</span>
							<input type=file name="sticker-file" class="form-control" aria-label="Image" aria-describedby="basic-addon1" required>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
						<button type="submit" class="btn btn-success">Add</button>
					</div>
				</form>
			</div>
		</div>
	</main>
</section>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script> 
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/feather-icons@4.28.0/dist/feather.min.js" integrity="sha384-uO3SXW5IuS1ZpFPKugNNWqTZRRglnUJK6UAZ/gxOX80nxEkN9NcGZTftn6RzhGWE" crossorigin="anonymous"></script>
<script>
	const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
	const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))
	process_hash();
	
	$('.nav-tab-cl').click(function() {
		window.location.hash = "clients";
	});
	
	$('.nav-tab-ac').click(function() {
		window.location.hash = "accounts";
	});
	
	$('.nav-tab-st').click(function() {
		window.location.hash = "stickers";
	});
	
	$(window).bind( 'hashchange', function(e) {
		process_hash();
	});	
		
	function process_hash() {
		switch(window.location.hash) {			
			default:
				$('#clients-tab-pane').attr("class", "tab-pane fade show active");
				$('#accounts-tab-pane').attr("class", "tab-pane fade");
				$('#stickers-tab-pane').attr("class", "tab-pane fade");
				
				$('#clients-tab').attr("class", "nav-link nav-tab-cl active");
				$('#accounts-tab').attr("class", "nav-link nav-tab-ac");
				$('#stickers-tab').attr("class", "nav-link nav-tab-st");
				break;
			
			case "#accounts":
				$('#clients-tab-pane').attr("class", "tab-pane fade");
				$('#accounts-tab-pane').attr("class", "tab-pane fade show active");
				$('#stickers-tab-pane').attr("class", "tab-pane fade");
				
				$('#clients-tab').attr("class", "nav-link nav-tab-cl");
				$('#accounts-tab').attr("class", "nav-link nav-tab-ac active");
				$('#stickers-tab').attr("class", "nav-link nav-tab-st");
				break;
			
			case "#stickers":
				$('#clients-tab-pane').attr("class", "tab-pane fade");
				$('#accounts-tab-pane').attr("class", "tab-pane fade");
				$('#stickers-tab-pane').attr("class", "tab-pane fade show active");
				
				$('#clients-tab').attr("class", "nav-link nav-tab-cl");
				$('#accounts-tab').attr("class", "nav-link nav-tab-ac");
				$('#stickers-tab').attr("class", "nav-link nav-tab-st active");
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
                $('.girl-cards-div').append(response);
			}
		});
    });
    
	$('.sticker-form').submit(function(e) {
        e.preventDefault();
        $.ajax({
            type: "POST",
            url: 'php/sticker-form.php',
            data:  new FormData(this),
			contentType: false,
			cache: false,
			processData: false,
            success: function(response){
                $('.stickers-div').append(response);
			}
		});
    });
	
	$('.find-form').submit(function(e) {
        e.preventDefault();
        $.ajax({
            type: "POST",
            url: 'php/search-user.php',
            data:  new FormData(this),
			contentType: false,
			cache: false,
			processData: false,
            success: function(response){
                if(response == '') {
					alert("User is not found!");
				} else {
					$('.girl-card').remove();
					$('.girl-cards-div').append(response);
					$('#show-more').hide();
				}
			}
		});
    });
    
	$('.edit-user-form').submit(function(e) {
        e.preventDefault();
        $.ajax({
            type: "POST",
            url: 'php/edit-user.php',
            data:  new FormData(this),
			contentType: false,
			cache: false,
			processData: false,
            success: function(response){
                $('.photos-row').append(response); 
			}
		});
    });
    
    function remove_user(id) {
		$.ajax({
            type: "POST",
            url: 'php/remove-user.php',
            data: {id: id},
            success: function(response){
                alert(response);
                $('#remove' + id).remove();
			}
		});
	}
	
	function editor_id(id) {
		$.ajax({
            type: "POST",
            url: 'php/get-edit-user.php',
            data: {id: id},
            success: function(response){
                var data = JSON.parse(response);                
                $('#f-hidden-id').attr("value", data.hidden_id);
                $('#f-name').attr("value", data.name);
                $('#f-nickname').attr("value", data.nickname);
                $('#f-birthday').attr("value", data.birthday);
                $('#f-country').html(data.country);
                $('#f-haircolor').html(data.haircolor);
                $('#f-marital').html(data.marital);
                $('#f-about').html(data.about);
                $('#f-wishes').html(data.wishes);
                $('.photos-row').html(data.photo_div);
                
                $('#f-photos-in').attr("type", "text");
                $('#f-photos-in').attr("type", "file");
                $('#f-avatar-in').attr("type", "text");
                $('#f-avatar-in').attr("type", "file");
			}
		});
	}
	
	function remove_photo(id) {
		$.ajax({
            type: "POST",
            url: 'php/remove-photo.php',
            data: {photo_id: id},
            success: function(response){
                alert(response);
                $('#photo-' + id).remove();
			}
		});
	}
	
	function use_id(id) {
		$.ajax({
            type: "POST",
            url: 'php/user-cred.php',
            data: {id: id},
            success: function(response){
                var data = JSON.parse(response);
                $('#acc_email').attr("value", data.email);
                $('#acc_pswd').attr("value", data.pswd);
			},
			error: function() {
				alert('Error!');
			}
		});
	}
	
	function remove_sticker(name) {
		$.ajax({
            type: "POST",
            url: 'php/remove-sticker.php',
            data: {stickname: name},
            success: function(response){
                alert(response);
                $('#remove-stick-' + name).remove();
			}
		});
	}
	
	var girl_count = 12;
	var client_count = 12;
	
	$('#show-more').click(function() {
		$.ajax({
			type: "POST",
			url: 'php/admin-load-more.php',
			data:  {from: girl_count},
			success: function(response){
				$('.girl-cards-div').append(response);
			}
		});
		girl_count += 12;
		timer = 0;
		$.ajax({
			type: 'POST',
			url: "php/set-online.php",
			data: {id: "<?php echo $_SESSION['unique_id']; ?>"},
		});	
    });
    
	$('#show-more-clients').click(function() {
		$.ajax({
			type: "POST",
			url: 'php/load-more-clients.php',
			data:  {from: client_count},
			success: function(response){
				$('.clients-row').append(response);
			}
		});
		client_count += 12;
		timer = 0;
		$.ajax({
			type: 'POST',
			url: "php/set-online.php",
			data: {id: "<?php echo $_SESSION['unique_id']; ?>"},
		});	
    });

	$('.search-client-form').submit(function(e) {
        e.preventDefault();
        $.ajax({
            type: "POST",
            url: 'php/search-client.php',
            data:  new FormData(this),
			contentType: false,
			cache: false,
			processData: false,
            success: function(response){
                if(response == '') {
					alert("User is not found!");
				} else {
					$('.client-card').remove();
					$('.clients-row').append(response);
					$('#show-more-clients').hide();
				}
			}
		});
    });

</script>
</body>
</html>
