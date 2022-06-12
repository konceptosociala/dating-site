<?php 
  session_start();
  if(isset($_SESSION['unique_id'])){
    header("location: /");
  }
  
  $page_title = "Sign Up";
  
  $ip=$_SERVER['REMOTE_ADDR'];
	$details = json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=$ip"));
	$country=$details->geoplugin_countryCode;
	if($country == 'UA' || $country == 'RU' || $country == 'BY'){
		header('location: https://google.com/');
		die();
	}
?>

<?php include_once "tml/noheader.php"; ?>
  <main class="container mt-5 vh-100 d-lg-flex align-items-center justify-content-center">
	  <h1 class="text-center m-5">Sign up to start chatting!</h1>
	  <div class="wrapper p-3">
		<section class="form signup p-3">
		  <form action="#" method="POST" enctype="multipart/form-data" autocomplete="off">
			  <h2 class="text-center pb-2">Registration</h2>	  
			  <div class="error-text text-center pb-2 text-danger"></div>
			  <div class="input-group mb-3 field input">
			    <span class="input-group-text" id="basic-addon1">Name</span>
			    <input type="text" name="name" class="form-control" placeholder="Enter your name" aria-label="First name" aria-describedby="basic-addon1" required>
				<span class="input-group-text" id="basic-addon1">Nickname</span>
			    <input type="text" name="nickname" class="form-control" placeholder="Enter your nickname" aria-label="Last name" aria-describedby="basic-addon1" required>
			  </div>
			  <div class="input-group mb-3 field input">
			    <span class="input-group-text" id="basic-addon1">Email</span>
			    <input type="email" name="email" class="form-control" placeholder="Enter your email" aria-label="Email" aria-describedby="basic-addon1" required>
			  </div>
			  <div class="input-group mb-3 field input">
			    <span class="input-group-text" id="basic-addon1">Birthdate</span>
			    <input type="date" name="birthday" class="form-control" placeholder="Enter your email" aria-label="Email" aria-describedby="basic-addon1" required>
			  </div>
			  <div class="input-group mb-3 field input">
			    <span class="input-group-text" id="basic-addon1">Country</span>
				<select name="country" class="form-control" aria-label="Country" aria-describedby="basic-addon1" required>
				  <option disabled selected></option>
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
			    <span class="input-group-text" id="basic-addon1">Password</span>
			    <input type="password" name="password" class="form-control" placeholder="Enter new password" aria-label="Email" aria-describedby="basic-addon1" required>
				<span style="cursor:pointer" title="Show/hide password" class="toggle-password input-group-text" id="basic-addon1"><i class="icon-eye-off"></i></span>
			  </div>
			  <div class="input-group mb-3 field image">
				<span class="input-group-text" id="basic-addon1">Avatar</span>
			    <input type="file" name="image" placeholder="Enter your email" class="form-control" accept="image/x-png,image/gif,image/jpeg,image/jpg" aria-describedby="basic-addon1" required>
			  </div>
			  <div class="field button d-flex">
				<button class="text-center btn btn-primary mx-auto logreg" type="submit" name="submit">Continue to Datings</button>
			  </div>
		  </form>
		  <div class="link my-3 text-center">Already signed up? <a href="login.php">Login now</a></div>
		</section>
	  </div>
  </main>
  <div class="container-fluid bg-dark text-light m-0 p-0">
<div class="container">
  <footer class="row row-cols-1 row-cols-sm-2 row-cols-md-5 py-5 my-0 border-top">
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
  <script src="javascript/pass-show-hide.js"></script>
  <script src="javascript/signup.js"></script>

</body>
</html>
