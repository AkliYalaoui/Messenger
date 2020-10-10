<?php
session_start();
  try{
    $con = new PDO("mysql:host=localhost;dbname=messenger","root","");    
  }catch(PDOException $e){
    header("Location: login.php");
    exit();
  }
  if(isset($_POST['sendmsg']) && isset($_POST['message']) && isset($_POST['to_id']) && is_numeric($_POST['to_id'])){
    $message = filter_var($_POST["message"],FILTER_SANITIZE_STRING);
    if(strlen($message) > 0){
      $stmt = $con->prepare('INSERT INTO messages (from_id,to_id,content,media) VALUES (?,?,?,?)');
      $stmt->execute(array($_SESSION['userid'],intval($_POST['to_id']),$message,"NULL"));
      header("Location: ../index.php?id={$_POST['to_id']}");
      exit();
    }
  }
?>