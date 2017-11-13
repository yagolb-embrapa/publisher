<?php

/**************************************************************************************
***  ARQUIVO QUE VERIFICA AS PENDENCIAS, MANDA EMAILS DE ACORDO COM O DIA           ***
***  PARA SER COLOCADO PRA EXECUÇÃO NO CRONTAB.										***
***	 A FREQUENCIA DE EXECUÇÃO DESSE ARQUIVO DEVE SER DISCUTIDA PELO UTILIZADOR		***
**************************************************************************************/

include("emailFunc.php");

/* PROCEDIMENTO COM ERRO DE LÓGICA. FOI SUBSTITUÍDO PELO PROCEDIMENTO setAtrasado()
function verificaAtrasos(){
	//$sqlQry = "SELECT `Id_Trabalho` FROM TRABALHOS ORDER BY `Id_Trabalho`;";
      $sqlQry = "SELECT `Id_Trabalho` FROM ACOMPANHAMENTO WHERE Id_Status_Trabalho = 5 ORDER BY `Id_Trabalho`;";
	$qry2 = T_query($sqlQry);	
	if ($qry2){
		while ($row = T_fetch_array($qry2)){
			T_free_result($qry);
			$sql = "SELECT `Id_Status_Trabalho`,`Data_Limite` FROM `ACOMPANHAMENTO` WHERE `Id_Trabalho` = '".$row["Id_Trabalho"]."' ORDER BY `Data_Operacao` DESC;";
			echo $sql ;
			$qry = T_query($sql);
			$rowTab = T_fetch_array($qry);
			$sqlRev = "SELECT Id_Usr FROM REVISORES WHERE Id_Trabalho = '".$row[0]."' AND NOT EXISTS (SELECT * FROM REVISOES WHERE Id_Usr = REVISORES.Id_Usr AND Data_Operacao = '".$rowAcomp["Data_Operacao"]."' AND Id_Trabalho = '".$row[0]."')";
			echo $sqlRev ;		
                        $qryRev = T_query($sqlRev);
			//agora vai a verificação do trabalho se ele estiver atrasado...
				if ($rowTab["Id_Status_Trabalho"]==5){
				        mailThem($row[0],5,$rowRev[0]);
						echo "O trabalho ".$row["Id_Trabalho"]." est&aacute; atrasado...\n";
				}
		}
	}	
echo "Passei 3...\n";

}
*/

function enviarLembrete(){
	$sqlTrab = "SELECT `Id_Trabalho` FROM TRABALHOS ORDER BY `Id_Trabalho`;";
	$qryTrab = T_query($sqlTrab);
	
	while ($row = T_fetch_array($qryTrab)){
		
		/* Pegando os dados do trabalho */
		$sqlAcomp = "SELECT AC.Data_Operacao, AC.Id_Status_Trabalho, UNIX_TIMESTAMP(AC.Data_Limite)AS Dt_Limite, TR.Versao 
		             FROM ACOMPANHAMENTO AC
						 INNER JOIN TRABALHOS TR ON TR.Id_Trabalho = AC.Id_Trabalho
		             WHERE AC.Id_Trabalho = '".$row[0]."' 
						 ORDER BY AC.Data_Operacao DESC";
		$qryAcomp = T_query($sqlAcomp);
		$rowAcomp = T_fetch_array($qryAcomp);
		/* Se estiver em revisao */
		if ($rowAcomp["Id_Status_Trabalho"] == 2){
			$timeatual = strtotime(date("Y-m-d")); 				
 			if (($timeatual+172800)==$rowAcomp["Dt_Limite"] && $rowAcomp['Versao']==1 ){
	 			/* Manda o lembrete pra todos os revisores que ainda nao revisaram */
				$sqlRev = "SELECT Id_Usr FROM REVISORES WHERE Id_Trabalho = '".$row[0]."' AND NOT EXISTS (SELECT * FROM REVISOES WHERE Id_Usr = REVISORES.Id_Usr AND Data_Operacao = '".$rowAcomp["Data_Operacao"]."' AND Id_Trabalho = '".$row[0]."')";
				$qryRev = T_query($sqlRev);
				if (T_num_rows($qryRev)){
					while ($rowRev = T_fetch_array($qryRev)){
						mailThem($row[0],3,$rowRev[0]);
						//echo "MAIL pra ".$rowRev[0]."<br>";		
					}				
				}
			}elseif(($timeatual+172800)==$rowAcomp["Dt_Limite"] && $rowAcomp['Versao']>1 ){				
				/* Manda o lembrete so pro relator do trabalho, caso ele nao tenha revisado, pois ja nao esta mais na primeira versao */
				$sqlRev = "SELECT Id_Usr FROM REVISORES WHERE Id_Trabalho = '".$row[0]."' AND Relator = 1 AND NOT EXISTS (SELECT * FROM REVISOES WHERE Id_Usr = REVISORES.Id_Usr AND Data_Operacao = '".$rowAcomp["Data_Operacao"]."' AND Id_Trabalho = '".$row[0]."')";				
				$qryRev = T_query($sqlRev);
				if (T_num_rows($qryRev)){					
					while ($rowRev = T_fetch_array($qryRev)){
						mailThem($row[0],3,$rowRev[0]);
						//echo "MAIL pra ".$rowRev[0]."<br>";		
					}					
				}			
			}
		}
		
	}
	
}

