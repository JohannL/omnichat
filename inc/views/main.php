<?php

Class View_Main
{
	function show($OC, $user_profiles)
	{
		echo $OC->T->parse('main', '', [
			'title' => 'Omnichat',
			'head link stylesheet' => $OC->router->make_link([
				OC::ARG__STYLESHEET => true,
			]),
			'head link javascript' => $OC->router->make_link([
				OC::ARG__JAVASCRIPT => true,
			]),
			'profiles' => $OC->T->parse_many('main', 'profile', 
					$user_profiles
				,
			  	[]
			  ),

		]);
	}
	// function get_user_list($auth);
}