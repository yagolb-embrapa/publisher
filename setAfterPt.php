<?php

include_once("sessions.php");
allow(112);
include_once("conexao.php");

$idtrabalho = $_GET["idtrabalho"];

$qryTrab = T_query("SELECT Titulo, Resumo, Versao FROM TRABALHOS WHERE Id_Trabalho = '".$idtrabalho."'");
$rowTrab = T_fetch_array($qryTrab);

?>
<link rel="stylesheet" href="style.css">
<form action="postAfterPt.php" method="post" enctype="multipart/form-data">
<table width="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="35%">Codigo:</td>
    <td width="65%"><?php echo $idtrabalho; ?></td>
  </tr>
  <tr>
    <td>Título:</td>
    <td><?php echo utf8_encode($rowTrab["Titulo"]); ?></td>
  </tr>
  <tr>
    <td>Versão atual: </td>
    <td><?php echo $rowTrab["Versao"]; ?></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>Documento:</td>
    <td><input name="f_trabalho" type="file" id="f_trabalho"></td>
	<input type="hidden" name="idtrabalho" id="idtrabalho" value="<?php echo $idtrabalho; ?>" />
	<input type="hidden" name="versao" id="versao" value="<?php echo $rowTrab["Versao"]; ?>" />
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><input type="submit" name="Submit" value="Enviar"></td>
  </tr>
</table>
</form>