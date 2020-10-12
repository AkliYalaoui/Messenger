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
    <aside id="conversations" class="conversations">
      <nav class="setting" id="settings">
        <h2 class="nav-brand">Settings<span id="close-settings">&times;</span></h2>
        <div class="account-name">
          <img src="<?= $_SESSION['avatar'] ?>">
          <h2><?= $_SESSION['username'] ?></h2>
        </div>
        <ul>
          <li>
            <span><i class="fa fa-moon fa-fw"></i>Dark Mode</span>
            <input type="checkbox" id="darkMode">
          </li>
          <li>
            <a href="#"><i class="fa fa-cog fa-fw"></i>Account Settings</a>
          </li>
          <li>
            <a id="logout" href="logout.php"><i class="fa fa-sign-out-alt fa-fw"></i>Logout</a>
          </li>
        </ul>
      </nav>
      <header>
        <i id="shrink-conver" class="fa fa-arrow-right fa-lg"></i>
        <h2><span>Discussions</span><i id="open-settings" class="fas fa-cog fa-fw"></i></h2>
        <!--Send Request To Api Later-->
        <form>
          <input id="searchfriend" type="text" placeholder="Search in messenger">
          <input id="uidhidden" type="hidden" value="<?= $_SESSION['userid'] ?>">
          <input type="submit" value="search" name="rqst">
          <i class="fab fa-searchengin fa-fw"></i>
        </form>
      </header>
      <main id="friends">
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
        <?php 
        if(!empty($allUsers)){
        foreach($allUsers as $user):?>
        <a href="?id=<?= $user['id'] ?>" title="<?= $user['username'] ?>">
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
                $tmp = explode(" ",$metaData['send_at']);
              ?>
              <div class="last-msg"><?= strlen(substr($metaData['content'],0,10)) < strlen($metaData['content'])  ? substr($metaData['content'],0,10)." ...":substr($metaData['content'],0,10) ?></div>
              <span class="time"><?= $tmp[0]  ?></span>
              <span class="time"><?= $tmp[1]  ?></span>
            </div>
          </div>
        </a>
        <?php endforeach; }
          else{
            echo "<div style='text-align:center; padding:10px; color:var(--txt-color);'>You have not made any friends yet </div>";
          }
          ?>
      </main>
    </aside>
    <section class="friends-chatting">
      <?php
      
      if(isset($_GET['id']) && is_numeric($_GET['id']) && intval($_GET['id']) != $_SESSION['userid']){
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
          <i id="infoDiscussion" class="fas fa-info-circle fa-fw"></i>
        </div>
        <div id="popup" class="pop-up">
          <span>
            <i class="fas fa-phone fa-fw"></i>Messenger Call
          </span>
          <span>
          <i class="fas fa-video fa-fw"></i>Video Call
          </span>
          <span id="discussion-icons">
            <span data-color="#0099ff"></span>
            <span data-color="#009688"></span>
            <span data-color="#673ab7"></span>
            <span data-color="#e91e63"></span>
          </span>
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
            if(isset($firtDisc[0]))
                echo $firtDisc[0];
          ?>
        </div>
        <div id="main-conversation" data-from="<?= $id ?>" data-to="<?= $_SESSION['userid'] ?>"></div>
      </main>
      <footer>
        <div class="preview-media">
          <span id="clear-selection">&times;</span>
          <img id="prev-img">
          <!-- <video id="prev-video"></video> -->
        </div>
        <div>
          <i class="fas fa-plus-circle"></i>
          <i id="attachfile" class="fas fa-image"></i>
          <i class="fas fa-sticky-note"></i>
          <form action="backend/post.php" method="POST" enctype="multipart/form-data">
            <input type="text" placeholder="Write a message" name="message">
            <input id="hidden-file-input" type="file" name="mediaAttached" style="display:none">
            <input type="hidden" value="<?= $id ?>" name="to_id">
            <input type="submit" value="send" name="sendmsg">
            <i class="fas fa-smile"></i>
            <i class="fas fa-paper-plane"></i>
          </form>
        </div>
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
  <script src="public/js/search.js"></script>
</body>

</html>