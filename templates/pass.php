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
</head>
<body>
	<div id="back">
		<a href='<?php echo $urlhome; ?>' >Go back to Shortener</a>
	</div>
	<div id="status" class="passstatus">
		<?php echo $url; ?>
	</div>
	<form id="passform" method="post">
		<label for="passinput" id="passlabel">Password</label>
		<input type="password" id="passinput" name="password" autofocus />
		<br>
		<button id="urlbutton">Go</button>
	</form>
	<div id="follow">
		follow <a href="https://rkravi.com" class="social">me</a> <a href="https://github.com/ravikishore1993/" class="social github">@	GitHub</a>
	</div>
</body>
</html>
