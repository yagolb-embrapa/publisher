<script src="windowfiles/dhtmlwindow.js"></script>
<link rel="stylesheet" href="windowfiles/dhtmlwindow.css" />
<?php

if (($_GET["ano"] < 2000)||($_GET["ano"] > date("Y"))) echo "<script> alert('Favor inserir uma data válida'); 
trabSelector.close(); </script>";
else{
	
	$destino = $_GET["dest"];
		
	$url = "rel_versoes";
	$wintitle = "Relatorio de Versões";	

	include_once("conexao.php");
	
	$ano = substr($_GET["ano"],-2);
	$sql = "SELECT Id_Trabalho, Titulo, Versao FROM TRABALHOS WHERE Id_Trabalho LIKE '%".$ano."' ORDER BY Id_Trabalho DESC";
	$qry = T_query($sql);
	
	$i = 1;
	if (!T_num_rows($qry)) echo "Nenhum trabalho listado nesse ano";
	else
	while($row = T_fetch_array($qry)){
		$i = ($i+1)%2;
		?>
		<div class="lista_registros<?php echo $i; ?>" style="display:block; cursor:pointer; width:100%;" onClick="ass=dhtmlwindow.open('listDiv', 'ajax', '<?php echo $url; ?>.php?idtrabalho=<?php echo $row["Id_Trabalho"]; ?>&versoes=<?php echo $row["Versao"]; ?>&ano=<?php echo $ano;?>', '<?php echo $wintitle ?>', 'width=480px,height=250px,center=1,resize=0,scrolling=1');">
		<?php echo $row["Id_Trabalho"]." - ".utf8_encode($row["Titulo"]); ?>
		</div>
		<?php
	}

}

?>