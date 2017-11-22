<?php
header("Content-Type: text/html; charset=ISO-8859-1",true);

include_once("conexao.php");
require_once("classes/LDAP.php");

$senha = $_POST["senha"];
$matricula = $_POST["login"];

$qryStr = "SELECT * FROM USR WHERE Login = '".$matricula."'";

$qry = T_query($qryStr);

if(T_num_rows($qry)) {
	// Tenta autenticar o LDAP
	if(LDAP::authenticate($matricula, $senha)) {
		$row = T_fetch_array($qry);
		
		$qryPerm = T_query("SELECT SUM(Id_Papel) FROM USR_has_PAPEIS WHERE Id_Usr = ".$row["Id_Usr"]);
		$rowPerm = T_fetch_array($qryPerm);
		
		session_start();
		$sessionUserTemp = $matricula;	
		$_SESSION["USER"] = ($row["Nome"]!="")?utf8_encode($row["Nome"]):utf8_encode($sessionUserTemp);
		$_SESSION["USERID"] = $row["Id_Usr"];
		$_SESSION["LAST_LOGIN"] = $row["Ultimo_Acesso"];
		$_SESSION["PERMISSAO"] = $rowPerm[0];
						
		T_free_result($qry);
		T_free_result($qryPerm);
		
		$mes = array("","Janeiro","Fevereiro","Março","Abril","Maio","Junho","Julho","Agosto","Setembro","Outubro","Novembro","Dezembro");
		$semana = array("Domingo","Segunda Feira","Terça Feira","Quarta Feira","Quinta Feira","Sexta Feira","Sábado");
		$ultimoAcesso = $semana[date("w")].", ".date("d")." de ".$mes[date("n")]." de ".date("Y")."; as ".date("G").":".date("i");
		
		$qryStr = "UPDATE USR SET `Ultimo_Acesso` = '".$ultimoAcesso."' WHERE `Id_Usr` = ".$_SESSION["USERID"];
		$qry = T_query($qryStr);
		T_free_result($qry);

		echo "<script> document.location = 'index.php' </script>";
	} else {
		// Senha incorreta
		echo "<script> window.location = 'login.php?login=nopass' </script>";
	}
} else {
	// Usuario nao encontrado
	echo "<script> window.location = 'login.php?login=nouser' </script>";		
}
?>
