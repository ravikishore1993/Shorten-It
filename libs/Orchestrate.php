<?php

	function OrchQuery($url, $config)
	{
		return Unirest::get
			(
				$config['ORCHESTRATE_LINK']."?query=".sha1($url), 
				null,
				null,
				$config['ORCHESTRATE_KEY'],
				null
			);
	}

	function OrchLastKey($config)
	{
		return Unirest::get
			(
				$config['ORCHESTRATE_LINK']."LASTKEYFLAG", 
				null,
				null,
				$config['ORCHESTRATE_KEY'],
				null
			);
	}

	function OrchPutLastKey($config, $current)
	{
		return Unirest::put
			(
				$config['ORCHESTRATE_LINK']."LASTKEYFLAG", 
				$headers = array("Content-Type" => "application/json"), 
				$body = json_encode($current), 
				$username = $config['ORCHESTRATE_KEY'], 
				$password = NULL
			);
	}

	function OrchPutNewLink($config, $current, $storage)
	{
		return Unirest::put
			(
				$config['ORCHESTRATE_LINK'].$current["key"], 
				$headers = array("Content-Type" => "application/json"), 
				$body = json_encode($storage), 
				$username = $config['ORCHESTRATE_KEY'], 
				$password = NULL
			);
	}

	function OrchGetLink($config, $name)
	{
		return  Unirest::get
			(
				$config['ORCHESTRATE_LINK'].$name, 
				null,
				null,
				$config['ORCHESTRATE_KEY'],
				null
			);
	}
?>