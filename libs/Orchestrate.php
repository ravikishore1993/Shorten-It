<?php

	function OrchQuery($url, $config)
	{
		$results = Unirest::get
			(
				$config['ORCHESTRATE_LINK']."?query=".sha1($url), 
				null,
				null,
				$config['ORCHESTRATE_KEY'],
				null
			);
		if(intval($results->body->count) > 0)
		{
			$newresults = [ "count" => 0 , "results" =>array() ];
			foreach ($results->body->results as $key => $value) {
				if(!isset($value->path->private))
				{
					$newresults['count'] = $newresults['count'] + 1 ;
					array_push($newresults['results'], $value);
				}
			}
			return json_decode(json_encode($newresults));
		}
		else
			return $results->body;
			
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