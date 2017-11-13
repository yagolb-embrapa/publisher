<?php 



include_once("conexao.php");

include_once("sessions.php");

allow(2);



$sql1 = "SELECT MAX(Data_Limite) as Data_Limite FROM ACOMPANHAMENTO where Id_Trabalho='".$_GET["idtrabalho"]."' limit 1";
$qry = T_query($sql1);
$row = T_fetch_array($qry);

$data = $row['Data_Limite'];

$sql = "SELECT TRABALHOS.Titulo, TRABALHOS.Versao, TRABALHOS.Id_Categoria_Trabalho, ACOMPANHAMENTO.*, CATEGORIAS_TRABALHO.Categoria_Trabalho, CATEGORIAS_TRABALHO.Conteudo FROM ACOMPANHAMENTO INNER JOIN TRABALHOS USING(Id_Trabalho) INNER JOIN CATEGORIAS_TRABALHO ON CATEGORIAS_TRABALHO.Id_Categoria_Trabalho = TRABALHOS.Id_Categoria_Trabalho WHERE Id_Trabalho = '".$_GET["idtrabalho"]."' ORDER BY Data_Operacao DESC LIMIT 1;";

$qry = T_query($sql);

$row = T_fetch_array($qry);

/*
 * 	Verificação para ter certeza que o relator será o ultimo a revisar
 */
$podeRevisar = true;

if ($row["Versao"]==1){
	
	$sqlTmp = "SELECT Relator FROM REVISORES WHERE Id_Trabalho = '".$_GET["idtrabalho"]."' AND Id_Usr = ".$_SESSION["USERID"];
	$rowTmp = T_fetch_array(T_query($sqlTmp));
	if ($rowTmp[0]==1){	
		$sqlTmp = "SELECT COUNT(*) ct FROM REVISOES WHERE Id_Trabalho = '".$_GET["idtrabalho"]."'";
		$rowTmp = T_fetch_array(T_query($sqlTmp));
		$ctRevisoes = $rowTmp["ct"];
		$sqlTmp = "SELECT COUNT(*) ct FROM REVISORES WHERE Id_Trabalho = '".$_GET["idtrabalho"]."'";
		$rowTmp = T_fetch_array(T_query($sqlTmp));
		$ctRevisores = $rowTmp["ct"];
		if (($ctRevisoes+1)<$ctRevisores){
			echo "<script> alert('O relator deve ser o último a enviar sua revisão.'); </script>";
			$podeRevisar = false;
		}
		
	}
		
}


$edit = 0;

	$dataOperacao = $row["Data_Operacao"];	

	$sqlE = "SELECT REVISOES.* FROM REVISOES INNER JOIN ACOMPANHAMENTO USING(Id_Trabalho) INNER JOIN REVISORES USING(Id_Usr) WHERE REVISOES.Id_Trabalho = '".$_GET["idtrabalho"]."' AND REVISOES.Id_Usr = ".$_SESSION["USERID"]." AND ACOMPANHAMENTO.Data_Limite='".$data."' AND Relator = 0 ORDER BY REVISOES.Data_Operacao DESC LIMIT 1";
		
	$qryE = T_query($sqlE);

	if (T_num_rows($qryE)){

		$rowE = T_fetch_array($qryE);
		
		// a partir da segunda versao... nao pode mais editar

		if ($row["versao"]==1)
			$edit = 1;
		else
			$edit = 0;

	}
 

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<title>Registrar revisao para trabalho <?php echo $_GET["idtrabalho"]; ?></title>

<link rel="stylesheet" href="style.css" />

<script language="javascript" src="TAjax.js"></script>

<script> var ajax = new TAjax(); 





function verifica()

