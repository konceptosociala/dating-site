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
  <body style="height: 100% !important">
	<?php if($page_title != "Admin Panel"): ?>
	<header class="fixed-top m-0">
		<nav class="sbg-nav navbar navbar-expand-lg p-3 m-0">
			<a class="text-light navbar-brand " href="/"><img height=50 src="/logo.svg"></a>
			<button class="bg-light navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navcont" aria-controls="navcont" aria-expanded="false" aria-label="Toggle navigation">
			  <span class="navbar-toggler-icon"></span>
			</button>
		</nav>
	</header>
	<?php endif ?>
