<?php

	function __autoload($class_name)
	{
		if (file_exists('../_model/class.'.$class_name.'.php'))
			require_once '../_model/class.'.$class_name.'.php';
		elseif (file_exists('../_model/generate/class.'.$class_name.'.php'))
			require_once '../_model/generate/class.'.$class_name.'.php';
		else
			require_once '../inc/class.'.$class_name.'.php';
	}

?>
