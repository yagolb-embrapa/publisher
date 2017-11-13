<?php 	
	include("sessions.php");
	allow(58);		
?>
<?php include("conexao.php"); 


?>
<html>
<head>
<?php include("inc/header.php"); ?>
<link rel="stylesheet" href="ibox/ibox.css" />
<script language="javascript" src="ibox/ibox.js"></script>
</head>
<script language="javascript" src="TAjax.js"></script>
<script language="javascript">
var ajax = new TAjax();
function mostra(id_trabalho){
	sm('opera',300,140);
	ajax.loadDiv('opera','list_trabalhos.php');
}

</script>
<link rel="stylesheet" href="windowfiles/dhtmlwindow.css" />
<script language="javascript" src="windowfiles/dhtmlwindow.js">

/***********************************************
* DHTML Window Widget- © Dynamic Drive (www.dynamicdrive.com)
* This notice must stay intact for legal use.
* Visit http://www.dynamicdrive.com/ for full source code
***********************************************/

</script>
<body>
<div id="opera" style="display:none; visibility:hidden;">ajskahkdjh</div>
<div align="center">
<table width="752" height="420" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF" >
<tr><td height="120"><?php include("inc/topo.php"); ?></td></tr>  
<tr>
<td align="center" valign="top">
&nbsp;
<h1>Meus trabalhos</h1>
&nbsp;

<div id="divListaTrabalhos"></div>
</td>
</tr>
<tr><td>
<?php include("inc/copyright.php"); ?>
</td></tr>
</table>

</div>
<div id="inv" style="display:none; visibility:hidden"></div>
<div class="divBottomFix" id="divMsg" style="display:none;background-color:#FF2828; color:#FEFEFE; border:#000033 1px solid; padding: 5px 15px 5px 15px; margin: 0 0 2px 2px;">Registro inserido com sucesso</div>
<script language="javascript">
ajax.loadDiv('divListaTrabalhos','mylist_revision.php');
</script>

</body>
</html>

