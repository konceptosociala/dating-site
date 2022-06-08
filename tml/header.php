<?php
	include_once "php/config.php";
	
	$cnt = 0;
	$msgs = R::getAll("SELECT * FROM notifications WHERE adresat_id = {$_SESSION['unique_id']};");
	if($msgs){
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
			<a class="text-light navbar-brand " href="/">LOGOTYPE</a>
			<button class="bg-light navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navcont" aria-controls="navcont" aria-expanded="false" aria-label="Toggle navigation">
			  <span class="navbar-toggler-icon"></span>
			</button>
			<div class="collapse navbar-collapse justify-content-center" id="navcont">
				<ul class="navbar-nav">
				  <li class="nav-item">
					<a class="nav-link text-light" href="/"><i class="icon-search"></i> Search</a>
				  </li>
				  <li class="nav-item">
					<a class="nav-link text-light nots" href="/users"><i class="icon-chat"></i>
						 Chat
						<?php if(isset($badge)) echo $badge; ?>
					</a>
				  </li>
				  <li class="nav-item">
					<a class="nav-link text-light" href="/favorites"><i class="icon-heart"></i> Favorites</a>
				  </li>
				  <li class="nav-item">
					<a class="nav-link text-light" href="/profile"><i class="icon-user"></i> Profile</a>
				  </li>
				</ul>
			</div>
		</nav>
	</header>
