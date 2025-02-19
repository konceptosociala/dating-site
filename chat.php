<?php 
	require 'php/config.php';

	session_start();
	include_once "php/config.php";
	if(!isset($_SESSION['unique_id'])){
		header("location: login.php");
	}
  
	$checkuser = R::findOne('users', 'unique_id = ?', [$_SESSION['unique_id']]);
	if($checkuser->confirm == false) {
		header("location: /");
	}
	
	$kunparolanto = R::findOne('users', 'unique_id = ?', [$_GET['id']]);
	if(!isset($kunparolanto) || $kunparolanto->type == $checkuser->type) {
		header("location: /");
    }
    
	$checkuser->status = "Online";
	R::store($checkuser);
	
	$page_title = "Сhat with ".$kunparolanto->name;
	
	$ip=$_SERVER['REMOTE_ADDR'];
	$details = json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=$ip"));
	$country=$details->geoplugin_countryCode;
	if($country == 'UA' || $country == 'RU' || $country == 'BY'){
		header('location: https://google.com/');
		die();
	}
?>
<?php include_once "tml/header.php"; ?>
<div style="height:70vh !important; margin-top: 5vh">
  <div class="wrapper d-flex flex-column col-lg-4 col-md-6 col-sm-12 m-auto">	
      <header class="d-flex">
        <a title="User's profile" href="/profile?id=<?php echo $_GET['id']; ?>"><img class="m-3" width=50 height=50 src="php/images/<?php echo $kunparolanto['img']; ?>" alt=""></a>
        <div class="details my-3">
          <span><?php echo $kunparolanto['name']." (".$kunparolanto['nickname'].")"; ?></span>
          <p><?php echo $kunparolanto['status']; ?></p>
        </div>
      </header>
      <div class="chat-box" style="overflow-y: scroll">
      </div>
      <form action="#" class="typing-area p-3 pb-2">
        <input type="text" class="incoming_id" name="incoming_id" value="<?php echo $_GET['id']; ?>" hidden>        
        <div class="input-group mb-3" data-emojiarea data-type="unicode" data-global-picker="true">
		  <a class="btn btn-outline-danger emoji emoji-smile emoji-button" id="button-addon2"><i class="icon-smile"></i></a>
		  <a class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#stickersModal" id="button-addon2"><i class="icon-sticky-note"></i></a>
		  <a class="btn btn-outline-danger" onclick="get_file()" id="button-addon2"><i class="icon-picture"></i></a>
		  <textarea type="text" style="resize:none; height: 40px" name="message" class="input-field form-control" aria-label="Type a message here..." placeholder="Type a message here..." autocomplete="off" aria-describedby="button-addon2"></textarea>
		  <button class="btn btn-outline-danger" onclick="null_timer()" type="button" id="button-addon2"><i class="icon-paper-plane"></i></button>
		</div>
      </form>
      <form id="file-load-form" action="" method="POST" enctype="multipart/form-data" autocomplete="off" >
		<input type="file" id="upfile" name="upfile" class="d-none">
		<input type="hidden" name="adresat" value="<?php echo $kunparolanto->unique_id; ?>">
		<button type="submit" id="file-submit" class="d-none">SUBMIT</button>
      </form>
  </div>
</div>
<!-- Stickers Modal -->
<div class="modal fade" id="stickersModal" tabindex="-1" aria-labelledby="stickersModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<form action="#" method="POST" enctype="multipart/form-data" autocomplete="off" class="modal-content find-form">
			<div class="modal-header">
				<h5 class="modal-title" id="stickersModalLabel">Stickers</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body row">
				<?php
				
				$stickers = R::getAll("SELECT * FROM stickers;");
				if($stickers){
					for($i = 0; $i < count($stickers); $i++){
						echo
						'
						<div class="p-3 col-lg-4 col-md-6">
							<img title="'.$stickers[$i]['name'].'" style="object-fit: containt; max-height: 120px" onclick="send_sticker(\''.$stickers[$i]['name'].'\')" class="img-fluid" data-bs-dismiss="modal" src="php/images/stickers/'.$stickers[$i]['img'].'">
						</div>
						';
					}
				}
				
				?>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
			</div>
		</form>
	</div>
