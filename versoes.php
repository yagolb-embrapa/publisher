<?php 	
	include("sessions.php");
	allow(4);		
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
<link rel="stylesheet" href="windowfiles/dhtmlwindow.css" />
<script language="javascript" src="windowfiles/dhtmlwindow.js">

/***********************************************
* DHTML Window Widget- © Dynamic Drive (www.dynamicdrive.com)
* This notice must stay intact for legal use.
* Visit http://www.dynamicdrive.com/ for full source code
***********************************************/

</script>
<script language="javascript">

function gogogo(){
	
	var tipo = document.getElementById('cbTipo').value;
	var comp = (document.getElementById('rbComp').checked)?document.getElementById('rbComp').value:document.getElementById('rbComp1').value;
	
	var dtini = document.getElementById('dtInit').value;
	var dtend = document.getElementById('dtEnd').value;
	
	if (periodoValido(dtini,dtend)){
		relwin = dhtmlwindow.open('rbox','ajax','relatorioEstatistica.php?comp='+comp+'&tipo='+tipo+'&dtini='+dtini+'&dtend='+dtend,'Relatório de Estatistica','width=400px,height=300px,center=1,scrolling=1');
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
<h1>Versões</h1>

<p>Exibir histórico de trabalho específico 

  <input name="txtAno" id="txtAno" type="text" size="4" maxlength="4" onKeyPress="mascara(this,soNumeros);">
   &nbsp;
   <input type="button" name="Submit2" value="Selecionar Trabalho" onClick="trabSelector = dhtmlwindow.open('listDiv','ajax','listTrabalhos_Versoes.php?dest=0&ano='+document.getElementById('txtAno').value,'Selecionar trabalho','width=400px,height=280px,center=1,scrolling=1');">
</p>
</td>
</tr>
<tr><td>
<?php include("inc/copyright.php"); ?>
</td></tr>
</table>

</div>
</body>
</html>

