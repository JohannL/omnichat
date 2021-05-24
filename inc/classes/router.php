<?php

Class Router
{

	// config hardcoded for now
	private
		$force_absolute_links = true,
		$cfg = [
			'hostname_site' 		=> OC_HOSTNAME,
			'site_path' 			=> OC_PATH,
		];

	function __construct()
	{
	}

	// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
	function parse_request(string $get) : array
	// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
	{
		$req = [
			OC::REQUEST__ARGS 		=> [],
			OC::REQUEST__SLASHES 	=> 0,
			OC::REQUEST__SITE		=> 0,
		];
		if ($get == '')
		{
			//
		}
		else
		{
			$fragments = explode('/', $get);
			$expecting = OC::ARG__NONE;
			foreach ($fragments as $frag)
			{
				if ($frag === '')
				{
					continue;
				}
				$req[OC::REQUEST__SLASHES]++;

				if (isset(OC::ARG_STRINGS_REV[$frag]))
				{
					$arg_enum = OC::ARG_STRINGS_REV[$frag];
					if (OC::ARG_TYPES[$arg_enum] == OC::ARGUMENT_TYPE__ATOM)
					{
						$req[OC::REQUEST__ARGS][$arg_enum] = true;
					}
					else
					{
						$expecting = $arg_enum;
					}
				}
				else if ($expecting != OC::ARG__NONE)
				{
					if (OC::ARG_TYPES[$arg_enum] == OC::ARGUMENT_TYPE__INT)
					{
						$frag_val = intval($frag);
					}
					else if (OC::ARG_TYPES[$arg_enum] == OC::ARGUMENT_TYPE__ANY)
					{
						$frag_val = rawurldecode($frag);
					}
					if (isset($req[OC::REQUEST__ARGS][$arg_enum]))
					{
						// add to array
						if (is_array($req[OC::REQUEST__ARGS][$arg_enum]))
						{
							$req[OC::REQUEST__ARGS][$arg_enum][] = $frag_val;
						}
						// turn single value into array
						else
						{
							$req[OC::REQUEST__ARGS][$arg_enum] = [$req[OC::REQUEST__ARGS][$arg_enum], $frag_val];
						}
					}
					// set single value
					else
					{
						$req[OC::REQUEST__ARGS][$arg_enum] = $frag_val;
					}
				}
				else
				{
					//
				}
			}
		}
		return $req;
	}

	// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
	function decide_action(array $request)
	// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
	{
		// $action = OC::ACTION__LOGIN;

		// da die benutzer-authentifizierung noch nicht implementiert ist, wird der einfachheit halber direkt die hauptseite geladen

		$action = OC::ACTION__MAIN;

		if (isset($request[OC::REQUEST__ARGS][OC::ARG__STYLESHEET]))
		{
			$action = OC::ACTION__STYLESHEET;
		}
		else if (isset($request[OC::REQUEST__ARGS][OC::ARG__MAIN]))
		{
			$action = OC::ACTION__MAIN;
		}
		else if (isset($request[OC::REQUEST__ARGS][OC::ARG__JAVASCRIPT]))
		{
			$action = OC::ACTION__JAVASCRIPT;
		}
		else if (isset($request[OC::REQUEST__ARGS][OC::ARG__REQUEST_PROFILE_ID]))
		{
			$action = OC::ACTION__REQUEST_PROFILE_ID;
		}
		return $action;
	}

	// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
	function make_link(array $args)
	// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
	{
		// should this ever be needed elsewhere, change this
		static $frags1 = 0;
		if ($frags1 === 0)
		{
			$frags1 = preg_split("~/~", OC_GET, -1, PREG_SPLIT_NO_EMPTY);
		}
		$where_to = '';
		foreach (OC::ARG_ORDER as $arg)
		{
			if (isset($args[$arg]))
			{
				$value = $args[$arg];
				if ($value === TRUE)
				{
					$where_to .= OC::ARG_STRINGS[$arg] . '/';
				}
				else if (is_array($value))
				{
					$where_to .= OC::ARG_STRINGS[$arg] . '/';
					// this might be overdoing it, benchmark later
					asort($value);
					foreach ($value as $v)
					{
						$where_to .= $v . '/';
					}
				}
				else
				{
					$where_to .= OC::ARG_STRINGS[$arg] . '/' . $value . '/';
				}
			}
		}
		$absolute = $this->force_absolute_links;
		$return = '';
		if ($absolute == FALSE)
		{
			// leave out the beginning if it is identical with the current url
			$same_start = 0;
			$frags2 = preg_split("~/~", $where_to, -1, PREG_SPLIT_NO_EMPTY);
			$still_same = true;
			foreach ($frags2 as $k => $frag)
			{
				if ($still_same && isset($frags1[$k]) && $frags1[$k] == $frag)
				{
					$same_start++;
				}
				else
				{
					$still_same = FALSE;
					$return .= ($return !== '' ? '/' : '') . $frag;
				}
			}
			if ((SF_GET_SLASHES - $same_start) > 0)
			{
				$return = str_repeat('../', SF_GET_SLASHES -  $same_start) . $return;
			}
			if ($return === '')
			{
				$return = '.';
			}
		}
		else
		{
			$return = 'http://' . $this->cfg['hostname_site'] . $this->cfg['site_path'] . $where_to;
		}
		//$force_trailing_slash = TRUE;
		//if ($force_trailing_slash)
		{
			$return .= substr($return, -1) != '/' ? '/' : '';
		}
		return $return;
	}




}