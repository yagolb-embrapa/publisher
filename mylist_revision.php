<?php
include_once("conexao.php");
include_once("sessions.php");
?>
<link rel="stylesheet" href="ibox/ibox.css" />
<script language="javascript" src="ibox/ibox.js"></script>
<script language="javascript" src="TAjax.js"></script>
<script>
var ajax = new TAjax();
</script>
<?php
if (!$_GET["pagina"]) $pagina = 1; else $pagina = $_GET["pagina"];
if (!$_GET["numero"]) $numero = 10; else $numero = $_GET["numero"];
$sql = "SELECT TRABALHOS.Id_Trabalho, TRABALHOS.Versao, TRABALHOS.Ext_arquivo, TRABALHOS.Ext_ficha, TRABALHOS.Titulo, TRABALHOS.Data_Post as DtPost , CATEGORIAS_TRABALHO.Categoria_Trabalho 
	 FROM TRABALHOS 
	 LEFT JOIN CATEGORIAS_TRABALHO ON CATEGORIAS_TRABALHO.Id_Categoria_Trabalho = TRABALHOS.Id_Categoria_Trabalho
 
WHERE TRABALHOS.Id_Usr='".$_SESSION["USERID"]."' ORDER BY Data_Post DESC, Id_Trabalho DESC LIMIT ".(($numero*$pagina)-$numero).",".$numero.";";
 $qry = T_query($sql);
