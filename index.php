<?php

//Include config variables
include 'config.php';
//       ^ See note in config file

session_start();
 
if(isset($_GET['logout'])){    
     
    //Simple exit message
    $logout_message = '<div class="msgln"><span class="left-info">User <b class="user-name-left">'. $_SESSION['name'] .'</b> has left the chat session.</span><br></div>';
    file_put_contents($log_file_path, $logout_message, FILE_APPEND | LOCK_EX);
     
    session_destroy();
    header('Location: index.php'); //Redirect the user
    die();
}
 
if(isset($_POST['enter'])){
    if($_POST['name'] != ''){
        $_SESSION['name'] = stripslashes(htmlspecialchars($_POST['name']));
        
        $login_message = '<div class="msgln"><span class="left-info">User <b class="user-name-left">'. $_SESSION['name'] .'</b> has logged into the chat session.</span><br></div>';
        file_put_contents($log_file_path, $login_message, FILE_APPEND | LOCK_EX);
        
        header('Location: index.php');
        die();
    }
    else{
        echo '<span class="error">Please type in a name</span>';
    }
}
 
function loginForm(){
    echo
    '
    <div id="wrapper">
    <div id="menu">
        <p class="welcome">Welcome to The End, friend. <br/> What led you here today?</p>
        <p class="welcome">The End is a WIP Messaging Service owned by <a href="https://t.me/Aquarirus">Aquarirus</a>, and currently does work if you know how to set up PHP</p>
    </div>
    <div id="loginform">
    <p>Please enter your name to continue!</p>
    <form action="index.php" method="post">
      <label for="name">Name &mdash;</label>
      <input type="text" name="name" id="name" />
      <input type="submit" name="enter" id="enter" value="Enter" />
    </form>
    </div></div>';
}
 
?>
 
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>The End</title>
        <meta name="description" content="The End Messaging Service" />
        <link rel="stylesheet" href="style.css" />
    </head>
    <body>
    <img src="endlogo.png" alt="The End Logo" class="center" height="75" width="365"> 
    <?php
    if(!isset($_SESSION['name'])){
        loginForm();
    }
    else {
    ?>
        <div id="wrapper">
            <div id="menu">
                <p class="welcome">Welcome, <b><?php echo $_SESSION['name']; ?></b></p>
                <p class="logout"><a id="exit" href="#">Exit Chat</a></p>
            </div>
 
            <div id="chatbox">
            <?php
            if(file_exists($log_file_path) && filesize($log_file_path) > 0){
                $contents = file_get_contents($log_file_path);          
                echo $contents;
            }
            ?>
            </div>
 
            <form name="message" action="">
                <input name="usermsg" type="text" id="usermsg" />
                <input name="submitmsg" type="submit" id="submitmsg" value="Send" />
            </form>
        </div>
        <script type="text/javascript" src="jquery.min.js"></script>
        <script type="text/javascript">
            // jQuery Document
            $(document).ready(function () {
                $("#submitmsg").click(function () {
                    var clientmsg = $("#usermsg").val();
                    $.post("post.php", { text: clientmsg });
                    $("#usermsg").val("");
                    return false;
                });
 
                function loadLog() {
                    var oldscrollHeight = $("#chatbox")[0].scrollHeight - 20; //Scroll height before the request
 
                    $.ajax({
                        url: "log.php",
                        cache: false,
                        success: function (html) {
                            $("#chatbox").html(html); //Insert chat log into the #chatbox div
 
                            //Auto-scroll           
                            var newscrollHeight = $("#chatbox")[0].scrollHeight - 20; //Scroll height after the request
                            if(newscrollHeight > oldscrollHeight){
                                $("#chatbox").animate({ scrollTop: newscrollHeight }, 'normal'); //Autoscroll to bottom of div
                            }   
                        }
                    });
                }
 
                setInterval (loadLog, 2500);
 
                $("#exit").click(function () {
                    var exit = confirm("Are you sure you want to end the session?");
                    if (exit == true) {
                    window.location = "index.php?logout=true";
                    }
                });
            });
        </script>
    </body>
</html>
<?php
}
?>
