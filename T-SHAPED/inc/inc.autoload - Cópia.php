<?php

	function __autoload($class_name)
	{
            if (file_exists('../model/class.'.$class_name.'.php'))
                    require_once '../model/class.'.$class_name.'.php';
            else
                    require_once '../model/class.'.$class_name.'.php';
	}

?>
<sc