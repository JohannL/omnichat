<?php

Class Model_Login
{
	// überprüfe ob benutzername und password angegeben wurden
	function validate_login($OC, $POST)
	{
		$result = [];

		if (	!isset($POST[OC::LOGIN_FORM__USERNAME])
			||	$POST[OC::LOGIN_FORM__USERNAME] == ''
			)
		{
			$result[] = 'please enter a username.';
		}
		if (	!isset($POST[OC::LOGIN_FORM__PASSWORD])
			||	$POST[OC::LOGIN_FORM__PASSWORD] == ''
		)
		{
			$result[] = 'please enter a password.';
		}
		return $result;
	}

	// hier müsste eigentlich die authentifizierug hin
	function authenticate_login($OC, $POST)
	{
		return [];
	}

}