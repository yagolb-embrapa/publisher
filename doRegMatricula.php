<?php 
	header("Content-Type: text/html; charset=ISO-8859-1",true);
	include_once("conexao.php");
	session_start();

	$matricula = $_POST["matriculaForm"];

	$qryStr = "UPDATE USR SET `matricula` = '".$matricula."' WHERE `Id_Usr` = ".$_SESSION["USERID"];
	$qry = T_query($qryStr);
	T_free_result($qry);
	echo "<script> document.location = 'index.php' </script>";
?>
