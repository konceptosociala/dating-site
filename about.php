<?php 
	
	session_start();
	if(!isset($_SESSION['unique_id'])){
		header("location: signup.php");
	}
	
	require 'php/config.php';
	$thisuser = R::findOne('users', 'unique_id = ?', [$_SESSION['unique_id']]);
	$thisuser->status = "Online";
	R::store($thisuser);
  
	$page_title = "About us";
  
	$ip=$_SERVER['REMOTE_ADDR'];
	$details = json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=$ip"));
	$country=$details->geoplugin_countryCode;
	if($country == 'UA' || $country == 'RU' || $country == 'BY'){
		header('location: https://google.com/');
		die();
	}

?>


<?php include_once "tml/header.php"; ?>
<main class="container p-5">
	<center><h1 class="display-2 mb-5">About us</h1></center>
	<h1 class="display-4 my-3">Praesent pellentesque</h1>
	<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc luctus nec est et mattis. Suspendisse elit ipsum, rutrum ut maximus molestie, rhoncus sed urna. In ut neque in mauris pharetra luctus ac et ex. Sed sit amet risus orci. Ut malesuada posuere vulputate. Phasellus eget risus eget arcu varius commodo. Integer luctus lacus ipsum. Cras blandit vehicula ex, a cursus purus laoreet eu. Integer mattis ullamcorper erat. Maecenas vitae fermentum augue, ut convallis nunc. Suspendisse interdum varius velit eu tincidunt. Suspendisse potenti. Vestibulum efficitur ac nisl eget aliquam. Quisque pharetra odio sit amet lobortis finibus. Donec eu metus efficitur, placerat mauris eu, lacinia ligula. </p>
	<h1 class="display-4 my-3">Sed faucibus tellus</h1>
	<p>Duis urna felis, placerat vitae interdum et, auctor id odio. Pellentesque pretium eleifend mauris, porttitor tempus tortor sodales ut. Etiam et justo rhoncus eros consequat pellentesque. Quisque placerat, mi imperdiet mattis egestas, dolor ante facilisis enim, pretium convallis nisl leo tristique turpis. Morbi et vestibulum velit. Integer elementum nulla et purus facilisis, eget convallis purus varius. Maecenas at metus at ex eleifend facilisis. Ut lectus erat, congue egestas felis sit amet, lacinia elementum justo. Donec placerat sem sit amet tempor auctor. Maecenas at enim semper, tempus velit vitae, dignissim justo. Aenean luctus accumsan sagittis. </p>
</main>
<footer class="bg-dark d-flex justify-content-center align-items-center mt-5">
	<p class="text-light m-3">Dating Website | <a class="text-light" href=about>About us</a></p>
</footer>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script> 
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
<?php include 'tml/offline.php'; ?>
</body>
</html>
