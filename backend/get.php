<?php

  header("Content-Type: application/json");
  $ajax = array();
  try{
    $con = new PDO("mysql:host=localhost;dbname=messenger","root","");    
  }catch(PDOException $e){
    header("Location: login.php");
    exit();
  }

  if(isset($_GET['from_id'],$_GET['to_id'])){
    $from = filter_var($_GET['from_id'],FILTER_SANITIZE_NUMBER_INT);
    $to = filter_var($_GET['to_id'],FILTER_SANITIZE_NUMBER_INT);
    $stmt = $con->prepare("SELECT * FROM messages WHERE (from_id=? AND to_id=?) OR (from_id=? AND to_id=?)");
    if($stmt->execute(array($from,$to,$to,$from))){
      $messages = $stmt->fetchAll();
    }else{
      header("Location: index.php");
      exit();
    }

    foreach($messages as $msg):
      $tmp = array();
      $tmp["msg"]   = $msg["content"];
      $tmp["date"]  = $msg["send_at"];
      if($msg['from_id'] != $from){
        $tmp['class'] = "msg-me";
      }else{
        $tmp['class'] = "msg-you";
      }
      array_push($ajax,$tmp);
    endforeach;
  }

  echo json_encode($ajax);