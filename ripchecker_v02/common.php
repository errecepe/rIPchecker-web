<?php
header('Content-Type: text/html; charset=utf-8');

function HTMLStart(){
	
	?>
	<!DOCTYPE HTML>
	<html>
	<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	
	<title>rIPchecker</title>
	<style>
	body{font-family:Verdana;font-size:10px;}
	</style>
	</head>
	<body>
	<aside>
		&nbsp;<p><img src='main.png' width='32' valign='middle'/><?php MainMenu();?></p>
	</aside>
	
	<?php
	
}


function HTMLEnd() {
	?>
	<aside>
		<br/><br/><br/><br/><h2>v0.2 rIPchecker Web</h2>
		<p><?php MainMenu();?></p>
	</aside>
	<footer>
		<p>rIPchecker Copyright 2021 @errecepe</p>
	</footer>
	</body>
	</html>
	<?php
	
}

function MainMenu(){
	?>
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href='index.php'>Home</a>&nbsp;|&nbsp;<a href='reserved.php'>Reservas</a>
	<?php 
}


?>