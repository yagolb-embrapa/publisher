<?php

	include_once("conexao.php");

$sql = " SELECT `TRABALHOS`.`Id_Trabalho`, `TRABALHOS`.`Titulo`, `TRABALHOS`.`Resumo`, `CATEGORIAS_TRABALHO`.`Categoria_Trabalho`, `TRABALHOS`.`Versao` 
FROM `TRABALHOS` , `CATEGORIAS_TRABALHO`
WHERE `TRABALHOS`.`Id_Trabalho` = '".$_GET["id_trabalho"]."'
AND `CATEGORIAS_TRABALHO`.`Id_Categoria_Trabalho` = `TRABALHOS`.`Id_Categoria_Trabalho`";
$qry = T_query($sql);

$row = T_fetch_array($qry);

?>
<html>
<?php include("inc/header.php"); ?>
<body>
<div style="width:450px; padding: 0 0 0 10px;">
  <form action="repost_trabalho.php" method="post" enctype="multipart/form-data" name="form1">
    <p><strong>Código:</strong> <?php echo $row["Id_Trabalho"]; ?> <strong>Versão atual:</strong> v<?php echo $row["Versao"]; ?><br>
        <strong>Titulo:</strong> <?php echo utf8_encode($row["Titulo"]); ?><br>
        <strong>Resumo:</strong>
    <div align="left" style="width:400px; overflow:auto; height: 80px;"><?php echo utf8_encode($row["Resumo"]); ?></div>
 
 	<table style="border:1px; border-color:#FF0000; border-style:solid; width:400px;" >
		<tr>
			<td>    <strong>Arquivo:</strong></td>
			<td>    <input name="Arquivo" type="file" id="Arquivo"></td>
		</tr>
		
 		<tr>							
			<td colspan="2">
					<font color="#FF0000" size="1">* Favor não informar autoria no texto do trabalho anexado.</font>
			</td>
		</tr>
	</table>
    <input type="hidden" name="id_trabalho" id="id_trabalho" value="<?php echo $_GET["id_trabalho"]; ?>" />
    <input type="hidden" name="versao" id="versao" value="<?php echo $row["Versao"]; ?>" />
    <div align="center" style="padding: 5px 0 0 0;">
      <input name="nu_blya" type="submit" value="Enviar Trabalho"/>
    </div>
  </form>
</div>
</body>
</html>