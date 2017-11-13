<?php 	
		include("sessions.php");
		allow(1);
?>
<?php include_once("conexao.php"); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("inc/header.php"); ?>
</head>
<script language="javascript" src="TAjax.js"></script>
<script> var ajax = new TAjax(); </script>
<body>
<div align="center">
<table width="752" height="420" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
<tr><td height="120"><?php include("inc/topo.php"); ?></td></tr>  
<tr>
  <td width=752 height="300" align="center" valign=top style="padding:10px 10px 0 10px;">
	<?php if ($_GET["res"]=="success"){ ?>
    <h1 style="color:#FF0000">TRABALHO ENVIADO COM SUCESSO!</h1>
    <p><a href="index.php">Voltar para a página inicial</a> </p>
	<?php } else if ($_GET["res"]=="error") { ?>
    <h1 style="color:#FF0000">ERRO AO INSERIR TRABALHO</h1>
	<?php } ?>
	  <div align="center">
	  <a href="sub_trab.php">
	    <h1>Submeter novo trabalho</h1></a><a href="resub_trab.php">
	    <h1>Submeter correção de trabalho</h1></a>
      </div>
		
	</td>
</tr>

<tr><td style="padding: 5px 0 0 0;">
<?php include("inc/copyright.php"); ?>
</td></tr>
</table>
</div>
</body>
</html>
