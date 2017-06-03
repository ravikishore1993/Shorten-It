<?php

	function DBInit($config)
	{
		$dsn = "mysql:host=$config['DBHOST'];dbname=$config['DB'];charset=utf8";
		$opt = [
		    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
		    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
		    PDO::ATTR_EMULATE_PREPARES   => false,
		];
		$db = new PDO($dsn, $config['DBUSER'], $config['DBPASS'], $opt);

		return $db;
	}

	function DBQuery($db, $url)
	{

		$query = $db->prepare('SELECT * from links WHERE hash = ?');
		$query->execute([sha1($url)]);
		$results = $query->fetchAll();
		$newresults = [ "count" => 0 , "results" =>array() ];

		if(intval(count($results)) > 0)
		{
			foreach ($results as $key => $value) {
				if(0 == intval($value['private']))
				{
					$newresults['count'] = $newresults['count'] + 1 ;
					array_push($newresults['results'], $value);
				}
			}
		}
		else
		{
			$newresults->count = 0;
			$newresults->results = $results;
		}
		
		return $newresults;
	}

	function DBLastKey($db)
	{
		$query = $db->prepare('SELECT value from constants WHERE name = ?');
		$query->execute(['LASTKEYFLAG']);
		$results = $query->fetchAll();		

		if(count($results) == 0)
		{
			$results = null;
		}
		else
		{
			$results = $results[0]->value;
		}

		return $results;
	}

	function DBPutLastKey($config, $current)
	{

		if("a" == $current["key"])
		{
			$query = $db->prepare('INSERT INTO constants(value,name) VALUES (?,?)');	
		}
		else
		{
			$query = $db->prepare('UPDATE constants SET value = ? WHERE name = ?');		
		}
		
		return $query->execute([$current["key"],'LASTKEYFLAG']);
	}

	function DBPutNewLink($config, $current, $storage)
	{
		$query = $db->prepare('INSERT INTO links(keyword,reftime,url,hash,private,password) VALUES (?,?,?,?,?,?)');
		return	$query->execute([
			$current["key"],
			1000*microtime(),
			$storage["url"],
			$storage["hash"],
			$storage["private"],
			$storage["password"]
			]);
	}

	function DBGetLink($db, $name)
	{
		$query = $db->prepare('SELECT * from links WHERE keyword = ?');
		$query->execute([$name]);
		$results = $query->fetchAll();
		if(0 == count($results))
		{
			$results = 0;
		}
		else
		{
			$results = $results[0];
		}

		return $results;
	}
?>