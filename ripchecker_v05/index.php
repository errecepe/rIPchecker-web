<?php
include 'common.php';

global $conn;
$conn=odbc_connect("ripchecker","","");	

HTMLStart();

?>


<form method="post" action="index.php"><input type="text" name="frm_search" value=""><input type="submit" value="Buscar grupo"></form>

	<header>
		<nav>
		<?php 
		if ($C_SHOWLASTSCANINFO==1){
			
			 $sql="SELECT TOP 1 dir_ip, dir_ultvezonline FROM direccionamiento order by direccionamiento.dir_ultvezonline desc";
			 $rs=odbc_exec($conn,$sql);
			 $ultscaneo=odbc_result($rs,"dir_ultvezonline");
			 $ultip=odbc_result($rs,"dir_ip");
			 echo "<p>Último scan: $ultscaneo (IP:$ultip)</p>";
			
		}
		?>
			<table><tr><th>Grupo</th><th>Rango</th><th>IP's Libre/Total</th><th>Fecha Alta</th></tr>
				<?php 
			
				
				$frm_search=isset($_POST['frm_search']) ? $_POST['frm_search'] : "";
				if (($frm_search)!="")
					{/*$sql="SELECT grp_id, grp_desc, grp_rango FROM grupo WHERE grp_desc like '%$frm_search%' order by grp_desc";*/
					 
					 $sql="select grupo.*, ssql.IP, libressql.IPLibre from grupo,(
						select count(*) as IP, grp_id
						from direccionamiento,grupo
						where dir_grp_id=grp_id
						 group by grp_id) ssql,
						(
						select count(*) as IPlibre, grp_id
						from direccionamiento,grupo
						where dir_grp_id=grp_id and direccionamiento.dir_ultvezonline is Null
						 group by grp_id) libressql
						where ssql.grp_id=grupo.grp_id and libressql.grp_id=grupo.grp_id and grp_desc like '%$frm_search%' order by grp_desc";
					}					
				else
					{/*$sql="SELECT grp_id, grp_desc, grp_rango FROM grupo order by grp_desc";*/
					$sql="select grupo.*, ssql.IP, libressql.IPLibre from grupo,(
						select count(*) as IP, grp_id
						from direccionamiento,grupo
						where dir_grp_id=grp_id
						 group by grp_id) ssql,
						(
						select count(*) as IPlibre, grp_id
						from direccionamiento,grupo
						where dir_grp_id=grp_id and direccionamiento.dir_ultvezonline is Null
						 group by grp_id) libressql
						where ssql.grp_id=grupo.grp_id and libressql.grp_id=grupo.grp_id order by grp_desc";
					}
				$rs=odbc_exec($conn,$sql);
				$regs=0;
				while (odbc_fetch_row($rs))
				{
					$id=odbc_result($rs,"grp_id");
					$grupo=utf8_encode(odbc_result($rs,"grp_desc"));
					$rango=odbc_result($rs,"grp_rango");
					$totalip=odbc_result($rs,"IP");
					$libreip=odbc_result($rs,"IPLibre");
					$fechaalta=odbc_result($rs,"grp_fechaalta");
					echo "<tr><td><a href='networkdetail.php?grpid=$id'>$grupo</a>&nbsp;&nbsp;</td><td>$rango&nbsp;&nbsp;</td><td>$libreip / $totalip&nbsp;&nbsp;</td><td>$fechaalta</td></tr>";
					$regs++;
				}
				
				?>
				
			</table>
			<?php echo "<p>$regs grupos</p>"; ?>
		</nav>
	</header>
	<!--
	<section>
	
		<article>
			<header>
				<h2>Article title</h2>
				<p>Posted on <time datetime="2009-09-04T16:31:24+02:00">September 4th 2009</time> by <a href="#">Writer</a> - <a href="#comments">6 comments</a></p>
			</header>
			<p>Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas.</p>
		</article>
		
		<article>
			<header>
				<h2>Article title</h2>
				<p>Posted on <time datetime="2009-09-04T16:31:24+02:00">September 4th 2009</time> by <a href="#">Writer</a> - <a href="#comments">6 comments</a></p>
			</header>
			<p>Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas.</p>
		</article>
		
	</section>
-->
<?php HTMLEnd();odbc_close($conn); ?>