if ($qry){
	if (T_num_rows($qry)){?>
	<table width="800" border="0" cellpadding="0" cellspacing="0" class="listaTrabalhos">
	<tr>
	  <th width="9%"><strong>Código do Trabalho </strong></th>
	  <th width="10%"><strong>Categoria</strong></th>
	<th width="10%"><strong>Sugestões de Correção</strong></th>
	  <th><strong>Título</strong></th>
	  <th width="8%"><strong>Data de Submissão </strong></th>
	  <th width="8%"><strong>Data de Revisão </strong></th>
	  <!-- <th width="8%"><strong>Prazo de Revisão </strong></th> -->
	  <th width="8%"><strong>Status </strong></th>
	</tr>

<?php
		while ($row = T_fetch_array($qry)){
			echo "<tr>";
			$rowFicha = T_fetch_array(T_query("SELECT Id_Status_Trabalho FROM ACOMPANHAMENTO WHERE Id_Trabalho='".$row["Id_Trabalho"]."' ORDER BY Data_Operacao DESC LIMIT 1;"));
			
			echo "<td align='center'>
					<a href=\"trabalhos/v".$row["Versao"]."/".$row["Id_Trabalho"].".".$row["Ext_arquivo"]."\" target=\"_blank\">".$row["Id_Trabalho"]."</a>";
			if (($rowFicha[0] == 8)||($rowFicha[0] == 7)) echo "<br><a href=\"trabalhos/fichasCat/ficha-".$row["Id_Trabalho"].".".$row["Ext_ficha"]."\" target=\"_blank\">Ficha Catalogr&aacute;fica</a>";
			echo "</td>";
echo "<td align='center'>".utf8_encode($row['Categoria_Trabalho'])."</td>";



$sql = "SELECT * from REVISOES where Id_Trabalho='".$row["Id_Trabalho"]."' and revisao>=1";

 
 $qry2 = T_query($sql);
$row2 = T_fetch_array($qry2);

if (count($row2)) {

				
				 echo "<td><a href=\"javascript://\" onClick=\"rwin=dhtmlwindow.open('rbox', 'ajax', 'lista_sugestoes.php?id_trabalho=".$row["Id_Trabalho"]."', 'Listagem de sugestões para o trabalho ".$row["Id_Trabalho"]."', 'width=450px,height=250px,center=1,resize=0,scrolling=1'); return false\">Mostrar</a></td>";
}
else echo "<td>&nbsp;</td>";

				
			echo "<td align='left'>".utf8_encode($row["Titulo"])."</td>";
			$row["DtPost"] = explode("-",$row["DtPost"]);
			echo "<td align='center'>".$row["DtPost"][2]."/".$row["DtPost"][1]."/".$row["DtPost"][0]."</td>";
			$sqltmp = "SELECT UNIX_TIMESTAMP(ACOMPANHAMENTO.Data_Operacao) AS Data_Oper, ACOMPANHAMENTO.Id_Status_Trabalho, ACOMPANHAMENTO.Data_Limite, STATUS_TRABALHO.Status_Trabalho AS Status 
			FROM ACOMPANHAMENTO INNER JOIN STATUS_TRABALHO USING (Id_Status_Trabalho) WHERE Id_Trabalho='".$row["Id_Trabalho"]."' 
			ORDER BY Data_Operacao DESC LIMIT 1;";
			$qrytmp = T_query($sqltmp);
			

 
			if ($qrytmp){
				if (T_num_rows($qrytmp)){
					$rowtmp = T_fetch_array($qrytmp);
					
					//colocar a data da ultima revisao para o st > 4
					if ($rowtmp["Id_Status_Trabalho"] > 4){
						$sqlgt4 = "SELECT UNIX_TIMESTAMP(ACOMPANHAMENTO.Data_Operacao) AS Data_Oper	FROM ACOMPANHAMENTO WHERE Id_Trabalho='".$row["Id_Trabalho"]."' AND Id_Status_Trabalho = 3 
						ORDER BY Data_Operacao DESC LIMIT 1;";
						$qrygt4 = T_query($sqlgt4);
						$rowgt4 = T_fetch_array($qrygt4);
					}
					
					
					//$rowtmp["Data_Operacao"] = explode("-",$rowtmp["Data_Operacao"]);
					echo "<td align='center'>";
					//echo ($rowtmp["Id_Status_Trabalho"]==3)?$rowtmp["Data_Operacao"][2]."/".$rowtmp["Data_Operacao"][1]."/".$rowtmp["Data_Operacao"][0]:"---";
					if ($rowtmp["Id_Status_Trabalho"]==3) echo date("d/m/Y",$rowtmp["Data_Oper"]);
					else if ($rowtmp["Id_Status_Trabalho"]>4) echo date("d/m/Y",$rowgt4["Data_Oper"]);
					else echo "---";
					echo "</td>";
					$rowtmp["Data_Limite"] = explode("-",$rowtmp["Data_Limite"]);
					//echo "<td align='center'>";
					//echo (($rowtmp["Id_Status_Trabalho"]==3)||($rowtmp["Id_Status_Trabalho"]==2))?$rowtmp["Data_Limite"][2]."/".$rowtmp["Data_Limite"][1]."/".$rowtmp["Data_Limite"][0]:"---";
					//echo "</td>";
					echo "<td align='center'>";
					
					if( $rowtmp["Id_Status_Trabalho"] == 8 )
						echo "<span style=\"color:#00CC00; font-weight:bold;\">".utf8_encode($rowtmp["Status"])."</span>";
					
					else if( $rowtmp["Id_Status_Trabalho"]== 9 )
						echo "<span style=\"color:#EE0000; font-weight:bold;\">".utf8_encode($rowtmp["Status"])."</span>";
				
					else
						echo utf8_encode($rowtmp["Status"]);
	
	
					echo "</td>";
	
				}
				else{
					echo "<td align='center'>---</td><td align='center'>---</td><td align='center'>---</td>";	
				}
			}	
			else{
				echo "<td align='center'>---</td><td align='center'>---</td><td align='center'>---</td>";
			}				
			echo "</tr>";	
		}
?>
</table>
<?php
	T_free_result($qry);
	
	$qry = T_query("SELECT TRABALHOS.Id_Trabalho, TRABALHOS.Versao, TRABALHOS.Ext_arquivo, TRABALHOS.Ext_ficha, TRABALHOS.Titulo, TRABALHOS.Data_Post as DtPost , CATEGORIAS_TRABALHO.Categoria_Trabalho 
	 FROM TRABALHOS 
	 LEFT JOIN CATEGORIAS_TRABALHO ON CATEGORIAS_TRABALHO.Id_Categoria_Trabalho = TRABALHOS.Id_Categoria_Trabalho
WHERE TRABALHOS.Id_Usr='".$_SESSION["USERID"]."'");

	$row = T_fetch_array($qry);
	echo "<table width=\"800\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"listaTrabalhos\">
<tr><td align=\"left\" style=\"padding-left:20px;\">";
		if ($pagina-1>0){
			echo "<a href=\"javascript://\" onclick=\"ajax.loadDiv('divListaTrabalhos','list_trabalhos.php?pagina=".($pagina-1)."');\"><img border=\"0\" align=\"middle\" src=\"img/anterior.gif\" width=\"28\" height=\"40\" />Anterior</a>";
		}
		echo "</td>
<td align=\"right\" style=\"padding-right:20px;\">";
		if ($row["count(*)"]>($pagina*$numero)){
			echo "<a href=\"javascript://\" onclick=\"ajax.loadDiv('divListaTrabalhos','list_trabalhos.php?pagina=".($pagina+1)."');\">Proxima<img align=\"middle\" src=\"img/proximo.gif\" width=\"28\" height=\"40\" border=\"0\"/></a>";
		}	
		echo "</td>
</tr>
</table>	";
	}
}
?>
