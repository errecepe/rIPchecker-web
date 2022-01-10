<?php
include 'common.php';

global $conn;
$conn=odbc_connect("ripchecker","","");	
HTMLStart();

global $USU_NIVEL;
global $USU_NOMBRE;

if (isset($_GET['mod_anotacion']) && $USU_NIVEL >=4){
	
	$sql_reserva="SELECT dir_notas FROM direccionamiento WHERE dir_id=" . $_GET['mod_anotacion'];
	$rs=odbc_exec($conn,$sql_reserva);
	if (odbc_fetch_row($rs)){$nota=odbc_result($rs,"dir_notas");}else{$nota="";}
	
	?>
	<header><nav><br/><br/><br/><br/><br/><br/>
	<form method='post' action='networkdetail.php'>
	<input type='hidden' name='modanotacion_dir_id' value='<?php echo $_GET['mod_anotacion']; ?>'>
	<input type='text' name='frm_notas' value='<?php echo $nota;?>' size='50'>
	<input type='submit' value='Reservar'>
	</form>
	</nav></header><br/><br/><br/><br/><br/><br/>
	<?php
}
elseif (isset($_POST['modanotacion_dir_id']) && isset($_POST['frm_notas'])){
	$sql_reserva="UPDATE direccionamiento SET dir_notas='".$_POST['frm_notas']."' where dir_id=" . $_POST['modanotacion_dir_id'];
		$rs=odbc_exec($conn,$sql_reserva);
		echo "<h2>Anotación modificada Ok!</h2>";
	
}
elseif (isset($_GET['elimina_anotacion']) && $USU_NIVEL >=4){
		$sql_reserva="UPDATE direccionamiento SET dir_notas='' where dir_id=" . $_GET['elimina_anotacion'];
		$rs=odbc_exec($conn,$sql_reserva);
		echo "<h2>Anotación eliminada Ok!</h2>";
}

elseif (isset($_GET['reserva_elimina_dir_id']) && $USU_NIVEL >=3){
		$sql_reserva="UPDATE direccionamiento SET dir_reservado=0 where dir_id=" . $_GET['reserva_elimina_dir_id'];
		$rs=odbc_exec($conn,$sql_reserva);
		echo "<h2>Reserva eliminada Ok!</h2>";
}


elseif (isset($_GET['reserva_dir_id'])){
	?>
	<header><nav><br/><br/><br/><br/><br/><br/>
	<form method='post' action='networkdetail.php'>
	<input type='hidden' name='reserva_dir_id' value='<?php echo $_GET['reserva_dir_id']; ?>'>
	<input type='text' name='frm_notas' value='Especifica aquí el motivo de la reserva' size='50'>
	<input type='submit' value='Reservar'>
	</form>
	</nav></header><br/><br/><br/><br/><br/><br/>
	<?php
	
}
elseif (isset($_POST['reserva_dir_id'])){
	if (isset($_POST['frm_notas'])){
		/*update notas y reserva */
		$sql_reserva="UPDATE direccionamiento SET dir_reservado=1 ,  dir_notas='" . utf8_decode($_POST['frm_notas']) . " (reservado por $USU_NOMBRE)' where dir_id=" . $_POST['reserva_dir_id'];
		$rs=odbc_exec($conn,$sql_reserva);
		echo "<h2>Reserva Ok!</h2>";
		
	}
}
else
{
?>



	<header>
		<nav>
			<?php
				if (!isset($_GET['grpid'])){echo "<p>algo raro has intentado...</p>";exit;}
				
				$grpid=$_GET['grpid'];
				$sql="SELECT * FROM grupo where grp_id=$grpid";
				$rs=odbc_exec($conn,$sql);
				$centro=utf8_encode(odbc_result($rs,"grp_desc"));
				$rango=odbc_result($rs,"grp_rango");
				$fecha_alta=odbc_result($rs,"grp_fechaalta");
				
				echo "&nbsp;<h3>$centro - $rango - Fecha Alta: $fecha_alta</h3>";
			?>
		
			<table><tr><th>IP</th><th>Ultima vez online</th><th>Ultima comprobación</th><th>Hostname</th><th>Reservado</th><th>Notas</th></tr>
				<?php 

				
				
				$grpid=$_GET['grpid'];
				$sql="SELECT * FROM direccionamiento where dir_grp_id=$grpid ORDER BY dir_orden";
				$rs=odbc_exec($conn,$sql);
				$regs=0;
				while (odbc_fetch_row($rs))
				{
					$id=odbc_result($rs,"dir_id");
					$ip=odbc_result($rs,"dir_ip");
					$ultvezonline=odbc_result($rs,"dir_ultvezonline");
					$ultcomprobacion=odbc_result($rs,"dir_ultcomprobacion");
					$hostname=odbc_result($rs,"dir_hostname");
					$reservado=odbc_result($rs,"dir_reservado");
					$notas=utf8_encode(odbc_result($rs,"dir_notas"));
					$bgcolor=isset($ultvezonline) ? "style='background-color:#FFAAAA'" : "style='background-color:#AAFFAA'";
					echo "<tr $bgcolor><td>$ip</td><td>$ultvezonline</td><td>$ultcomprobacion</td><td>$hostname</td><td>";
					echo $reservado == '0' ?  ($C_ENABLE_FREE_RESERVE == '1' || $USU_NIVEL>=2 ? "<a href='networkdetail.php?reserva_dir_id=$id' title='Reserva IP'>No</a>" : "No") : ($USU_NIVEL>=3 ? "<a href='networkdetail.php?reserva_elimina_dir_id=$id' title='Elimina reserva'>Si</a>" : "Si");
					echo "</td><td>$notas ". ($USU_NIVEL>=4 && $notas!='' ? "<a href='networkdetail.php?elimina_anotacion=$id' title='Elimina anotacion'>X</a>&nbsp;" : "") . ($USU_NIVEL>=4 ? "<a href='networkdetail.php?mod_anotacion=$id' title='Modifica anotacion'>...</a>" : "")."</td></tr>";
					$regs++;
				}
				?>
			</table>
			<?php echo "<p>$regs IP's</p>"; ?>
		</nav>
	</header>

	
	
<?php 

}


HTMLEnd();odbc_close($conn); ?>