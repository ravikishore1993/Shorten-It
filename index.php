<?php
	
	error_reporting(E_ALL);
    ini_set('display_errors', 'On');

	require 'vendor/autoload.php';
	$config = include 'config/config.php';

	$shortener = new \Slim\Slim
	(
		array
		(
            'debug' => $config['DEBUG'],
            'mode' => $config['MODE'],
            'templates.path' => 'templates',
        )
	);

	$shortener->get('/', function () use ($shortener)
	{
    	$shortener->render('home.php');
	});

	$shortener->post('/', function () use ($shortener) 
	{
		global $config;
		$response = array('success' => false, 'url' => '');
		$url = $shortener->request->post('url');
	 
	    if (!preg_match("~^(?:f|ht)tps?://~i", $url)) 
	    {
        	$url = "http://" . $url;
		}
		if(!filter_var($url, FILTER_VALIDATE_URL))
		{
			echo json_encode($response);
			die();
		}
		else if(strpos($url, $config['DEPLOY_URL']) > -1)
		{
			echo json_encode($response);
			die();	
		}
		else
		{
			// Search for existence
			global $config;
			$search = Unirest::get
			(
				$config['ORCHESTRATE_LINK']."?query=".sha1($url), 
				null,
				null,
				$config['ORCHESTRATE_KEY'],
				null
			);
			if(intval($search->body->count) == 1)
			{
				$result = $search->body->results[0]->path->key;
				$response['success'] = true;
				$response['url'] = $config['DEPLOY_URL'].$result;
			}
			else
			{
				// fetch lastkeyflag. increment and put.
				$lastresponse = Unirest::get
				(
					$config['ORCHESTRATE_LINK']."LASTKEYFLAG", 
					null,
					null,
					$config['ORCHESTRATE_KEY'],
					null
				);
				$lastresponse = $lastresponse->body;
				if(!isset($lastresponse->key))
				{
					$current["key"]="a";
				}
				else
				{
					$current["key"] = strval($lastresponse->key);	
					$current["key"]++;
				}
				Unirest::put
				(
					$config['ORCHESTRATE_LINK']."LASTKEYFLAG", 
					$headers = array("Content-Type" => "application/json"), 
					$body = json_encode($current), 
					$username = $config['ORCHESTRATE_KEY'], 
					$password = NULL
				);
				
				$storage["url"] = $url;
				$storage["hash"] = sha1($url);
				Unirest::put
				(
					$config['ORCHESTRATE_LINK'].$current["key"], 
					$headers = array("Content-Type" => "application/json"), 
					$body = json_encode($storage), 
					$username = $config['ORCHESTRATE_KEY'], 
					$password = NULL
				);
				$response['success'] = true;
				$response['url'] = $config['DEPLOY_URL'].$current["key"];
			}
			echo json_encode($response);
			die();
		}
	});

	$shortener->get('/404', function () use ($shortener) 
	{
		$shortener->render('404.php');
	});

	$shortener->get('/:name', function ($name) use ($shortener) 
	{
		global $config;
		if(!preg_match('/^[a-zA-Z]+$/', $name))
		{
			$shortener->redirect('/404');
			die();
		}
		$response = Unirest::get
		(
			$config['ORCHESTRATE_LINK'].$name, 
			null,
			null,
			$config['ORCHESTRATE_KEY'],
			null
		);
		$response = $response->body;
		if(!isset($response->url))
		{
			$shortener->redirect('/404');
			die();
		}
		else
		{
			$shortener->redirect($response->url);
			die();
		}

	});

	$shortener->run();
?>