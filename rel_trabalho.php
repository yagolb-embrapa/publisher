<?php

include_once("sessions.php");

include_once("conexao.php");

$idtrabalho = $_GET["idtrabalho"];
$sqlTrabalho = "SELECT Titulo, Resumo, Publico_Alvo, Comentario,  UNIX_TIMESTAMP(Data_Post) AS Dt_Post, Comentario, CATEGORIAS_TRABALHO.Categoria_Trabalho FROM TRABALHOS INNER JOIN CATEGORIAS_TRABALHO USING (Id_Categoria_Trabalho) WHERE Id_Trabalho = '".$idtrabalho."'";
$rowTrabalho = T_fetch_array(T_query($sqlTrabalho));

$sqlAutores = "SELECT USR.Nome FROM AUTORES INNER JOIN USR USING (Id_Usr) WHERE Id_Trabalho = '".$idtrabalho."' ORDER BY Ordem";
$qryAutores = T_query($sqlAutores);


$sqlRelator = "SELECT USR.Nome FROM USR INNER JOIN REVISORES USING (Id_Usr) WHERE Id_Trabalho = '".$idtrabalho."' AND Relator = 1";
$qryRelator = T_query($sqlRelator);

$sqlRevisores = "SELECT USR.Nome FROM USR INNER JOIN REVISORES USING (Id_Usr) WHERE Id_Trabalho = '".$idtrabalho."' AND Relator = 0";
$qryRevisores = T_query($sqlRevisores);

$sqlAreas = "SELECT AREAS_ATUACAO.Area_Atuacao FROM AREAS_ATUACAO_TRABALHO INNER JOIN AREAS_ATUACAO USING(Id_Area_Atuacao) WHERE Id_Trabalho = '".$idtrabalho."'";
$qryAreas = T_query($sqlAreas);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Trabalho <?php echo $idtrabalho; ?></title>
<link rel="stylesheet" href="style.css" />
</head>
<style>
.tbl_trab{
	font-size: 10pt;
}

.tbl_trab ul{
	margin:0 0 0 15px;
	padding:0 0 0 0;
}

.tbl_trab li{
	list-style-image:url(img/arrowright.gif);
}

.tbl_trab td{
	padding: 0 0 5px 5px;
	border-bottom:#CCCCCC 1px solid;
}


</style>
<script language="javascript">
function rel_print(){
	nova = window.open('rel_trabalho_print.php?idtrabalho=<?php echo $idtrabalho; ?>','relatorio print','width=500,height=400,toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,copyhistory=yes,resizable=no');
}
</script>
<body>
<table width="463" border="0" align="center" cellpadding="0" cellspacing="0" class="tbl_trab">
  <tr>
    <td width="36%" align="left" valign="top"><strong>T&iacute;tulo</strong></td>
    <td width="64%" align="left" valign="top"><?php echo utf8_encode($rowTrabalho["Titulo"]); ?></td>
  </tr>
  <tr>
    <td align="left" valign="top"><strong>Autores:</strong></td>
    <td align="left" valign="top"><?php
	if (T_num_rows($qryAutores)){
	//echo "<ul>";
		while ($rowAutores = T_fetch_array($qryAutores)){
			echo "<img src='img/arrowright.gif'> ".utf8_encode($rowAutores[0])."<br>";
		}
	//echo "</ul>";
	}
	?></td>
  </tr>
  <tr>
    <td align="left" valign="top"><strong>Revisores:</strong></td>
    <td align="left" valign="top"><?php
	$check = 0; 
	if (T_num_rows($qryRelator))
	{
	
	//echo "<ul>";
		while ($rowRelator = T_fetch_array($qryRelator)){
			echo "<img src='img/arrowright.gif'> ".utf8_encode($rowRelator[0])." (Relator) <br>";
			$check++;
		}
	//echo "</ul>";
	}
	if (T_num_rows($qryRevisores)){
	//echo "<ul>";
		while ($rowRevisor = T_fetch_array($qryRevisores)){
			echo "<img src='img/arrowright.gif'> ".utf8_encode($rowRevisor[0])." (Revisor)<br>";
			$check++;
		}
	//echo "</ul>";
	
	
	
	}
	if( $check == 0)
		echo "Ainda n&atilde;o foram definidos. ";
	
	
	?></td>
  </tr>
  
  
  <tr>
    <td align="left" valign="top"><strong>Resumo:</strong></td>
    <td align="left" valign="top"><?php echo utf8_encode($rowTrabalho["Resumo"]); ?></td>
  </tr>
  <tr>
    <td align="left" valign="top"><strong>Categoria:</strong></td>
    <td align="left" valign="top"><?php echo utf8_encode($rowTrabalho["Categoria_Trabalho"]); ?></td>
  </tr>
  <tr>
    <td align="left" valign="top"><strong>Observa&ccedil;&otilde;es:</strong></td>
    <td align="left" valign="top"><?php echo utf8_encode($rowTrabalho["Comentario"]); ?></td>
  </tr>


  <tr>
    <td align="left" valign="top"><strong>P&uacute;blico Alvo: </strong></td>
    <td align="left" valign="top"><?php echo utf8_encode($rowTrabalho["Publico_Alvo"]); ?></td>
  </tr>
  <tr>
    <td align="left" valign="top"><strong>&Aacute;reas:</strong></td>
    <td align="left" valign="top"><?php
	if (T_num_rows($qryAreas)){
	//echo "<ul>";
		while ($rowAreas = T_fetch_array($qryAreas)){
			echo "<img src='img/arrowright.gif'> ".utf8_encode($rowAreas[0])."<br>";
		}	
	//echo "</ul>";
	}
	?></td>
  </tr>
  <tr>
    <td align="left" valign="top"><strong>Data de Submiss&atilde;o: </strong></td>
    <td align="left" valign="top"><?php echo date("d/m/Y",$rowTrabalho["Dt_Post"]); ?></td>
  </tr>
</table>
<div align="right"><a href="javascript://" style="color:#0000FF;" onclick="rel_print()">Visualizar versao para impress&atilde;o</a></div>
</body>
</html>
