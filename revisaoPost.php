<?php

/***********************************************************************************************
CONFIGURACOES PARA REALIZAR OS COMENTARIOS PARA O REVISOR, PARA O COMITE E VERIFICA SE TODOS
OS REVISORES JÁ POSTARAM A SUA REVISÃO OU AINDA TEM ALGUMA PENDENTE.
***********************************************************************************************/

include_once("conexao.php");
include_once("sessions.php");
include_once("emailFunc.php");
include_once("uploadFunc.php");

function intPOST( $valor)
{
	global $_POST;
	if(   empty( $_POST[$valor]))
		return 'null';
	else return intval($_POST[$valor]);
}


// FUNCAO PARA VERIFICAR SE TODOS OS REVISORES JÁ POSTARAM AS REVISOES
function checkRevisoes($idtrabalho){

//pega o numero total de revisores de tal trabalho
$qry = T_query("SELECT COUNT(*) AS Cont FROM REVISORES WHERE Id_Trabalho = '".$idtrabalho."';");
$row = T_fetch_array($qry);
$total_revisores = $row["Cont"];

T_free_result($qry);

//pega o numero de revisoes feitas pra tal acompanhamento
//$sql = "SELECT COUNT(*) AS Cont1 FROM REVISOES WHERE Id_Trabalho = '".$idtrabalho."' AND Data_Operacao = '".$data_operacao."';";
$sql = "SELECT COUNT(*) AS Cont1 FROM REVISOES WHERE Id_Trabalho = '".$idtrabalho."';";
$qry = T_query($sql);
$row = T_fetch_array($qry);
$total_revisoes = $row["Cont1"];

return ($total_revisores == $total_revisoes);

}

//AGORA VEM AS OPERACOES COM DADOS RECEBIDOS ATRAVES DO POST
$idusr = $_POST["Id_Usr"];
$idtrabalho = $_POST["Id_Trabalho"];
$categoria = intPOST("Categoria");
$publico_alvo = T_escape_string(intPOST("publico_alvo"));
$publico_alvo_outros = T_escape_string($_POST["publico_alvo_outros"]);
$originalidade = T_escape_string(intPOST("originalidade"));
$densidade = T_escape_string(intPOST("densidade"));
$redacao = T_escape_string(intPOST("redacao"));
//$titulo = T_escape_string(intPOST("titulo"));
$referencias = T_escape_string(intPOST("referencias"));
$aceitacao = T_escape_string(intPOST("aceitacao"));
$compreensao = T_escape_string(intPOST("compreensao"));
$comentautor = T_escape_string($_POST["Coment_Autor"]);
$comentcp = T_escape_string($_POST["Coment_CP"]);

$arquivo_revisao = $_FILES["arquivo_revisao"];
$ext_arquivo = getExtension($arquivo);
//if (!is_dir("revisoes/".$idtrabalho)) mkdir("revisoes/".$idtrabalho, 0771);


$publico_alvo = array();
for( $i = 0; $i<4;  $i++)
{
	if(!empty($_POST["publico_alvo{$i}"]) )
		$publico_alvo[($i)] = T_escape_string( $_POST["publico_alvo{$i}"]);
 }


$erro = array();
 
 

