<?php
include 'common.php';


HTMLStart();
?>


<form method="post" action="index.php"><input type="text" name="frm_search" value=""><input type="submit" value="Buscar"></form>

	<header>
		<nav>
			<table><tr><th>Grupo</th><th>Rango</th></tr>
				<?php 
				$conn=odbc_connect("ripchecker","","");
				
				$frm_search=isset($_POST['frm_search']) ? $_POST['frm_search'] : "";
				if (($frm_search)!="")
					{$sql="SELECT grp_id, grp_desc, grp_rango FROM grupo WHERE grp_desc like '%$frm_search%' order by grp_desc";}					
				else
					{$sql="SELECT grp_id, grp_desc, grp_rango FROM grupo order by grp_desc";}
				$rs=odbc_exec($conn,$sql);
				$regs=0;
				while (odbc_fetch_row($rs))
				{
					$id=odbc_result($rs,"grp_id");
					$grupo=utf8_encode(odbc_result($rs,"grp_desc"));
					$rango=odbc_result($rs,"grp_rango");
					echo "<tr><td><a href='networkdetail.php?grpid=$id'>$grupo</a></td><td>$rango</td></tr>";
					$regs++;
				}
				odbc_close($conn);
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
<?php HTMLEnd(); ?>