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


$extra = '';
$restrito = false;
if (hasPerm(64) && !hasPerm(8) && !hasPerm(16)) { // Revisor Gramatical vê apenas acompanhamentos com status 10
	$restrito = true;
	$extra = "and (SELECT Id_Status_Trabalho FROM ACOMPANHAMENTO a where a.Id_Trabalho=TRABALHOS.Id_Trabalho ORDER BY Data_Operacao DESC LIMIT 1)=10";
	$_GET["norev"] = '';
	}
else if (hasPerm(32) && !hasPerm(8) && !hasPerm(16)) { // Bibliotecario vê apenas acompanhamentos com status 6
	$restrito = true;
	$extra = "and (SELECT Id_Status_Trabalho FROM ACOMPANHAMENTO a where a.Id_Trabalho=TRABALHOS.Id_Trabalho ORDER BY Data_Operacao DESC LIMIT 1)=6";
	$_GET["norev"] = '';
}


if (!$_GET["norev"]){




$sql = "SELECT TRABALHOS.Id_Trabalho, TRABALHOS.Versao, TRABALHOS.Ext_arquivo, TRABALHOS.Ext_ficha, TRABALHOS.Titulo, TRABALHOS.Data_Post as DtPost , CATEGORIAS_TRABALHO.Categoria_Trabalho FROM TRABALHOS LEFT JOIN REVISORES USING (Id_Trabalho) LEFT JOIN USR ON (REVISORES.Id_Usr=USR.Id_Usr) LEFT JOIN CATEGORIAS_TRABALHO ON CATEGORIAS_TRABALHO.Id_Categoria_Trabalho = TRABALHOS.Id_Categoria_Trabalho WHERE REVISORES.Relator=1 $extra ORDER BY Data_Post DESC, Id_Trabalho DESC LIMIT ".(($numero*$pagina)-$numero).",".$numero.";";


 $qry = T_query($sql);

if ($qry){
	if (T_num_rows($qry)){?>
	<table width="800" border="0" cellpadding="0" cellspacing="0" class="listaTrabalhos">
	<tr>
	  <th width="9%"><strong>Código do Trabalho </strong></th>
	  <th width="10%"><strong>Categoria</strong></th>
<?if (!$restrito) { ?><th width="15%"><strong>Relator/Revisor</strong></th><? } ?>
	  <th><strong>Título</strong></th>
	  <th width="8%"><strong>Data de Submissão </strong></th>
	  <th width="8%"><strong>Data de Revisão </strong></th>
	  <th width="8%"><strong>Prazo de Revisão </strong></th>
	  <th width="8%"><strong>Status </strong></th>
	</tr>

<?php


		while ($row = T_fetch_array($qry)){
			echo "<tr>";
			$rowFicha = T_fetch_array(T_query("SELECT Id_Status_Trabalho FROM ACOMPANHAMENTO WHERE Id_Trabalho='".$row["Id_Trabalho"]."' ORDER BY Data_Operacao DESC LIMIT 1;"));

            //Verifica a extensão da última versão do arquivo
			if (file_exists("trabalhos/v".$row["Versao"]."/".$row["Id_Trabalho"].".doc")) 
			   $href = "trabalhos/v".$row["Versao"]."/".$row["Id_Trabalho"].".doc";
			else if (file_exists("trabalhos/v".$row["Versao"]."/".$row["Id_Trabalho"].".docx")) 
			   $href = "trabalhos/v".$row["Versao"]."/".$row["Id_Trabalho"].".docx";
			else if (file_exists("trabalhos/v".$row["Versao"]."/".$row["Id_Trabalho"].".odt")) 
			   $href = "trabalhos/v".$row["Versao"]."/".$row["Id_Trabalho"].".odt";
			else if (file_exists("trabalhos/v".$row["Versao"]."/".$row["Id_Trabalho"].".pdf")) 
			   $href = "trabalhos/v".$row["Versao"]."/".$row["Id_Trabalho"].".pdf";
			else $href = "javascript:alert('Arquivo não encontrado')";
			
			echo "<td align='center'>
					<a href=\"".$href."\" target=\"_blank\">".$row["Id_Trabalho"]."</a>";
			if (($rowFicha[0] == 8)||($rowFicha[0] == 7)) echo "<br><a href=\"trabalhos/fichasCat/ficha-".$row["Id_Trabalho"].".".$row["Ext_ficha"]."\" target=\"_blank\">Ficha Catalogr&aacute;fica</a>";
			echo "</td>";
				echo "<td align='center'>".utf8_encode($row['Categoria_Trabalho'])."</td>";
				
				
				$sqlRev = "SELECT USR.Nome AS Revisor, Relator FROM REVISORES JOIN USR USING (Id_Usr) WHERE Id_Trabalho = '".$row["Id_Trabalho"]."' ORDER BY Relator DESC";
				$qryRev = T_query($sqlRev);			
				if (!$restrito) {
					echo "<td align='left' style=\"padding: 6px 0 0 0;\">";
					$rv = 0;
					while ($rowRev = T_fetch_array($qryRev)){
						$rv++;
				
						$sqltmp = "SELECT   ACOMPANHAMENTO.Id_Status_Trabalho
											FROM ACOMPANHAMENTO INNER JOIN STATUS_TRABALHO USING (Id_Status_Trabalho) WHERE Id_Trabalho='".$row["Id_Trabalho"]."' 
						ORDER BY Data_Operacao DESC LIMIT 1;";

						$qrytmp = T_query($sqltmp);
						if ($qrytmp)
							if (T_num_rows($qrytmp))
								$rowtmp = T_fetch_array($qrytmp);
					
				
						
						//if( $rowtmp['Id_Status_Trabalho'] != 6 && $rowtmp['Id_Status_Trabalho'] != 7 && $rowtmp['Id_Status_Trabalho'] != 8 && $rowtmp['Id_Status_Trabalho'] != 9)
						if( $rowtmp['Id_Status_Trabalho'] <= 2) 
						$onclick = "onClick=\"rwin=dhtmlwindow.open('rbox', 'ajax', 'change_revisores.php?id_trabalho=".$row["Id_Trabalho"]."', 'Alterar Revisores para Trabalho ".$row["Id_Trabalho"]."', 'width=450px,height=250px,center=1,resize=0,scrolling=1'); return false\"";	
						else
							$onclick = ""; 	


						echo "<div  onMouseover=\"fixedtooltip('".utf8_encode($rowRev["Revisor"])."', this, event, '150px')\" onMouseout=\"delayhidetip()\" style=\"cursor:default; display:inline; border:#CCCCCC 1px solid; padding: 2px 4px 1px 4px; margin: 0 1px 0 1px;\" $onclick  >".$rv."</div>";
					}
					echo "</td>";
				}
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
					if ($rowtmp["Id_Status_Trabalho"] == 4 || $rowtmp["Id_Status_Trabalho"] > 5){
						$sqlgt4 = "SELECT UNIX_TIMESTAMP(ACOMPANHAMENTO.Data_Operacao) AS Data_Oper	FROM ACOMPANHAMENTO WHERE Id_Trabalho='".$row["Id_Trabalho"]."' AND Id_Status_Trabalho = 3 
						ORDER BY Data_Operacao DESC LIMIT 1;";
						$qrygt4 = T_query($sqlgt4);
						$rowgt4 = T_fetch_array($qrygt4);
					}
					
					
					//$rowtmp["Data_Operacao"] = explode("-",$rowtmp["Data_Operacao"]);
					echo "<td align='center'>";
					//echo ($rowtmp["Id_Status_Trabalho"]==3)?$rowtmp["Data_Operacao"][2]."/".$rowtmp["Data_Operacao"][1]."/".$rowtmp["Data_Operacao"][0]:"---";
					if ($rowtmp["Id_Status_Trabalho"]==3) echo date("d/m/Y",$rowtmp["Data_Oper"]);
					else if ($rowtmp["Id_Status_Trabalho"]== 4 || $rowtmp["Id_Status_Trabalho"] > 5) echo date("d/m/Y",$rowgt4["Data_Oper"]);
					else echo "---";
					echo "</td>";
					$rowtmp["Data_Limite"] = explode("-",$rowtmp["Data_Limite"]);
					echo "<td align='center'>";
					echo (($rowtmp["Id_Status_Trabalho"]==3)||($rowtmp["Id_Status_Trabalho"]==2))?$rowtmp["Data_Limite"][2]."/".$rowtmp["Data_Limite"][1]."/".$rowtmp["Data_Limite"][0]:"---";
					echo "</td>";
					echo "<td align='center'>";
/*se ag. rev*/		if ($rowtmp["Id_Status_Trabalho"]==1) echo "<a href=\"javascript://\" onClick=\"rwin=dhtmlwindow.open('rbox', 'ajax', 'setNewDataLimite.php?id_trabalho=".$row["Id_Trabalho"]."', 'Deteminar data limite para revis&atilde;o do Trabalho ".$row["Id_Trabalho"]."', 'width=450px,height=250px,center=1,resize=0,scrolling=1'); return false\">";
/*se ag. cor*/		if ($rowtmp["Id_Status_Trabalho"]==3) echo "<a href=\"javascript://\" onClick=\"rwin=dhtmlwindow.open('rbox', 'ajax', 'setAfterRev.php?idtrabalho=".$row["Id_Trabalho"]."', 'Deteminar destino do Trabalho ".$row["Id_Trabalho"]."', 'width=700px,height=250px,center=1,resize=0,scrolling=1'); return false\">";
/*se em norm*/		if ($rowtmp["Id_Status_Trabalho"]==6) echo "<a href=\"javascript://\" onClick=\"rwin=dhtmlwindow.open('rbox', 'ajax', 'setAfterNorm.php?idtrabalho=".$row["Id_Trabalho"]."', 'Enviar documento ".$row["Id_Trabalho"]." para Editoração', 'width=450px,height=250px,center=1,resize=0,scrolling=1'); return false\">";
/*se em correção pt*/	if ($rowtmp["Id_Status_Trabalho"]==10) echo "<a href=\"javascript://\" onClick=\"rwin=dhtmlwindow.open('rbox', 'ajax', 'setAfterPt.php?idtrabalho=".$row["Id_Trabalho"]."', 'Enviar documento ".$row["Id_Trabalho"]." corrigido para Normalização', 'width=450px,height=250px,center=1,resize=0,scrolling=1'); return false\">";
/*se em edit*/		if ($rowtmp["Id_Status_Trabalho"]==7) echo "<a href=\"javascript://\" onClick=\"if (confirm('Deseja mudar o status do trabalho ".$row["Id_Trabalho"]." para publicado?')) ajax.loadDiv('inv','setPublished.php?idtrabalho=".$row["Id_Trabalho"]."');\">";
					if ($rowtmp["Id_Status_Trabalho"]==8) echo "<span style=\"color:#00CC00; font-weight:bold;\">";
					if ($rowtmp["Id_Status_Trabalho"]==9) echo "<span style=\"color:#EE0000; font-weight:bold;\">";
					echo utf8_encode($rowtmp["Status"]);
					if (($rowtmp["Id_Status_Trabalho"]==8)||($rowtmp["Id_Status_Trabalho"]==9)) echo "</span>";
					if (($rowtmp["Id_Status_Trabalho"]==1)||($rowtmp["Id_Status_Trabalho"]==3)||($rowtmp["Id_Status_Trabalho"]==6)||($rowtmp["Id_Status_Trabalho"]==10)||($rowtmp["Id_Status_Trabalho"]==7)) echo "</a>";
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
	
	$qry = T_query("SELECT count(*) FROM TRABALHOS LEFT JOIN REVISORES USING (Id_Trabalho) LEFT JOIN USR ON (REVISORES.Id_Usr=USR.Id_Usr) 
WHERE REVISORES.Relator=1 ORDER BY Data_Post");
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

}//fim do if(!norev)
else if (!$restrito)
{

$sql = "SELECT TRABALHOS.Id_Trabalho, TRABALHOS.Titulo, TRABALHOS.Data_Post as DtPost, TRABALHOS.Versao, TRABALHOS.Ext_arquivo, CATEGORIAS_TRABALHO.Categoria_Trabalho
FROM TRABALHOS 
LEFT JOIN CATEGORIAS_TRABALHO ON CATEGORIAS_TRABALHO.Id_Categoria_Trabalho = TRABALHOS.Id_Categoria_Trabalho
WHERE NOT EXISTS (SELECT * FROM REVISORES WHERE Id_Trabalho=TRABALHOS.Id_Trabalho) ORDER BY Data_Post DESC, Id_Trabalho DESC LIMIT ".(($numero*$pagina)-$numero).",".$numero.";";

$qry = T_query($sql);

if ($qry){
	if (T_num_rows($qry)){?>
	<div align="left" style="font-size:8pt; color:#FF0000; font-weight:bold;">* Clique sobre o c&oacute;digo do trabalho para selecionar os revisores</div>
	<table width="800" border="0" cellpadding="0" cellspacing="0" class="listaTrabalhos">
	<tr>
	   
	  <th width="9%"><strong>Código do Trabalho </strong></td>
	  <th width="10%"><strong>Categoria</strong></td>
	  <th><strong>Título</strong></td>
	  <th width="8%"><strong>Data de Submissão </strong></td>
	</tr>

<?php


		while ($row = T_fetch_array($qry)){
			echo "<tr >";
			 
			echo "<td align='center' >
					<img src='./img/file.gif' width='15px' height='15px' style=\"cursor:pointer;\" onclick=\"window.open('trabalhos/v".$row["Versao"]."/".$row["Id_Trabalho"].".".$row["Ext_arquivo"]."');\">&nbsp;
					<span style=\"cursor:pointer\" onClick=\"rwin=dhtmlwindow.open('rbox', 'ajax', 'set_revisores.php?id_trabalho=".$row["Id_Trabalho"]."', 'Selecionar Revisores para Trabalho ".$row["Id_Trabalho"]."', 'width=450px,height=250px,center=1,resize=0,scrolling=1'); return false\">
						".$row["Id_Trabalho"]."
					</span>		
			</td>";
			echo "<td align='left'>".utf8_encode($row["Categoria_Trabalho"])."</td>";
			echo "<td align='left'>".utf8_encode($row["Titulo"])."</td>";
			$row["DtPost"] = explode("-",$row["DtPost"]);
			echo "<td align='center'>".$row["DtPost"][2]."/".$row["DtPost"][1]."/".$row["DtPost"][0]."</td>";
		
	
	

			echo "</tr>";	
		}
?>
</table>
<?php
	T_free_result($qry);
	
	$qry = T_query("SELECT count(*) FROM TRABALHOS WHERE NOT EXISTS (SELECT * FROM REVISORES WHERE Id_Trabalho=TRABALHOS.Id_Trabalho) ORDER BY Data_Post");
	$row = T_fetch_array($qry);
	echo "<table width=\"800\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"listaTrabalhos\">
<tr><td align=\"left\" style=\"padding-left:20px;\">";
		if ($pagina-1>0){
			echo "<a href=\"javascript://\" onclick=\"ajax.loadDiv('divListaTrabalhos','list_trabalhos.php?pagina=".($pagina-1)."&norev='+document.getElementById('cbnorev').value);\"><img border=\"0\" align=\"middle\" src=\"img/anterior.gif\" width=\"28\" height=\"40\" />Anterior</a>";
		}
		echo "</td>
<td align=\"right\" style=\"padding-right:20px;\">";
		if ($row["count(*)"]>($pagina*$numero)){
			echo "<a href=\"javascript://\" onclick=\"ajax.loadDiv('divListaTrabalhos','list_trabalhos.php?pagina=".($pagina+1)."&norev='+document.getElementById('cbnorev').value);\">Proxima<img align=\"middle\" src=\"img/proximo.gif\" width=\"28\" height=\"40\" border=\"0\"/></a>";
		}	
		echo "</td>
</tr>
</table>	";
	
	

	
	}
}
}

?>
