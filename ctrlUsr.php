<?php 	
	include("sessions.php");
	include("conexao.php"); 


?>
<html>
<head>
<?php include("inc/header.php"); ?>
</head>
<script language="javascript" src="TAjax.js"></script>
<script language="javascript">
var ajax = new TAjax();
</script>
<body>
<div align="center">
<table width="752" height="420" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF" >
<tr><td height="120"><?php include("inc/topo.php"); ?></td></tr>  
<tr>
  <td width=752 height="300" align="center" valign=top style="padding:20px 10px 0 10px;">
  <div align="center" id="divManip" style="margin: 0 0 25px 0; padding: 2px 2px 2px 2px;">
  </div>
  <div align="center" id="divListUsr">
  </div>
  </td>
</tr>
<tr><td>
<?php include("inc/copyright.php"); ?>
</td></tr>
</table>

</div>
<div class="divBottomFix" id="divMsg" style="display:none;background-color:#FF2828; color:#FEFEFE; border:#000033 1px solid; padding: 5px 15px 5px 15px; margin: 0 0 2px 2px;">Registro inserido com sucesso</div>
<script language="javascript">
ajax.loadDiv('divManip','lista_usr.php');
</script>
</body>
</html>