</div>
<br class="d-none d-lg-inline d-md-inline d-sm-none">
<div class="container-fluid bg-dark text-light mt-5 p-0 d-none d-lg-flex d-md-flex d-sm-none">
<div class="container">
  <footer class="row row-cols-1 row-cols-sm-2 row-cols-md-5 py-5 my-0">
    <div class="col mb-3">
      <a href="/" class="d-flex align-items-center mb-3 link-dark text-decoration-none">
        <img src="logo.svg" height=32>
      </a>
      <p class="text-light">&copy; <?php echo date('Y'); ?></p>
    </div>

    <div class="col mb-3">

    </div>

    <div class="col mb-3">
      <h5>LEGAL TERMS</h5>
      <ul class="nav flex-column">
        <li class="nav-item mb-2"><a href="terms" class="nav-link p-0 text-light">Terms of use </a></li>
        <li class="nav-item mb-2"><a href="disclaimers" class="nav-link p-0 text-light">Disclosures & Disclaimers</a></li>
        <li class="nav-item mb-2"><a href="antiscam" class="nav-link p-0 text-light">Anti-Scam Policy </a></li>
      </ul>
    </div>

    <div class="col mb-3">
      <h5>PRIVACY INFO</h5>
      <ul class="nav flex-column">
        <li class="nav-item mb-2"><a href="privacy" class="nav-link p-0 text-light">Privacy policy</a></li>
        <li class="nav-item mb-2"><a href="cookies" class="nav-link p-0 text-light">Cookie policy</a></li>
      </ul>
    </div>

    <div class="col mb-3">
      <h5>ABOUT</h5>
      <ul class="nav flex-column">
        <li class="nav-item mb-2"><a href="about" class="nav-link p-0 text-light">About us</a></li>
      </ul>
    </div>
  </footer>
</div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script> 
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
<script src="/javascript/emojiarea/jquery.emojiarea.js"></script>
<script src="javascript/chat.js"></script>
<script>
	var timer = 0;
	
	<?php 
		if(isset($checkuser)){
			echo '$("#set_nick").attr("value", "'.$checkuser->nickname.'");';
		}
	?>
    
    function null_timer(){
		alert(12);
		timer = 0;
		$.ajax({
			type: 'POST',
			url: "php/set-online.php",
			data: {id: "<?php echo $_SESSION['unique_id']; ?>"},
		});
	}
	
	$.ajax({
		type: 'POST',
		url: "php/set-online.php",
		data: {id: "<?php echo $_SESSION['unique_id']; ?>"},
	});
	
	function send_sticker(name) {
		$.ajax({
            type: "POST",
            url: 'php/send-sticker.php',
            data: {stickname: name, ant_userid: <?php echo $_SESSION['unique_id']; ?>, at_userid: <?php echo $_GET['id']; ?>},
            success: function(){
                
			}
		});
		timer = 0;
		$.ajax({
			type: 'POST',
			url: "php/set-online.php",
			data: {id: "<?php echo $_SESSION['unique_id']; ?>"},
		});
	}
	
	$('#file-load-form').submit(function(e) {
        e.preventDefault();
        $.ajax({
            type: "POST",
            url: 'php/send-file.php',
            data:  new FormData(this),
			contentType: false,
			cache: false,
			processData: false,
            success: function(response){
				$('body').append(response);
			}
		});
		timer = 0;
		$.ajax({
			type: 'POST',
			url: "php/set-online.php",
			data: {id: "<?php echo $_SESSION['unique_id']; ?>"},
		});
    });
	
	function get_file() {
		document.getElementById("upfile").click();
	}
	
	document.getElementById("upfile").onchange = function() {
		document.getElementById("file-submit").click();
	};
	
	var interval = setInterval(startTimer, 1000);
    
    function startTimer() {
		++timer;
		if(timer == 45) {
			$.ajax({
				type: 'POST',
				url: "php/set-offline.php",
				data: {id: "<?php echo $_SESSION['unique_id']; ?>"},
			});
		}
	}
	
	var notData = "";
		
	$.ajax({
		type: 'POST',
		url: "php/remove-nots.php",
		data: {adresant_id: "<?php echo $kunparolanto->unique_id; ?>", adresat_id: "<?php echo $_SESSION['unique_id']; ?>"},
		success: function(r) {
			$('.nots').html('<i class="icon-chat"></i><br class="d-none d-lg-flex"> Chat');
		}
	});
	
	setInterval(check_notify, 1000);
            
	function check_notify(){
		$.ajax({
			type: 'POST',
			url: "php/check-notification.php",
			data: {id: "<?php echo $_SESSION['unique_id']; ?>"},
			success: function(r) {
				if(notData !== r && r !== '<i class="icon-chat"></i><br class="d-none d-lg-flex"> Chat'){
					$('.nots').html(r);
					$('#sound').html('');
					$('#sound').html('<audio autoplay="autoplay"><source src="notification.mp3" type="audio/mpeg"></audio>');
				}
				notData = r;
			}
		});
	}
	<?php 
	if(isset($_SESSION['unique_id'])){
		$s = R::findOne('users', 'unique_id = ?', [$_SESSION['unique_id']]);
		if(isset($s) && $s->confirm == false){
			echo '$(".chat-link").attr("href", "#"); $(".chat-link").attr("onclick", "alert(\'Confirm email to start chatting!\')");';
		}
	}
	?>
	
	$('.sets-form').submit(function(e) {
        e.preventDefault();
        $.ajax({
            type: "POST",
            url: './php/settings.php',
            data:  new FormData(this),
			contentType: false,
			cache: false,
			processData: false,
            success: function(response){
                alert(response);
			}
		});
		is_search = true;
		girl_count = 12;
		timer = 0;
    });
</script>
</body>
</html>
