<?php

include_once("emailFunc.php");
include_once("conexao.php");
include_once("uploadFunc.php");


$id_trabalho = $_POST["id_trabalho"];
$versao = $_POST["versao"];
$ext = getExtension($_FILES["Arquivo"]);
$versao++;

if (!is_dir("trabalhos/v".$versao)){
	mkdir("trabalhos/v".$versao, 0771);
}

if (uploadFile($_FILES["Arquivo"],$id_trabalho,"trabalhos/v".$versao)){

$sql = "UPDATE `TRABALHOS` SET `Versao` = ".$versao.", Ext_arquivo = '".$ext."' WHERE `Id_Trabalho` = '".$id_trabalho."';";
$qry = T_query($sql);
unset($sql);
$sql = "INSERT INTO ACOMPANHAMENTO (`Data_Operacao`,`Id_Trabalho`,`Id_Status_Trabalho`) VALUES (now(),'".$id_trabalho."',1);";
$qry = T_query($sql);
mailThem($id_trabalho,10);
echo "<script> alert('Nova versão de trabalho submetida com sucesso.'); 
	window.location = 'submissao.php'; </script>";

}
else{
	echo "erro no upload";
}
?>