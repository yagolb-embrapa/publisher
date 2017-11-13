<?php

include("conexao.php");
include("emailFunc.php");

$right = true;
$id_trabalho = $_POST["id_trabalho"];
$data = T_getDate($_POST["dt_limite"]);
$revisores = array($_POST["revisor1_id"],$_POST["revisor2_id"],$_POST["revisor3_id"],$_POST["revisor4_id"]);
$revisores = array_unique($revisores);

$revisores_antigos = array();
$lista_revisores_email = array();

foreach( $_POST['revisor_old'] as $chave => $valor )
{
	$revisores_antigos[$valor]['set'] = 1;
	$revisores_antigos[$valor]['ativo'] = 0;
}



for ($i = 0; $i < sizeof($revisores); $i++) if ($revisores[$i]==0) unset($revisores[$i]);

if (sizeof($revisores)<2) $right = false;



if ($right)
{
	$sqlQry = "SELECT Ordenacao FROM REVISORES WHERE Id_Trabalho='".$id_trabalho."' ORDER BY Ordenacao DESC LIMIT 1";
	$qry = T_query($sqlQry);		
	$rowOrd = T_fetch_array($qry);
	$ordenacao = ( $rowOrd['Ordenacao']);


 	$j = 0;
	for ($i = 0; $i <sizeof($revisores); $i++)
	{	
		if ($revisores[$i])
		{
		
			if( $revisores_antigos[$revisores[$i]]['set'] != 1 )
			{
				$ordenacao++;
				$sqlQry = "INSERT INTO REVISORES (Id_Trabalho,Id_Usr,Relator, Ordenacao) VALUES ('".$id_trabalho."',".$revisores[$i].",";
				$sqlQry .=($i == 0)?"1,":"0,";
				$sqlQry .=" '".$ordenacao."'); ";
				$qry = T_query($sqlQry);		
				if (!$qry) "erro nessa sql<br>"; 

				$lista_revisores_email[$j++] = $revisores[$i];
			}
			else
			{
				$relator =($i == 0)? "1" : "0";
				$sqlQry = "UPDATE REVISORES SET Relator={$relator}, Ordenacao={$i} WHERE Id_Trabalho='".$id_trabalho."' AND Id_Usr='".$revisores[$i]."' ";
				$qry = T_query($sqlQry);		
				if (!$qry) "erro nessa sql<br>"; 

				$revisores_antigos[$revisores[$i]]['ativo'] = 1;
			}
		}
	}
	
	foreach( $revisores_antigos as $chave => $valor)
	{
		if( $valor['ativo'] == 0 )
		{
			$sqlQry = "DELETE FROM REVISORES WHERE Id_Trabalho='".$id_trabalho."' AND Id_Usr='".$chave."' ";
			$qry = T_query($sqlQry);					
		}
	}
	
 	mailThem($id_trabalho,13, $lista_revisores_email);
}
	
echo "<script> ";
if ($right) echo "alert('Revisores selecionados com sucesso".$ordenacao."'); rwin.close(); ";
else echo "alert ('Número de revisores selecionados insuficiente.'); ";
echo "ajax.loadDiv('divListaTrabalhos','list_trabalhos.php');</script>";

?>