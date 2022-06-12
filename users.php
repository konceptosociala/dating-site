<?php 
  session_start();
  include_once "php/config.php";
  if(!isset($_SESSION['unique_id'])){
    header("location: login.php");
  }
  
  $checkmail = R::findOne('users', 'unique_id = ?', [$_SESSION['unique_id']]);
  if($checkmail->confirm == false) {
	header("location: /");
  }
  
  $thisuser = R::findOne('users', 'unique_id = ?', [$_SESSION['unique_id']]);
  $thisuser->status = "Online";
  R::store($thisuser);
  
  $page_title = "Active chats";
  
  $ip=$_SERVER['REMOTE_ADDR'];
	$details = json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=$ip"));
	$country=$details->geoplugin_countryCode;
	if($country == 'UA' || $country == 'RU' || $country == 'BY'){
		header('location: https://google.com/');
		die();
	}
?>
<?php include_once "tml/header.php"; ?>
<style>body {overflow-x: hidden}</style>
<div class="row" style="height:80% !important;">
  <div class="wrapper container my-auto col-lg-6 col-md-8 col-sm-12">
    <section class="users">
      <header>
        <div class="content">
          <?php            
            $row = R::findOne('users', 'unique_id = ?', [ $_SESSION['unique_id'] ]);
          ?>
          <img src="php/images/<?php echo $row['img']; ?>" alt="">
          <div class="details">
            <span><?php echo $row['fname']. " " . $row['lname'] ?></span>
            <p><?php echo $row['status']; ?></p>
          </div>
        </div>
        <a href="php/logout.php?logout_id=<?php echo $row['unique_id']; ?>" class="logout">Logout</a>
      </header>
      <div class="search">
        <span class="text">Select an user to start chat</span>
        <input type="text" disabled hidden placeholder="Enter name to search...">
      </div>
      <div class="users-list">
  
      </div>
    </section>
  </div>
  </div>
<div class="container-fluid bg-dark text-light m-0 p-0">
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
  <script src="javascript/users.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
  <script>
	var timer = 0;
    var interval = setInterval(startTimer, 1000);
    
    <?php 
		if(isset($thisuser)){
			echo '$("#set_nick").attr("value", "'.$thisuser->nickname.'");';
		}
	?>
    
    $.ajax({
			type: 'POST',
			url: "php/set-online.php",
			data: {id: "<?php echo $_SESSION['unique_id']; ?>"},
		});
    
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
