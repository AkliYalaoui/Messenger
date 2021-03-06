<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Messenger | Login</title>
  <link rel="stylesheet" href="public/css/login-styles.css">
  <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="public/css/all.min.css">
</head>

<body>
  <?php
    session_start();
    if(isset($_SESSION['username'])){
      header("Location: index.php");
      exit();
    }else{
    //Check For User If Exist When Trying To Login
    try{
      $con = new PDO("mysql:host=localhost;dbname=messenger","root","");    
    }catch(PDOException $e){
      header("Location: login.php");
      exit();
    }
    if(isset($_POST['sigin'])){
      $username = filter_var($_POST['username'],FILTER_SANITIZE_STRING);
      $pwd = filter_var($_POST['password'],FILTER_SANITIZE_STRING);
      $bio = filter_var($_POST['bio'],FILTER_SANITIZE_STRING);

      $stmt= $con->prepare('SELECT * FROM users WHERE username=?');
      if($stmt->execute(array($username))){
        if($stmt->rowCount() > 0){
          echo "<div class='error'>User already exists</div>";
        }else{
          $stmt= $con->prepare('INSERT INTO users (username,password,bio) VALUES(?,?,?)');
          if($stmt->execute(array($username,sha1($pwd),$bio))){
            header("Location: login.php");
            exit();
          }else{
            echo "<div class='error'> Something went wrong</div>";
          }
        }
      }
    }
?>
  <div class="login-container">
    <i class="fab fa-facebook-messenger"></i>
    <form action="<?= htmlspecialchars($_SERVER['PHP_SELF'])?>" method="POST">
      <label>Username :</label>
      <input type="text" placeholder="Username" name="username" autocomplete="off" required autofocus>
      <label>Password :</label>
      <input type="password" placeholder="Password" name="password" pattern=".{8,}" title="Password must contain at least 8 characters" required autocomplete="new-password">
      <label>Biography :</label>
      <input type="text" placeholder="Tell your friends who are you" name="bio" required autocomplete="off">
      <a href="login.php">You already have an account, login now !</a>
      <input type="submit" value="Sign in" name="sigin">
    </form>
  </div>
  <script>
      if(localStorage.getItem('enabled')){
        document.documentElement.style.setProperty('--msg-color',localStorage.getItem('--msg-color'));
        document.documentElement.style.setProperty('--bg-color',localStorage.getItem('--bg-color'));
        document.documentElement.style.setProperty('--txt-color',localStorage.getItem('--txt-color'));
        document.documentElement.style.setProperty('--in-color',localStorage.getItem('--in-color'));
        document.documentElement.style.setProperty('--hover-color',localStorage.getItem('--hover-color'));
        document.documentElement.style.setProperty('--border-color',localStorage.getItem('--border-color'));
      }
  </script>
  <?php
    }
  ?>
</body>

</html>