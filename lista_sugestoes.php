<?php

include_once("conexao.php");

if (isset($_GET["id_trabalho"]))
    $id_trabalho = $_GET["id_trabalho"];
else
    $id_trabalho = $_GET["idtrabalho"];


$sqlTrab = "SELECT Versao, Titulo FROM TRABALHOS WHERE Id_Trabalho = '".$id_trabalho."';";
$qryTrab = T_query($sqlTrab);
$rowTrab = T_fetch_array($qryTrab);
$titulo = utf8_encode($rowTrab["Titulo"]);
echo "<span style='color:#5d554a;size:12;font-weight:bold;'>
			Código: $id_trabalho<br>
			Título: $titulo
		</span><br><br><hr>";
$sqlTrab = "SELECT * FROM REVISOES WHERE Id_Trabalho = '".$id_trabalho."' AND arquivo <> '' order by revisao desc;";
$qryTrab = T_query($sqlTrab);
$anterior = '';
$x = 1;
while ($rowTrab = T_fetch_array($qryTrab)) {
	$versao = $rowTrab['revisao'];
	if ($versao != $anterior) {
		if ($anterior != '') echo "<hr>";
		echo "<br><b>Revisão</b>: $versao<br><br>";
		$x = 1;
		$anterior = $versao;
	}
	echo "Sugestão $x: Clique <a style='color:blue' href='revisoes/v$versao/$rowTrab[arquivo]'>aqui</a> para fazer download <br>";
	$x++;
}
?>
<link rel="stylesheet" href="style.css">
<script language="javascript" src="TAjax.js"></script>

<div id="escondida" style="display:none; visibility:hidden;"></div>


<?
for ($x = $versao;$x >=1;$x--) {
	
}
?>