<?php

	include_once("conexao.php");
	
	echo "<select name=\"Id_Municipio\">";
	
	if ($_GET["coduf"]){
		$qry = T_query("SELECT `Id_Municipio`,`Municipio` FROM `MUNICIPIOS` WHERE `Cod_UF` = '".$_GET["coduf"]."'");
		while ($row1 = T_fetch_array($qry)){	
			echo "<option value=\"".$row1[0]."\" ";
			if ($row1[0]==$_GET["id_munic"]) echo "selected=\"selected\"";
			echo">".utf8_encode($row1[1])."</option>";
		}
	}
	else	echo "<option value=\"0\">Selecione uma UF</option>";
		
	
	echo "</select>";
		

?>
<script language="javascript">
ajax.showElement('divMunic','inline');
</script>