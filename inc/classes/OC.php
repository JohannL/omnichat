<?php

Class OC
{
	const
		// how the action is named in the URL
		ACTION__ROOT									= 'act_root',	// usually redirects unregistered visitors to login
		ACTION__LOGIN									= 'act_login',
		ACTION__LOGOUT									= 'act_logout',
		ACTION__MAIN									= 'act_main',
		ACTION__STYLESHEET								= 'act_stylesheet',
		ACTION__JAVASCRIPT								= 'act_javascript',
		ACTION__REQUEST_PROFILE_ID						= 'act_request_profile_id',

		// just for debugging, make the values numbers later
		ARGUMENT_TYPE__ATOM								= 'arg_atom',
		ARGUMENT_TYPE__INT								= 'arg_int',
		ARGUMENT_TYPE__ANY								= 'arg_any',

		// just for debugging, make the values numbers later
		ARG__NONE										= 'arg_none',
		ARG__STYLESHEET									= 'arg_stylesheet',
		ARG__LOGIN										= 'arg_login',
		ARG__MAIN										= 'arg_main',
		ARG__JAVASCRIPT									= 'arg_javascript',
		ARG__REQUEST_PROFILE_ID							= 'arg_request_profile_id',

		// used in URL
		ARG_STRINGS										= [
			OC::ARG__MAIN									=> 		'_main',
			OC::ARG__JAVASCRIPT								=> 		'_javascript',
			OC::ARG__LOGIN									=> 		'_login',
			OC::ARG__STYLESHEET								=> 		'_stylesheet',
			OC::ARG__REQUEST_PROFILE_ID						=> 		'_request_profile_id',
		],

		ARG_STRINGS_REV									= [
			'_main'											=> 		OC::ARG__MAIN,
			'_stylesheet'									=> 		OC::ARG__STYLESHEET,
			'_login'										=> 		OC::ARG__LOGIN,
			'_javascript'									=> 		OC::ARG__JAVASCRIPT,
			'_request_profile_id'							=> 		OC::ARG__REQUEST_PROFILE_ID,
		],

		ARG_TYPES										= [
			OC::ARG__LOGIN									=>		OC::ARGUMENT_TYPE__ATOM,
			OC::ARG__MAIN									=>		OC::ARGUMENT_TYPE__ATOM,
			OC::ARG__STYLESHEET								=>		OC::ARGUMENT_TYPE__ATOM,
			OC::ARG__JAVASCRIPT								=>		OC::ARGUMENT_TYPE__ATOM,
			OC::ARG__REQUEST_PROFILE_ID						=>		OC::ARGUMENT_TYPE__ATOM,
		],

		ARG_ORDER										= [
			OC::ARG__STYLESHEET,
			OC::ARG__LOGIN,
			OC::ARG__MAIN,
			OC::ARG__JAVASCRIPT,
			OC::ARG__REQUEST_PROFILE_ID,
		],

		// just for debugging, make the values numbers later
		REQUEST__ARGS									= 'req_args',
		REQUEST__SLASHES								= 'req_slashes',
		REQUEST__SITE									= 'req_site',

		// just for debugging, make the values numbers later
		RESPONSE__HTTP_STATUS							= 'resp_http_status',
		RESPONSE__BODY									= 'resp_body',
		RESPONSE__TITLE									= 'resp_title',
		RESPONSE__FLAGS									= 'resp_flags',
		RESPONSE__MIME_TYPE								= 'mime_type',

		MIME_TYPE__CSS									= 'text/css; charset=UTF-8',
		MIME_TYPE__JAVASCRIPT							= 'application/javascript; charset=UTF-8',
		MIME_TYPE__HTML									= 'text/html; charset=UTF-8',

		LOGIN_FORM__USERNAME							= 'username',
		LOGIN_FORM__PASSWORD							= 'password',

		DUMMY = 'x';

	private
		$request;

	public
		$router,
		$T;

	function __construct()
	{
		$this->T = new Template('./inc/templates/');
		$this->db = new Database($this, DB_HOST, DB_USERNAME, DB_PASSWORD, DB_DB_NAME, DB_TABLE_PREFIX);
		$this->router = new Router();
		// der request wird in ein array aus argumenten umgesetzt
		$this->request = $this->router->parse_request(OC_GET);
		// anhand der argumente wird entschieden, was zu tun ist
		$this->action = $this->router->decide_action($this->request);
		$this->go($this->action);
	}

	function go($action)
	{
		if ($action == OC::ACTION__LOGIN)
		{
			new Controller_Login($this);
		}
		else if ($action == OC::ACTION__MAIN)
		{
			new Controller_Main($this);
		}
		else if ($action == OC::ACTION__REQUEST_PROFILE_ID)
		{
			$model_directory = new Model_Directory();
			$key = $_POST['key'];
			echo $model_directory->request_profile_id($this, $key);
		}
		else if ($action == OC::ACTION__STYLESHEET)
		{
			header('Content-Type: ' . OC::MIME_TYPE__CSS);
			echo file_get_contents('./inc/css/style.css');
		}
		else if ($action == OC::ACTION__JAVASCRIPT)
		{
			header('Content-Type: ' . OC::MIME_TYPE__JAVASCRIPT);
			echo file_get_contents('./inc/javascript/peerjs.min.js') . "\n";
			echo file_get_contents('./inc/javascript/libs.js') . "\n";
			echo file_get_contents('./inc/javascript/OC_Peer.js') . "\n";
			echo file_get_contents('./inc/javascript/OC_Host.js') . "\n";
			echo file_get_contents('./inc/javascript/OC.js') . "\n";
		}
	}

	function msg($msg)
	{
		echo $msg;
	}

	function dump($what)
	{
		echo '<pre>' . var_export($what, true) . '</pre>';
	}

}