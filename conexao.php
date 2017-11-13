<?php
/*-----------------------------------------------------------
CLASSE PARA ENCAPSULAMENTO...
Simula os metodos de manipula��o em um banco de dados. Atualmente,
o mySql � usado atrav�s do objeto mySql Improved;
m�todos implementados:

ResultSet T_query(String query): simulando o m�todo mysql_query();
array[] T_fetch_array(ResultSet result): simulando o m�todo mysql_fetch_array();
int T_num_rows(ResultSet result): simulando o m�todo mysql_num_rows();
void T_close(): simulando o m�todo mysql_close();
variant T_lastVal(ResultSet result): 	retorna o ultimo valor de chave prim�ria da 
										tabela, normalmente um inteiro;
ResultSet T_query_geral(String tabela, String where, int order): m�todo que faz um
		SELECT simples, para tabelas de dom�nio somente. O where � para o campo de
		descri��o e order indica qual dos campos (0,1) ser� usado para ordenar;
String[] T_getFieldNames(String tabela): retorna um array com os nomes das tabelas;
void T_free_result(ResultSet result): simulando o m�todo mysql_free_result();
int getVarcharLimit(String tabela,int campo): pega o limite de um campo varchar
											 identificado por [campo] de determinada 
											 tabela;
int T_errno(): simula a fun��o mysqli_errno(mysqli link);
int T_insert_id(): simula a função mysqli_insert_id. 
string T_escape_string($string): simula a função mysqli_real_escape_String
string T_getDate(string): retorna a data no formato yyyy-mm-dd ou yy-mm-dd
int T_field_count(string tabela): retorna o numero de campos de uma tabela
-----------------------------------------------------------*/

define("HOST","localhost");
define("DATABASE","bdsgp");
define("DBUSER","usersgp");
define("DBSENHA","usersgp");

function var_name(&$var, $scope=0)
{
    $old = $var;
    if (($key = array_search($var = 'unique'.rand().'value', !$scope ? $GLOBALS : $scope)) && ($var = $old || true)) return $key; 
}

if (function_exists("mysqli_connect")){
	  $conn = mysqli_connect(HOST,DBUSER,DBSENHA,DATABASE) or die ("<script> window.location = 'error_page.php?erro=0000'; </script>"); 
} else echo "<script> window.location = 'error_page.php?erro=0001'; </script>";

function T_query_geral($tabela,$where="",$order=0){
	global $conn;
	$qry = mysqli_query($conn,"SHOW COLUMNS FROM ".$tabela);
	for ($i = 0; $res1 = mysqli_fetch_array($qry);$i++){
		 $campo[$i]= $res1["Field"];
	}
	mysqli_free_result($qry);	
	
	$qryTxt = "SELECT * FROM ".$tabela;

	if ($where!=""){
			$qryTxt .= " WHERE ".$campo[1]." LIKE '%".$where."%'";
	}
	
		
	$qryTxt .= " ORDER BY ".$campo[$order]." ASC";
	
	
	
	return mysqli_query($conn,$qryTxt);
}

function T_query($qryStr){
	global $conn;
	return mysqli_query($conn,$qryStr);	
}

function T_close(){
	global $conn;
	mysqli_close($conn);
}

function T_fetch_array($result){
	if (!$result) {
		echo T_error(); //APAGUE ESSA LINHA QDO FOR APRESENTAR
		return false;
	}
	return mysqli_fetch_array($result);
}

function T_num_rows($result){
	return mysqli_num_rows($result);
}

function T_lastVal($tabela,$campo=""){
	global $conn;
	$aux = false;
	$primaria = false;
	if ($campo==""){
		$qry = mysqli_query($conn,"SHOW COLUMNS FROM ".$tabela);
		while ($res1 = mysqli_fetch_array($qry)){
			if (!$aux) $aux = $res1["Field"];
			if ($res1["Key"]=='PRI') $primaria = $res1["Field"];
		}
		mysqli_free_result($qry);	
	}
	else $primaria = $campo;	
	if (!$primaria) $primaria = $aux;
	$qryStr = "SELECT ".$primaria." FROM ".$tabela." ORDER BY ".$primaria." DESC LIMIT 1 ";
	$qry = mysqli_query($conn,$qryStr);
	$res = mysqli_fetch_array($qry);
	mysqli_free_result($qry);
	return $res[0];	
}

function getFieldNames($tabela){
	global $conn;
	
	$qry = T_query("SHOW COLUMNS FROM ".$tabela);
	for ($i=0; $res = T_fetch_array($qry); $i++)
		$campos[$i] = $res["Field"];
	T_free_result($qry);
	return $campos;
	
}

function T_free_result($result){
	mysqli_free_result($result);
}

function getVarcharLimit($tabela,$campo){

	global $conn;
	$qry = T_query("SHOW COLUMNS FROM ".$tabela);
	for ($i=0; $res = T_fetch_array($qry); $i++){
		$sizes[$i] = $res["Type"];
	}
	$ret = preg_replace('/\D/', '', $sizes[$campo]);
	T_free_result($qry);
	return $ret;
}

function T_error(){
	global $conn;
	return mysqli_error($conn);
}

function T_errno(){

	global $conn;
	return mysqli_errno($conn);
	
	/*
	
	Alguns erros mais comuns...
	1048: Coluna %d não pode ser nula
	1062: Valor de chave duplicado
	1105: Erro desconhecido
	
	
	*/

}

function T_insert_id(){
	
	global $conn;
	return mysqli_insert_id($conn);
	
}

function T_escape_string($str){ // ESSA FUNÇAO FAZ DECODE DO CHARSET UTF8
	global $conn;
	$str = utf8_decode($str);
	if (get_magic_quotes_gpc())	$str = stripslashes($str);
	return mysqli_real_escape_string($conn,$str);
}

function T_getDate($dt,$sep=""){
	if (empty($sep)) $sep = "/";
	$dt = explode($sep,$dt);
	return $dt[2]."-".$dt[1]."-".$dt[0];	
}

function T_field_count($tabela){
	global $conn;
	$qry = mysqli_query($conn,"SELECT count(*) AS `counter` FROM information_schema.columns WHERE table_schema = '".DATABASE."'
AND table_name = 'TRABALHOS';");
	$row = mysqli_fetch_array($qry);
	mysqli_free_result($qry);
	return $row["counter"];
}

?>
