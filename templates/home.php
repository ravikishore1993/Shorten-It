<!DOCTYPE html>
<html>
<head>
	<title>Shorten It!</title>
	<link rel="stylesheet" type="text/css" href="assets/css/style.css">
	<link rel="shortcut icon" href="assets/icons/favicon.ico"/>

	<script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
	<script src="assets/scripts/main.js"></script>
</head>
<body>
	<div id="main-title">
		Shorten It!	
	</div>
	<div id="subtitle">
		A Minimal URL shortener
	</div>
	<form id="form">
		<input type="text" id="urlinput" onclick="select()" placeholder="Paste your Url"/>
		<br>
		<button id="urlbutton">Go</button>
	</form>
	<div id="ajax-loader">
		<img src="assets/icons/ajax-loader.gif">
	</div>
	<div id="status">
		
	</div>
</body>
</html>