if ( count($erro) == 0) {

	$qry = T_query("SELECT Data_Operacao FROM ACOMPANHAMENTO WHERE Id_Trabalho = '".$idtrabalho."' ORDER BY Data_Operacao DESC LIMIT 1");
	$row = T_fetch_array($qry);
	$dataoperacao = $row[0];
	unset($row);
	T_free_result($qry);
	


			/*$query = "SELECT Id_Trabalho FROM ACOMPANHAMENTO WHERE Id_Status_Trabalho=3 and Id_Trabalho = '".$idtrabalho."'";
			$resultado = T_query($query);
			if( T_num_rows($resultado) > 0 )
				$revisao=T_num_rows($resultado);
			else $revisao=1;	
			*/

	$query = "SELECT Versao FROM TRABALHOS WHERE Id_Trabalho = '".$idtrabalho."'";
	$resultado = T_query($query);
	if( T_num_rows($resultado) > 0 )
	{
		$campo = T_fetch_array($resultado);
		$revisao = $campo['Versao'];
	}


	
$arquivoNome = '';
$ext_arquiv = '';
			$arquivo = $_FILES["Arquivo"];
		if ($arquivo['tmp_name']) {
			$ext_arquivo = getExtension($arquivo);

			if (!is_dir("revisoes")) mkdir("revisoes", 0777);
			if (!is_dir("revisoes/v".$revisao)) mkdir("revisoes/v".$revisao,0777);
			$x = 1;
			while (is_file("revisoes/v".$revisao."/$idtrabalho"."Rev-".$x.'.'.$ext_arquivo)) {
				$x++;
			}
			$arquivoNome = $idtrabalho . 'Rev-'.$x;
			
			if (uploadFile($arquivo,$arquivoNome,"revisoes/v".$revisao."/")){
				mailThem($idtrabalho,17);
				
				
			}
}
//else $revisao = '';

    if ($arquivoNome) $arquivoNome .= '.' . $ext_arquivo; //adiciona extensão do arquivo
	//dataoperacao, idtrabalho, idusr, comentautor, comentcp
	if ($_POST["Editar"])	$sql = "UPDATE REVISOES SET 
	Publico_Alvo_Outros='".$publico_alvo_outros."', Originalidade=".$originalidade.", Densidade=".$densidade.", Redacao=".$redacao.",  Referencias=".$referencias.", Aceitacao=".$aceitacao.", Compreensao=".$compreensao.",  Coment_Autor = '".$comentautor."', Coment_CP = '".$comentcp."',arquivo='".$arquivoNome."' WHERE Id_Trabalho = '".$idtrabalho."' AND Id_Usr = ".$idusr." AND Data_Operacao = '".$dataoperacao."';";
	
	else	$sql = "INSERT INTO REVISOES(Id_Trabalho, Data_Operacao, Id_Usr,  Publico_Alvo_Outros, Originalidade, Densidade, Redacao,  Referencias, Aceitacao, Compreensao, Coment_Autor, Coment_CP,arquivo,revisao) VALUES 	('".$idtrabalho."','".$dataoperacao."',".$idusr.",   '".$publico_alvo_outros."' ,  ".$originalidade." , ".$densidade." ,  ".$redacao." ,   ".$referencias." ,  ".$aceitacao." ,  ".$compreensao." , '".$comentautor."','".$comentcp."','".$arquivoNome."','".$revisao."');";


	$query_publico_alvo = "DELETE FROM OCORRENCIA_PUBLICO_ALVO WHERE Id_Trabalho = '".$idtrabalho."' AND Id_Usr='".$idusr."' AND Data_Operacao='".$dataoperacao."'";
	$resultado = T_query($query_publico_alvo);
	
	if( count($publico_alvo) > 0)
	{
		foreach( $publico_alvo as $chave => $valor)
		{
			$query_insert_pa = "INSERT INTO OCORRENCIA_PUBLICO_ALVO ( Id_Trabalho, Id_Usr, Id_Publico_Alvo, Data_Operacao) VALUES ( '".$idtrabalho."', '".$idusr."', '".$valor."', '".$dataoperacao."' ); ";
			$resultado = T_query($query_insert_pa);
		}
	}

	if (T_query($sql)){
		echo "<script> alert('Revisão enviada com sucesso'); </script>";
	
		mailThem($idtrabalho,8,$idusr);
	}
	else echo "<script> alert('ERRO AO INSERIR REVISAO');  </script>";
	
	
	// caso o trabalho esteja numa versao posterior a primeira, nao ha necessidade de verificar se outros revisores
	// ja enviaram suas revisoes, pois so havera um unico revisor
	$query = "SELECT Versao FROM TRABALHOS WHERE Id_Trabalho = '".$idtrabalho."'";
	$resultado = T_query($query);
	if( T_num_rows($resultado) > 0 )
	{
		$campo = T_fetch_array($resultado);
		$versao = $campo['Versao'];
		if( $campo['Versao'] == 1)
			$flag_correcao = false;
		else
			$flag_correcao = true;


	}


	//$revisao = $versao;


	if (checkRevisoes($idtrabalho) || $flag_correcao){

		$rowlim = T_fetch_array(T_query("SELECT Data_Limite FROM ACOMPANHAMENTO WHERE Id_Trabalho = '".$idtrabalho."' ORDER BY Data_Operacao DESC LIMIT 1"));
		$sql1 = "INSERT INTO ACOMPANHAMENTO (Data_Operacao, Id_Trabalho, Id_Status_Trabalho, Data_Limite) VALUES 
		(NOW(), '".$idtrabalho."',3,'".$rowlim[0]."');";
		if (T_query($sql1)){
			mailThem($idtrabalho,9);
			//$query = "SELECT Id_Trabalho FROM ACOMPANHAMENTO WHERE Id_Status_Trabalho=3 and Id_Trabalho = '".$idtrabalho."'";
			//$resultado = T_query($query);
			//if( T_num_rows($resultado) > 0 )
				//$revisao=$versao;
			
	
/*
			$arquivo = $_FILES["Arquivo"];
			//$ext_arquivo = getExtension($arquivo);

			if (!is_dir("revisoes")) mkdir("revisoes", 0771);
			if (!is_dir("revisoes/v".$revisao)) mkdir("revisoes/v".$revisao,0771);
			$x = 1;
			while (is_file("revisoes/v".$revisao)) {
				$x++;
			}
			$arquivoNome = $id_trabalho . 'Rev-'.$x.'.'.$ext_arquivo;

			if (uploadFile($arquivo,$arquivoNome,"revisoes/v".$revisao)){
				
				
				
			}

*/
		}
		else echo "ERRO AO INSERIR ACOMPANHAMENTO";	
	}
	/*
	echo "<script> 
			rwin.close();
			ajax.loadDiv('divListaTrabalhos','mylist_revtrabalhos.php');
		  </script>";
*/
//header("Location: revisao.php");
echo "<script>window.location = 'revisao.php';</script>";
}
 
?>