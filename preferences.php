<?php 	
		include("sessions.php");
		allow(16);
		include_once("conexao.php"); 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("inc/header.php"); ?>
</head>
<script language="javascript" src="windowfiles/dhtmlwindow.js"></script>
<link rel="stylesheet" href="windowfiles/dhtmlwindow.css" type="text/css" />
<script language="javascript" src="TAjax.js"></script>
<script> var ajax = new TAjax(); </script>
<body>
<div align="center">
<table width="752" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
<tr><td height="120"><?php include("inc/topo.php"); ?></td></tr>  
<tr>
  <td width=752 align="center" valign=top style="padding:10px 10px 0 10px;"><h1>CONFIGURAÇÃO
  </h1>
  <?php 
  
  	if (!$_GET["conf"]){
		echo "<p>&nbsp;</p>
		<p><a href=\"preferences.php?conf=1\" style=\"color:#0000FF;\">Configurações de sistema</a></p>
		<p><a href=\"preferences.php?conf=2\" style=\"color:#0000FF;\">Tabelas de domínio</a></p>
		<p>&nbsp;</p>";
	}
  	else if ($_GET["conf"] == 1){
	$sql = "SELECT * FROM EMAIL_CONF";
	$row = T_fetch_array(T_query($sql));
	$row["Assinatura_Presidente"] = str_replace("\\n","\n",$row["Assinatura_Presidente"]);
	$row["Assinatura_Secretario"] = str_replace("\\n","\n",$row["Assinatura_Secretario"]);
?>
  <form id="form1" name="form1" method="post" action="preferences_post1.php">
    <table width="500" border="0" align="center" cellpadding="0" cellspacing="0">
      <tr>
        <td width="300"><p>Assinatura do Presidente</p></td>
        <td width="240"><textarea name="assinatura_presidente" cols="35" rows="2" id="assinatura_presidente" onkeypress="if (this.value.length > 10) substr();"><?php echo utf8_encode($row["Assinatura_Presidente"]); ?></textarea></td>
      </tr>
      <tr>
        <td><p>Assinatura do Secretário</p></td>
        <td><textarea name="assinatura_secretario" cols="35" rows="2" id="assinatura_secretario"><?php echo utf8_encode($row["Assinatura_Secretario"]); ?></textarea></td>
      </tr>
      <tr>
        <td>E-mail do Presidente</td>
        <td><input name="email_presidente" type="text" id="email_presidente" value="<?php echo utf8_encode($row["Email_Presidente"]); ?>" size="35" maxlength="80" /></td>
      </tr>
      <tr>
        <td>E-mail do Secretário </td>
        <td><input name="email_secretario" type="text" id="email_secretario" value="<?php echo utf8_encode($row["Email_Secretario"]); ?>" size="35" maxlength="80" /></td>
      </tr>
	 <tr>
        <td>Nome do Revisor de Texto </td>
        <td><input name="revisor" type="text" id="revisor" value="<?php echo utf8_encode($row["Revisor"]); ?>" size="35" maxlength="80" /></td>
      </tr>
      <tr>
        <td>E-mail do Revisor de Texto </td>
        <td><input name="email_revisor" type="text" id="email_revisor" value="<?php echo utf8_encode($row["Email_Revisor"]); ?>" size="35" maxlength="80" /></td>
      </tr>
      <tr>
        <td>Nome do Bibliotecário </td>
        <td><input name="bibliotecario1" type="text" id="bibliotecario1" value="<?php echo utf8_encode($row["Bibliotecario1"]); ?>" size="35" maxlength="80" /></td>
      </tr>
      <tr>
        <td>Nome do Bibliotecário (2) </td>
        <td><input name="bibliotecario2" type="text" id="bibliotecario2" value="<?php echo utf8_encode($row["Bibliotecario2"]); ?>" size="35" maxlength="80" /></td>
      </tr>
      <tr>
        <td>E-mail do Bibliotecário </td>
        <td><input name="email_bib_1" type="text" id="email_bib_1" value="<?php echo utf8_encode($row["Email_Bib_1"]); ?>" size="35" maxlength="80" /></td>
      </tr>
      <tr>
        <td>E-mail do Bibliotecário (2) </td>
        <td><input name="email_bib_2" type="text" id="email_bib_2" value="<?php echo utf8_encode($row["Email_Bib_2"]); ?>" size="35" maxlength="80" /></td>
      </tr>
      <tr>
        <td>Nome do Editor </td>
        <td><input name="editor" type="text" id="editor" value="<?php echo utf8_encode($row["Editor"]); ?>" size="35" maxlength="80" /></td>
      </tr>
      <tr>
        <td>E-mail do Editor </td>
        <td><input name="email_editor" type="text" id="email_editor" value="<?php echo utf8_encode($row["Email_Editoracao"]); ?>" size="35" maxlength="80" /></td>
      </tr>
    </table>
    <br />
    <input type="submit" name="Submit" value="Salvar" />&nbsp;<input type="button" name="Submit2" value="Cancelar" onclick="document.location = 'preferences.php'"/>
  </form>
	<?php } //FIM DO IF CONF == 1
	else if ($_GET["conf"]==2){
	 ?>  
	 <a href="javascript:void(0);" onclick="rwin=dhtmlwindow.open('rbox', 'ajax', 'listTabDom.php?tab=0', 'Tabela de &Aacute;reas de Atua&ccedil;&atilde;o', 'width=400px,height=380px,center=1,resize=0,scrolling=1');" style="color:#0000FF">Tabela de Áreas de Atuação</a><br />
	 <a href="javascript:void(0);" onclick="rwin=dhtmlwindow.open('rbox', 'ajax', 'listTabDom.php?tab=1', 'Tabela de Cargos', 'width=400px,height=380px,center=1,resize=0,scrolling=1');" style="color:#0000FF">Tabela de Cargos</a><br />
	 <a href="javascript:void(0);" onclick="rwin=dhtmlwindow.open('rbox', 'ajax', 'listTabDom.php?tab=2', 'Tabela de Categorias de Trabalho', 'width=400px,height=380px,center=1,resize=0,scrolling=1');" style="color:#0000FF">Tabela	de Categorias de Trabalho</a><p /><?php } //fim do if conf == 2?>	 </td>
</tr>

<tr><td style="padding: 5px 0 0 0;">
<?php include("inc/copyright.php"); ?>
</td></tr>
</table>
</div>
</body>
</html>
