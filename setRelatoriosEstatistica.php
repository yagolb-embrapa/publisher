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
		relwin = dhtmlwindow.open('rbox','ajax','relatorioEstatistica.php?comp='+comp+'&tipo='+tipo+'&dtini='+dtini+'&dtend='+dtend,'Relatório de Estatistica','width=400px,height=300px,center=1,scrolling=1,resize=1');
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
<h1>Relatórios de Estatística </h1>
<form name="form1" method="post" action="">
  <div align="center">
    <table width="308" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="79" align="left">Estatística:</td>
        <td width="131" align="left"><select id="cbTipo" name="cbTipo">
            <option value="0" selected>por Tipo</option>
            <option value="1">por Status</option>
            <option value="2">por Revisor</option>
          </select>
        </td>
        <td width="139" align="left" scope="col"><input name="rbComp" id="rbComp" type="radio" value="0" checked>
          Resumido</td>
      </tr>
      <tr>
        <td align="left">&nbsp;</td>
        <td align="left">&nbsp;</td>
        <td align="left"><input name="rbComp" id="rbComp1" type="radio" value="1">
          Completo</td>
      </tr>
    </table>
    <br>
  De 
  <input name="dtInit" type="text" id="dtInit" onKeyPress="mascara(this,maskDt);" size="10" maxlength="10">
   até 
   <input name="dtEnd" type="text" id="dtEnd" onKeyPress="mascara(this,maskDt);" size="10" maxlength="10">
   <br>
   <input type="button" name="Submit" value="Gerar Relatório" onClick="gogogo();">
  </div>
</form><p>&nbsp;</p></td>
</tr>
<tr><td>
<?php include("inc/copyright.php"); ?>
</td></tr>
</table>

</div>
</body>
</html>

