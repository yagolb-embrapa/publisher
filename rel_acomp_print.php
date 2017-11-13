<?php

include_once("conexao.php");

$idtrabalho = $_GET["idtrabalho"];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Trabalho <?php echo $idtrabalho; ?></title>
</head>
<script language="javascript"> window.print(); </script>
<style>
body{
	font-family: "DejaVu Sans", Verdana, Arial, sans-serif;
}

h1{
	font-size: 18pt;
}


</style>
<body>
<?php

$sql = "SELECT UNIX_TIMESTAMP(Data_Operacao) AS Dt_Operacao, UNIX_TIMESTAMP(Data_Limite) AS Dt_Limite, Id_Status_Trabalho FROM ACOMPANHAMENTO WHERE Id_Trabalho = '".$idtrabalho."' ORDER BY Data_Operacao DESC";
$qry = T_query($sql);
echo "<center><h1 style=\"font-family: \"DejaVu Sans\", Verdana, Arial, sans-serif;\">Hist&oacute;rico do trabalho ".$idtrabalho."</h1></center>";

while ($row = T_fetch_array($qry)){

	echo "<div style=\"display:block\">";
	echo "<b>".date("d/m/Y",$row["Dt_Operacao"])."</b> - ";
	switch($row["Id_Status_Trabalho"]){
		case 1:	echo "Autor submeteu nova versão do trabalho. Documento aguardando revisao.";
				break;
		case 2: echo "Documento foi enviado para revisão. Data limite para revis&atilde;o foi ".date("d/m/Y",$row["Dt_Limite"]);
				break;
		case 3:	echo "Revisores eviaram seus comentários sobre o documento. Documento aguardando decisão do CP";
				break;
		case 4:	echo "Documento enviado para correção";
				break;
		case 6: echo "Documento enviado para a normalização bibliográfica";
				break;
		case 7: echo "Documento em editoração";
				break;
		case 8:	echo "Documento foi publicado";
				break;
		case 9: echo "Documento foi rejeitado";
				break;		
	}
	echo "</div>";

}
unset($row);
//Imprime a primeira submissão do trabalho
$row = T_fetch_array(T_query("SELECT UNIX_TIMESTAMP(Data_Post) FROM TRABALHOS WHERE Id_Trabalho = '".$idtrabalho."'"));
$datasub = $row[0];
echo "<div style=\"display:block;\"><b>".date("d/m/Y",$datasub)."</b> - Primeira versão do documento submetida pelo autor.</div>";
?>
</body>
</html>