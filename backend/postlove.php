<?php

  header("Content-Type: application/json");
  try{
    $con = new PDO("mysql:host=localhost;dbname=messenger","root","");    
  }catch(PDOException $e){
    header("Location: login.php");
    exit();
  }
  if(isset($_POST['m_id']) && isset($_POST['love']) && is_numeric($_POST['m_id']) && is_numeric($_POST['love'])){
    $love = intval($_POST['love']);
    $m_id = intval($_POST['m_id']);
    $stmt = $con->prepare('UPDATE messages SET love=? WHERE id=?');
    $stmt->execute(array($love,$m_id));
  }
