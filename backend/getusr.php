<?php

  header("Content-Type: application/json");
  $ajax = array();
  try{
    $con = new PDO("mysql:host=localhost;dbname=messenger","root","");    
  }catch(PDOException $e){
    header("Location: login.php");
    exit();
  }

  $search = isset($_GET['search'])  ? filter_var($_GET['search'],FILTER_SANITIZE_STRING): "";
  $userid = isset($_GET['userid'])  ? filter_var($_GET['userid'],FILTER_SANITIZE_NUMBER_INT): 0;
  $allUsers = array();

  if($userid !== 0){
    $stmt = $con->prepare("SELECT * FROM users where id != ? AND username like ?");
    if($stmt->execute(array($userid,"%$search%"))){
      $usrs = $stmt->fetchAll();
      $allUsers['data'] = $usrs;
    }else{
      $allUsers['err'] = "BAD";
    }
  }else{
    $allUsers['err'] = "BAD";
  }

  echo json_encode($allUsers);