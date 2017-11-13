<?php

include_once("conexao.php");
if (!$_GET["counter"])
$autorct = 0;
else
$autorct = $_GET["counter"];

?>
<link rel="stylesheet" href="windowfiles/dhtmlwindow.css">
<script language="javascript" src="windowfiles/dhtmlwindow.js">
</script>
<script language="javascript" src="TAjax.js"></script>
<script>var tempAjax = new TAjax();</script>
<?php if ($autorct){ ?>
<div id="divAutorAtual<?php echo $autorct; ?>">
    <input name="algum" type="text" id="autor<?php echo $autorct; ?>" style="cursor:pointer" onclick="autwin=dhtmlwindow.open('autbox', 'ajax', 'listAutor.php?aut_txt='+this.id, 'Selecionar Autor para trabalho', 'width=450px,height=250px,center=1,resize=0,scrolling=1'); return false" size="38" readonly="readonltwy">
    <input type="hidden" name="autor_id" id="autor<?php echo $autorct; ?>_id" value="0" />
	<input type="button" value="Remover" onclick="tempAjax.loadDiv('divAutorAtual<?php echo $autorct; ?>','');"  />

</div>
<?php } ?>
<div id="divAutor<?php echo $autorct; ?>">
<input type="button" value="Adicionar autor ao trabalho" onclick="tempAjax.loadDiv('divAutor<?php echo $autorct; ?>','multiple_aut.php?counter=<?php echo $autorct+1; ?>');"  />
</div>


