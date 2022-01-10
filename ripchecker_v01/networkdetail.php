<?php
include 'common.php';


HTMLStart();

if (isset($_GET['reserva_dir_id'])){
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
		
		
		
		$conn=odbc_connect("ripchecker","","");
		$sql_reserva="UPDATE direccionamiento SET dir_reservado=1 ,  dir_notas='" . $_POST['frm_notas'] . "' where dir_id=" . $_POST['reserva_dir_id'];
		$rs=odbc_exec($conn,$sql_reserva);
		echo "Reserva Ok!";
		odbc_close($conn);
	}
}
else
{
?>



	<header>
		<nav>
			<table><tr><th>IP</th><th>Ultima vez online</th><th>Ultima comprobación</th><th>Hostname</th><th>Reservado</th><th>Notas</th></tr>
				<?php 


				$conn=odbc_connect("ripchecker","","");
				$grpid=$_GET['grpid'];
				$sql="SELECT * FROM direccionamiento where dir_grp_id=$grpid ORDER BY dir_orden";
				$rs=odbc_exec($conn,$sql);
				while (odbc_fetch_row($rs))
				{
					$id=odbc_result($rs,"dir_id");
					$ip=odbc_result($rs,"dir_ip");
					$ultvezonline=odbc_result($rs,"dir_ultvezonline");
					$ultcomprobacion=odbc_result($rs,"dir_ultcomprobacion");
					$hostname=odbc_result($rs,"dir_hostname");
					$reservado=odbc_result($rs,"dir_reservado");
					$notas=odbc_result($rs,"dir_notas");
					
					$bgcolor=isset($ultvezonline) ? "style='background-color:#FFAAAA'" : "style='background-color:#AAFFAA'";
					echo "<tr $bgcolor><td>$ip</td><td>$ultvezonline</td><td>$ultcomprobacion</td><td>$hostname</td><td>";
					echo $reservado == '0' ?  "<a href='networkdetail.php?reserva_dir_id=$id'>No</a>" : "Si";
					echo "</td><td>$notas</td></tr>";
					
				}
				odbc_close($conn);
				?>
				
			</table>
		</nav>
	</header>

	
	
<?php 

}


HTMLEnd(); ?>