<?php
    require('../inc/class.TemplatePower.php');
    
	session_start();
	if (!(empty($_SESSION['nomeUsuario'])))
	{
		header('location: ./usuarios-exec.php?op=Listar');
		exit;
	};
	
    $tpl = new TemplatePower("../view/_master.htm");

    $tpl->prepare();
    

    $tpl->printToScreen();
?>