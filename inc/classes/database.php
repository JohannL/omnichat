<?php

declare(strict_types=1);

class Database
{
	private $OC;
	// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
	function __construct ($OC, $hostname, $username, $password, $database, $table_prefix)
	// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
	{
		$this->BMX = $OC;
		if (!defined('DB_LOGLEVEL_ERROR')) define('DB_LOGLEVEL_ERROR', 0);
		if (!defined('DB_LOGLEVEL_ALL')) define('DB_LOGLEVEL_ALL', 1);
		$this->pdo = NULL;
		$this->is_connected = false;
		$this->connection_error = false;
		$this->hostname = $hostname;
		$this->username = $username;
		$this->password = $password;
		$this->database = $database;
		$this->buffered_queries = [];
		$this->tp = $table_prefix;

		$this->query_count = 0;
		$this->error_count = 0;
		$this->error_msg = '';
		$this->time_elapsed = 0;
	}
	// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
	function disconnect($persistant = false)
	// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
	{
		$this->pdo = NULL;
		$this->is_connected = false;
	}
	// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
	function connect($persistant = false)
	// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
	{
		$stopwatch = Helpers::stopwatch_start();
		// $this->msg('connect db');
		if ($this->is_connected == true)
		{
			return TRUE;
		}
		else if ($this->connection_error)
		{

		}

		$pdo_options = [];
		if ($persistant)
		{
			$pdo_options[PDO::ATTR_PERSISTENT] = true;
		}
		$pdo_options[PDO::ATTR_EMULATE_PREPARES] = false;
		try
		{
		    $this->pdo = new PDO('mysql:host='.$this->hostname.';dbname='.$this->database, $this->username, $this->password, $pdo_options);
		}
		catch (PDOException $e)
		{
			$this->error_msg = $e->getMessage();
			$this->msg_error($this->error_msg);
			$this->connection_error = true;
			return FALSE;
		}
		// $this->msg('connected db', Helpers::stopwatch_stop($stopwatch));
		$this->is_connected = true;
	}
	// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
	function query($query, $args = NULL)
	// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
	{
		if (!$this->is_connected)
		{
			if ($this->connection_error)
			{
				return FALSE;
			}
			else
			{
				$this->connect();
			}
		}
		//hrtime(true);
		$this->query_count++;
		$stopwatch = Helpers::stopwatch_start();
	    if (!$args)
	    {
	        $statement = $this->pdo->query($query);
			if ($statement === FALSE)
			{
				$this->msg_dump_titled('SQL error', var_export($this->pdo->errorInfo(), true));
			}
	    }
	    else
	    {
	    	$statement = $this->pdo->prepare($query);
			if ($statement === FALSE)
			{
				$this->msg_dump_titled('SQL error', var_export($this->pdo->errorInfo(), true));
			}
			else
			{
				$statement->execute($args);
			}
	    }
	    // $this->msg_query($query, Helpers::stopwatch_stop($stopwatch));
	    // $this->msg_table($this->msg_table($args));
	    return $statement;
	}
	// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
	function fetch($query, $args = NULL)
	// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
	{
		$statement = $this->query($query, $args);
		if ($statement === false || $statement === null)
		{
			return [];
		}
		else
		{
			return $statement->fetch(PDO::FETCH_ASSOC);
		}
	}

/*
	// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
	function fetch($query, $args = NULL)
	// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
	{
		$statement = $this->query($query, $args);
		if ($statement === false || $statement === null)
		{
			return [];
		}
		else
		{
			return $statement->fetch(PDO::FETCH_ASSOC);
		}
	}
*/
	// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
	function fetch_all($query, $args = NULL)
	// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
	{
		$statement = $this->query($query, $args);
		if ($statement === false || $statement === null)
		{
			return [];
		}
		else
		{
			return $statement->fetchAll(PDO::FETCH_ASSOC);
		}
	}
	// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
	function msg($message)
	// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
	{
		echo $message;
	}
	// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
	function msg_query($message)
	// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
	{
		echo $message;
	}
	// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
	function msg_error($message)
	// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
	{
		echo $message;
	}
	// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
	function msg_table($message)
	// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
	{
		echo var_export($message, true);
	}
	// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
	function msg_dump($message)
	// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
	{
		echo var_export($message, true);
	}
	// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
	function msg_dump_titled($message, $title)
	// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
	{
		echo $title;
		echo var_export($message, true);
	}
}