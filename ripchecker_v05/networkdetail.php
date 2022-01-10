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
	if (odbc_fetch_row($rs)){$nota=utf8_encode(odbc_result($rs,"dir_notas"));}else{$nota="";}
	
	?>
	<header><nav><br/><br/><br/><br/><br/><br/>
	<form method='post' action='networkdetail.php'>
	<input type='hidden' name='modanotacion_dir_id' value='<?php echo $_GET['mod_anotacion']; ?>'>
	<?php if (isset($_GET['grpid'])){
	echo "<input type='hidden' name='paramsextra' value='grpid=". $_GET['grpid'] . "'>";
	}elseif (isset($_GET['reserved'])){
	echo "<input type='hidden' name='paramsextra' value='reserved=1'>";
	}elseif (isset($_GET['frm_search'])){
	echo "<input type='hidden' name='paramsextra' value='frm_search=".$_GET['frm_search']."'>";
	}
	?>
	<input type='text' name='frm_notas' value='<?php echo $nota;?>' size='50'>
	<input type='submit' value='Modificar'>
	</form>
	</nav></header><br/><br/><br/><br/><br/><br/>
	<?php
}
elseif (isset($_POST['modanotacion_dir_id']) && isset($_POST['frm_notas'])){
	$sql_reserva="UPDATE direccionamiento SET dir_notas='".utf8_decode($_POST['frm_notas'])."' where dir_id=" . $_POST['modanotacion_dir_id'];
		$rs=odbc_exec($conn,$sql_reserva);
		echo "<h2>Anotación modificada Ok!</h2>";
		if (isset($_POST['paramsextra'])){echo "<a href='networkdetail.php?".$_POST['paramsextra']."' title='Volver'>Volver</a>";}
	
}
elseif (isset($_GET['elimina_anotacion']) && $USU_NIVEL >=4){
		$sql_reserva="UPDATE direccionamiento SET dir_notas='' where dir_id=" . $_GET['elimina_anotacion'];
		$rs=odbc_exec($conn,$sql_reserva);
		echo "<h2>Anotación eliminada Ok!</h2>";
		if (isset($_GET['grpid'])){echo "<a href='networkdetail.php?grpid=".$_GET['grpid']."' title='Volver'>Volver</a>";}
		if (isset($_GET['reserved'])){echo "<a href='networkdetail.php?reserved=1' title='Volver'>Volver</a>";}
		if (isset($_GET['frm_search'])){echo "<a href='networkdetail.php?frm_search=".$_GET['frm_search']."' title='Volver'>Volver</a>";}
}



elseif (isset($_GET['reserva_elimina_dir_id']) && $USU_NIVEL >=3){
		$sql_reserva="UPDATE direccionamiento SET dir_reservado=0 where dir_id=" . $_GET['reserva_elimina_dir_id'];
		$rs=odbc_exec($conn,$sql_reserva);
		echo "<h2>Reserva eliminada Ok!</h2>";
		if (isset($_GET['grpid'])){echo "<a href='networkdetail.php?grpid=".$_GET['grpid']."' title='Volver'>Volver</a>";}
		if (isset($_GET['reserved'])){echo "<a href='networkdetail.php?reserved=1' title='Volver'>Volver</a>";}
		if (isset($_GET['frm_search'])){echo "<a href='networkdetail.php?frm_search=".$_GET['frm_search']."' title='Volver'>Volver</a>";}
		
}		


