<?php

include("conexao.php");
include("emailFunc.php");

$right = true;
$id_trabalho = $_POST["id_trabalho"];
$data = T_getDate($_POST["dt_limite"]);
$revisores = array($_POST["revisor1_id"],$_POST["revisor2_id"],$_POST["revisor3_id"],$_POST["revisor4_id"]);
$revisores = array_unique($revisores);

for ($i = 0; $i < sizeof($revisores); $i++) if ($revisores[$i]==0) unset($revisores[$i]);

if (sizeof($revisores)<2) $right = false;

if ($right){
	for ($i = 0; $i <sizeof($revisores); $i++){
		if ($revisores[$i]){
			$sqlQry = "INSERT INTO REVISORES (Id_Trabalho,Id_Usr,Relator, Ordenacao) VALUES ('".$id_trabalho."',".$revisores[$i].",";
			$sqlQry .=($i == 0)?"1, ":"0, ";
			$sqlQry .= $i.");";
			$qry = T_query($sqlQry); 
			if (!$qry) "erro nessa sql<br>"; 
		}
	}
	
	$sqlQry = "INSERT INTO ACOMPANHAMENTO(Data_Operacao,Id_Trabalho,Id_Status_Trabalho,Data_Limite) 
	VALUES(NOW(),'".$id_trabalho."',2,'".$data."');";
	$qry = T_query($sqlQry);
	if ($qry) mailThem($id_trabalho,2);
}
	
echo "<script> ";
if ($right) echo "alert('Revisores selecionados com sucesso'); rwin.close(); ";
else echo "alert ('Número de revisores selecionados insuficiente.'); ";
echo "ajax.loadDiv('divListaTrabalhos','list_trabalhos.php?norev=1');</script>";

?>