<?php

    $link = mysqli_connect("localhost", "root", "", "webdeveloper_secretdiary");
    
    if (mysqli_connect_error()) {
        
        die ("Database Connection Error");
        
    }

?>