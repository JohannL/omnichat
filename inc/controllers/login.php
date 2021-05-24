<?php

Class Controller_Login
{
	function __construct($OC)
	{
		if (empty($_POST))
		{
			$view_login = new View_Login();
			echo $view_login->show_form($OC, [], []);
		}
		else
		{
			$model_login = new Model_Login($OC);
			$view_login = new View_Login();
			$validation_result = $model_login->validate_login($OC, $_POST);
			// validation OK
			if (empty($validation_result))
			{
				$auth_result = $model_login->authenticate_login($OC, $_POST);
				if (empty($auth_result))
				{
					header('Location: ' . $OC->router->make_link([
						OC::ARG__MAIN => true,
					]));
				}
				else
				{
					echo var_dump($auth_result, true);
					$view_login->show_form($OC, $validation_result, $auth_result);
				}
			}
			else
			{
				$view_login->show_form($OC, $validation_result, []);
				return;
			}
		}
	}
}