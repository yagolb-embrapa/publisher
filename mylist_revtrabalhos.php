<?php

include_once("sessions.php");
include_once("conexao.php");
allow(2);

$sql = "SELECT Id_Trabalho, Titulo, Ext_arquivo, Versao FROM TRABALHOS WHERE EXISTS (SELECT * FROM REVISORES WHERE Id_Trabalho = TRABALHOS.Id_Trabalho AND Id_Usr = ".$_SESSION["USERID"].");";
$qry = T_query($sql);

if (!T_num_rows) echo "nenhum trabalho para revisao";
else{
$revisao = false;
	while ($row = T_fetch_array($qry)){
		

		
		$sql1 = "SELECT * FROM ACOMPANHAMENTO WHERE Id_Trabalho = '".$row["Id_Trabalho"]."' ORDER BY Data_Operacao DESC LIMIT 1;";
		$qry1 = T_query($sql1);
		$row1 = T_fetch_array($qry1);
			
						
		if (($row1["Id_Status_Trabalho"]==2)||($row1["Id_Status_Trabalho"]==5))
		{
			$relator = 0;
			// verifica se o usuario eh relator do trabalho
			$query = "SELECT Relator FROM REVISORES WHERE Id_Trabalho = '".$row["Id_Trabalho"]."' AND Id_Usr = ".$_SESSION["USERID"];
			$resultado = T_query($query);
			if( T_num_rows( $resultado) > 0 )
			{
				$campo = T_fetch_array($resultado);
				$relator = $campo['Relator'];
			}
			
			// Caso seja a primeira versao do trabalho, todos os revisores podem postar a revisao
			// Caso contrario, apenas o relator pode postar uma nova revisao
			if (( $relator == 1 ) || ( $row['Versao'] == 1) )
			{
				if (!$revisao){
					echo "<table width=\"800\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"listaTrabalhos\">
					<tr>
					  <th width=\"10%\">Download</th>
					  <th width=\"9%\"><strong>Código do Trabalho </strong></th>
					  <th><strong>Relator/Revisor</strong></th>
					  <th><strong>Título</strong></th>
					  <th width=\"8%\"><strong>Data Limite </strong></th>
					  <th><strong>Status</strong></th>
					</tr>";	
				}
				echo "<tr>";
				echo "<td align='center'><a target=\"_blank\" href='trabalhos/v".$row["Versao"]."/".$row["Id_Trabalho"].".".$row["Ext_arquivo"]."'>Download</a></td>";
				echo "<td style=\"cursor:pointer\" onClick=\"rwin=dhtmlwindow.open('rbox', 'ajax', 'InsRevisao.php?idtrabalho=".$row["Id_Trabalho"]."', 'Registrar revisão para Trabalho ".$row["Id_Trabalho"]."', 'width=700px,height=450px,center=1,resize=0,scrolling=1'); return false\">".$row["Id_Trabalho"]."</td>";
				
				 
				$sqlRev = "SELECT USR.Nome AS Revisor, Relator FROM REVISORES JOIN USR USING (Id_Usr) WHERE Id_Trabalho = '".$row["Id_Trabalho"]."' ORDER BY Relator DESC, Revisor ASC";
				$qryRev = T_query($sqlRev);			
				echo "<td align='left' style=\"padding: 6px 0 0 0;\">";
				$rv = 0;
				while ($rowRev = T_fetch_array($qryRev)){
				
					if( $rv == 0)
						$extra = " (relator) ";
					else
						$extra = " (revisor) ";
				
					$rv++;
					echo "<div  onMouseover=\"fixedtooltip('".utf8_encode($rowRev["Revisor"].$extra)."', this, event, '150px')\" onMouseout=\"delayhidetip()\" style=\"cursor:default; display:inline; border:#CCCCCC 1px solid; padding: 2px 4px 1px 4px; margin: 0 1px 0 1px;\">".$rv."</div>";
				}
				echo "</td>";
				 				
				echo "<td style=\"cursor:pointer\" onClick=\"rwin=dhtmlwindow.open('rbox', 'ajax', 'InsRevisao.php?idtrabalho=".$row["Id_Trabalho"]."', 'Registrar revisão para Trabalho ".$row["Id_Trabalho"]."', 'width=700px,height=450px,center=1,resize=0,scrolling=1'); return false\">".utf8_encode($row["Titulo"])."</td>";
					$row1["Data_Limite"] = explode("-",$row1["Data_Limite"]);
					echo "<td style=\"cursor:pointer\" onClick=\"rwin=dhtmlwindow.open('rbox', 'ajax', 'InsRevisao.php?idtrabalho=".$row["Id_Trabalho"]."', 'Registrar revisão para Trabalho ".$row["Id_Trabalho"]."', 'width=700px,height=450px,center=1,resize=0,scrolling=1'); return false\">".$row1["Data_Limite"][2]."/".$row1["Data_Limite"][1]."/".$row1["Data_Limite"][0]."</td>";
					
					
					$sql5 = "SELECT * FROM REVISOES WHERE Id_Trabalho ='".$row['Id_Trabalho']."' AND revisao=".$row['Versao']." AND  Id_Usr = ".$_SESSION['USERID'] . ";";
					if (T_num_rows(T_query($sql5))) {
						$status = '<font color=green>Revisado</font>';
					}
					else $status = '<font color=red>Pendente</font>';
					
					echo "<td><b>$status</b></td>";

					echo "</tr>";
					$revisao = true;
			}			
		}
	}
	if ($revisao) echo "</table>";
	else echo "Nenhuma revisão a ser feita.";
	
	

}


?>