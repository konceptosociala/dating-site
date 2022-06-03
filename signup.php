<?php 
  session_start();
  if(isset($_SESSION['unique_id'])){
    header("location: /");
  }
  
  $page_title = "Sign Up";
?>

<?php include_once "tml/noheader.php"; ?>
  <main class="container vh-100 d-lg-flex align-items-center justify-content-center">
	  <h1 class="text-center m-5">Sign up to start chatting!</h1>
	  <div class="wrapper p-3">
		<section class="form signup p-3">
		  <form action="#" method="POST" enctype="multipart/form-data" autocomplete="off">
			  <h2 class="text-center pb-2">Registration</h2>	  
			  <div class="error-text text-center pb-2"></div>
			  <div class="input-group mb-3 field input">
			    <span class="input-group-text" id="basic-addon1">First Name</span>
			    <input type="text" name="name" class="form-control" placeholder="Enter your first name" aria-label="First name" aria-describedby="basic-addon1" required>
			  </div>
			  <div class="input-group mb-3 field input">
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

  <script src="javascript/pass-show-hide.js"></script>
  <script src="javascript/signup.js"></script>

</body>
</html>
