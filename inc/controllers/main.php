<?php

Class Controller_Main
{
	function __construct($OC)
	{
		$view_main = new View_Main();
		$model_directory = new Model_Directory();
		// hardcoded user ID for now
		$user_profiles = $model_directory->get_user_profiles($OC, 1);
		$view_main->show($OC, $user_profiles);
	}
}