<?php 
	ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);
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
?>
<?php include_once "tml/header.php"; ?>
<div style="height:80% !important">
  <div class="wrapper d-flex flex-column col-lg-4 col-md-6 col-sm-12 m-auto">	
      <header class="d-flex">
        <img class="m-3" width=50 height=50 src="php/images/<?php echo $kunparolanto['img']; ?>" alt="">
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
		  <a class="btn btn-outline-danger" id="button-addon2" data-bs-toggle="popover" title="Popover title" data-bs-content="And here's some amazing content. It's very engaging. Right?"><i class="icon-sticky-note"></i></a>
		  <textarea type="text" style="resize:none; height: 40px" name="message" class="input-field form-control" aria-label="Type a message here..." placeholder="Type a message here..." autocomplete="off" aria-describedby="button-addon2"></textarea>
		  <button class="btn btn-outline-danger" type="button" id="button-addon2"><i class="icon-paper-plane"></i></button>
		</div>
      </form>
  </div>
</div>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script> 
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
<script src="/javascript/emojiarea/jquery.emojiarea.js"></script>
<script src="javascript/chat.js"></script>
  
</body>
</html>
