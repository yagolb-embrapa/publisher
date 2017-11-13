<?php
        include_once("uploadFunc.php");
        include_once("conexao.php");
        include_once("sessions.php");
        allow(1);

        $idtrabalho = $_POST["idTrabalho"];
	$id_status_trabalho = ($_POST["Id_Status_Trabalho"])?T_escape_string($_POST["Id_Status_Trabalho"]):"NULL";

	$sql = "INSERT INTO ACOMPANHAMENTO (Data_Operacao,Id_Trabalho,Id_Status_Trabalho) VALUES (NOW(),'".$idtrabalho."','".$id_status_trabalho."');";
	(T_query($sql));

	echo "<script> window.location = 'confirma_alteracao.php?res=success'; </script>";
?>

