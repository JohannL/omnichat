<?php

// register autoloading for controllers, models, views, and general classes
spl_autoload_register(function ($class) {
	global
			$sf_cfg;
	if (substr($class, 0, 1) == 'M')
	// if (substr($class, 0, 6) == 'Model_')
	{
		$filename = './inc/models/' . strtolower(substr($class, 6)) . '.php';
	}
	else if (substr($class, 0, 1) == 'V')
	// else if (substr($class, 0, 5) == 'View_')
	{
		$filename = './inc/views/' . strtolower(substr($class, 5)) . '.php';
	}
	else if (substr($class, 0, 1) == 'C')
	// else if (substr($class, 0, 11) == 'Controller_')
	{
		$filename = './inc/controllers/' . strtolower(substr($class, 11)) . '.php';
	}
	else
	{
		$filename = './inc/classes/' . strtolower($class) . '.php';
	}
	// echo $class . ' # ' . $filename . '<hr>';
	include $filename;
});

