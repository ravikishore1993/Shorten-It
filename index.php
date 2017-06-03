<?php
	
	error_reporting(E_ALL);
    ini_set('display_errors', 'On');

	require 'vendor/autoload.php';
	require 'libs/DBOperations.php';
	$config = include 'config/config.php';
	$db = DBInit($config);

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
		global $db;
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
			$search = DBQuery($db,$url);
			if((intval($search->count) > 0) && ($isPrivate == false))
			{
				$result = $search->results[0]["keyword"];
				$response['success'] = true;
				$response['url'] = $config['DEPLOY_URL'].$result;
			}
			else
			{
				$lastresponse = DBLastKey($db);
				if(NULL == $lastresponse)
				{
					$current["key"]="a";
				}
				else
				{
					$current["key"] = strval($lastresponse);
					$current["key"]++;
				}
				DBPutLastKey($db, $current);
				$storage["url"] = $url;
				$storage["hash"] = sha1($url);
				if($isPrivate)
				{
					$storage["private"] = 1;
					$storage["password"] = sha1($password);
				}
				else
				{
					$storage["private"] = 0;
					$storage["password"] = NULL;
				}
				DBPutNewLink($db, $current, $storage);
				
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
		global $db;
		if(!preg_match('/^[a-zA-Z]+$/', $name))
		{
			$shortener->redirect('/404');
			die();
		}
		$response = DBGetLink($db,$name);
		if(null == $response)
		{
			$shortener->redirect('/404');
			die();
		}
		else if(intval($response->private))
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
        global $db;
		if(!preg_match('/^[a-zA-Z]+$/', $name))
		{
			$shortener->redirect('/404');
			die();
		}
		$response = DBGetLink($db,$name);
		if(null == $response)
		{
			$shortener->redirect('/404');
			die();
		}
		else if(intval($response->private))
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
