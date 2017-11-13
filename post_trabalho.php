<?php

include_once("emailFunc.php");
include_once("uploadFunc.php");
include_once("conexao.php");
include_once("sessions.php");
allow(1);

$id_usr = T_escape_string($_POST["Id_Usr"]);
$titulo = "'".T_escape_string($_POST["Titulo"])."'";
$resumo = ($_POST["Resumo"])?"'".T_escape_string($_POST["Resumo"])."'":"NULL";
$publico_alvo = ($_POST["Publico_Alvo"])?"'".T_escape_string($_POST["Publico_Alvo"])."'":"NULL";
$observacoes = ($_POST["Observacoes"])?"'".T_escape_string($_POST["Observacoes"])."'":"NULL";
$id_area_atuacao[0] = ($_POST["Id_Area_Atuacao"])?T_escape_string($_POST["Id_Area_Atuacao"]):false;
$id_area_atuacao[1] = ($_POST["Id_Area_Atuacao1"])?T_escape_string($_POST["Id_Area_Atuacao1"]):false;
$id_categoria_trabalho = ($_POST["Id_Categoria_Trabalho"])?T_escape_string($_POST["Id_Categoria_Trabalho"]):"NULL";

$id_autores = explode("#",$_POST["Autores"]);


$ano = date("y");

$qryFnd = T_query("SELECT `Id_Trabalho` FROM `TRABALHOS` WHERE `Id_Trabalho` LIKE '%".$ano."' ORDER BY `Id_Trabalho` DESC LIMIT 1;");

if (T_num_rows($qryFnd)){
$rowFnd = T_fetch_array($qryFnd);
$rowFnd = explode("_",$rowFnd[0]);
$rowFnd[0]++;
$id_trabalho = str_pad($rowFnd[0]."_".$ano,6,"0",STR_PAD_LEFT);
}
else
$id_trabalho = "001_".$ano;

$arquivo = $_FILES["Arquivo"];
$ext_arquivo = getExtension($arquivo);

if (!is_dir("trabalhos")) mkdir("trabalhos", 0771);
if (!is_dir("trabalhos/v1")) mkdir("trabalhos/v1",0771);

$id_area_atuacao = array_unique($id_area_atuacao);
$id_autores = array_unique($id_autores);

$data_atual = date('Y-m-d');

//COMEÇANDO A ENVIAR O ARQUIVO...
if (uploadFile($arquivo,$id_trabalho,"trabalhos/v1")){

$sqlQry = "INSERT INTO `TRABALHOS` (`Id_Trabalho`,`Id_Usr`,`Titulo`,`Resumo`,`Publico_Alvo`, `Comentario`,`Data_Post`,`Id_Categoria_Trabalho`,`Ext_Arquivo`) 
VALUES('".$id_trabalho."',".$id_usr.",".$titulo.",".$resumo.",".$publico_alvo.",".$observacoes.",'".$data_atual."',".$id_categoria_trabalho.",'".$ext_arquivo."');";

//$qry = T_query($sqlQry);
global $conn;
$qry = mysqli_query($conn,$sqlQry) or die(mysqli_error($conn));

if ($qry){
	foreach ($id_area_atuacao as $area)
		if ($area) T_query("INSERT INTO `AREAS_ATUACAO_TRABALHO` (`Id_Trabalho`,`Id_Area_Atuacao`) VALUES ('".$id_trabalho."',".$area.");");
	$ct = 0;
	foreach ($id_autores as $autores){
		if ($autores!=0){
			T_query("INSERT INTO `AUTORES` (`Id_Usr`,`Id_Trabalho`,`Ordem`) VALUES (".$autores.",'".$id_trabalho."',".$ct.");");
			$ct++;
		}
	}
	mailThem($id_trabalho,1);
	echo "<script> window.location = 'submissao.php?res=success'; </script>";
	
}
else{
	removeArquivo("v1",$id_trabalho.".".$ext_arquivo);
	echo "<script> window.location = 'submissao.php?res=error'; </script>";
}

}
else{
	echo "<script> window.location = 'submissao.php?res=error&up=no'; </script>";
}



?>