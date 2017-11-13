<?php

// ARQUIVO PARA MANIPULAR UPLOAD DE ARQUIVOS...

function getExtension($arquivo){
	$ext = explode(".",$arquivo["name"]);
	return $ext[sizeof($ext)-1];
}

//retorna TRUE se a extensao FOR IGUAL A EXTENSAO FORNECIDA;
function validExtension($extension,$arquivo){
	$extension = str_replace(".","",$extension);
	$extension = explode(";",$extension);
	foreach ($extension as $ext){
		if (getExtension($arquivo)==$ext) return true;
	}
	return false;	
}

//UPLODAR ARQUIVO PRO SERVIDOR
function uploadFile($arquivo,$name="",$dest="arquivos/",$validate=""){

	if (empty($name)) $name = $arquivo["name"];
	else $name = $name.".".getExtension($arquivo);
	$validate = (!empty($validate))?validExtension($validate,$arquivo):true;
	if (substr($dest,strlen($dest)-1)!="/") $dest .= "/";
	if ($validate) {
 		$v = move_uploaded_file($arquivo["tmp_name"],$dest.$name);
		chmod($dest.$name,0777);
		return $v;
	}
	else {
		
		return false;
	}
	
}

//CRIA DIRETORIO NO SERVIDOR
function makeDir($local){
	
	if (!is_dir($local)){
		return mkdir($local,0777);
	}
	else return false;
	
}

//REMOVE DIRETORIO DO SERVIDOR
function removeDir($local){
	if (is_dir($local))
	{
		 
		if ($dh = opendir($local))
		{
			while (($file = readdir($dh)) !== false)
			{
				unlink($local.'/'.$file);
			}
		closedir($dh);
		}
		else return false;
		return rmdir($local);
	}
	else return false;
}

//REMOVE ARQUIVO DO SERVIDOR
function removeArquivo($local,$arquivo){
	
	if (is_dir($local)){
		return unlink($local."/".$arquivo);
	}
	else return false;
	
}

//RETORNA O TAMANHO DO ARQUIVO EM MB
function getSize($arquivo){
	$tam = $arquivo["size"]/1024;
	return $tam;	
}

?>