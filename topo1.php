<link rel="stylesheet" href="style.css" type="text/css"  />

<div align="center"><img src="img/topo.jpg" width="750" height="100">
<div class="meiogeral" style="height:20px; background:url(img/bgmeio.jpg); background-repeat:repeat-x; vertical-align:middle;" align="right">
<table align="center" width="100%" border="0" cellpadding="0" cellspacing="0">
<tr>
<td align="left"> 
</td>
<td align="right">
<?php
$mes = date("n");
$mesextenso = array("","Janeiro","Fevereiro","Mar�o","Abril","Maio","Junho","Julho","Agosto","Setembro","Outubro","Novembro","Dezembro");
$ano = date("Y");
$dia = date("d");
$semana = date("w");
$semanaextenso = array("Domingo","Segunda","Ter�a","Quarta","Quinta","Sexta","S�bado");
echo utf8_encode("$semanaextenso[$semana], $dia de $mesextenso[$mes] de $ano");
?>
</td>
</tr>
</table>




</div>
</div>
