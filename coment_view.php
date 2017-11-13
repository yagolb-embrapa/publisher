<?php

	include_once("sessions.php");
	allow(26);
	include_once("conexao.php");
	
	$row = T_fetch_array(T_query("SELECT Relator FROM REVISORES WHERE Id_Usr = ".$_SESSION["USERID"]." AND Id_Trabalho = '".$_GET["idtrabalho"]."'"));
	if (!$row[0]) echo "Você não tem acesso a essa area.";
	else{
		
		unset($row);
		
		$sqlAc = "SELECT Data_Operacao FROM ACOMPANHAMENTO WHERE Id_Trabalho = '".$_GET["idtrabalho"]."' ORDER BY Data_Operacao DESC LIMIT 1";
		$qryAc = T_query($sqlAc);
		$rowAc = T_fetch_array($qryAc);
		
		$sql = "SELECT REVISOES.Coment_Autor, REVISOES.arquivo, REVISOES.Coment_CP FROM REVISOES WHERE REVISOES.Id_Trabalho = '".$_GET["idtrabalho"]."' AND  REVISOES.Id_Usr NOT IN ( SELECT REVISORES.Id_Usr FROM REVISORES WHERE REVISORES.Id_Trabalho = '".$_GET["idtrabalho"]."' AND REVISORES.Relator = 1)";
		
		
 		$qry = T_query($sql); 
		
		if (T_num_rows($qry)){
			echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" class=\"listaTrabalhos\">
					  <tr>
						<th>Comentário para Autor </th>
						<th>Comentário para CP </th>
					  </tr>";
			while ($row = T_fetch_array($qry)){
				echo "	<tr>
							<td>".utf8_encode($row["Coment_Autor"])."</td>
							<td>".utf8_encode($row["Coment_CP"])."</td>
						</tr>";
				if ($row["arquivo"]!=''){
					echo "<tr>";
					echo "<td colspan=2 style='text-align:right; padding-right:1.3em;'>";
					echo "Clique <a style='color:blue' href='revisoes/v1/".$row["arquivo"]."'>aqui</a> para ver arquivo com comentários do revisor.";
					echo "</td>";
					echo "</tr>";
				}
			}				
			echo "</table>";
		}
		else{
			echo "Nenhuma revisão foi enviada ainda.";
		}
		
		
			
	
	
	}

?>
<link rel="stylesheet" href="style.css">

  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
</table>