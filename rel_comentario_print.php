<?php

include_once("sessions.php");

include_once("conexao.php");

$idtrabalho = $_GET["idtrabalho"];

$sqlTrabalho = "SELECT	
					TRABALHOS.Id_Categoria_Trabalho,
					REVISOES.Id_Usr AS Id_Revisor,
					REVISOES.Coment_Autor, 
					REVISOES.Coment_CP,
					Originalidade.Originalidade, 
					Densidade.Densidade,
					Redacao.Redacao, 
					Referencias.Referencias, 
					Aceitacao.Aceitacao,
					Compreensao.Compreensao,	
					REVISOES.Data_Operacao, 
					REVISORES.Relator, 
					USR.Nome
				FROM REVISOES 
				INNER JOIN REVISORES ON REVISORES.Id_Trabalho= REVISOES.Id_Trabalho AND REVISORES.Id_Usr = REVISOES.Id_Usr
				INNER JOIN USR ON USR.Id_Usr = REVISOES.Id_Usr
				LEFT JOIN TRABALHOS ON TRABALHOS.Id_Trabalho = REVISOES.Id_Trabalho
				LEFT JOIN Originalidade ON Originalidade.Id_Originalidade = REVISOES.Originalidade
				LEFT JOIN Densidade ON Densidade.Id_Densidade = REVISOES.Densidade
				LEFT JOIN Redacao ON Redacao.Id_Redacao = REVISOES.Redacao
				LEFT JOIN Referencias ON Referencias.Id_Referencias = REVISOES.Referencias
				LEFT JOIN Aceitacao ON Aceitacao.Id_Aceitacao = REVISOES.Aceitacao
				LEFT JOIN Compreensao ON Compreensao.Id_Compreensao = REVISOES.Compreensao
 				WHERE REVISOES.Id_Trabalho = '".$idtrabalho."'
				ORDER BY REVISOES.Data_Operacao DESC, REVISORES.Relator DESC";
				 

$resultado = (T_query($sqlTrabalho));				 


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Trabalho <?php echo $idtrabalho; ?></title>
</head>
<script language="javascript"> window.print(); </script>
<style>
.tbl_trab{
	font-size: 10pt;
	font-family: "DejaVu Sans", Verdana, Arial, sans-serif;
}

.tbl_trab ul{
	margin:0 0 0 15px;
	padding:0 0 0 0;
}

.tbl_trab td{
	padding: 0 0 5px 5px;
	border-bottom:#CCCCCC 1px solid;
}


</style>
<body>
<?php
	
	echo "
	<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">
				<tr><td><h1><center>Coment&aacute;rio sobre o trabalho ".$idtrabalho."</center></h1></td></tr>
	</table><br>";
	
	
	$resultado = (T_query($sqlTrabalho));
	if( T_num_rows($resultado) == 0 )
	{
		echo "
		<table   align='left'>
				<tr><td>Nenhum coment&aacute;rio dispon&iacute;vel</td></tr>
		</table>";	
		 
	} 
	else
	{
		echo "
		<table width=\"463\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\" class=\"tbl_trab\">";
		 
		while( $campo = T_fetch_array( $resultado) )
		{
		
			$cargo = ( $campo['Relator'] == 1 ) ? "Relator" : "Revisor";
			$data = substr($campo['Data_Operacao'], 8, 2).'/'.substr($campo['Data_Operacao'], 5, 2).'/'.substr($campo['Data_Operacao'], 0, 4);
		
		
			echo"
			<tr>
				<td  align=\"left\" valign=\"top\">
					<strong>".$data."</strong><br>
					<strong>".$cargo.":</strong>
					&nbsp; ".utf8_encode($campo['Nome'])."<br>";
					
					
					
				if( $campo['Id_Categoria_Trabalho'] != 5 )
				{
					// publico alvo
					echo"<strong>P&uacute;blico Alvo:</strong> &nbsp; ";
					
					$query_publico_alvo = "
						SELECT Publico_Alvo.Publico_Alvo 
						FROM OCORRENCIA_PUBLICO_ALVO
						INNER JOIN Publico_Alvo ON Publico_Alvo.Id_Publico_Alvo = OCORRENCIA_PUBLICO_ALVO.Id_Publico_Alvo
						WHERE OCORRENCIA_PUBLICO_ALVO.Id_Trabalho='".$idtrabalho."' 
						AND OCORRENCIA_PUBLICO_ALVO.Id_Usr = '".$campo["Id_Revisor"]."'";
	
						$resultado2 = T_query($query_publico_alvo);						
						$i = 0;	
						while( $campo2 = T_fetch_array( $resultado2))
						{
							echo (($i++) == 0) ? "" :  "<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
										&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
							echo utf8_encode($campo2['Publico_Alvo']);
							
						}
					
						if( !empty (  $campo['Publico_Alvo_Outros'] ) )
						{
							"<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Outros: ";
							echo $campo['Publico_Alvo_Outros'];
						}			
					
						echo"<br>
						<strong>Originalidade:</strong> &nbsp; ".utf8_encode($campo['Originalidade'])."<br> 
						<strong>Densidade:</strong> &nbsp; ".utf8_encode($campo['Densidade'])."<br> 
						<strong>Reda&ccedil;&atilde;o:</strong> &nbsp; ".utf8_encode($campo['Redacao'])."<br> 
						<strong>Refer&ecirc;ncias Bibliogr&aacute;ficas:</strong> &nbsp; ".utf8_encode($campo['Referencias'])."<br> 
						<strong>Compreens&atilde;o:</strong> &nbsp; ".utf8_encode($campo['Compreensao'])."<br> ";
					}					
					
					echo"
					<strong>Aceita&ccedil;&atilde;o:</strong> &nbsp; ".utf8_encode($campo['Aceitacao'])."<br> 
					<strong>Coment&aacute;rio para o CP:</strong> &nbsp; ".utf8_encode($campo['Coment_CP'])."<br> 
					<strong>Coment&aacute;rio para o Autor:</strong> &nbsp; ".utf8_encode($campo['Coment_Autor'])."					
				</td>
			</tr>
			";
		}
		
		echo "
		</table>";
		 
	}
	 
?>
  
</body>
</html>
