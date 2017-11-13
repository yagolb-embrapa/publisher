<?php

include_once("conexao.php");
include_once("emailFunc.php");

$idtrabalho = $_POST["idtrabalho"];
$destino = $_POST["rbDest"];
$data = implode("/",array_reverse(explode("/",$_POST['textRevExtra_data'])));
$right = true;

switch ($destino){
	case 0:		$sql = "INSERT INTO ACOMPANHAMENTO (Data_Operacao,Id_Trabalho,Id_Status_Trabalho) VALUES 
				(NOW(),'".$idtrabalho."',4);";
				$qry = T_query($sql);
				if ($qry){
					mailThem($idtrabalho,4);
				 	echo "<script> alert ('O trabalho foi enviado para correçao com sucesso'); </script>";
				}
				else echo "<script> alert('Erro ao enviar o trabalho para correçao'); </script>";
				break;
	case 1:		$sql = "INSERT INTO ACOMPANHAMENTO (Data_Operacao,Id_Trabalho,Id_Status_Trabalho) VALUES 
				(NOW(),'".$idtrabalho."',6);";
				$qry = T_query($sql);
				if ($qry){
					mailThem($idtrabalho,6);
					mailThem($idtrabalho,11);
					echo "<script> alert('O trabalho foi enviado para a normalização bibliográfica com sucesso'); </script>";
				}
				else echo "<script> alert('Erro ao enviar o trabalho para normalização'); </script>";
				break;

	case 4:		$sql = "INSERT INTO ACOMPANHAMENTO (Data_Operacao,Id_Trabalho,Id_Status_Trabalho) VALUES 
				(NOW(),'".$idtrabalho."',10);";
				$qry = T_query($sql);
				if ($qry){
					mailThem($idtrabalho,6);
					mailThem($idtrabalho,15);
					echo "<script> alert('O trabalho foi enviado para a revisão gramatical com sucesso'); </script>";
				}
				else echo "<script> alert('Erro ao enviar o trabalho para a revisão gramatical'); </script>";
				break;
	case 2:		$sql = "INSERT INTO ACOMPANHAMENTO (Data_Operacao,Id_Trabalho,Id_Status_Trabalho) VALUES 
				(NOW(),'".$idtrabalho."',9);";
				$qry = T_query($sql);
				if ($qry) {
					mailThem($idtrabalho,7);
					echo "<script> alert('O trabalho foi rejeitado com sucesso'); </script>";
				}
				else echo "<script> alert('Erro ao rejeitar o trabalho'); </script>";
				break;
	case 3:		if ($_POST["txtRevExtra_id"]){
					$revextra = $_POST["txtRevExtra_id"];
					$sql = "INSERT INTO REVISORES (Id_Trabalho, Id_Usr) VALUES ('".$idtrabalho."',".$revextra.");";
					$qry = T_query($sql);
					if ($qry){
						$sql = "SELECT Data_Operacao, Id_Trabalho FROM ACOMPANHAMENTO WHERE Id_Trabalho = '".$idtrabalho."' ORDER BY Data_Operacao DESC LIMIT 1";
						$qry = T_query($sql);
						$row = T_fetch_array($qry);
						//$sqld = "DELETE FROM ACOMPANHAMENTO WHERE Data_Operacao = '".$row["Data_Operacao"]."' AND Id_Trabalho = '".$idtrabalho."';";
						//$qryd = T_query($sqld);
$sql = "UPDATE ACOMPANHAMENTO set Data_Operacao = NOW(), Id_Status_Trabalho = 2, Data_Limite = '".$data."' WHERE Id_Trabalho = '".$idtrabalho."' ORDER BY Data_Operacao DESC LIMIT 1";
				$qry = T_query($sql);
					}

					if (($qry)){
						mailThem($idtrabalho,2,$revextra);
						echo "<script> alert('Revisor extra adicionado com sucesso. Trabalho enviado para revisao.'); </script>";
					}
					else echo "<script> alert('Erro ao adicionar revisor extra'); </script>";
				}
				else{
					echo "<script> alert('Um revisor deve ser selecionado para ser revisor extra'); </script>";
					$right = false;
				}
				break;
	case 5:	$sql = "INSERT INTO ACOMPANHAMENTO (Data_Operacao,Id_Trabalho,Id_Status_Trabalho) VALUES 
				(NOW(),'".$idtrabalho."',3);";
				$qry = T_query($sql);
				if ($qry) {
					//mailThem($idtrabalho,7);
					echo "<script> alert('O trabalho vai para o status de aguardando correção'); </script>";
				}
				//else echo "<script> alert('Erro ao rejeitar o trabalho'); </script>";
				break;
}

echo "<script>  ";
if ($right) echo "rwin.close(); ";
echo "ajax.loadDiv('divListaTrabalhos','list_trabalhos.php');
</script>";
?>