<?php
include 'common.php';

global $conn;
$conn=odbc_connect("ripchecker","","");	
HTMLStart();


if (isset($_GET['reserva_elimina_dir_id'])){
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
		
		
		
		
		$sql_reserva="UPDATE direccionamiento SET dir_reservado=1 ,  dir_notas='" . $_POST['frm_notas'] . "' where dir_id=" . $_POST['reserva_dir_id'];
		$rs=odbc_exec($conn,$sql_reserva);
		echo "Reserva Ok!";
		
	}
}
else
{
?>



	<header>
		<nav>
			<table><tr><th>IP</th><th>Centro</th><th>Ultima vez online</th><th>Ultima comprobación</th><th>Hostname</th><th>Reservado</th><th>Notas</th></tr>
				<?php 


				
				
				$sql="SELECT * FROM direccionamiento,grupo where dir_reservado=1 and grp_id=dir_grp_id ORDER BY grp_desc, dir_orden";
				$rs=odbc_exec($conn,$sql);
				$regs=0;
				while (odbc_fetch_row($rs))
				{
					
					$id=odbc_result($rs,"dir_id");
					$centro=utf8_encode(odbc_result($rs,"grp_desc"));
					$ip=odbc_result($rs,"dir_ip");
					$ultvezonline=odbc_result($rs,"dir_ultvezonline");
					$ultcomprobacion=odbc_result($rs,"dir_ultcomprobacion");
					$hostname=odbc_result($rs,"dir_hostname");
					$reservado=odbc_result($rs,"dir_reservado");
					$notas=utf8_encode(odbc_result($rs,"dir_notas"));
					
					$bgcolor=isset($ultvezonline) ? "style='background-color:#FFAAAA'" : "style='background-color:#AAFFAA'";
					echo "<tr $bgcolor><td>$ip</td><td>$centro</td><td>$ultvezonline</td><td>$ultcomprobacion</td><td>$hostname</td><td>";
					echo $reservado == '0' ?  "<a href='networkdetail.php?reserva_dir_id=$id'>No</a>" : ($USU_NIVEL>=3 ? "<a href='reserved.php?reserva_elimina_dir_id=$id'>Si</a>" : "Si");
					echo "</td><td>$notas</td></tr>";
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