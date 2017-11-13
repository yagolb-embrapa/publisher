<?php

include_once("conexao.php");

$sqlAcomp = "SELECT * FROM REVISOES WHERE Id_Trabalho = '".$_GET["idtrabalho"]."' ORDER BY revisao DESC LIMIT 1";
$qryAcomp = T_query($sqlAcomp);
$rowAcomp = T_fetch_array($qryAcomp);

$sqlRevisao = "SELECT REVISOES.Coment_Autor, REVISOES.Coment_CP, Aceitacao.Aceitacao, REVISORES.Relator 
				FROM REVISOES JOIN REVISORES USING (Id_Usr,Id_Trabalho) 
				LEFT JOIN Aceitacao ON REVISOES.Aceitacao = Aceitacao.Id_Aceitacao
				WHERE Id_Trabalho = '".$_GET["idtrabalho"]."' AND revisao = '".$rowAcomp["revisao"]."' ORDER BY REVISORES.Relator DESC";
				//WHERE Id_Trabalho = '".$_GET["idtrabalho"]."' AND Data_Operacao = '".$rowAcomp["Data_Operacao"]."' ORDER BY REVISORES.Relator DESC";
$qryRevisao = T_query($sqlRevisao);
					


?><br>
<script language="javascript" src="windowfiles/dhtmlwindow.js"></script>
<link rel="stylesheet" href="windowfiles/dhtmlwindow.css" />
<script language="javascript" src="TAjax.js"></script>
<script> var ajax = new TAjax(); </script>
<script> 
function manda(){
	ajax.ajaxFormPost('unseen','postAfterRev.php','frmAfterRev');
}

</script>
<div id="unseen"></div>
<table width="100%" border="1" cellpadding="0" cellspacing="0" class="listaTrabalhos">
  <tr>
    <th scope="col">Coment&aacute;rios para o Autor </th>
    <th scope="col">Coment&aacute;rios para o CP </th>
    <th scope="col">Parecer </th>
  </tr>
 <?php
 
 	while ($rowRevisao = T_fetch_array($qryRevisao)){
 
 ?>
  <tr>
    <td <? if ($rowRevisao["Relator"]){ ?>style="font-weight:bold"<? } ?>><?php echo utf8_encode($rowRevisao["Coment_Autor"]); ?></td>
    <td <? if ($rowRevisao["Relator"]){ ?>style="font-weight:bold"<? } ?>><?php echo utf8_encode($rowRevisao["Coment_CP"]); ?></td>
    <td <? if ($rowRevisao["Relator"]){ ?>style="font-weight:bold"<? } ?>><?php echo utf8_encode($rowRevisao["Aceitacao"]); ?></td>
  </tr>

  
 <?php
 	}
 ?>
</table>
<script>
function verifica() {
	if(document.getElementById('rbDest').checked && !document.getElementById('txtRevExtra_data').value) {
		alert('Coloque uma data limite!');
return false;
		}	
return true;
}
</script>

<div align="left" style="font-size:8pt; font-weight:bold;">*NEGRITO: Parecer do relator</div>
<form id="frmAfterRev" name="frmAfterRev" method="post" action="javascript:manda();">
  <input name="rbDest" type="radio" value="0" checked="checked"/>
  Enviar para autores realizar corre&ccedil;&atilde;o <br />
  <input name="rbDest" id='rbDest' type="radio" value="3" />
   Adicionar revisor extra 
   <input size=30 name="txtRevExtra" type="text" id="txtRevExtra" readonly="readonly" style="cursor:pointer" onclick="revwin=dhtmlwindow.open('revbox', 'ajax', 'rev_selector.php?trabalho=<?php echo $_GET["idtrabalho"]; ?>&extra=rev&rev_txt='+this.id, 'Selecionar Revisor (Relator) para trabalho <?php echo $_GET["idtrabalho"]; ?>', 'width=450px,height=250px,center=1,resize=0,scrolling=1'); return false"/>
   <input name="txtRevExtra_id" type="hidden" id="txtRevExtra_id" /> Data Limite: <input onKeyPress="mascara(this,maskDt);" size="10" maxlength="10" name='textRevExtra_data' type='text' id="txtRevExtra_data">
   <br />
<input name="rbDest" type="radio" value="4" />
  Aprovar e enviar para revis&atilde;o gramatical<br />
  <!--<input name="rbDest" type="radio" value="1" />
  Enviar para Normaliza&ccedil;&atilde;o Bibliogr&aacute;fica<br />-->
  <input name="rbDest" type="radio" value="2" />Rejeitar
  <input type="hidden" name="idtrabalho" value="<?php echo $_GET["idtrabalho"]; ?>" />
  <br />
  <input type="submit" name="Submit" onClick='return verifica()' value="Salvar" />
</form>
</body>
</html>
