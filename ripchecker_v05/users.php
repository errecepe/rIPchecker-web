<?php
include 'common.php';
global $conn;
$conn=odbc_connect("ripchecker","","");	
HTMLStart();
global $USU_NIVEL;
global $USU_NOMBRE;
if ($USU_NIVEL<10){echo "<p>No deberías estar aquí<p>";exit;}

// Alta
if ($C_ENABLE_CREATE_USERS == 1){
	echo "<br/><details>";
	echo "<summary>Alta Usuario</summary>";
	echo "<br/><form method='post' action='users.php'>";
	echo "<table>";
	echo "<tr><td>Domain\Username&nbsp;</td><td><input type='text' name='frm_user'></td></tr>";
	echo "<tr><td>Full Name&nbsp;</td><td><input type='text' name='frm_name'></td></tr>";
	echo "<tr><td>Nivel&nbsp;</td><td><input type='text' name='frm_level'></td></tr>";
	echo "<tr><td colspan='2' align='center'><input type='submit' value='Añadir'></td></tr>";
	echo "</table>";
	echo "</form>";
	echo "</details><br/>";
	if (isset($_POST['frm_user']) && (isset($_POST['frm_level'])) && (isset($_POST['frm_name']))){
		// Viene ya el formulario lleno
		$usuario=$_POST['frm_user'];
		$nivel=$_POST['frm_level'];
		$nombre=$_POST['frm_name'];
		$ahora = date("Y-m-d H:i:s");
		$sql_insert="INSERT INTO USUARIO (usu_fullusername, usu_nombre, usu_nivel,usu_fechaalta) VALUES ('$usuario', '$nombre', $nivel, '$ahora')";
		$rs=odbc_exec($conn,$sql_insert);
		if (odbc_num_rows($rs)==1){echo "<p>Alta de usuario ok!";}else{echo "<p>Error creando usuario</p>";}
	}
}
// Inicio modificacion : muestra formulario
if (isset($_GET['frm_mod_user'])){
	$usuario=$_GET['frm_mod_user'];
	$sql_getvalues="SELECT * FROM USUARIO where USU_FULLUSERNAME='$usuario'";
	$rs=odbc_exec($conn,$sql_getvalues);
	
	$nombre=odbc_result($rs,"usu_nombre");
	$nivel=odbc_result($rs,"usu_nivel");
	$fechaalta=odbc_result($rs,"usu_fechaalta");
	echo "<form method='post' action='users.php'><table>";
	echo "<tr><td>Domain\Username&nbsp;</td><td><input type='text' name='frm_user_mod'  value='$usuario' readonly></td></tr>";
	echo "<tr><td>Full Name&nbsp;</td><td><input type='text' name='frm_name' value='$nombre'></td></tr>";
	echo "<tr><td>Nivel&nbsp;</td><td><input type='text' name='frm_level'  value='$nivel'></td></tr>";
	echo "<tr><td>Fecha alta&nbsp;</td><td><input type='text' name='frm_fechaalta' value='$fechaalta' disabled></td></tr>";
	echo "<tr><td colspan='2' align='center'><input type='submit' value='Modificar'></td></tr>";
	echo "</table></form>";
}
// Continua modificacion : recibe valores y modifica
if (isset($_POST['frm_user_mod'])){
	$usuario=$_POST['frm_user_mod'];
	$nivel=$_POST['frm_level'];
	$nombre=$_POST['frm_name'];
	$sql_update="UPDATE USUARIO SET USU_NOMBRE='$nombre', USU_NIVEL='$nivel' WHERE usu_fullusername='$usuario'";
	$rs=odbc_exec($conn,$sql_update);
	if (odbc_num_rows($rs)==1){echo "<p>Modificación de usuario ok!";}else{echo "<p>Error modificando usuario</p>";}
}
// Eliminación : recibe usuario y elimina (sin confirmación)
if (isset($_GET['frm_erase_user'])){
	$usuario=$_GET['frm_erase_user'];
	$sql_delete="DELETE FROM USUARIO WHERE usu_fullusername='$usuario'";
	$rs=odbc_exec($conn,$sql_delete);
	if (odbc_num_rows($rs)==1){echo "<p>Eliminación de usuario ok!";}else{echo "<p>Error eliminando usuario</p>";}
}
// Listado
?>
	<header>
		<nav>
			<table><tr><th>Nombre de usuario completo</th><th>Nombre</th><th>Nivel</th><th>Fecha Alta</th><th>Ed</th><th>El</th></tr>
				<?php 
				$sql="select * from usuario order by usu_fullusername";
				$rs=odbc_exec($conn,$sql);
				$regs=0;
				while (odbc_fetch_row($rs))
				{
					$fullusername=odbc_result($rs,"usu_fullusername");
					$nombre=odbc_result($rs,"usu_nombre");
					$nivel=odbc_result($rs,"usu_nivel");
					$fechaalta=odbc_result($rs,"usu_fechaalta");
					echo "<tr><td>$fullusername&nbsp;&nbsp;</td><td>$nombre&nbsp;&nbsp;</td><td>$nivel&nbsp;&nbsp;</td>";
					echo "<td>$fechaalta</td><td><a href='users.php?frm_mod_user=$fullusername' title='modificar usuario'>...</a></td><td><a href='users.php?frm_erase_user=$fullusername' title='eliminar usuario'>x</a></td></tr>";
					$regs++;
				}
			echo "</table><p>$regs usuario/s</p>"; ?>
		</nav>
	</header>
	
<?php HTMLEnd();odbc_close($conn); ?>