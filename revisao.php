<?php 	
	include("sessions.php");
	allow(2);		
?>
<?php include("conexao.php"); 
?>
<html>
<head>
<?php include("inc/header.php"); ?>
</head>
<script language="javascript" src="TAjax.js"></script>
<script language="javascript">
var ajax = new TAjax();
</script>
<link rel="stylesheet" href="balloontip.css">
<script language="javascript" src="balloontip.js"></script>
<link rel="stylesheet" href="windowfiles/dhtmlwindow.css" />
<script language="javascript" src="windowfiles/dhtmlwindow.js">

/***********************************************
* DHTML Window Widget- © Dynamic Drive (www.dynamicdrive.com)
* This notice must stay intact for legal use.
* Visit http://www.dynamicdrive.com/ for full source code
***********************************************/

</script>
<script>

function setaCheckbox( indice, valor, id )
{
	if(document.getElementById(id).checked == true )
	{	
		document.getElementById('set_publico_alvo'+indice).value = valor;
	}
	else
	{	
		document.getElementById('set_publico_alvo'+indice).value = 0;
	}
	
}
</script>

<body>
<div align="center">
<table width="752" height="420" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF" >
<tr><td height="120"><?php include("inc/topo.php"); ?></td></tr>  
<tr>
<td align="center" valign="top">
&nbsp;
<h1>Revisão</h1>
&nbsp;
<div align="left" style="color:#FF0000; font-size:8pt; font-weight:bold;">* Clique sobre o título do trabalho para enviar a revis&atilde;o</div>
<div id="divListaTrabalhos"></div>
</td>
</tr>
<tr><td>
<?php include("inc/copyright.php"); ?>
</td></tr>
</table>

</div>
<div class="divBottomFix" id="divMsg" style="display:none;background-color:#FF2828; color:#FEFEFE; border:#000033 1px solid; padding: 5px 15px 5px 15px; margin: 0 0 2px 2px;">Registro inserido com sucesso</div>
<script language="javascript">
ajax.loadDiv('divListaTrabalhos','mylist_revtrabalhos.php');
</script>
</body>
</html>

