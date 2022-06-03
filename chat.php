<?php 
  session_start();
  include_once "php/config.php";
  if(!isset($_SESSION['unique_id'])){
    header("location: login.php");
  }
?>
<?php include_once "tml/header.php"; ?>
  <div class="wrapper">
    <section class="chat-area">
      <header>
        <?php 
          $user_id = mysqli_real_escape_string($conn, $_GET['user_id']);
          $user = R::findOne('users', 'unique_id = ? AND type = "female"', [$user_id]);
          if(!isset($user)) {
            header("location: /");
          }
        ?>
        <img src="php/images/<?php echo $user['img']; ?>" alt="">
        <div class="details">
          <span><?php echo $user['fname']. " " . $user['lname'] ?></span>
          <p><?php echo $user['status']; ?></p>
        </div>
      </header>
      <div class="chat-box">

      </div>
      <form action="#" class="typing-area">
        <input type="text" class="incoming_id" name="incoming_id" value="<?php echo $user_id; ?>" hidden>
        <input type="text" name="message" class="input-field" placeholder="Type a message here..." autocomplete="off">
        <button><i class="icon-paper-plane"></i></button>
      </form>
    </section>
  </div>

  <script src="javascript/chat.js"></script>

</body>
</html>
