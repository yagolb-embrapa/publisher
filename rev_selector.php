<?php
include_once("conexao.php");
?>
<script language="javascript" src="TAjax.js"></script>
<script language="javascript">
var tmpajax = new TAjax();
</script>
<div align="center" style="height:220px;">
	&Aacute;rea
	<select name="select" id="cbArea" >
	<option	value="0">Todas</option>
	<?php 
		$sqlQry = "SELECT Id_Area_Atuacao, Area_Atuacao FROM AREAS_ATUACAO ORDER BY Area_Atuacao";
		$qry = T_query($sqlQry);
		while ($row = T_fetch_array($qry)){
			echo "<option value='".$row["Id_Area_Atuacao"]."'>".utf8_encode($row["Area_Atuacao"])."</option>";
		}
	?>
	</select>
	<input type="button" value="Filtrar" onClick="tmpajax.loadDiv('revSelector','listRev.php?trabalho=<?php echo $_GET["trabalho"]; if ($_GET["extra"]) echo "&extra=1"; ?>&rev_txt=<?php echo $_GET["rev_txt"]; ?>&sort='+document.getElementById('cbArea').value);" />
	<div id="revSelector" align="center" style="margin-top:10px; overflow:auto; display:block; width:100%; height:97%; border:#CCCCCC 1px solid;">
	</div>
</div>
<script>
tmpajax.loadDiv('revSelector','listRev.php?trabalho=<?php echo $_GET["trabalho"]; 
if ($_GET["extra"]) echo "&extra=1"; ?>&rev_txt=<?php echo $_GET["rev_txt"]; ?>');
</script>