<?php

include_once("sessions.php");
allow(112);
include_once("conexao.php");
include_once("uploadFunc.php");
include_once("emailFunc.php");

if ((!validExtension("pdf;doc;docx;odt;zip",$_FILES["f_trabalho"])))
	echo "<script> alert('Tipo de arquivo não permitido ".$_FILES['f_trabalho']['name']."'); </script>";
else{ //se o formato de arquivo for valido...

	$f_trabalho = $_FILES["f_trabalho"];
	$ext_ficha = getExtension($_FILES["f_trabalho"]);
	$versao = $_POST["versao"];
	$idtrabalho = $_POST["idtrabalho"];
	$versao++;	
	
	if (!is_dir("trabalhos/v".$versao)) mkdir("trabalhos/v".$versao,0775);
		
	if ((uploadFile($f_trabalho,$idtrabalho,"trabalhos/v".$versao))){
			
		//se deu tudo certo com os uploads... vai o trabalho...
		$sql = "UPDATE TRABALHOS SET Versao = ".$versao.", Ext_ficha = '".$ext_ficha."' WHERE Id_Trabalho = '".$idtrabalho."'";
		$qry = T_query($sql);
		
		unset($sql);
		$tipoTrabalho = T_fetch_array(T_query("SELECT Id_Categoria_Trabalho FROM TRABALHOS WHERE Id_Trabalho = '".$idtrabalho."'"));
		$tipoTrabalho = $tipoTrabalho[0];
		
		$sql = "INSERT INTO ACOMPANHAMENTO (Data_Operacao,Id_Trabalho,Id_Status_Trabalho) VALUES 
		(NOW(),'".$idtrabalho;
		$sql .= ($tipoTrabalho==5)?"',7);":"',6);";
		$qry = T_query($sql);
		if ($qry){	
			if ($tipoTrabalho==5)
				mailThem($idtrabalho,12);
			else{
				mailThem($idtrabalho,16);
				mailThem($idtrabalho,11);
			}
		}			
	}
	else{
		echo "<script> alert('Erro ao submeter arquivos'); </script>";
	}

}

echo "<script> document.location = 'acompan.php?rev=true'; </script>";

?>