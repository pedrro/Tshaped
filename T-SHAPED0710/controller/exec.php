<?php
    require('../inc/class.TemplatePower.php');
    
    $tpl = new TemplatePower("../view/_master.htm");

    $tpl->prepare();
    

    $tpl->printToScreen();         
?>                