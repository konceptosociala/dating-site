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
<div class="row" style="height:80% !important">
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
  
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script> 
  <script src="javascript/users.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
  <script>
	var timer = 0;
    var interval = setInterval(startTimer, 1000);
    
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
