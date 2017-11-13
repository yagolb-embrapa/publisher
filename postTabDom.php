<?php

include_once("sessions.php");
allow(16);
include_once("conexao.php");

$valor = T_escape_string($_POST["valor"]);

if (str_replace(" ","",$valor) == ""){
	echo "<script>	alert('O valor a ser inserido não pode ser nulo');
	document.href = 'preferences.php?conf=2';
	</script>";
}
else{
	switch ($_POST["tab"]){
		case 0:	$sql = "INSERT INTO AREAS_ATUACAO (Area_Atuacao) VALUES ('".$valor."');";
				break;
		case 1: $sql = "INSERT INTO CARGOS (Cargo) VALUES ('".$valor."');";
				break;
		case 2: $sql = "INSERT INTO CATEGORIAS_TRABALHO (Categoria_Trabalho) VALUES ('".$valor."');";
		
	}
	if (T_query($sql)) echo "<script> alert('Novo registro inserido com sucesso!'); </script>";
	else echo "<script> alert('Erro ao inserir novo registro'); </script>";
	echo "<script> document.location = 'preferences.php'; </script>";
	
}




?>