{

 	if( document.getElementById('Categoria').value == 5)

	{

 		if (document.getElementById('Coment_Autor').value.length==0) return false;

		if (document.getElementById('Coment_CP').value.length==0) return false;

		if (!document.getElementById('aceitacao1').checked && !document.getElementById('aceitacao2').checked && !document.getElementById('aceitacao3').checked)  

			return false;

	}

	else

	{

   

  		if ( (!document.getElementById('publico_alvo0').checked && !document.getElementById('publico_alvo1').checked && !document.getElementById('publico_alvo2').checked && !document.getElementById('publico_alvo3').checked )	&&	parseInt(document.getElementById('publico_alvo_outros').value.length) == 0	)  

			return false;

 			

		if (!document.getElementById('originalidade1').checked && !document.getElementById('originalidade2').checked && !document.getElementById('originalidade3').checked && !document.getElementById('originalidade4').checked && !document.getElementById('originalidade5').checked)  

			return false;	

			

		if (!document.getElementById('densidade1').checked && !document.getElementById('densidade2').checked && !document.getElementById('densidade3').checked)  

			return false;

			

		if (!document.getElementById('redacao1').checked && !document.getElementById('redacao2').checked && !document.getElementById('redacao3').checked && !document.getElementById('redacao4').checked && !document.getElementById('redacao5').checked)  

			return false;

			

		if (!document.getElementById('referencias1').checked && !document.getElementById('referencias2').checked && !document.getElementById('referencias3').checked && !document.getElementById('referencias4').checked)  

			return false;

			

		if (!document.getElementById('aceitacao1').checked && !document.getElementById('aceitacao2').checked && !document.getElementById('aceitacao3').checked)  

			return false;			

		

		if (!document.getElementById('compreensao1').checked && !document.getElementById('compreensao2').checked && !document.getElementById('compreensao3').checked && !document.getElementById('compreensao4').checked)  

			return false;

	 

	 	if (document.getElementById('Coment_Autor').value.length==0) return false;

		if (document.getElementById('Coment_CP').value.length==0) return false;

	}

	return true;	

}





function postar()

{



	if (verifica()) document.getElementById('frmRevisao').submit();

	else alert('É necessario preencher todos os campos para continuar');

}



function publicoAlvoOutros (valor)

{

	if( valor == true)

	{

		document.getElementById('publico_alvo_outros').style.display= 'block';

	}

	else

	{

		document.getElementById('publico_alvo_outros').value = '';

		document.getElementById('publico_alvo_outros').style.display= 'none';

	}

}



  





</script>

</head>





<body>



 

