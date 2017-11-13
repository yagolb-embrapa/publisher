<?php
	include_once("uploadFunc.php");
	include_once("conexao.php");
	include_once("sessions.php");
	allow(1);

	$idtrabalho = $_POST["idTrabalho"];

	$versao = T_fetch_array(T_query("SELECT `Versao` FROM `TRABALHOS` WHERE `Id_Trabalho` = '".$idtrabalho."';"))[0];
	$destino = "trabalhos/v".$versao;

	## Faz um backup do arquivo existente
	$ls_cmd = "ls ".$destino."/".$idtrabalho."*";
	$src = rtrim(shell_exec($ls_cmd));
	$dst = $src.".back";
	$mv_cmd = "mv ./".$src." ./".$dst;
	shell_exec($mv_cmd);

	## Substitui o arquivo existente pelo submetido
	$arquivo = $_FILES["Arquivo"];
	uploadFile($arquivo, $idtrabalho, $destino);

	echo "<script> window.location = 'confirma_alteracao.php?res=success'; </script>";
?>
