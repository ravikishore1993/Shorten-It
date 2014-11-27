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

	$shortener->run();
?>