function setAtrasado(){
	/* Pegando todos os trabalhos ainda nao finalizados */	
	$sql = "SELECT Id_Trabalho, Versao FROM TRABALHOS WHERE NOT EXISTS (SELECT * FROM ACOMPANHAMENTO WHERE Id_Trabalho = TRABALHOS.Id_Trabalho AND Id_Status_Trabalho >5) ORDER BY `Id_Trabalho`;";
	$qry = T_query($sql);	
	if ($qry){	
		while ($row = T_fetch_array($qry)){	
			/* Pega o ultimo status (pela data de operacao */		
			$sqlS = "SELECT Data_Cobranca, Data_Operacao,`Id_Status_Trabalho`,`Data_Limite` FROM `ACOMPANHAMENTO` WHERE `Id_Trabalho` = '".$row["Id_Trabalho"]."' ORDER BY `Data_Operacao` DESC LIMIT 1;";
			$qryS = T_query($sqlS);
			if ($rowS = T_fetch_array($qryS)){	
				$data_atual = strtotime(date("Y-m-d"));
				/*Se estiver em revisao*/							
				if ($rowS["Id_Status_Trabalho"] == 2){						
					
					$data_limite = strtotime($rowS["Data_Limite"]);					
					/* Verifica se esta atrasado */
					if ($data_atual > $data_limite){
						//echo "EM REVISAO E ATRASADO<br>";
					   /* Muda status do acompanhamento */
						$sqlC = "INSERT INTO ACOMPANHAMENTO (Data_Operacao, Id_Trabalho, Id_Status_Trabalho, Data_Limite) VALUES (now(),'".$row["Id_Trabalho"]."',5,'".$rowS["Data_Limite"]."');";
						$qryC = T_query($sqlC);
						
						/* Verifica a versao do trabalho */
						if($row['Versao'] > 1){
							//manda so pro relator atrasado
							$cond = " AND Relator = 1 ";
							echo "So pro relator<br>";														 
						}else{							
							//manda pra todos os atrasados
							$cond = "";
							echo "Pra todos<br>";
						}								
						
						/* Envia e-mail de cobrança para revisores que estao em atraso */
						$sqlRev = "SELECT Id_Usr FROM REVISORES WHERE Id_Trabalho = '".$row[0]."' {$cond} AND NOT EXISTS (SELECT * FROM REVISOES WHERE Id_Usr = REVISORES.Id_Usr AND Data_Operacao = '".$rowS["Data_Operacao"]."' AND Id_Trabalho = '".$row[0]."')";
						$qryRev = T_query($sqlRev);
						if (T_num_rows($qryRev)){
							while ($rowRev = T_fetch_array($qryRev)){
								mailThem($row[0],5,$rowRev[0]);
								//echo "EMAIL COM DADOS -> TRABALHO: {$row[0]} | PARA REVISOR: {$rowRev[0]}<br>";
							}
						}
					}							
				}else if($rowS["Id_Status_Trabalho"] == 5 && (($data_atual - strtotime($rowS["Data_Operacao"])) % 259200) == 0){
					/* Verifica a versao do trabalho */
						if($row['Versao'] > 1){
							//manda so pro relator atrasado
							$cond = " AND Relator = 1 ";
							echo "So pro relator<br>";														 
						}else{							
							//manda pra todos os atrasados
							$cond = "";
							echo "Pra todos<br>";
						}								
						
						/* Envia e-mail de cobrança para revisores que estao em atraso */
						$sqlRev = "SELECT Id_Usr FROM REVISORES WHERE Id_Trabalho = '".$row[0]."' {$cond} AND NOT EXISTS (SELECT * FROM REVISOES WHERE Id_Usr = REVISORES.Id_Usr AND Data_Operacao = '".$rowS["Data_Operacao"]."' AND Id_Trabalho = '".$row[0]."')";
						$qryRev = T_query($sqlRev);
						if (T_num_rows($qryRev)){
							while ($rowRev = T_fetch_array($qryRev)){
								mailThem($row[0],5,$rowRev[0]);
								//echo "EMAIL COM DADOS -> TRABALHO: {$row[0]} | PARA REVISOR: {$rowRev[0]}<br>";
							}
						}
				
				/*
					//echo "<br><br>SOMENTE ATRASADO<br>";
					$query = "SELECT Data_Operacao,`Id_Status_Trabalho`,`Data_Limite` FROM `ACOMPANHAMENTO` WHERE `Id_Trabalho` = '".$row["Id_Trabalho"]."'  AND Id_Status_Trabalho = '2' ORDER BY `Data_Operacao` DESC LIMIT 1;";
					$result = T_query($query);
					$campo = T_fetch_array($result);
					$data_date = date("Y-m-d");
					$data_atual = strtotime($data_date);						
					$data_cobranca = strtotime($rowS["Data_Cobranca"]);						
					//Se faz 3 ou mais dias que enviou o aviso, envia-o novamente			
					if($data_atual >= $data_cobranca + 259200){
						// Verifica a versao do trabalho 
						if($row['Versao'] > 1){
							//manda so pro relator atrasado
							$cond = " AND Relator = 1 ";														 
						}else{							
							//manda pra todos os atrasados
							$cond = "";
						}	
																					
						$sqlRev = "SELECT Id_Usr FROM REVISORES WHERE Id_Trabalho = '".$row[0]."' {$cond} AND NOT EXISTS (SELECT * FROM REVISOES WHERE Id_Usr = REVISORES.Id_Usr AND Data_Operacao = '".$campo["Data_Operacao"]."' AND Id_Trabalho = '".$row[0]."')";
						$sqlRev2 = "SELECT Id_Usr FROM REVISORES WHERE Id_Trabalho = '".$row[0]."' {$cond} AND NOT EXISTS (SELECT * FROM REVISOES WHERE Id_Usr = REVISORES.Id_Usr AND Data_Operacao = '".$rowS["Data_Operacao"]."' AND Id_Trabalho = '".$row[0]."')";														
						$qryRev = T_query($sqlRev);
						$flag_bd = 0;
						if (T_num_rows($qryRev) || T_num_rows($qryRev2)){
							while ($rowRev = T_fetch_array($qryRev)){
								$qryRev2 = T_query($sqlRev2);																		
								while($rowRev2 = T_fetch_array($qryRev2)){										
									if($rowRev['Id_Usr'] == $rowRev2['Id_Usr']){//se estiver nos dois
										mailThem($row[0],5,$rowRev[0]);									
										//echo "EMAIL COM DADOS -> TRABALHO: {$row[0]} | PARA REVISOR: {$rowRev[0]}<br>";
										$flag_bd = 1;
									}
								}
							}
						}
					}					
					if($flag_bd == 1){
						$query_update  = "UPDATE `ACOMPANHAMENTO` SET Data_Cobranca = '{$data_date}' WHERE Id_Trabalho = '".$row[0]."' AND Id_Status_Trabalho = '5' AND Data_Operacao = '".$rowS["Data_Operacao"]."'";							
						$result_update = T_query($query_update);
						//echo "ATUALIZANDO ACOMPANHAMENTO EM Id_Trabalho = '".$row[0]." AND Id_Status_Trabalho = '5' AND Data_Operacao = '".$rowS["Data_Operacao"]."'<br><br>";
						$flag_bd = 0; 
					}
					*/
				}
					
			}			
		}	
	}
	
}


//EXECUTANDO O QUE PRECISA...
setAtrasado();
enviarLembrete();
//verificaAtrasos();

?>