<?php

include_once("sessions.php");
include_once("conexao.php");
allow(1);

$sql = "SELECT `Id_Trabalho`,`Versao`,`Titulo` FROM `TRABALHOS` WHERE `Id_Usr` = ".$_SESSION["USERID"];
$qry = T_query($sql);

$exists = false;

if (T_num_rows($qry)){
while ($row = T_fetch_array($qry)){

unset($sql);
$sql = "SELECT ACOMPANHAMENTO.`Id_Status_Trabalho` FROM `ACOMPANHAMENTO` 	
									INNER JOIN REVISORES ON REVISORES.Id_Trabalho = ACOMPANHAMENTO.Id_Trabalho		
									WHERE ACOMPANHAMENTO.`Id_Trabalho` = '".$row["Id_Trabalho"]."' 
										  AND REVISORES.Relator=1	
									ORDER BY ACOMPANHAMENTO.`Data_Operacao` DESC LIMIT 1;";
$qry1 = T_query($sql);
$row1 = T_fetch_array($qry1);

	if ($row1[0]==4){
	if (!$exists) echo "
	<div align=\"left\" style=\"font-size:8pt; color:#FF0000; font-weight:bold;\">*Clique sobre o trabalho para inserir a revis&atilde;o</div>
	<table width=\"800\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"listaTrabalhos\">
						<tr>
						  <th width=\"9%\"><strong>Código do Trabalho </strong></th>
						  <th><strong>Título</strong></th>
						  <th width=\"8%\"><strong>Versão </strong></th>
						</tr>";	
	
	echo "<tr style=\"cursor:pointer\" onClick=\"r = dhtmlwindow.open('rsub','ajax','submit_trabalho.php?id_trabalho=".$row["Id_Trabalho"]."','Submeter correção para o trabalho ".$row["Id_Trabalho"]."','width=465px,height=270px,center=1,resize=0,scrolling=0');\">";
	echo "<td align=\"center\">".utf8_encode($row["Id_Trabalho"])."</td>";
	echo "<td align=\"center\">".utf8_decode($row["Titulo"])."</td>";
	echo "<td align=\"center\">".utf8_encode($row["Versao"])."</td>";
	
	
	$exists = true;
	}
}//fim do while
if (!$exists)	echo "Nenhum trabalho em correção";
else echo "</table>";
}//fim do numrows
else{
echo "Nenhum trabalho em correção";
}
?>