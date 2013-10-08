<?php
	
	session_start();
    unset($_SESSION);
    session_destroy();
	
    header('location: ../controller/exec.php');
    exit;

?>
