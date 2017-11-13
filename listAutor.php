<?php
header("Content-Type: text/html; charset=ISO-8859-1",true);
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
include_once("conexao.php");

$sql = "SELECT Id_Usr, Nome FROM USR WHERE EXISTS (SELECT * FROM `USR_has_PAPEIS` WHERE Id_Usr = USR.Id_Usr AND Id_Papel = 1) ORDER BY Nome;";
$qry = T_query($sql);
?><script>

function setAutor(nome,codigo){
	document.getElementById('<? echo $_GET["aut_txt"]; ?>').value = nome;
	document.getElementById('<? echo $_GET["aut_txt"]; ?>_id').value = codigo;
	autwin.close();
}
</script>

<div align="left" style="overflow:auto">

<?php
$i = 1;
while ($row = T_fetch_array($qry)){
$row["Nome"] =  ($row["Nome"]);
?>

<div class="lista_registros<?php $i = ($i+1)%2; echo $i; ?>" style="width: 97%;">
<?php
	echo "<a href=\"javascript://\" onclick=\"setAutor('".$row["Nome"]."',".$row["Id_Usr"].");\">".$row["Nome"]."</a>";
?>
</div>
<?php
}

?>

</div>