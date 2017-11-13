<?php

include_once("sessions.php");
allow(24);
include_once("conexao.php");

$idtrabalho = $_GET["idtrabalho"];

$sql = "INSERT INTO ACOMPANHAMENTO (Data_Operacao,Id_Trabalho,Id_Status_Trabalho) VALUES 
(NOW(),'".$idtrabalho."',8);";
$qry = T_query($sql);

if ($qry) 	echo "<script> alert('Trabalho publicado com sucesso'); </script>";
else		echo "<script> alert('Erro ao publicar trabalho'); </script>";

?>
<script>
ajax.loadDiv('divListaTrabalhos','list_trabalhos.php?norev=0');
</script>