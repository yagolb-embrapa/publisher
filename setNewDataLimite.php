<?php

include_once("conexao.php");

$id_trabalho = $_GET["id_trabalho"];

$sqlTrab = "SELECT Titulo FROM TRABALHOS WHERE Id_Trabalho = '".$id_trabalho."';";
$qryTrab = T_query($sqlTrab);
$rowTrab = T_fetch_array($qryTrab);

?>
<link rel="stylesheet" href="style.css">
<script language="javascript" src="TAjax.js"></script>
<script language="javascript">
var ajax = new TAjax();
	function manda(formid){
		if ((document.getElementById('txtDataLimite').value.length==0)||(!checaData(document.getElementById('txtDataLimite').value)))
			alert('Data nula ou inválida');
		else
			ajax.ajaxFormPost('escondida','postNewDataLimite.php','frmNewRev');
	}
</script>
<div id="escondida" style="display:none; visibility:hidden;"></div>
<p><strong>Id do trabalho:</strong> <?php echo $id_trabalho; ?><br>
Titulo: <?php echo utf8_encode($rowTrab["Titulo"]); ?></p>
<form name="frmNewRev" id="frmNewRev" method="post" action="javascript:manda(this.id);">
  <p>Nova data limite para revisão: 
    <input type="text" id="txtDataLimite" name="txtDataLimite" maxlength="10" size="10" onKeyPress="mascara(this,maskDt)">
	<input type="hidden" name="id_trabalho" value="<?php echo $id_trabalho; ?>">
    <br>
    <input type="submit" name="Submit" value="Gravar">
  </p>
</form>
