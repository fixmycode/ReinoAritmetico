<!doctype html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<title>Blackbird Software</title>
	<meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
	<link href='http://fonts.googleapis.com/css?family=Raleway:100,500' rel='stylesheet' type='text/css'>
	<style>
		body {
			margin: 0;
			padding: 0;
			font-family:Raleway, sans-serif;
			color: #888;
			background: url('../img/bg-ra.gif');
		}

		.start {
			height: 76px;
			position: absolute;
			top: 50%;
			left: 0; right: 0;
			text-align: center;
			margin-top: -38px;
		}

		h1 {
			font-size: 64px;
			font-weight: 100;
			margin: 0;
		}

		h1 > span {
			font-weight: 500;
		}

		.container {
			max-width: 434px;
			width: 85%;
			margin: auto;
			text-align: center;
		}
		.container img.logo {
			margin: 30px 0;
			max-width: 434px;
			width: 100%;
		}
		.container a{
			padding: 15px 20px;
			display: inline-block;
			color: white;
			text-decoration: none;
			text-align: center;
			background: #2f5bc9;

		}
	</style>
</head>
<body>
	<div class="container">
		{{ HTML::image('img/ra-logo.png', 'logo', ['class' => 'logo']) }}
		<br>
		<a href="/app-debug.apk">Descargar APK</a>
	</div>
</body>
</html>
