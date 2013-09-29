<?php

    unset($_SESSION);
    session_destroy(); 

    header('location: ../controller/exec.php');
    exit;

?>