<?php if ($podeRevisar){ ?>
<form id="frmRevisao" name="frmRevisao" method="post" action="revisaoPost.php" enctype="multipart/form-data">
<?php } ?>
  <table width="650" height="209" border="0" align="center" cellpadding="0" cellspacing="0">

	<tr>

      <td height="21" colspan="2">&nbsp;</td>

    </tr>



    <tr>

		<td colspan="2">

			<table style="border:1px; border-style:solid; border-color:#000000"> 	

				<tr bgcolor="#ebe1d7">

		  			<td height="21" colspan="2" align="right"> <font color="#0000"><b>Categoria <?php echo utf8_encode($row["Categoria_Trabalho"]); ?></b></font></td>

				</tr>

				<tr>

		  			<td height="21" colspan="2" align="justify"> <?php echo utf8_encode($row["Conteudo"]); ?></td>

				</tr>

			</table>

		</td>

	</tr>

	<tr>

      <td height="21" colspan="2">&nbsp;</td>

    </tr>

	

	<tr>

      <td height="21" colspan="2"><strong>Trabalho número:</strong> <?php echo $_GET["idtrabalho"]; ?></td>

    </tr>

	<tr>

      <td height="21" colspan="2">&nbsp;</td>

    </tr>	

	

    <tr>

      <td height="30" colspan="2"><strong>Título:</strong> <?php echo utf8_encode($row["Titulo"]); ?></td>

    </tr>
	<?php if ($podeRevisar){ ?>
	<tr>

	  <td colspan="2" align="right"><?php

	  // VERIFICAR SE É RELATOR PARA VER OS OUTROS COMENTARIOS....

	  $sqlR = "SELECT Relator FROM REVISORES WHERE Id_Usr = ".$_SESSION["USERID"]." AND Id_Trabalho = '".$_GET["idtrabalho"]."'";

	  $rowR = T_fetch_array(T_query($sqlR));

	  if ($rowR[0]){

	  	echo "<a href=\"javascript://\"  style=\"font-size:8pt; color:#FF0000; font-weight:bold;\" onClick=\"cview = dhtmlwindow.open('cv','ajax','coment_view.php?idtrabalho=".$row["Id_Trabalho"]."','Ver coment&aacute;rios do trabalho ".$row["Id_Trabalho"]."','width=440px,height=180px,center=1,resize=0,scrolling=1');\">Ver coment&aacute;rios dos outros revisores</a>";

	  }

	  

	  

	  ?>

         </td>

	</tr>

	<tr>

      <td height="21" colspan="2">&nbsp;</td>

    </tr>

	

<?php 



if( $row['Id_Categoria_Trabalho'] != 5 )

{

?>	

    <tr>

      <td colspan='2' height="68" align="justify" valign="top"><strong>Público Alvo</strong><br /> 

	  	Na opinião do revisor, a que público-alvo se destina o trabalho (mais de uma opção pode ser

preenchida):<br /> 

		

<?php

	$ocorrencia_publico_alvo = array();

	$query = "SELECT * FROM OCORRENCIA_PUBLICO_ALVO INNER JOIN ACOMPANHAMENTO USING(Id_Trabalho) WHERE Id_Usr = ".$_SESSION["USERID"]." AND Id_Trabalho = '".$_GET["idtrabalho"]."'  AND ACOMPANHAMENTO.Data_Limite='".$data."'";

	$resultado = T_query($query);

	if( T_num_rows($resultado))

	{

		while( $campo = T_fetch_array($resultado))

		{
			$ocorrencia_publico_alvo[$campo['Id_Publico_Alvo']] = 1;

		}

	}





	$query = "SELECT * FROM Publico_Alvo";

	$resultado = T_query($query);

	$count = 0;

	while( $campo = T_fetch_array($resultado))

	{

 		$checked = ( $ocorrencia_publico_alvo[$campo['Id_Publico_Alvo']] == 1  ) ? " checked='true' " : "";

		$valor_checked = ( $ocorrencia_publico_alvo[$campo['Id_Publico_Alvo']] == 1  ) ? $campo['Id_Publico_Alvo'] : "0";

		echo"

			<input type='checkbox' id='publico_alvo".$count."' value='".$campo['Id_Publico_Alvo']."' $checked  onclick=\"setaCheckbox( {$count}, this.value, 'publico_alvo".$count."');\">".utf8_encode($campo['Publico_Alvo'])."

			<input type='hidden' name='publico_alvo{$count}' value='{$valor_checked}' id='set_publico_alvo{$count}' <br />";			

 						

		$count++;	

	}

?>



<?php

	$checked_pa_outros = (!empty( $rowE['Publico_Alvo_Outros'] ) ) ? " checked='true' " : "" ;

	$style_outros = (!empty( $rowE['Publico_Alvo_Outros'] ) ) ? "style='display: block;' " : "style='display: none;'" ;

?>		

		

		

		

		

		<input type='checkbox' onclick="publicoAlvoOutros(this.checked)" <?php echo $checked_pa_outros ?> />Outros: <br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

											  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

		<textarea  <?php echo $style_outros; ?> id='publico_alvo_outros' name='publico_alvo_outros' rows="5" cols="70"  /><?php echo utf8_encode($rowE['Publico_Alvo_Outros']); ?></textarea>

			



	  </td>

    </tr>

	<tr>

      <td height="21" colspan="2">&nbsp;</td>

    </tr>

	<tr>

      <td height="21" colspan="2">&nbsp;</td>

    </tr>



    <tr>

      <td colspan='2' height="68" align="justify" valign="top"><strong>Originalidade</strong><br /> 

	  	Neste tópico, os revisores devem avaliar o trabalho sob a perspectiva do avanço do conhecimento e

também a contribuição do(s) autor(es) na resolução de problemas tecnológicos. Nem sempre os

trabalhos se apresentam como solução inédita, mas deve-se considerar a criatividade e a inovação

abordadas no mesmo.<br />  

<?php

	$query = "SELECT * FROM Originalidade";

	$resultado = T_query($query);

	$count = 1;

	while( $campo = T_fetch_array($resultado))

	{

		$checked = ( $campo['Id_Originalidade'] == $rowE['Originalidade']) ? " checked='true' " : "";

		

		echo"

		<input type='radio' name='originalidade' id='originalidade".$count."' value='".$campo['Id_Originalidade']."' $checked >".utf8_encode($campo['Originalidade'])."<br />";

		

		$count++;

	}

?>  

 	  </td>

    </tr>

	<tr>

      <td height="21" colspan="2">&nbsp;</td>

    </tr>

	<tr>

      <td height="21" colspan="2">&nbsp;</td>

    </tr>



    <tr>

      <td colspan='2' height="68" align="justify" valign="top"><strong>Densidade</strong><br /> 

	  	Com relação ao tamanho do trabalho (número de páginas e de itens) e ao seu conteúdo:<br /> 

<?php

	$query = "SELECT * FROM Densidade";

	$resultado = T_query($query);

	$count = 1;

	while( $campo = T_fetch_array($resultado))

	{

		$checked = ( $campo['Id_Densidade'] == $rowE['Densidade']) ? " checked='true' " : "";

		

		echo"

		<input type='radio' name='densidade' id='densidade".$count."' value='".$campo['Id_Densidade']."' $checked >".utf8_encode($campo['Densidade'])."<br />";

		

		$count++;

	}

?>  

 	  </td>

    </tr>

	<tr>

      <td height="21" colspan="2">&nbsp;</td>

    </tr>

	<tr>

      <td height="21" colspan="2">&nbsp;</td>

    </tr>



   <tr>

      <td colspan='2' height="68" align="justify" valign="top"><strong>Redação</strong><br /> 

	  	O(s) autor(es) utilizou(aram) corretamente dos recursos de grafia e de sintaxe da língua portuguesa?<br />

<?php

	$query = "SELECT * FROM Redacao";

	$resultado = T_query($query);

	$count = 1;

	while( $campo = T_fetch_array($resultado))

	{

		$checked = ( $campo['Id_Redacao'] == $rowE['Redacao']) ? " checked='true' " : "";

		

		echo"

		<input type='radio' name='redacao' id='redacao".$count."' value='".$campo['Id_Redacao']."' $checked >".utf8_encode($campo['Redacao'])."<br />";

		

		$count++;

		

		

	}

?>  

 	  </td>

    </tr>

	<tr>

      <td height="21" colspan="2">&nbsp;</td>

    </tr>



	<tr>

      <td height="21" colspan="2">&nbsp;</td>

    </tr>

	

<?php

/*	 	

	 <tr>

      <td colspan='2' height="68" align="justify" valign="top"><strong>Título</strong><br /> 

	  	São adequados ao trabalho?<br />

		<input type="radio" name="titulo" value="1" <?php if( $rowE['Titulo'] == 1 ) echo " checked='true' "; ?> />Perfeitamente.<br /> 

		<input type="radio" name="titulo" value="2" <?php if( $rowE['Titulo'] == 2 ) echo " checked='true' "; ?>/>Deve(m) ser alterado(s) para enfatizar a idéia principal do trabalho.<br />

		<input type="radio" name="titulo" value="3" <?php if( $rowE['Titulo'] == 3 ) echo " checked='true' "; ?>/>Não está(ão) de acordo com o trabalho.<br />

  	  </td>

    </tr>

	<tr>

      <td height="21" colspan="2">&nbsp;</td>

    </tr>



	<tr>

      <td height="21" colspan="2">&nbsp;</td>

    </tr>

*/

?>	

	 <tr>

      <td colspan='2' height="68" align="justify" valign="top"><strong>Referências Bibliográficas</strong><br /> 

<?php

	$query = "SELECT * FROM Referencias";

	$resultado = T_query($query);

	$count = 1;

	while( $campo = T_fetch_array($resultado))

	{

		$checked = ( $campo['Id_Referencias'] == $rowE['Referencias']) ? " checked='true' " : "";

		

		echo"

		<input type='radio' name='referencias' id='referencias".$count."' value='".$campo['Id_Referencias']."' $checked >".utf8_encode($campo['Referencias'])."<br />";

		

		$count++;

	}

?>  

  	  </td>

    </tr>

	<tr>

      <td height="21" colspan="2">&nbsp;</td>

    </tr>

	<tr>

      <td height="21" colspan="2">&nbsp;</td>

    </tr>

	<tr>

      <td colspan='2' height="68" align="justify" valign="top"><strong>Compreensão do trabalho</strong><br />

	  De acordo com o esforço despendido na compreensão do trabalho:<br /> 

<?php

	$query = "SELECT * FROM Compreensao";

	$resultado = T_query($query);

	$count = 1;

	while( $campo = T_fetch_array($resultado))

	{

		$checked = ( $campo['Id_Compreensao'] == $rowE['Compreensao']) ? " checked='true' " : "";

		

		echo"

		<input type='radio' name='compreensao' id='compreensao".$count."' value='".$campo['Id_Compreensao']."' $checked >".utf8_encode($campo['Compreensao'])."<br />";

		

		$count++;

	}

?>  



  	  </td>

    </tr>	

	

	<tr>

      <td height="21" colspan="2">&nbsp;</td>

    </tr>

	<tr>

      <td height="21" colspan="2">&nbsp;</td>

    </tr>

	

	

	

<?php

}

?>



<tr>

      <td colspan='2' height="68" align="justify" valign="top"><strong>Com relação à aceitação do trabalho</strong><br /> 





<?php

	$query = "SELECT * FROM Aceitacao";

	$resultado = T_query($query);

	$count = 1;

	while( $campo = T_fetch_array($resultado))

	{

		$checked = ( $campo['Id_Aceitacao'] == $rowE['Aceitacao']) ? " checked='true' " : "";

		

		echo"

		<input type='radio' name='aceitacao' id='aceitacao".$count."' value='".$campo['Id_Aceitacao']."' $checked >".utf8_encode($campo['Aceitacao'])."<br />";

	

		$count++;	

	}

?>  



  	  </td>

    </tr>

	

	

	

	

	<tr>

      <td height="21" colspan="2">&nbsp;</td>

    </tr>

	<tr>

      <td height="21" colspan="2">&nbsp;</td>

    </tr>



    <tr>

      <td width="174" height="68" align="left" valign="top" colspan="2"><strong>Comentários para o autor:</strong> <br>

		<textarea name="Coment_Autor" cols="90" rows="4" id="Coment_Autor"><?php 

	  	if ($edit) echo utf8_encode($rowE["Coment_Autor"]);

		  ?></textarea>

		</td>

    </tr>

	<tr>

      <td height="21" colspan="2">&nbsp;</td>

    </tr>

	<tr>

      <td height="21" colspan="2">&nbsp;</td>

    </tr>



    <tr>

      <td height="68" align="left" valign="top" colspan="2">

	  	<strong>Comentários para o comitê de publicações:</strong>

	 	<br />

	 	<textarea name="Coment_CP" cols="90" rows="4" id="Coment_CP"><?php

	  	if ($edit) echo utf8_encode($rowE["Coment_CP"]);

	  ?></textarea></td>

    </tr>

	<tr>

		<td colspan="2"><br>

				<table style="border:1px; border-color:#FF0000; border-style:solid; width:537px;" >

					

					<tr>

						<td align="left" valign="top">Selecionar Arquivo </td>

						<td><input name="Arquivo" type="file" id="Arquivo" size="35"/></td>

					</tr>

					<tr>

						<td colspan="2">

							<font color="#FF0000" size="1">* OPCIONAL - Enviar documento revisado com sugestões para os autores.</font>

						</td>

					</tr>

				</table>

			</td>

	</tr>

	<tr>

	  <td>&nbsp;</td>

	  <td align="right">&nbsp;</td>

	</tr>

    <tr>

      <td height="22" align="right"><input type="button" name="Button" value="Registrar Revisão" onclick="postar();" /></td>

      <td align="left" style="padding-left: 5px;"><input type="button" name="Submit2" value="Cancelar" onclick="rwin.close();" /></td>

    </tr>

	<?php } //fim do if($podeRevisar)?>

  </table>	

<?php if ($podeRevisar){ ?>
	  <input type="hidden" name="Editar" id="Editar" value="<?php echo $edit; ?>" />

  <input type="hidden" name="Id_Trabalho" id="Id_Trabalho" value="<?php echo $_GET["idtrabalho"]; ?>" />

  <input type="hidden" name="Id_Usr" id="Id_Usr" value="<?php echo $_SESSION["USERID"]; ?>" />

  <input type="hidden" name="Categoria" id="Categoria" value="<?php echo $row['Id_Categoria_Trabalho']; ?>" />

</form>
<?php } ?>
<div id="teste"  ></div>



</body>

<div id="nada" style="display:none; visibility:hidden;"></div>



</html>

