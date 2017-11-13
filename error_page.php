<html>
<header>
<?php include("inc/header.php"); ?>
<style>
.erro{
font-size:14pt;
color:#FF0000;
font-weight:bold;
text-shadow:#666666;
}
</style>
</header>
<body>
<div align="center">
<table width="752" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF" >
<tr><td colspan="2"><?php 
$short = true;
include("inc/topo.php"); ?></td></tr>  
<tr>
<td align="center" height="300" class="erro"><img src="img/security_icon.gif" width="34" height="34" align="absmiddle">
  <?php if ($_GET["erro"]==0) {?>Não foi possivel estabelecer conexão com o Banco de Dados. <?php } ?>
  <?php if ($_GET["erro"]==1) {?>Não há suporte para o componente de conexão no servidor.<?php } ?></td>
</tr>
<tr>
<td colspan="2"><?php include("inc/copyright1.php"); ?></td>
</tr>
</table>

</div>
</body>
</html>

