<?php

//recebe o idtrabalho e a versao em questao para procurar o arquivo na respectiva pasta
function busca_arquivo($idtrabalho, $i){
	$array_ext[0] = "doc";
	$array_ext[1] = "pdf";
	$array_ext[2] = "odt";
	$array_ext[3] = "zip";
	
	for($count=0;$count<count($array_ext);$count++){
		$arquivo = "trabalhos/v".$i."/".$idtrabalho.".".$array_ext[$count];
		//echo $arquivo."<br>";		
		if(file_exists($arquivo))
			return $array_ext[$count];			
	}
	return NULL;
}

//Criada esta funcao pois algumas raras vezes o arquivo recebe o nome *_old 
function busca_arquivo_old($idtrabalho, $i){
	$array_ext[0] = "doc";
	$array_ext[1] = "pdf";
	$array_ext[2] = "odt";
	$array_ext[3] = "zip";
	
	for($count=0;$count<count($array_ext);$count++){
		$arquivo = "trabalhos/v".$i."/".$idtrabalho."_old.".$array_ext[$count];
		//echo $arquivo."<br>";
		if(file_exists($arquivo))
			return $array_ext[$count];			
	}
	return NULL;
}

include_once("conexao.php");

$idtrabalho = $_GET["idtrabalho"];
$x = 1;

//pegando a data da primeira versao
$row = T_fetch_array(T_query("SELECT UNIX_TIMESTAMP(Data_Post) FROM TRABALHOS WHERE Id_Trabalho = '".$idtrabalho."'"));
$datas_versoes[$x] = date("d/m/Y",$row[0]);
//$desc_versoes[$x] = "Artigo submetido";
$desc_versoes[$x] = "Primeira versão do trabalho";
$x++;
$num_versoes = 1;
unset($row);

$extenso[2] = "Segunda";
$extenso[3] = "Terceira";
$extenso[4] = "Quarta";
$extenso[5] = "Quinta";
$extenso[6] = "Sexta";

//pegando as datas de cada versao
$sql = "SELECT UNIX_TIMESTAMP(Data_Operacao) AS Dt_Operacao, UNIX_TIMESTAMP(Data_Limite) AS Dt_Limite, Id_Status_Trabalho FROM ACOMPANHAMENTO WHERE Id_Trabalho = '".$idtrabalho."' ORDER BY Data_Operacao";
$qry = T_query($sql);
while ($row = T_fetch_array($qry)){	
	switch($row["Id_Status_Trabalho"]){		
		case 1:	//$desc_versoes[$x] = "Autor submeteu nova versão do trabalho";				
					$desc_versoes[$x] = "{$extenso[++$num_versoes]} versão do trabalho";
					$datas_versoes[$x] = date("d/m/Y",$row["Dt_Operacao"]);					
					$x++;	
				break;	
		/*case 4:	$desc_versoes[$x] = "Enviado para correção";
					$datas_versoes[$x] = date("d/m/Y",$row["Dt_Operacao"]);
					$x++;
				break;*/	
		case 6: $desc_versoes[$x] = "Trabalho normalizado";
					$datas_versoes[$x] = date("d/m/Y",$row["Dt_Operacao"]);
					$x++;
				break;
		/*case 7:  $desc_versoes[$x] = "Trabalho em editoração";
					$datas_versoes[$x] = date("d/m/Y",$row["Dt_Operacao"]);
					$x++;	
				break;*/
		case 8:	$desc_versoes[$x] = "Trabalho publicado";
					$datas_versoes[$x] = date("d/m/Y",$row["Dt_Operacao"]);
					$x++;
				break;
		case 10: $desc_versoes[$x] = "Trabalho com revisão gramatical";
					$datas_versoes[$x] = date("d/m/Y",$row["Dt_Operacao"]);
					$x++;
				break;					
	}
	echo "</div>";
}

//pega os dados referentes ao trabalho
$ano = substr($_GET["ano"],-2);
$sql = "SELECT Id_Trabalho, Titulo, Versao, Ext_arquivo FROM TRABALHOS WHERE Id_Trabalho = '{$idtrabalho}' ORDER BY Id_Trabalho DESC";
$qry = T_query($sql);
$row = T_fetch_array($qry);
$versoes = $row['Versao'];
$ext = $row['Ext_arquivo'];
$titulo = utf8_encode($row['Titulo']);

echo "<span style='color:#5d554a;size:12;font-weight:bold;'>
			Código: {$row['Id_Trabalho']}<br>
			Título: {$titulo}
		</span><br><br>";

//montagem da lista de versoes, com as respectivas datas 
$j=1;	
for($i=$versoes;$i>0;$i--){
	$j = ($j+1)%2;
	//verifica existencia e extensao dos arquivos
	$arquivo = "trabalhos/v".$i."/".$idtrabalho.".".$ext;
		
	if(file_exists($arquivo)){		
		echo "<div class=\"lista_registros{$j}\" style=\"display:block; cursor:pointer; width:100%;\" onClick=\"window.open('trabalhos/v{$i}/{$idtrabalho}.{$ext}');\">";
	}
	else{		
		$ext_encontrada = busca_arquivo($idtrabalho, $i);		
		if(!$ext_encontrada){
			$ext_encontrada = busca_arquivo_old($idtrabalho, $i);
			if(!$ext_encontrada)
				echo "";
			else{				
				echo "<div class=\"lista_registros{$j}\" style=\"display:block; cursor:pointer; width:100%;\" onClick=\"window.open('trabalhos/v{$i}/{$idtrabalho}_old.{$ext_encontrada}');\">";
			}					
		}else			
			echo "<div class=\"lista_registros{$j}\" style=\"display:block; cursor:pointer; width:100%;\" onClick=\"window.open('trabalhos/v{$i}/{$idtrabalho}.{$ext_encontrada}');\">";	
	}	
			
	//echo "<b><span style='color:blue;'>Versão {$i}: </span></b>";	
	echo "<b>Versão {$i} - </b>";
	echo $datas_versoes[$i]." - ";
	echo $desc_versoes[$i];	
	echo "</div>";
	
}

?>