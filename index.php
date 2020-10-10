<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Messenger</title>
  <link rel="stylesheet" href="public/css/style.css">
  <link rel="stylesheet" href="public/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
</head>

<body>
  <?php 
    session_start();
    if(isset($_SESSION['username'])){
  ?>
  <div class="flex-container">
    <aside class="conversations">
      <nav class="setting" id="settings">
        <h2 class="nav-brand">Settings<span id="close-settings">&times;</span></h2>
        <div class="account-name">
          <img src="<?= $_SESSION['avatar'] ?>">
          <h2><?= $_SESSION['username'] ?></h2>
        </div>
        <ul>
          <li>
            <span><i class="fa fa-moon fa-fw"></i>Dark Mode</span>
            <input type="checkbox">
          </li>
          <li>
            <a href="#"><i class="fa fa-cog fa-fw"></i>Account Settings</a>
          </li>
          <li>
            <a href="logout.php"><i class="fa fa-sign-out-alt fa-fw"></i>Logout</a>
          </li>
        </ul>
      </nav>
      <header>
        <h2><span>Discussions</span><i id="open-settings" class="fas fa-cog fa-fw"></i></h2>
        <!--Send Request To Api Later-->
        <form>
          <input type="text" placeholder="Search in messenger">
          <input type="submit" value="search">
          <i class="fab fa-searchengin fa-fw"></i>
        </form>
      </header>
      <main>
        <?php
          try{
            $con = new PDO("mysql:host=localhost;dbname=messenger","root","");    
          }catch(PDOException $e){
            header("Location: login.php");
            exit();
          }
          
          $stmt = $con->prepare('SELECT * from messages WHERE  messages.from_id = ? OR messages.to_id = ?');
          if($stmt->execute(array($_SESSION['userid'],$_SESSION['userid']))){
            $IDS = $stmt->fetchAll();
            $allUsers = array();
            foreach($IDS as $id){
              $stmt = $con->prepare("SELECT * FROM users WHERE id = ?");
              if($stmt->execute(array($id['from_id']))){
                $usr = $stmt->fetch();
                if($usr["id"] != $_SESSION['userid']) $allUsers[$usr["id"]] = $usr;
              }else{
                header("Location: login.php");
                exit();
              }
              if($stmt->execute(array($id['to_id']))){
                $usr = $stmt->fetch();
                if($usr["id"] != $_SESSION['userid']) $allUsers[$usr["id"]] = $usr;
              }else{
                header("Location: login.php");
                exit();
              }
            }
          }else{
            header("Location: login.php");
            exit();
          }
        ?>
        <!--Get Dynamic Conversations from api Later-->
        <?php foreach($allUsers as $user):?>
        <a href="?id=<?= $user['id'] ?>">
          <div class="conversation">
            <img src="<?= $user['avatar']?>">
            <div class="conversation-meta-data">
              <h4 class="friend-name"><?= $user['username'] ?></h4>
              <?php 
                $stmt = $con->prepare("SELECT * FROM `messages` 
                                        WHERE (from_id = ? AND to_id= ? ) OR (from_id = ? AND to_id= ? )
                                        ORDER BY send_at DESC
                                        LIMIT 1");
                if($stmt->execute(array($user['id'],$_SESSION['userid'],$_SESSION['userid'],$user['id']))){
                  $metaData = $stmt->fetch();
                }else{
                  header("Location: index.php");
                  exit();
                }
              ?>
              <span class="last-msg"><?= $metaData['content'] ?></span>.
              <span class="time"><?= $metaData['send_at'] ?></span>
            </div>
          </div>
        </a>
        <?php endforeach; ?>
      </main>
    </aside>
    <section class="friends-chatting">
      <?php
      
      if(isset($_GET['id']) && is_numeric($_GET['id'])){
        $id = intval($_GET['id']);
        $stmt = $con->prepare("SELECT * FROM users WHERE id=?");
        if($stmt->execute(array($id)) && $stmt->rowCount() != 0){
          $friend = $stmt->fetch();
        }else{
          header("Location: index.php");
          exit();
        }      
      ?>
      <header>
        <div class="friend-meta-data">
          <img src="<?= $friend['avatar'] ?>">
          <span><?= $friend['username'] ?></span>
        </div>
        <div class="chat-actions">
          <i class="fas fa-phone fa-fw"></i>
          <i class="fas fa-video fa-fw"></i>
          <i class="fas fa-info-circle fa-fw"></i>
        </div>
      </header>
      <main>
        <div class="friend-bio">
          <img src="<?= $friend['avatar'] ?>">
          <div class="bio-details">
            <span><?= $friend['username'] ?></span>
            <span>You are friends on Facebook</span>
            <span><?= $friend['bio'] ?></span>
          </div>
        </div>
        <div class="discussion-start">
          <?php
            $stmt = $con->prepare("SELECT messages.send_at FROM `messages` WHERE (from_id = ? AND to_id= ? ) OR (from_id = ? AND to_id= ? ) ORDER BY send_at ASC LIMIT 1 ");
            if($stmt->execute(array($id,$_SESSION['userid'],$_SESSION['userid'],$id))){
              $firtDisc = $stmt->fetch();
            }else{
              header("Location: index.php");
              exit();
            }
            echo $firtDisc[0];
          ?>
        </div>
        <div id="main-conversation" data-from="<?= $id ?>" data-to="<?= $_SESSION['userid'] ?>"></div>
      </main>
      <footer>
        <i class="fas fa-plus-circle"></i>
        <i class="fas fa-image"></i>
        <i class="fas fa-sticky-note"></i>
        <form action="backend/post.php" method="POST">
          <input type="text" placeholder="Write a message" name="message" required>
          <input type="hidden" value="<?= $id ?>" name="to_id">
          <input type="submit" value="send" name="sendmsg">
          <i class="fas fa-smile"></i>
          <i class="fas fa-paper-plane"></i>
        </form>
      </footer>
      <?php
      }else{
      ?>
      <div class="waiting-discuss">
        <h2>Start talking to friends !</h2>
        <img src="public/assets/waiting.gif">
      </div>
      <?php
      }
      ?>
    </section>
  </div>

  <?php
    }else{
      header("Location: login.php");
      exit();
    }
  ?>
  <script src="public/js/app.js"></script>
  <script src="public/js/chat.js"></script>
</body>

</html>