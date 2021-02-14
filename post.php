<?php

//Include config variables
include 'config.php';
//       ^ See note in config file

session_start();
if(isset($_SESSION['name'])){
    $text = $_POST['text'];
     
    $text_message = '<div class="msgln"><span class="chat-time">'.date('g:i A').'</span> <b class="user-name">'.$_SESSION['name'].'</b> '.stripslashes(htmlspecialchars($text)).'<br></div>'."\n";
    
    file_put_contents($log_file_path, $text_message, FILE_APPEND | LOCK_EX);
}

?>