elseif (isset($_GET['reserva_dir_id'])){
	?>
	<header><nav><br/><br/><br/><br/><br/><br/>
	<form method='post' action='networkdetail.php'>
	<input type='hidden' name='reserva_dir_id' value='<?php echo $_GET['reserva_dir_id']; ?>'>
	<?php if (isset($_GET['grpid'])){
	echo "<input type='hidden' name='paramsextra' value='grpid=". $_GET['grpid'] . "'>";
	}elseif (isset($_GET['reserved'])){
	echo "<input type='hidden' name='paramsextra' value='reserved=1'>";
	}elseif (isset($_GET['frm_search'])){
	echo "<input type='hidden' name='paramsextra' value='frm_search=".$_GET['frm_search']."'>";
	}
	?>
	
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
		if (isset($_POST['paramsextra'])){echo "<a href='networkdetail.php?".$_POST['paramsextra']."' title='Volver'>Volver</a>";}
		
	}
}
else
{
?>



	<header>
		<nav>
			<?php
				//if (!isset($_GET['grpid']) && !isset($_GET['reserved'])){echo "<p>algo raro has intentado...</p>";exit;}
				
				
				
				if (isset($_GET['grpid'])){
					$grpid=$_GET['grpid'];
					$sql="SELECT * FROM grupo where grp_id=$grpid";
					
					$rs=odbc_exec($conn,$sql);
					$centro=utf8_encode(odbc_result($rs,"grp_desc"));
					$rango=odbc_result($rs,"grp_rango");
					$fecha_alta=odbc_result($rs,"grp_fechaalta");
					echo "&nbsp;<h3>$centro - $rango - Fecha Alta: $fecha_alta</h3>";
					$grpid=$_GET['grpid'];
					
					$sql="SELECT * FROM direccionamiento where dir_grp_id=$grpid ORDER BY dir_orden";				
					echo "<table><tr><th>IP</th><th>Ultima vez online</th><th>Ultima comprobación</th><th>Hostname</th><th>Reservado</th><th>Notas</th></tr>";
					$muestracolcentro=0;
					$paramsextra="grpid=$grpid";
					}
				elseif (isset($_GET['reserved'])){
					echo "&nbsp;<h3>IP's reservadas de todos los grupos</h3>";
					$sql="SELECT * FROM direccionamiento,grupo where dir_reservado=1 and grp_id=dir_grp_id ORDER BY grp_desc, dir_orden";
					echo "<table><tr><th>IP</th><th>Centro</th><th>Ultima vez online</th><th>Ultima comprobación</th><th>Hostname</th><th>Reservado</th><th>Notas</th></tr>";
					$muestracolcentro=1;
					$paramsextra="reserved=1";
				}
				elseif(isset($_POST['frm_search']) || isset($_GET['frm_search'])){
					$frm_search =isset($_POST['frm_search']) ? $_POST['frm_search'] : $_GET['frm_search'];
					echo "&nbsp;<h3>Buscando '<i>$frm_search'</i></h3>";
					$sql="SELECT * FROM direccionamiento,grupo where (dir_ip like '$frm_search' or dir_hostname like '%$frm_search%' or dir_notas like '%$frm_search%') and grp_id=dir_grp_id ORDER BY grp_desc, dir_orden";
					echo "<table><tr><th>IP</th><th>Centro</th><th>Ultima vez online</th><th>Ultima comprobación</th><th>Hostname</th><th>Reservado</th><th>Notas</th></tr>";
					$muestracolcentro=1;
					$paramsextra="frm_search=$frm_search";
				}

				else

				{
					//si viene sin parametros, muestra para buscar
					echo "<form method='post' action='networkdetail.php'><input type='text' name='frm_search' value='10.85.%'><input type='submit' value='Buscar IP/hostname/notas'></form>";
					echo "<br/>Es búsqueda con <b>LIKE</b>. Usar comodin % solo al buscar IP.<br/>Si se buscan hostname o notas, la búsqueda ya incluye % al inicio y final";
					
				}
				
		
		
				if (isset($sql))
				{
				$inicio_busqueda_fecha = strtotime(date('d-m-y h:i:s'));
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
						echo "<tr $bgcolor><td>$ip</td>";
						
						if ($muestracolcentro==1) {$centro=utf8_encode(odbc_result($rs,"grp_desc"));echo "<td>$centro</td>";};
						
						echo "<td>$ultvezonline</td><td>$ultcomprobacion</td><td>$hostname</td><td>";
						echo $reservado == '0' ?  ($C_ENABLE_FREE_RESERVE == '1' || $USU_NIVEL>=2 ? "<a href='networkdetail.php?reserva_dir_id=$id&$paramsextra' title='Reserva IP'>No</a>" : "No") : ($USU_NIVEL>=3 ? "<a href='networkdetail.php?reserva_elimina_dir_id=$id&$paramsextra' title='Elimina reserva'>Si</a>" : "Si");
						
						echo "</td><td>$notas ". ($USU_NIVEL>=4 && $notas!='' ? "<a href='networkdetail.php?elimina_anotacion=$id&$paramsextra' title='Elimina anotacion'>X</a>&nbsp;" : "") . ($USU_NIVEL>=4 ? "<a href='networkdetail.php?mod_anotacion=$id&$paramsextra' title='Modifica anotacion'>...</a>" : "")."</td></tr>";
						$regs++;
					}
				
				$fin_busqueda_fecha = strtotime(date('d-m-y h:i:s'));
				$differenceInSeconds = $fin_busqueda_fecha - $inicio_busqueda_fecha;
				echo "</table><p>$regs IP's mostradas en $differenceInSeconds segundos</p>";
				}
				?>
		</nav>
	</header>

	
	
<?php 

}


HTMLEnd();odbc_close($conn); ?>