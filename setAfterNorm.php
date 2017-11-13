<?php

include_once("sessions.php");
allow(48);
include_once("conexao.php");

$idtrabalho = $_GET["idtrabalho"];

$qryTrab = T_query("SELECT Titulo, Resumo, Versao, Id_Categoria_Trabalho FROM TRABALHOS WHERE Id_Trabalho = '".$idtrabalho."'");
$rowTrab = T_fetch_array($qryTrab);

?>
<link rel="stylesheet" href="style.css">
<form action="postAfterNorm.php" method="post" enctype="multipart/form-data">
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
    <td>
        <input name="f_trabalho" type="file" id="f_trabalho">
        <input type="hidden" name="idtrabalho" id="idtrabalho" value="<?php echo $idtrabalho; ?>" />
	<input type="hidden" name="versao" id="versao" value="<?php echo $rowTrab["Versao"]; ?>" />
    </td>
  </tr>
  <?php if (($rowTrab["Id_Categoria_Trabalho"]!=2)&&($rowTrab["Id_Categoria_Trabalho"]!=3)&&($rowTrab["Id_Categoria_Trabalho"]!=5)){ ?>
  <tr>
    <td>Ficha Catalográfica: </td>
    <td><input name="f_fichaCat" type="file" id="f_fichaCat"></td>
  </tr>
  <?php } ?>
  <tr>
    <td>&nbsp;</td>
    <td><input type="submit" name="Submit" value="Enviar"></td>
  </tr>
</table>
</form>