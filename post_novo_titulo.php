<?php
        include_once("uploadFunc.php");
        include_once("conexao.php");
        include_once("sessions.php");
        allow(1);

        $Titulo = T_escape_string($_POST["Titulo"]);
        //echo "Titulo = ".$Titulo."<br>";
        $idtrabalho = $_POST["idtrabalho"];
        //echo "idtrabalho = ".$idtrabalho;
	
        $id_status_trabalho = ($_POST["Id_Status_Trabalho"])?T_escape_string($_POST["Id_Status_Trabalho"]):"NULL";

	$sql = "UPDATE TRABALHOS SET Titulo = '$Titulo' WHERE Id_Trabalho = '$idtrabalho'";
	(T_query($sql));

	echo "<script> window.location = 'confirma_alteracao.php?res=success'; </script>";
?>

