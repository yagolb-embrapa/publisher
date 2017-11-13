<?php

include_once("sessions.php");
allow(4);
include_once("conexao.php");

$datainicial = $_GET["dtini"];
$datafinal = $_GET["dtend"];
$completo = $_GET["comp"];
$tipo = $_GET["tipo"];

$tipos = array("por categoria","por status","por revisor");

$datainicial = implode("-",array_reverse(explode("/",$datainicial)));
$datafinal = array_reverse(explode("/",$datafinal));
if ($datafinal[2]<29) $datafinal[2]++;
$datafinal = implode("-",$datafinal);

$timeini = strtotime($datainicial);
$timefim = strtotime($datafinal);


?>
<script language="javascript">
function rel_print(){
	nova = window.open('relatorioEstatistica_print.php?dtini=<?php echo $_GET["dtini"]; ?>&dtend=<?php echo $_GET["dtend"]; ?>&comp=<?php echo $_GET["comp"]; ?>&tipo=<?php echo $_GET["tipo"];?>','relatorio print','width=500,height=400,toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,copyhistory=yes,resizable=no');
}
</script>
<center><h1>Relat&oacute;rio <?php echo ($completo)?"completo":"resumido"; ?> <?php echo $tipos[$tipo]; ?></h1>
<p style="font-size:8pt;">Per&iacute;odo: de <?php echo $_GET["dtini"]; ?> at&eacute; <?php echo $_GET["dtend"]; ?></p>
</center>
<div align="right"><a href="javascript://" style="color:#0000FF;" onclick="rel_print()">Visualizar versao para impress&atilde;o</a></div>
<?php

	if (!$completo){
		switch($tipo){
		
		case 0:		//relatorio resumido dos trabalhos aceitos...
					$sql = "SELECT CATEGORIAS_TRABALHO.Categoria_Trabalho, COUNT(DISTINCT Id_Trabalho) AS Ct
					FROM ACOMPANHAMENTO
					LEFT JOIN TRABALHOS
					USING ( Id_Trabalho )
					LEFT JOIN CATEGORIAS_TRABALHO
					USING ( Id_Categoria_Trabalho )
					WHERE Data_Operacao >= TIMESTAMP('".$datainicial."') 
					AND Data_Operacao <= TIMESTAMP('".$datafinal."') 
					GROUP BY CATEGORIAS_TRABALHO.Categoria_Trabalho";
					$qry = T_query($sql);
					if (!T_num_rows($qry)){
						echo "Nenhum trabalho listado";
					}
					else{
						
						echo "<table width=\"100%\" class=\"listaTrabalhos\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
								  <tr>
									<th scope=\"col\">Categoria</th>
									<th scope=\"col\">Quantidade</th>
								  </tr>";
						
						while ($row = T_fetch_array($qry)){
							echo "<tr>
								<td>".utf8_encode($row[0])."</td>
								<td>".$row[1]."</td></tr>";
						}
						echo "</table>";
						
					}
					break;
		case 1:		// relatorio resumido pelo status
					$sqlTrab = "SELECT Id_Trabalho FROM TRABALHOS";
					$qryTrab = T_query($sqlTrab);
					if (T_num_rows($qryTrab)){
						
						$ct = array(0); //variavel para contagem de trabalhos no periodo...
						$ct = array_pad($ct,10,0);
												
						while($rowTrab = T_fetch_array($qryTrab)){
							
							$sqlStat = "SELECT Id_Status_Trabalho, Data_Operacao FROM ACOMPANHAMENTO WHERE Id_Trabalho = '".$rowTrab[0]."' AND UNIX_TIMESTAMP(Data_Operacao) >= ".$timeini." AND UNIX_TIMESTAMP(Data_Operacao) <= ".$timefim."  ORDER BY Data_Operacao DESC LIMIT 1";
							$qryStat = T_query($sqlStat);
							
							if (T_num_rows($qryStat)){
								$rowStat = T_fetch_array($qryStat);
								$ct[$rowStat["Id_Status_Trabalho"]]++;
							}
							
							
							
																					
						}
						$sql = "SELECT * FROM STATUS_TRABALHO ORDER BY Status_Trabalho";
						$qry = T_query($sql);
						
						echo "<table width=\"100%\" class=\"listaTrabalhos\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
								  <tr>
									<th scope=\"col\">Status</th>
									<th scope=\"col\">Quantidade</th>
								  </tr>";
						
						while ($row = T_fetch_array($qry)){
							echo "<tr>
								<td style=\"text-align:left; padding-left:20px;\">".utf8_encode($row["Status_Trabalho"])."</td><td style=\"text-align:right; padding-right:60px;\">".$ct[$row["Id_Status_Trabalho"]]."</td>
							</tr>";
						}
						
						echo "</table>";
						
					}
					else{
						echo "Nenhum trabalho listado.";
					}
					break;
					
		case 2:		//relatorio resumido por revisor...
					$table = false;
					$sqlRev = "SELECT DISTINCT Id_Usr, USR.Nome FROM REVISORES INNER JOIN USR USING (Id_Usr) ORDER BY USR.Nome";
					$qryRev = T_query($sqlRev);
					if (T_num_rows($qryRev)){
						while ($rowRev = T_fetch_array($qryRev)){
							$sqlTrab = "SELECT Id_Trabalho FROM TRABALHOS WHERE EXISTS (SELECT * FROM REVISORES WHERE Id_Trabalho = TRABALHOS.Id_Trabalho AND Id_Usr = ".$rowRev["Id_Usr"].");";
							$qryTrab = T_query($sqlTrab);
							if (T_num_rows($qryTrab)){
								$trabCt = 0;
								
								while ($rowTrab = T_fetch_array($qryTrab)){
									$sqlAcomp = "SELECT * FROM ACOMPANHAMENTO WHERE Id_Trabalho = '".$rowTrab[0]."' AND UNIX_TIMESTAMP(Data_Operacao) >= ".$timeini." AND UNIX_TIMESTAMP(Data_Operacao) <= ".$timefim;
									$qryAcomp = T_query($sqlAcomp);
									if (T_num_rows($qryAcomp)) $trabCt++;
								}
								
								if ($trabCt){
									if (!$table){
										echo "<table width=\"100%\" class=\"listaTrabalhos\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\"><tr><th scope=\"col\">Revisor</th><th scope=\"col\">Quantidade</th></tr>";
										$table = true;
									}
									echo "<tr><td style=\"text-align:left; padding-left:20px;\">".utf8_encode($rowRev["Nome"])."</td><td>".$trabCt."</td></tr>";
								}
							}							
						}// FIM DO WHILE ROWREV
						if ($table) echo "</table>";
					}
					
					
					
					break;
					
					
		default:	echo "ainda não implementado";	
		
		}
	}// fim IF COMPLETO
	else{
		switch($tipo){
		
		case 0:		//relatorio completo dos trabalhos aceitos...
					$sql = "SELECT CATEGORIAS_TRABALHO.Categoria_Trabalho, COUNT(DISTINCT Id_Trabalho) AS Ct, CATEGORIAS_TRABALHO.Id_Categoria_Trabalho
					FROM ACOMPANHAMENTO
					LEFT JOIN TRABALHOS
					USING ( Id_Trabalho )
					LEFT JOIN CATEGORIAS_TRABALHO
					USING ( Id_Categoria_Trabalho )
					WHERE Data_Operacao >= TIMESTAMP('".$datainicial."') 
					AND Data_Operacao <= TIMESTAMP('".$datafinal."') 
					GROUP BY CATEGORIAS_TRABALHO.Categoria_Trabalho";
					$qry = T_query($sql);
					if (!T_num_rows($qry)){
						echo "Nenhum trabalho listado";
					}
					else{
						
						echo "<table width=\"100%\" class=\"listaTrabalhos\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
								  <tr>
									<th scope=\"col\">Categoria</th>
									<th scope=\"col\">Quantidade</th>
								  </tr>";
						
						while ($row = T_fetch_array($qry)){
							if ($row[1]) $border = "style=\"border-color:#E3E3E3\"";
							else $border = "";
							echo "<tr>
								<td ".$border.">".utf8_encode($row[0])."</td>
								<td ".$border.">".$row[1]."</td></tr>";
								if ($row[1]){
								echo "<tr><td colspan=2 style=\"text-align:left; padding-left:20px;\">";
							//---- PARTE DO COMPLETO... ESCREVER OS NOMES DOS TRABALHOS...----//
									$sqlTrabalhos = "SELECT DISTINCT TRABALHOS.Titulo, Id_Trabalho FROM ACOMPANHAMENTO 
									INNER JOIN TRABALHOS USING(Id_Trabalho) 
									WHERE Data_Operacao >= TIMESTAMP('".$datainicial."') 
									AND Data_Operacao <= TIMESTAMP('".$datafinal."') 
									AND TRABALHOS.Id_Categoria_Trabalho = ".$row[2]." 
									ORDER BY TRABALHOS.Titulo";
									$qryTrabalhos = T_query($sqlTrabalhos);									
									while($rowTrabalhos = T_fetch_array($qryTrabalhos)){
										echo "<p><img src='img/doc.gif'>&nbsp;".utf8_encode($rowTrabalhos[0]);
										if (hasPerm(8)){
											$rowAutor = T_fetch_array(T_query("SELECT USR.Nome FROM AUTORES INNER JOIN USR USING (Id_Usr) WHERE Id_Trabalho = '".$rowTrabalhos[1]."' AND ORDEM = 0"));
											echo "<br><b>Autor:</b> ".utf8_encode($rowAutor[0])."<br>";
										}										
									}									
								echo "</td></tr>";	
								}// fim do if row[1]
								
							
								
						}
						echo "</table>";
						
					}
					break;
		case 1:		// relatorio completo pelo status
					$sqlTrab = "SELECT Id_Trabalho, Titulo FROM TRABALHOS";
					$qryTrab = T_query($sqlTrab);
					if (T_num_rows($qryTrab)){
						
						$ct = array(0); //variavel para contagem de trabalhos no periodo...
						$ct = array_pad($ct,10,0);
						
												
						while($rowTrab = T_fetch_array($qryTrab)){
							
							$sqlStat = "SELECT Id_Status_Trabalho, Data_Operacao FROM ACOMPANHAMENTO WHERE Id_Trabalho = '".$rowTrab[0]."' AND UNIX_TIMESTAMP(Data_Operacao) >= ".$timeini." AND UNIX_TIMESTAMP(Data_Operacao) <= ".$timefim."  ORDER BY Data_Operacao DESC LIMIT 1";
							$qryStat = T_query($sqlStat);
							
							if (T_num_rows($qryStat)){
								$rowStat = T_fetch_array($qryStat);
								
								$ctt[$rowStat["Id_Status_Trabalho"]][$ct[$rowStat["Id_Status_Trabalho"]]] = $rowTrab["Titulo"];
								$rowAutor = T_fetch_array(T_query("SELECT USR.Nome FROM AUTORES INNER JOIN USR USING (Id_Usr) WHERE Id_Trabalho = '".$rowTrab["Id_Trabalho"]."' AND ORDEM = 0"));
								$cta[$rowStat["Id_Status_Trabalho"]][$ct[$rowStat["Id_Status_Trabalho"]]] = $rowAutor[0];
								$ct[$rowStat["Id_Status_Trabalho"]]++;
							}
							
							
							
																					
						}
						$sql = "SELECT * FROM STATUS_TRABALHO ORDER BY Status_Trabalho";
						$qry = T_query($sql);
						
						echo "<table width=\"100%\" class=\"listaTrabalhos\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
								  <tr>
									<th scope=\"col\">Status</th>
									<th scope=\"col\">Quantidade</th>
								  </tr>";
						
						while ($row = T_fetch_array($qry)){
							if ($ct[$row["Id_Status_Trabalho"]]>0) $border = "border-color:#E3E3E3";
							else $border = "";
							echo "<tr>
								<td style=\"text-align:left; padding-left:20px; ".$border."\">".utf8_encode($row["Status_Trabalho"])."</td><td style=\"text-align:right; padding-right:60px; ".$border."\">".$ct[$row["Id_Status_Trabalho"]]."</td></tr>";
							
							if ($ct[$row["Id_Status_Trabalho"]]>0){
								echo "<tr><td colspan=2 style=\"text-align:left; padding-left:20px;\">";
								$i = 0;

								for ($i=0;$i<$ct[$row["Id_Status_Trabalho"]];$i++){
									echo "<p><img src='img/doc.gif'>".utf8_encode($ctt[$row["Id_Status_Trabalho"]][$i]);
									if (hasPerm(8)) echo "<br><b>Autor:</b> ".utf8_encode($cta[$row["Id_Status_Trabalho"]][$i]);								
								}

								echo "</td></tr>";
							}
							
						}
						
						echo "</table>";
					}
					else{
						echo "Nenhum trabalho listado.";
					}
					break;
					
		case 2:		//relatorio completo por revisor...
					$table = false;
					$sqlRev = "SELECT DISTINCT Id_Usr, USR.Nome FROM REVISORES INNER JOIN USR USING (Id_Usr) ORDER BY USR.Nome";
					$qryRev = T_query($sqlRev);
					if (T_num_rows($qryRev)){
						while ($rowRev = T_fetch_array($qryRev)){
							$sqlTrab = "SELECT Id_Trabalho FROM TRABALHOS WHERE EXISTS (SELECT * FROM REVISORES WHERE Id_Trabalho = TRABALHOS.Id_Trabalho AND Id_Usr = ".$rowRev["Id_Usr"].");";
							$qryTrab = T_query($sqlTrab);
							if (T_num_rows($qryTrab)){
								$trabCt = 0;
								
								unset($trabTitle);
								while ($rowTrab = T_fetch_array($qryTrab)){
									$sqlAcomp = "SELECT DISTINCT TRABALHOS.Titulo, Id_Trabalho FROM ACOMPANHAMENTO INNER JOIN TRABALHOS USING (Id_Trabalho) WHERE Id_Trabalho = '".$rowTrab[0]."' AND UNIX_TIMESTAMP(Data_Operacao) >= ".$timeini." AND UNIX_TIMESTAMP(Data_Operacao) <= ".$timefim;
									$qryAcomp = T_query($sqlAcomp);
									if (T_num_rows($qryAcomp)){
										$rowAcomp = T_fetch_array($qryAcomp);
										$trabTitle[$trabCt] = $rowAcomp[0];
										$trabId[$trabCt] = $rowAcomp[1];
										$trabCt++;
									}
								}
								
								if ($trabCt){
									if (!$table){
										echo "<table width=\"100%\" class=\"listaTrabalhos\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\"><tr><th scope=\"col\">Revisor</th><th scope=\"col\">Quantidade</th></tr>";
										$table = true;
									}
									$border = "border-color:#E3E3E3";
									echo "<tr><td style=\"text-align:left; padding-left:20px; ".$border."\">".utf8_encode($rowRev["Nome"])."</td><td style=\"".$border."\">".$trabCt."</td></tr>";
									echo "<tr><td style=\"text-align:left\" colspan=2>";
									
									$i = 0;
									for ($i =0; $i<$trabCt; $i++){
										echo "<p><img src='img/doc.gif'> ".utf8_encode($trabTitle[$i]);
										if (hasPerm(8)){
											$rowAutor = T_fetch_array(T_query("SELECT USR.Nome FROM AUTORES INNER JOIN USR USING (Id_Usr) WHERE Id_Trabalho = '".$trabId[$i]."' AND ORDEM = 0"));					
											echo "<br><b>Autor:</b> ".utf8_encode($rowAutor[0]);
										}
										echo "</li>";
									}
									echo "</td></tr>";
								}
							}							
						}// FIM DO WHILE ROWREV
						if ($table) echo "</table>";
					}
					
					
					
					break;
					
					
		default:	echo "ainda não implementado";	
		
		}		
		
	}// fim do completo
	
?>
