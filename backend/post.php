<?php
session_start();
  try{
    $con = new PDO("mysql:host=localhost;dbname=messenger","root","");    
  }catch(PDOException $e){
    header("Location: login.php");
    exit();
  }
  if(isset($_POST['sendmsg']) && isset($_FILES['mediaAttached']) && isset($_POST['message']) && isset($_POST['to_id']) && is_numeric($_POST['to_id'])){
    $message = filter_var($_POST["message"],FILTER_SANITIZE_STRING);
    $media   = $_FILES['mediaAttached'];
    $extension = array('png','jpg','jpeg');
    $img = "NULL";
    print_r($media);
    if(!empty($media['name'])){
      if(in_array(strtolower(pathinfo($media['name'],PATHINFO_EXTENSION)),$extension)){
        if($media['error'] == 0){
          if($media['size'] < pow(2,22)){
            $uid = uniqid(true);
            if(move_uploaded_file($media['tmp_name'],"../media/".$uid.$media['name'])){
              $img = "media/".$uid.$media['name'];
            }
          }
        }
      }
      $a = true;
    }else{
      $a = false;
    }
    if(strlen($message) > 0){
      $b = true;
    }else{
      $b = false;
    }

    if( $a || $b ){
      $stmt = $con->prepare('INSERT INTO messages (from_id,to_id,content,media) VALUES (?,?,?,?)');
      $stmt->execute(array($_SESSION['userid'],intval($_POST['to_id']),$message,$img));
    }
    header("Location: ../index.php?id={$_POST['to_id']}");
    exit();
  }