<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
    <meta name="Description" CONTENT="Minimal URL shortener">
	<title>Shorten It!</title>
    <meta name="robots" content="noindex,nofollow">

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
		<input type="text" id="urlinput" onclick="select()" placeholder="Paste your Url" autofocus />
		<br>
		<div id="passdiv">
			<label for="passinput" id="passlabel">Password</label>
			<input type="password" id="passinput" onclick="passinput()" />
		</div>
		<button id="urlbutton">Go</button>
		<span id="check">
			<input type="checkbox" class="css-checkbox" name="private" value="private" id="checkboxid"> <label for="checkboxid" class="css-label">Private</label> 
		</span>
	</form>
	<div id="ajax-loader">
		<img src="assets/icons/ajax-loader.gif">
	</div>
	<div id="status">

	</div>
	<div id="follow">
		follow <a href="https://rkravi.com" class="social">me</a> <a href="https://github.com/ravikishore1993/" class="social github">@	GitHub</a>
	</div>
</body>
</html>
