<?php

Class View_Login
{
	function show_form($OC, $validation_result, $auth_result)
	{
		echo $OC->T->parse('login', '', [
			'title' => 'Omnichat Login',
			'head link stylesheet' => $OC->router->make_link([
				OC::ARG__STYLESHEET => true,
			]),
			'head link javascript' => $OC->router->make_link([
				OC::ARG__JAVASCRIPT => true,
			]),
			'form_action' => $OC->router->make_link([
				OC::ARG__LOGIN => true,
			]),
			'form_username' => OC::LOGIN_FORM__USERNAME,
			'form_password' => OC::LOGIN_FORM__PASSWORD,
			'errors'	=> implode('<br>', $validation_result)
							. implode('<br>', $auth_result)
		]);
	}
	// function get_user_list($auth);
}