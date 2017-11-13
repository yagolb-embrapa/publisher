<?php

include_once("conexao.php");

/*

FUNÇÕES PARA A MANIPULACAO DE EMAILS...

mailThem(string id_trabaho, int msg); // por enquanto a msg com id 1 eh pros revisores veres q tem um
trabalho novo pra eles corrigi... 

*/

function mailThem($trabalho,$tipo,$usr=0){
	
	if ($usr){
		$sqlRev = "SELECT Nome, Email FROM USR WHERE Id_Usr = ".$usr;
		$qryRev = T_query($sqlRev);
		if (T_num_rows($qryRev)) $rowRev = T_fetch_array($qryRev);
		T_free_result($qryRev);
	}
	
	$sqlEmail = "SELECT Tipo, Body FROM EMAIL_BODY WHERE Id_Email_Body = ".$tipo;
	$qryEmail = T_query($sqlEmail);
	$rowEmail = T_fetch_array($qryEmail);
	T_free_result($qryEmail);
		
	$sqlAss = "SELECT * FROM EMAIL_CONF";
	$qryAss = T_query($sqlAss);
	$rowAss = T_fetch_array($qryAss);
	T_free_result($qryAss);
	
	$sqlTrab = "SELECT Id_Trabalho, Versao, Titulo, USR.Nome AS Autor, USR.Email FROM TRABALHOS JOIN USR USING (Id_Usr) WHERE Id_Trabalho = '".$trabalho."'";
	$qryTrab = T_query($sqlTrab);
	$rowTrab = T_fetch_array($qryTrab);
	T_free_result($qryTrab);
	
	$sqlRel = "SELECT Id_Trabalho, USR.Nome AS Autor, USR.Email FROM REVISORES JOIN USR USING (Id_Usr) WHERE Id_Trabalho = '".$trabalho."' AND Relator=1";
	$qryRel = T_query($sqlRel);
	$rowRel = T_fetch_array($qryRel);
	T_free_result($qryRel);
	
	
	$sqlAcomp = "SELECT UNIX_TIMESTAMP(Data_Operacao) AS Dt_Operacao, Id_Trabalho, Id_Status_Trabalho, UNIX_TIMESTAMP(Data_Limite) AS Dt_Limite FROM ACOMPANHAMENTO WHERE Id_Trabalho = '".$trabalho."' ORDER BY Data_Operacao DESC LIMIT 1;";
	$qryAcomp = T_query($sqlAcomp);
	$rowAcomp = T_fetch_array($qryAcomp);

	$header = "From: Comite de Publicacoes <cp@cnptia.embrapa.br>". "\r\n";
	$header .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
	
	$parameter ="-fcp@cnptia.embrapa.br";

		
	switch($tipo){
		
		case 1:			$trab_str = "\"".$rowTrab["Titulo"]."\"";
					$corpo = sprintf($rowEmail[1],$rowTrab["Autor"],$trab_str,$rowTrab["Id_Trabalho"]);
					$corpo = str_replace("\\n","<br>",$corpo);
					mail($rowTrab["Email"],$rowEmail["Tipo"],$corpo,$header);
					mail($rowAss["Email_Secretario"],$rowEmail["Tipo"],$corpo,"To: Secretario <".$rowAss["Email_Secretario"].">"."\r\n".$header);
					mail($rowAss["Email_Presidente"],$rowEmail["Tipo"],$corpo,$header,$parameter);
					break;
		
		case 2:		$sqlR = "SELECT Nome, Email FROM USR INNER JOIN REVISORES USING(Id_Usr) WHERE REVISORES.Id_Trabalho = '".$trabalho."'"; 
					if (!empty($usr)) $sqlR .= " AND Id_Usr = ".$usr;
					$qryR = T_query($sqlR);
					while ($rowR = T_fetch_array($qryR)){
						$corpo = sprintf($rowEmail[1],$rowR["Nome"],$rowTrab["Titulo"],$rowTrab["Versao"],date("d/m/Y",$rowAcomp["Dt_Limite"]),$rowAss["Assinatura_Presidente"]);
						$corpo = str_replace("\\n","<br>",$corpo);
						mail($rowR["Email"],$rowEmail["Tipo"],$corpo,$header,$parameter);
						mail($rowAss["Email_Secretario"],$rowEmail["Tipo"],$corpo,$header,$parameter);
					}
					break;
		
		case 3: 	$trab_str = "\"".$rowTrab["Titulo"]."\" , nr. ".$trabalho.",";
					$corpo = sprintf($rowEmail[1],$rowRev["Nome"],$trab_str,date("d/m/Y",$rowAcomp["Dt_Limite"]),$rowAss["Assinatura_Secretario"]);
					$corpo = str_replace("\\n","<br>",$corpo);
					mail($rowRev["Email"],$rowEmail["Tipo"],$corpo,$header,$parameter);
					mail($rowAss["Email_Secretario"],$rowEmail["Tipo"],$corpo,$header,$parameter);
					break;
					
					
		case 4: 	//$sqlAcomp = "SELECT * FROM ACOMPANHAMENTO WHERE Id_Trabalho = '".$trabalho."' AND Id_Status_Trabalho = 2 ORDER BY Data_Operacao DESC LIMIT 1";
		            $sqlAcomp = "SELECT * FROM ACOMPANHAMENTO WHERE Id_Trabalho = '".$trabalho."' AND (Id_Status_Trabalho = 2 OR Id_Status_Trabalho = 5) ORDER BY Data_Operacao DESC LIMIT 1";
					$qryAcomp = T_query($sqlAcomp);
					$rowAcomp = T_fetch_array($qryAcomp);
					
					$sqlRevisao = "SELECT 
					TRABALHOS.Id_Categoria_Trabalho,
					REVISOES.Id_Usr AS Id_Revisor,
					REVISOES.Coment_Autor, 
					REVISOES.Coment_CP,
					REVISOES.Publico_Alvo_Outros,
					Originalidade.Originalidade, 
					Densidade.Densidade,
					Redacao.Redacao, 
					Referencias.Referencias, 
					Aceitacao.Aceitacao,
					Compreensao.Compreensao
					FROM REVISOES
					LEFT JOIN TRABALHOS ON TRABALHOS.Id_Trabalho = REVISOES.Id_Trabalho
 					LEFT JOIN Originalidade ON Originalidade.Id_Originalidade = REVISOES.Originalidade
					LEFT JOIN Densidade ON Densidade.Id_Densidade = REVISOES.Densidade
					LEFT JOIN Redacao ON Redacao.Id_Redacao = REVISOES.Redacao
					LEFT JOIN Referencias ON Referencias.Id_Referencias = REVISOES.Referencias
					LEFT JOIN Aceitacao ON Aceitacao.Id_Aceitacao = REVISOES.Aceitacao
					LEFT JOIN Compreensao ON Compreensao.Id_Compreensao = REVISOES.Compreensao
					WHERE REVISOES.Id_Trabalho = '".$trabalho."' AND REVISOES.Data_Operacao = '".$rowAcomp["Data_Operacao"]."'";
					$qryRevisao = T_query($sqlRevisao);
					
					$parecer = "----------------------------------------<br>";
					while ($rowRevisao = T_fetch_array($qryRevisao))
					{						 										
						if( $rowRevisao['Id_Categoria_Trabalho'] != 5 )
						{

							$parecer.= "<b>Publico Alvo:</b><br>";
							$query_publico_alvo = "
									SELECT Publico_Alvo.Publico_Alvo 
											FROM OCORRENCIA_PUBLICO_ALVO
											INNER JOIN Publico_Alvo ON Publico_Alvo.Id_Publico_Alvo = OCORRENCIA_PUBLICO_ALVO.Id_Publico_Alvo
											WHERE OCORRENCIA_PUBLICO_ALVO.Id_Trabalho='".$trabalho."' 
													AND OCORRENCIA_PUBLICO_ALVO.Id_Usr = '".$rowRevisao["Id_Revisor"]."'";
	
							$resultado = T_query($query_publico_alvo);						
							while( $campo = T_fetch_array( $resultado))
							{
								$parecer.= ($campo['Publico_Alvo'])."<br>";
							}
							
							if( !empty( $rowRevisao['Publico_Alvo_Outros'] ) )
							{
								$parecer.= $rowRevisao['Publico_Alvo_Outros']."<br>";		 					
							}
							
							
							$parecer.= "<br><br><b>Originalidade:</b><br>";
							$parecer.=  ($rowRevisao['Originalidade'])."<br>";
							
							$parecer.= "<br><br><b>Densidade:</b><br>";
							$parecer.=  ($rowRevisao['Densidade'])."<br>";
							
							$parecer.= "<br><br><b>Reda&ccedil;&atilde;o:</b><br>";
							$parecer.=  ($rowRevisao['Redacao'])."<br>";
													
							$parecer.= "<br><br><b>Refer&ecirc;ncias Bibliogr&aacute;ficas:</b><br>";
							$parecer.=  ($rowRevisao['Referencias'])."<br>";
							 							
							$parecer.= "<br><br><b>Compreens&atilde;o:</b><br>";
							$parecer.=  ($rowRevisao['Compreensao'])."<br>";
							 
							// cria uma copia do conteudo do email atual que sera enviado para a secretaria do cp
							// a copia contem algumas informações que o email para o autor não tem
 							$parecer_secretaria = $parecer;
							$parecer_secretaria .= "<br><br><b>Aceita&ccedil;&atilde;o:</b><br>";
							$parecer_secretaria .= $rowRevisao['Aceitacao']."<br>";
						}
																			
						$parecer.= "<br><br><b>Coment&aacute;rios:</b><br>";
						$parecer .=  ($rowRevisao["Coment_Autor"]);
						$parecer .= "<br>----------------------------------------<br>";
											
													
						$parecer_secretaria .= "<br><br><b>Coment&aacute;rios:</b><br>";
						$parecer_secretaria .=  ($rowRevisao["Coment_Autor"]);
						$parecer_secretaria .= "<br>----------------------------------------<br>";
						
					}
			
					$corpo = sprintf($rowEmail[1],$rowTrab["Autor"],$trabalho,$rowTrab["Titulo"],$parecer,$rowAss["Assinatura_Secretario"]);
					$corpo = str_replace("\\n","<br>",$corpo);
					mail($rowTrab["Email"],$rowEmail["Tipo"],$corpo,$header,$parameter);
					
					// Copia do email para a secretaria
					$corpo = sprintf($rowEmail[1],$rowTrab["Autor"],$trabalho,$rowTrab["Titulo"],$parecer_secretaria,$rowAss["Assinatura_Secretario"]);
					$corpo = str_replace("\\n","<br>",$corpo);
					mail($rowAss["Email_Secretario"],$rowEmail["Tipo"],$corpo,$header,$parameter);
					break;
 	
 		case 5: 	$trab_str = "\"".$rowTrab["Titulo"]."\" , nr. ".$trabalho.",";
					$corpo = sprintf($rowEmail[1],$rowRev["Nome"],$trab_str,date("d/m/Y",$rowAcomp["Dt_Limite"]),$rowAss["Assinatura_Presidente"]);
					$corpo = str_replace("\\n","<br>",$corpo);
					mail($rowRev["Email"],$rowEmail["Tipo"],$corpo,$header,$parameter);
					mail($rowAss["Email_Secretario"],$rowEmail["Tipo"],$corpo,$header,$parameter);
					mail($rowAss["Email_Presidente"],$rowEmail["Tipo"],$corpo,$header,$parameter);
					break;
		case 6: 	$trab_str = "\"".$rowTrab["Titulo"]."\" , nr. ".$trabalho.",";
					$corpo = sprintf($rowEmail[1],$rowTrab["Autor"],$trab_str,$rowAss["Assinatura_Presidente"]);
					$corpo = str_replace("\\n","<br>",$corpo);
					mail($rowTrab["Email"],$rowEmail["Tipo"],$corpo,$header,$parameter);
					mail($rowAss["Email_Secretario"],$rowEmail["Tipo"],$corpo,$header,$parameter);
					mail($rowAss["Email_Presidente"],$rowEmail["Tipo"],$corpo,$header,$parameter);
					break;
		case 7: 	$sqlAcomp = "SELECT * FROM ACOMPANHAMENTO WHERE Id_Trabalho = '".$trabalho."' AND Id_Status_Trabalho = 3 ORDER BY Data_Operacao DESC LIMIT 1";
					$qryAcomp = T_query($sqlAcomp);
					$rowAcomp = T_fetch_array($qryAcomp);
					
					$sqlRevisao = "SELECT Coment_Autor FROM REVISOES WHERE Id_Trabalho = '".$trabalho."' AND Data_Operacao = '".$rowAcomp["Data_Operacao"]."'";
					$qryRevisao = T_query($sqlRevisao);
					
					$parecer = "<br>----------------------------------------<br>";
					while ($rowRevisao = T_fetch_array($qryRevisao)){
						$parecer .= ($rowRevisao["Coment_Autor"]);
						$parecer .= "<br>----------------------------------------<br>";
					}
						
					$corpo = sprintf($rowEmail[1],  $rowTrab["Autor"],$rowTrab["Titulo"],$trabalho,$parecer,$rowAss["Assinatura_Presidente"]);
					$corpo = str_replace("\\n","<br>",$corpo);
					mail($rowTrab["Email"],$rowEmail["Tipo"],$corpo,$header,$parameter);
					mail($rowAss["Email_Presidente"],$rowEmail["Tipo"],$corpo,$header,$parameter);
					mail($rowAss["Email_Secretario"],$rowEmail["Tipo"],$corpo,$header,$parameter);				
					break;
		case 8: 	$corpo = sprintf($rowEmail[1],$rowRev["Nome"],$rowTrab["Titulo"],$trabalho,$rowAss["Assinatura_Presidente"]);
					$corpo = str_replace("\\n","<br>",$corpo);
					mail($rowRev["Email"],$rowEmail["Tipo"],$corpo,$header,$parameter);
					break;
		case 9: 	$corpo = sprintf($rowEmail[1],$rowTrab["Titulo"],$trabalho);
					$corpo = str_replace("\\n","<br>",$corpo);
					mail($rowAss["Email_Secretario"],$rowEmail["Tipo"],$corpo,$header,$parameter);
					mail($rowAss["Email_Presidente"],$rowEmail["Tipo"],$corpo,$header,$parameter);
					break;

		case 10:	$corpo = sprintf($rowEmail[1],$rowTrab["Autor"],$rowTrab["Versao"],$rowTrab["Titulo"]);
					$corpo = str_replace("\\n","<br>",$corpo);
					mail($rowTrab["Email"],$rowEmail["Tipo"],$corpo,$header,$parameter);
					mail($rowAss["Email_Secretario"],$rowEmail["Tipo"],$corpo,$header,$parameter);
					break;
		case 11:	$trab_str = "\"".$rowTrab["Titulo"]."\" , nr. ".$trabalho.",";
				$corpo = sprintf($rowEmail[1],$rowAss["Bibliotecario1"],$trab_str,$rowAss["Assinatura_Secretario"]);
					$corpo = str_replace("\\n","<br>",$corpo);
					mail($rowAss["Email_Bib_1"],$rowEmail["Tipo"],$corpo,$header,$parameter);
					mail($rowAss["Email_Secretario"],$rowEmail["Tipo"],$corpo,$header,$parameter);
					$corpo = sprintf($rowEmail[1],$rowAss["Bibliotecario2"],$trab_str,$rowAss["Assinatura_Secretario"]);
					$corpo = str_replace("\\n","<br>",$corpo);
					mail($rowAss["Email_Bib_2"],$rowEmail["Tipo"],$corpo,$header,$parameter);
					break;	
		case 12:	$trab_str = "\"".$rowTrab["Titulo"]."\" , nr. ".$trabalho.",";
 					$corpo = sprintf($rowEmail[1],$rowAss["Editor"],$trab_str,$rowAss["Bibliotecario1"]);
					$corpo = str_replace("\\n","<br>",$corpo);
					mail($rowAss["Email_Editoracao"],$rowEmail["Tipo"],$corpo,$header,$parameter);
					mail($rowAss["Email_Secretario"],$rowEmail["Tipo"],$corpo,$header,$parameter);
					break;
					
		case 13:	
					foreach( $usr as $chave => $valor)
					{
						$sqlR = "SELECT Nome, Email FROM USR INNER JOIN REVISORES USING(Id_Usr) WHERE REVISORES.Id_Trabalho = '".$trabalho."' AND REVISORES.Id_Usr = '".$valor."' LIMIT 1"; 
	
		 				$qryR = T_query($sqlR);
			 			$rowR = T_fetch_array($qryR) ;
				 		$corpo = sprintf($rowEmail[1],$rowR["Nome"],$rowTrab["Titulo"],$rowTrab["Versao"],date("d/m/Y",$rowAcomp["Dt_Limite"]),$rowAss["Assinatura_Presidente"]);
					 	$corpo = str_replace("\\n","<br>",$corpo);
 						mail($rowR["Email"],$rowEmail["Tipo"],$corpo,$header,$parameter);
	 					mail($rowAss["Email_Secretario"],$rowEmail["Tipo"],$corpo,$header,$parameter);
						
					}
					break;
			
		case 14:
					$sqlR = "SELECT Nome, Email FROM USR INNER JOIN REVISORES USING(Id_Usr) WHERE REVISORES.Id_Trabalho = '".$trabalho."' AND Relator = 1 "; 
					if ($usr) $sqlR .= " AND Id_Usr = ".$usr;
					$qryR = T_query($sqlR);
					while ($rowR = T_fetch_array($qryR))
					{
						$corpo = sprintf($rowEmail[1],$rowR["Nome"],$rowTrab["Titulo"],$rowTrab["Versao"],date("d/m/Y",$rowAcomp["Dt_Limite"]),$rowAss["Assinatura_Presidente"]);
						$corpo = str_replace("\\n","<br>",$corpo);
						mail($rowR["Email"],$rowEmail["Tipo"],$corpo,$header,$parameter);
						mail($rowAss["Email_Secretario"],$rowEmail["Tipo"],$corpo,$header,$parameter);
					}
					break;
		case 15:	$trab_str = "\"".$rowTrab["Titulo"]."\" , no ".$trabalho.",";
				$corpo = sprintf($rowEmail[1],$rowAss["Revisor"],$trab_str,$rowAss["Assinatura_Secretario"]);
					$corpo = str_replace("\\n","<br>",$corpo);
					mail($rowAss["Email_Revisor"],$rowEmail["Tipo"],$corpo,$header,$parameter);
					mail($rowAss["Email_Secretario"],$rowEmail["Tipo"],$corpo,$header,$parameter);
					break;	
		case 16:	$trab_str = "\"".$rowTrab["Titulo"]."\" , no ".$trabalho.",";
					$corpo = sprintf($rowEmail[1],$rowAss["Editor"],$trab_str,$rowAss["Revisor"]);
					$corpo = str_replace("\\n","<br>",$corpo);
					mail($rowAss["Email_Editor"],$rowEmail["Tipo"],$corpo,$header,$parameter);
					mail($rowAss["Email_Secretario"],$rowEmail["Tipo"],$corpo,$header,$parameter);
					break;
		case 17: 
		$trab_str = "\"".$rowTrab["Titulo"]."\" , no ".$trabalho."";
				$corpo = sprintf($rowEmail[1],$rowTrab["Autor"],$trab_str,$rowAss["Assinatura_Presidente"]);	
					$corpo = str_replace("\\n","<br>",$corpo);
					mail($rowAss["Email_Editor"],$rowEmail["Tipo"],$corpo,$header,$parameter);
					mail($rowAss["Email_Secretario"],$rowEmail["Tipo"],$corpo,$header,$parameter);
		break;	
		default:	$corpo = false;
					break;
	}
		
		
	return $corpo;
}





?>