<?php
  require 'rb.php';

  $hostname = "localhost";
  $username = "root";
  $password = "12345678";
  $dbname = "dating";

  $conn = mysqli_connect($hostname, $username, $password, $dbname);
  R::setup('mysql:host='.$hostname.';dbname='.$dbname, $username, $password);
  
  if(!$conn){
    echo "Database connection error".mysqli_connect_error();
  }
?>
