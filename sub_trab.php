<?php 	
		include("sessions.php");
		allow(3);
?>
<?php include_once("conexao.php"); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("inc/header.php"); ?>
</head>
<link rel="stylesheet" href="windowfiles/dhtmlwindow.css" />
<script language="javascript" src="windowfiles/dhtmlwindow.js">

/***********************************************
* DHTML Window Widget- © Dynamic Drive (www.dynamicdrive.com)
* This notice must stay intact for legal use.
* Visit http://www.dynamicdrive.com/ for full source code
***********************************************/

</script>
<script language="javascript" src="TAjax.js"></script>
<script> var ajax = new TAjax(); </script>
<body>
<div align="center">
<table width="752" height="420" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
<tr><td height="120"><?php include("inc/topo.php"); ?></td></tr>  
<tr>
  <td width=752 height="300" align="center" valign=top style="padding:10px 10px 0 10px;">
	  <div align="center">
<h1>Submeter novo trabalho</h1>
    <div style="border:#000066 1px groove; padding:5px 0 5px 0; width:620px;">
	<form action="post_trabalho.php" method="post" enctype="multipart/form-data" name="frmTrabalho" id="frmTrabalho">
        <table width="89%" style="margin: 0 0 5px 0;" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="27%" align="left" valign="top">T&iacute;tulo do trabalho:</td>
            <td width="73%"><textarea name="Titulo" cols="50" rows="2" id="Titulo"></textarea></td>
          </tr>
          <tr>
            <td align="left" valign="top" style="padding: 10px 0 5px 0;"><p>Autores:</p></td>
            <td style="padding: 10px 0 5px 0;"><div id="divAutores"></div></td>
          </tr>
		  <tr>
            <td align="left" valign="top"><p>Resumo:</p></td>
            <td><textarea name="Resumo" cols="50" rows="6" id="Resumo"></textarea></td>
          </tr>
          <tr>
            <td align="left" valign="top">P&uacute;blico Alvo: </td>
            <td><textarea name="Publico_Alvo" cols="50" rows="3" id="Publico_Alvo"></textarea>
				<input type="hidden" name="Id_Usr" id="Id_Usr" value="<?php echo $_SESSION["USERID"]; ?>" />
			</td>
          </tr>
		  <tr>
            <td align="left" valign="top">Observa&ccedil;&otilde;es: </td>
            <td><textarea name="Observacoes" cols="50" rows="3" id="Observacoes"></textarea>
			</td>
          </tr>
          <tr>
            <td align="left" valign="top">Categoria:</td>
            <td><select name="Id_Categoria_Trabalho" id="Id_Categoria_Trabalho">
				<option selected="selected" value="0">----</option>
				<?php 
					$qryStr = "SELECT `Id_Categoria_Trabalho`,`Categoria_Trabalho` FROM `CATEGORIAS_TRABALHO`";
					$qry = T_query($qryStr);
					
					while ($row = T_fetch_array($qry)){
						echo "<option value=\"".$row[0]."\">".utf8_encode($row[1])."</option>";
					}
					
				?>
            </select>
            </td>
          </tr>
          <tr>
            <td align="left" valign="top">&Aacute;rea</td>
            <td>
			<select name="Id_Area_Atuacao" id="Id_Area_Atuacao">
				<option selected="selected" value="0">----</option>
				<?php 
					$qryStr = "SELECT `Id_Area_Atuacao`,`Area_Atuacao` FROM `AREAS_ATUACAO`";
					$qry = T_query($qryStr);
					
					while ($row = T_fetch_array($qry)){
						echo "<option value=\"".$row[0]."\">".utf8_encode($row[1])."</option>";
					}
					
				?>
            </select>
			</td>
          </tr>
		  <tr>
            <td align="left" valign="top">Segunda &Aacute;rea</td>
            <td>
			<select name="Id_Area_Atuacao1" id="Id_Area_Atuacao1">
				<option selected="selected" value="0">----</option>
				<?php 
					$qryStr = "SELECT `Id_Area_Atuacao`,`Area_Atuacao` FROM `AREAS_ATUACAO`";
					$qry = T_query($qryStr);
					
					while ($row = T_fetch_array($qry)){
						echo "<option value=\"".$row[0]."\">".utf8_encode($row[1])."</option>";
					}
					
				?>
            </select>
			</td>
          </tr>
          <tr>
		  	<td colspan="2">
				<table style="border:1px; border-color:#FF0000; border-style:solid; width:537px;" >
					
					<tr>
						<td align="left" valign="top">Selecionar Arquivo </td>
						<td><input name="Arquivo" type="file" id="Arquivo" size="35"/></td>
					</tr>
					<tr>
						<td colspan="2">
							<font color="#FF0000" size="1">* Favor não informar autoria no texto do trabalho anexado.</font>
						</td>
					</tr>
				</table>
			</td>
         </tr>
        </table>
		<input type="hidden" name="autor_id" value="<?php echo $_SESSION["USERID"]; ?>" />
		<input type="hidden" id="Autores" name="Autores" value="" />
		<input type="button" value="Enviar Trabalho" onClick="if (verifica()) document.getElementById('frmTrabalho').submit();"/>
		<input type="button" value="Cancelar" onClick="window.location = 'submissao.php'" />
      </form>
	</div>
<script language="javascript">

ajax.loadDiv('divAutores','multiple_aut.php');

function verifica(){
	
	if (document.getElementById('Titulo').value.length==0){
		alert('O Titulo do trabalho deve ser informado.');
		return false;
	}
	if (document.getElementById('Resumo').value.length==0){
		alert('O Resumo do trabalho deve ser informado.');
		return false;
	}
	if (document.getElementById('Publico_Alvo').value.length==0){
		alert('O Publico Alvo do trabalho deve ser informado.');
		return false;
	}	
	if (document.getElementById('Id_Categoria_Trabalho').value==0){
		alert('A Categoria do Trabalho do trabalho deve ser informada.');
		return false;
	}	
	if (document.getElementById('Id_Area_Atuacao').value==0){
		alert('A Area  do trabalho deve ser informada.');
		return false;
	}
	if (document.getElementById('Arquivo').value.length==0){
		alert('Nenhum arquivo selecionado para o envio.');
		return false;
	}
	if (!document.getElementById('Arquivo').value.match('.')){
		alert(document.getElementById('Arquivo').value+' não é um arquivo valido.');
		return false;
	} else {
		var arr = document.getElementById('Arquivo').value.split('.');
		var exten = new Array("odt","doc","docx","pdf","zip");
		var val = false;
		for (i in exten){
			if (exten[i] == arr[arr.length-1]) val = true;
		}
		if (!val){
			alert('Extensao invalida.');
			return false;
		}
	}
	
	document.getElementById('Autores').value = concatenaValores('autor_id');	
	
	return true;
	
}

</script>
	  </div>
	  <br />
	</td>
</tr>

<tr><td style="padding: 5px 0 0 0;">
<?php include("inc/copyright.php"); ?>
</td></tr>
</table>
</div>
</body>
</html>
