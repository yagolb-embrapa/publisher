<?php
include_once("conexao.php");
if (!$_GET["sort"]){
$sql = "SELECT USR.Nome, USR.Id_Usr FROM USR, USR_has_PAPEIS 
WHERE USR.Id_Usr = USR_has_PAPEIS.Id_Usr AND USR_has_PAPEIS.Id_Papel = 2 AND NOT EXISTS (SELECT * FROM AUTORES WHERE Id_Usr = USR.Id_Usr AND Id_Trabalho = '".$_GET["trabalho"]."')";
}
else{
$sql = "SELECT USR.Nome, USR.Id_Usr FROM USR, USR_has_PAPEIS 
WHERE USR.Id_Usr = USR_has_PAPEIS.Id_Usr AND USR_has_PAPEIS.Id_Papel = 2 AND EXISTS 
(SELECT * FROM USR_has_AREAS_ATUACAO WHERE Id_Usr = USR.Id_Usr AND Id_Area_Atuacao=".$_GET["sort"].") AND NOT EXISTS (SELECT * FROM AUTORES WHERE Id_Usr = USR.Id_Usr AND Id_Trabalho = '".$_GET["trabalho"]."')";
}

if ($_GET["extra"]){
$sql .= " AND NOT EXISTS (SELECT * FROM REVISORES WHERE Id_Trabalho = '".$_GET["trabalho"]."' AND Id_Usr = USR.Id_Usr)";
}

$sql.=" ORDER BY USR.Nome";
$qry = T_query($sql);

?>
<script language="javascript">
function setRev(nome,codigo){
	document.getElementById('<?php echo $_GET["rev_txt"]; ?>').value = nome;
	document.getElementById('<?php echo $_GET["rev_txt"]."_id"; ?>').value = codigo;
	revwin.close();
}
</script>
<div align="left">
<?php
$i = 1;
while ($row = T_fetch_array($qry)){
$row["Nome"] = utf8_encode($row["Nome"]);

?>
<div class="lista_registros<?php $i = ($i+1)%2; echo $i; ?>" style="width: 97%;">
<?php
	$qryRev = T_query("SELECT count(*) AS Numero, Id_Trabalho FROM REVISORES WHERE Id_Usr = ".$row["Id_Usr"]." AND Id_Trabalho='".$_GET["trabalho"]."' GROUP BY Id_Usr");
	$rowRev = T_fetch_array($qryRev);	
	echo T_error();
	echo "<a href=\"javascript://\" onclick=\"setRev('".$row["Nome"]."',".$row["Id_Usr"].");\">".$row["Nome"]." ";
	$sqlAc = "SELECT Id_Status_Trabalho FROM ACOMPANHAMENTO WHERE Id_Trabalho = '".$_GET["trabalho"]."' ORDER BY Data_Operacao DESC LIMIT 1";
	$qryAc = T_query($sqlAc);
	$rowAc = T_fetch_array($qryAc);
	if (($rowRev["Numero"])&&($rowAc["Id_Status_Trabalho"]<=4)) echo "(Revisando ".$rowRev["Numero"]." trabalho";
	if (($rowRev["Numero"]==1)&&($rowAc["Id_Status_Trabalho"]<=4)) echo ")"; else if ($rowRev["Numero"]>1) echo "s)";
	echo "</a>";
	
?>
</div>
<?php	
}
?>
</div>
