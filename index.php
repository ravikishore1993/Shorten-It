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
		$response = array('success' => false, 'url' => '');
		$url = $shortener->request->post('url');
		if(!filter_var($url, FILTER_VALIDATE_URL))
		{
			echo json_encode($response);
			die();
		}
		else
		{
			
		}
	});

	$shortener->get('/:name', function ($name) use ($shortener) 
	{
		$shortener->redirect('https://google.co.in');
	});

	$shortener->get('/404', function () use ($shortener) 
	{
		$shortener->render('404.php');
	});

	$shortener->run();
?>