<?php 
	include_once("conexao.php");
	$id_trabalho = $_GET["id_trabalho"];
	$sql = "SELECT TRABALHOS.Titulo, 
	CATEGORIAS_TRABALHO.Categoria_Trabalho, TRABALHOS.Comentario ,
	USR.Nome AS Autor FROM USR, TRABALHOS, CATEGORIAS_TRABALHO WHERE USR.Id_Usr = TRABALHOS.Id_Usr 
	AND CATEGORIAS_TRABALHO.Id_Categoria_Trabalho = TRABALHOS.Id_Categoria_Trabalho AND 
	TRABALHOS.Id_Trabalho = '".$id_trabalho."';";
	
	$qry = T_query($sql);
	$row = T_fetch_array($qry);
	T_free_result($qry);
	
	
	
	/* Recupera autores */
	$sql_autores = " SELECT U.Nome FROM AUTORES A
						INNER JOIN USR U ON U.Id_Usr = A.Id_Usr
						WHERE Id_Trabalho = '".$id_trabalho."' ORDER BY A.Ordem";
						
	$qry = T_query($sql_autores);
	
	$array_autores = array();
	while( $row_a = T_fetch_array($qry))
		$array_autores[] = $row_a['Nome'];

	
	
	/* Recupera Áreas do Trabalho */
	$sql_areas = " SELECT A.Area_Atuacao FROM AREAS_ATUACAO A
						INNER JOIN AREAS_ATUACAO_TRABALHO AT ON AT.Id_Area_Atuacao = A.Id_Area_Atuacao
						WHERE AT.Id_Trabalho = '".$id_trabalho."' ";
						
	$qry = T_query($sql_areas);
	
	$array_areas = array();
	while( $row_a = T_fetch_array($qry))
		$array_areas[] = $row_a['Area_Atuacao'];
 
	T_free_result($qry);
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>
<link rel="stylesheet" href="windowfiles/dhtmlwindow.css" />
<script language="javascript" src="windowfiles/dhtmlwindow.js">
/***********************************************
* DHTML Window Widget- © Dynamic Drive (www.dynamicdrive.com)
* This notice must stay intact for legal use.
* Visit http://www.dynamicdrive.com/ for full source code
***********************************************/
</script>
<script language="javascript" src="TAjax"></script>
<script>
var tmpajax = new TAjax();

function postar(){
	if ((document.getElementById('dt_limite').value.length==0)||(!checaData(document.getElementById('dt_limite').value))){
		alert('Data nula ou inválida');
		return false;
	}
	if (document.getElementById('revisor1_id').value<1){
		alert('É necessario selecionar o revisor relator');
		return false;
	}
	return true;
}

</script>
<link rel="stylesheet" href="style.css"  />
</head>

<body>
<div id="result" style="display:none; visibility:hidden"></div>
<div align="center">
<div align="left" style="width:430px">
  <p><strong>Titulo do trabalho:</strong> <?php echo utf8_encode($row["Titulo"]); ?><br>
    <strong>Autor(es):</strong> 
	<?php 
	$i = 0;
	foreach ( $array_autores as $chave => $valor )
	{
		if( ! $i | 0 )
			echo utf8_encode($valor).'<br>';
		else
			echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.utf8_encode($valor).'<br>';
		$i++;
	}
	?>
    <strong>Categoria:</strong> <?php echo utf8_encode($row["Categoria_Trabalho"]); ?><br />
	<strong>&Agrave;rea(s):</strong>
	<?php
	$i = 0;
	foreach ( $array_areas as $chave => $valor )
	{
		if( ! $i | 0 )
			echo utf8_encode($valor).'<br>';
		else
			echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.utf8_encode($valor).'<br>';
		$i++;
	}
	?>
	
	<strong>Observa&ccedil;&otilde;es:</strong> <?php echo utf8_encode($row["Comentario"]); ?>
	</p>
  <form name="frmSetRev" id="frmSetRev" method="post" action="javascript: if (postar()) tmpajax.ajaxFormPost('result','post_revisores.php','frmSetRev');">
    <p><strong>Data Limite (dd/mm/aaaa):</strong> 
      <input name="dt_limite" type="text" id="dt_limite" onKeyPress="mascara(this,maskDt);" size="10" maxlength="10">
      <input type="hidden" name="id_trabalho" id="id_trabalho" value="<?php echo $_GET["id_trabalho"]; ?>" />
	  <br>
      <strong>Revisor 1 (Relator):</strong> 
    <input type="text" name="revisor1" id="revisor1" readonly="readonly" style="cursor:pointer" onclick="revwin=dhtmlwindow.open('revbox', 'ajax', 'rev_selector.php?trabalho=<?php echo $_GET["id_trabalho"]; ?>&rev_txt='+this.id, 'Selecionar Revisor (Relator) para trabalho <?php echo $_GET["id_trabalho"]; ?>', 'width=450px,height=250px,center=1,resize=0,scrolling=1'); return false">
    <input type="hidden" name="revisor1_id" id="revisor1_id" value="0"/>
    <br>
    <strong>Revisor 2:</strong> 
    <input type="text" name="revisor2" id="revisor2" readonly="readonly" style="cursor:pointer" onclick="revwin=dhtmlwindow.open('revbox', 'ajax', 'rev_selector.php?trabalho=<?php echo $_GET["id_trabalho"]; ?>&rev_txt='+this.id, 'Selecionar Revisor para trabalho <?php echo $_GET["id_trabalho"]; ?>', 'width=450px,height=250px,center=1,resize=0,scrolling=1'); return false">
    <input type="hidden" name="revisor2_id" id="revisor2_id" value="0" /><br>
    <strong>Revisor 3:</strong> 
    <input type="text" name="revisor3" id="revisor3" readonly="readonly" style="cursor:pointer" onclick="revwin=dhtmlwindow.open('revbox', 'ajax', 'rev_selector.php?trabalho=<?php echo $_GET["id_trabalho"]; ?>&rev_txt='+this.id, 'Selecionar Revisor para trabalho <?php echo $_GET["id_trabalho"]; ?>', 'width=450px,height=250px,center=1,resize=0,scrolling=1'); return false">
    <input type="hidden" name="revisor3_id" id="revisor3_id" value="0" onchange=""/>
    (Opcional) <a href="javascript://" style="color:#FF0000" onclick="document.getElementById('revisor3_id').value=0; document.getElementById('revisor3').value='';">Remover</a> <br />
	<strong>Revisor 4:</strong> 
    <input type="text" name="revisor4" id="revisor4" readonly="readonly" style="cursor:pointer" onclick="revwin=dhtmlwindow.open('revbox', 'ajax', 'rev_selector.php?trabalho=<?php echo $_GET["id_trabalho"]; ?>&rev_txt='+this.id, 'Selecionar Revisor para trabalho <?php echo $_GET["id_trabalho"]; ?>', 'width=450px,height=250px,center=1,resize=0,scrolling=1'); return false">
    <input type="hidden" name="revisor4_id" id="revisor4_id" value="0" onchange=""/>
    (Opcional) <a href="javascript://" style="color:#FF0000" onclick="document.getElementById('revisor4_id').value=0; document.getElementById('revisor4').value='';">Remover</a> <br />
    </p>
    <div align="center"><input  type="submit" value="Enviar" style="height:22px;" /> </div>
  </form>
  <p>&nbsp;</p>
</div>
</div>


</body>
</html>
