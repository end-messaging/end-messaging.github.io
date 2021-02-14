<?php
//Echo log file

//Include config variables
include 'config.php';
//       ^ See note in config file

if(file_exists($log_file_path) && filesize($log_file_path) > 0){
    $contents = file_get_contents($log_file_path);
    echo $contents;
    die();
}
else {
    http_response_code(404);
    die();
}

?>
