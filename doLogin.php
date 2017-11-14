<?php
header("Content-Type: text/html; charset=ISO-8859-1",true);

include_once("conexao.php");
require_once("classes/LDAP.php");

$login = strtolower($_POST["login"]);
$senha = md5(sha1($_POST["senha"]));
$senhaLDAP = $_POST["senha"];
$matricula = $_POST["login"];
$autenticou = 0;

$qryStr = "SELECT * FROM USR WHERE Login = '".$login."'";
$qryStrLDAP = "SELECT * FROM USR WHERE matricula = '".$matricula."'";

$qry = T_query($qryStrLDAP);

if(T_num_rows($qry)) {
	// Possui matricula LDAP
	if(LDAP::authenticate($matricula, $senhaLDAP)) {
		$row = T_fetch_array($qry);
		$autenticou = 1;
	} else {
		// Senha incorreta
		echo "<script> window.location = 'login.php?login=nopass' </script>";
	}
} else {
	// Nao possui matricula LDAP
	$qry = T_query($qryStr);
	if(T_num_rows($qry)){
		// Possui cadastro antigo
		$row = T_fetch_array($qry);
		if ($row["Passwd"]==$senha){		
			$autenticou = 2;
		} else {
			// Senha incorreta
			echo "<script> window.location = 'login.php?login=nopass' </script>";
		}
	} else {
		// Usuario nao encontrado
		echo "<script> window.location = 'login.php?login=nouser' </script>";	
	}
}

if($autenticou > 0) {
	$qryPerm = T_query("SELECT SUM(Id_Papel) FROM USR_has_PAPEIS WHERE Id_Usr = ".$row["Id_Usr"]);
	$rowPerm = T_fetch_array($qryPerm);
		
	session_start();
	$sessionUserTemp = $matricula;
	if($autenticou == 2) {
		$sessionUserTemp = $row["Login"];
	}	
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

	if($row["matricula"] == NULL) {
		echo "<script> document.location = 'regMatricula.php' </script>";	
	}
	echo "<script> document.location = 'index.php' </script>";
}
?>
