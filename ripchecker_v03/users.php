<?php
include 'common.php';

global $conn;
$conn=odbc_connect("ripchecker","","");	

HTMLStart();

global $USU_NIVEL;
global $USU_NOMBRE;
if ($USU_NIVEL<10){echo "<p>No deberías estar aquí<p>";exit;}

?>




	<header>
		<nav>
	
			<table><tr><th>Nombre de usuario completo</th><th>Nombre</th><th>Nivel</th><th>Fecha Alta</th></tr>
				<?php 
			
				
						
				
					/*$sql="SELECT grp_id, grp_desc, grp_rango FROM grupo order by grp_desc";*/
					$sql="select * from usuario order by usu_fullusername";
					
				$rs=odbc_exec($conn,$sql);
				$regs=0;
				while (odbc_fetch_row($rs))
				{
					$fullusername=odbc_result($rs,"usu_fullusername");
					$nombre=odbc_result($rs,"usu_nombre");
					$nivel=odbc_result($rs,"usu_nivel");
					$fechaalta=odbc_result($rs,"usu_fechaalta");
				
					echo "<tr><td>$fullusername&nbsp;&nbsp;</td><td>$nombre&nbsp;&nbsp;</td><td>$nivel&nbsp;&nbsp;</td><td>$fechaalta</td></tr>";
					$regs++;
				}
				
				?>
				
			</table>
			<?php echo "<p>$regs usuario/s</p>"; ?>
		</nav>
	</header>
	

<?php HTMLEnd();odbc_close($conn); ?>