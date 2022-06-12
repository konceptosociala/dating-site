<?php
	include_once "php/config.php";
	
	$cnt = 0;
	if(isset($_SESSION['unique_id'])) $msgs = R::getAll("SELECT * FROM notifications WHERE adresat_id = {$_SESSION['unique_id']};");
	if(isset($msgs)){
		while($cnt < count($msgs)){
			$cnt++;
		}
	}
	
	if($cnt > 0) $badge = '<span class="badge rounded-pill bg-danger">'.$cnt.'<span class="visually-hidden">unread messages</span></span>';
?>

<!DOCTYPE html>
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta http-equiv="x-ua-compatible" content="ie=edge" />
    <title>Datings <?php if(isset($page_title)) {echo " | ".$page_title;} ?></title>
    <link rel="stylesheet" href="/style.css">
    <link rel="stylesheet" href="/css/style.css" type="text/css">
	<link rel="stylesheet" href="/css/fontello-embedded.css" type="text/css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
  </head>
  <body style="height: 100vh !important">
	<div class="d-none" id="sound"></div>
    <header class="sticky-top m-0">
		<nav class="sbg-nav navbar navbar-expand-lg p-3 m-0">
			<a class="text-light navbar-brand " href="/"><img height=50 src="/logo.svg"></a>
			<button class="bg-light navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navcont" aria-controls="navcont" aria-expanded="false" aria-label="Toggle navigation">
			  <span class="navbar-toggler-icon"></span>
			</button>
			<div class="collapse navbar-collapse justify-content-center" id="navcont">
				<ul class="navbar-nav">
				  <li class="nav-item">
					<a class="nav-link text-light text-left text-lg-center text-sm-left mx-2" href="/"><i class="icon-search"></i><br class="d-none d-lg-flex"> Search</a>
				  </li>
				  <li class="nav-item">
					<a class="nav-link text-light nots chat-link text-left text-lg-center text-sm-left mx-2" href="/users"><i class="icon-chat"></i>
						<br class="d-none d-lg-flex"> Chat
						<?php if(isset($badge)) echo $badge; ?>
					</a>
				  </li>
				  <li class="nav-item">
					<a class="nav-link text-light text-left text-lg-center text-sm-left mx-2" href="/favorites"><i class="icon-heart"></i><br class="d-none d-lg-flex"> Favorites</a>
				  </li>
				  <?php if(isset($_SESSION['unique_id'])): ?>
				  <li class="nav-item dropdown">
				    <a class="nav-link text-light text-left text-lg-center text-sm-left mx-2 dropdown-toggle pr-ok" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
					  <i class="icon-user"></i><br class="d-none d-lg-flex"> Profile
				    </a>
				    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
					  <li><a class="dropdown-item" href="/profile">View profile</a></li>
					  <li><a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#setModal" href="#">Settings</a></li>
  					  <li><hr class="dropdown-divider"></li>
					  <li><a class="dropdown-item" href="/php/logout">Logout</a></li>
				    </ul>
				  </li>
				  <?php else: ?>
				  <li class="nav-item">
					<a class="nav-link text-light pr-log text-left text-lg-center text-sm-left mx-2" href="/login"><i class="icon-user"><br class="d-none d-lg-flex"> </i>Profile</a>
				  </li>
				  <?php endif ?>
				</ul>
			</div>
		</nav>
	</header>
	<div class="modal fade" id="setModal" tabindex="-1" aria-labelledby="setModalLabel" aria-hidden="true">
		  <div class="modal-dialog">
			<form action="#" method="POST" enctype="multipart/form-data" autocomplete="off" class="modal-content sets-form">
			  <div class="modal-header">
				<h5 class="modal-title" id="setModalLabel">Settings</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			  </div>
			  <div class="modal-body">
				<div class="input-group mb-3 field input">
					<span class="input-group-text" id="basic-addon1">Nickname</span>
					<input type=text id="set_nick" name="set_nick" class="form-control" aria-label="Nick" aria-describedby="basic-addon1" required>
				</div>
				<div class="input-group mb-3 field input">
					<span class="input-group-text" id="basic-addon1">Old password</span>
					<input type=password id="set_pswd" name="set_pswd" class="form-control" aria-label="Pass" aria-describedby="basic-addon1" required>
				</div>
				<div class="input-group mb-3 field input">
					<span class="input-group-text" id="basic-addon1">New password</span>
					<input type=password id="set_new_pswd" name="set_new_pswd" class="form-control" aria-label="Pass" aria-describedby="basic-addon1" required>
				</div>
			  </div>
			  <div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
				<button type="submit" class="btn btn-success">Submit</button>
			  </div>
			</form>
		  </div>
		</div>
