<?php
header('Content-Type: text/html; charset=utf-8');

include 'conf.php';




function HTMLStart(){
	global $conn;
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
	<?php
		global $USU_FULLNAME;
		global $USU_NOMBRE;
		global $USU_NIVEL;
		
		$user = $_SERVER['AUTH_USER'];
		$sql="SELECT * from usuario where usu_fullusername='$user'";
		$rs=odbc_exec($conn,$sql);
		if (odbc_fetch_row($rs)<1)
		{
			$USU_FULLNAME=$user;
			$USU_NOMBRE=$user;
			$USU_NIVEL=0;
		}else{
			$USU_FULLNAME=odbc_result($rs,"usu_fullusername");
			$USU_NOMBRE=odbc_result($rs,"usu_nombre");
			$USU_NIVEL=odbc_result($rs,"usu_nivel");
		}
		global $C_REQUIREDLEVEL;
		global $C_MSGNOACCESS;
		if ($USU_NIVEL<$C_REQUIREDLEVEL){echo "<h2>Lo siento <u>$USU_NOMBRE</u>, no tienes permisos.<br/>$C_MSGNOACCESS</h2>";exit;}
	?>
	
	<aside>
		&nbsp;<p><img src='main.png' width='32' valign='middle'/><?php MainMenu();?></p>
	</aside>
	
	<?php
	
}


function HTMLEnd() {
	?>
	<aside>
		<br/><br/><br/><br/><h2>v0.5 rIPchecker Web</h2>
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
	global $USU_NOMBRE;
	global $USU_NIVEL;
	
	
	echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href='index.php'>Home</a>&nbsp;|&nbsp;<a href='networkdetail.php'>IP's</a>&nbsp;|&nbsp;<a href='networkdetail.php?reserved=1'>Reservas</a>&nbsp;|&nbsp;";
	if ($USU_NIVEL>=10){echo "<a href='users.php'>Usuarios</a>&nbsp;|&nbsp;";}
	echo "$USU_NOMBRE (Nivel:$USU_NIVEL)";
}


?>