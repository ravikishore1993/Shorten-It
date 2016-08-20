<?php
	
	error_reporting(E_ALL);
    ini_set('display_errors', 'On');

	require 'vendor/autoload.php';
	require 'libs/Orchestrate.php';
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
		$password = $shortener->request->post('password');
		$isPrivate = (intval($shortener->request->post('privateLink')) == 1)? true : false;
		if( !preg_match("#\b(([\w-]+://?|www[.])[^\s()<>]+(?:\([\w\d]+\)|([^[:punct:]\s]|/)))#i", $url) || strpos($url, $config['DEPLOY_URL']) > -1)
		{
			echo json_encode($response);
			die();
		}
		else
		{
			$search = OrchQuery($url,$config);
			if((intval($search->count) > 0) && ($isPrivate == false))
			{
				$result = $search->results[0]->path->key;
				$response['success'] = true;
				$response['url'] = $config['DEPLOY_URL'].$result;
			}
			else
			{
				$lastresponse = OrchLastKey($config);
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
				OrchPutLastKey($config, $current);
				$storage["url"] = $url;
				$storage["hash"] = sha1($url);
				if($isPrivate)
				{
					$storage["private"] = true;
					$storage["password"] = sha1($password);
				}
				OrchPutNewLink($config, $current, $storage);
				
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
		$response = OrchGetLink($config,$name);
		$response = $response->body;
		if(!isset($response->url))
		{
			$shortener->redirect('/404');
			die();
		}
		else if(isset($response->private))
		{
			$shortener->render('pass.php', array("url" => $config['DEPLOY_URL'].$name, "urlhome" => $config['DEPLOY_URL']));
			die();
		}
		else
		{
			$shortener->redirect($response->url);
			die();
		}

	});

	$shortener->post('/:name', function ($name) use ($shortener) 
	{
		global $config;
		if(!preg_match('/^[a-zA-Z]+$/', $name))
		{
			$shortener->redirect('/404');
			die();
		}
		$response = OrchGetLink($config,$name);
		$response = $response->body;
		if(!isset($response->url))
		{
			$shortener->redirect('/404');
			die();
		}
		else if(isset($response->private))
		{
			$password = sha1(trim($shortener->request->post('password')));
			$actualPassword = $response->password;
			if($password === $actualPassword)
			{
				$shortener->redirect($response->url);
				die();				
			}
			else
			{
				$shortener->render('pass.php', array("url" => $config['DEPLOY_URL'].$name, "urlhome" => $config['DEPLOY_URL']));
				die();
			}
		}
		else
		{
			$shortener->redirect($response->url);
			die();
		}

	});

	$shortener->run();
?>