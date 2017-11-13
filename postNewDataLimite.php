<?php

include_once("conexao.php");
include_once("emailFunc.php");

$id_trabalho = $_POST["id_trabalho"];
$data_limite = $_POST["txtDataLimite"];
$data_limite = explode("/",$data_limite);
$data_limite = $data_limite[2]."-".$data_limite[1]."-".$data_limite[0];


$sql = "INSERT INTO ACOMPANHAMENTO (Id_Trabalho,Data_Operacao,Id_Status_Trabalho,Data_Limite) 
VALUES ('".$id_trabalho."',NOW(),2,'".$data_limite."');";
$qry = T_query($sql);
if ($qry){
	echo "<script> alert('Nova data limite definida com sucesso!'); </script>";
	mailThem($id_trabalho,14);
}
else echo "<script> alert('Erro ao definir nova data limite.'); </script>";

echo "<script> rwin.close(); ajax.loadDiv('divListaTrabalhos','list_trabalhos.php');</script>";


?>