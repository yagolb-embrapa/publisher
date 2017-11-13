<?php
include("sessions.php");
allow();
include_once("conexao.php");


/*

	MANIPULAO DE AOES REFERENTES A USUARIOS NO BANCO DE DADOS...
	1. INSERT
	2. EDIT
	3. DELETE
	4. EDIT PERM.

*/

switch($_GET["op"]){


case 1:	//comeando a inserao
		$login = addslashes(strtolower($_POST["Login"]));
		$login = (!strlen($login))?"NULL":"'".T_escape_string($login)."'";
		$passwd = (!strlen($_POST["Passwd"]))?"NULL":"'".md5(sha1($_POST["Passwd"]))."'";
		$nome = (!strlen($_POST["Nome"]))?"NULL":"'".T_escape_string($_POST["Nome"])."'";
		$endereco = (!strlen($_POST["Endereco"]))?"NULL":"'".T_escape_string($_POST["Endereco"])."'";
		$telefone = (!strlen($_POST["Telefone"]))?"NULL":"'".$_POST["Telefone"]."'";
		$email = (!strlen($_POST["Email"]))?"NULL":"'".T_escape_string($_POST["Email"])."'";
		$id_cargo = ($_POST["Id_Cargo"])?$_POST["Id_Cargo"]:'NULL';
		$id_municipio = ($_POST["Id_Municipio"])?$_POST["Id_Municipio"]:'NULL';
		$id_areas_atuacao = $_POST["Areas_Atuacao"];
		if ($id_areas_atuacao) $id_areas_atuacao = explode("#",$id_areas_atuacao);
		
		
		$qryStr = "INSERT INTO `USR` (Id_Municipio,Id_Cargo,Login,Passwd,Nome,Endereco,Email,Telefone)
		VALUES (".$id_municipio.",".$id_cargo.",".$login.",".$passwd.",".$nome.",".$endereco.",".$email.",".$telefone.");";
	
				
$qry = T_query($qryStr);
		if ($qry){
			if ($id_areas_atuacao){
				$qryStr = "SELECT `Id_Usr` FROM `USR` WHERE `Login` = ".$login."";
				$qry = T_query($qryStr);
				
				$row = T_fetch_array($qry);
				
				if ($id_areas_atuacao){
					foreach ($id_areas_atuacao as $area){
						$qryStr = "INSERT INTO `USR_has_AREAS_ATUACAO` (`Id_Usr`,`Id_Area_Atuacao`) VALUES (".$row[0].",".$area.");";
						$qry = T_query($qryStr);
					}
				}
				
				/*for ($i=0; $i<sizeof($id_areas_atuacao);$i++){
					
					$qryStr = "INSERT INTO `USR_has_AREAS_ATUACAO` (`Id_Usr`,`Id_Area_Atuacao`) VALUES (".$row[0].",".$id_areas_atuacao[$i].");";

					$qry = T_query($qryStr);
				}	*/
			}
			
			echo "<script> 	ajax.loadDiv('divMsg','msgbox.php?st=11'); 
						ajax.loadDiv('divManip','usrFunc.php');</script>";
			
		}else{
			echo "<script>ajax.loadDiv('divManip','usrFunc.php?func=1'); </script>";
			switch (T_errno()){
			case 1048: 	echo "<script> alert('Os campos obrigatorios devem ser preenchidos'); 
										alert('".$qryStr."');</script>";
						break;
			case 1062:	echo "<script> alert('Login e/ou E-mail ja existentes');</script>";
						break;
			default:	echo "<script> alert('erro q apareceu...".T_errno()."'); </script>";
			}
			echo "<script> 
			alert('erro q apareceu...".T_errno()."');
			ajax.loadDiv('divMsg','msgbox.php?st=10'); </script>";
		}
		
		break;

case 2:	//comeando a edicao
		$id_usr = $_GET["Id_Usr"];
		$nome = T_escape_string($_POST["Nome"]);
		$endereco = T_escape_string($_POST["Endereco"]);
		$telefone = $_POST["Telefone"];
		$email = T_escape_string($_POST["Email"]);
		$id_cargo = ($_POST["Id_Cargo"])?$_POST["Id_Cargo"]:'NULL';
		$id_municipio = ($_POST["Id_Municipio"])?$_POST["Id_Municipio"]:'NULL';
		$id_areas_atuacao = $_POST["Areas_Atuacao"];
		if ($id_areas_atuacao) $id_areas_atuacao = explode("#",$id_areas_atuacao);
		
		$qry = T_query('UPDATE `USR` SET `Id_Municipio` = '.$id_municipio.',
		`Id_Cargo` = '.$id_cargo.',
		`Nome` = \''.$nome.'\',
		`Endereco` = \''.$endereco.'\',
		`Email` = \''.$email.'\',
		`Telefone` = \''.$telefone.'\' WHERE `Id_Usr` = '.$id_usr.';');
	
		if ($qry){
			$qryStr = "DELETE FROM `USR_has_AREAS_ATUACAO` WHERE `Id_Usr` = ".$id_usr.";";
			T_query($qryStr);
			
				if ($id_areas_atuacao){				
					foreach ($id_areas_atuacao as $area){
						$qryStr = "INSERT INTO `USR_has_AREAS_ATUACAO` (`Id_Usr`,`Id_Area_Atuacao`) VALUES (".$id_usr.",".$area.");";
						$qry = T_query($qryStr);
					}	
				}
			echo "<script> ajax.loadDiv('divMsg','msgbox.php?st=21'); ajax.loadDiv('divManip','usrFunc.php'); </script>";
		}else{
			if (T_errno()==1062) echo "<script> alert('E-mail já existente.'); </script>";
			echo "<script> ajax.loadDiv('divMsg','msgbox.php?st=20'); ajax.loadDiv('divManip','usrFunc.php?func=2&Id_Usr=".$id_usr."'); </script>";
		}
				
		break;

		
case 3:	//deletando usuarios....
		$id_usr = $_GET["Id_Usr"];
		$id_curr = $_SESSION["USERID"];
		$qryStr = "DELETE FROM `USR` WHERE `Id_Usr` = ".$id_usr;
		if ($id_usr == $id_current) $qry = false;
		$qry = T_query($qryStr);
		if ($qry){
			echo "<script> ajax.loadDiv('divMsg','msgbox.php?st=41'); ajax.loadDiv('divManip','usrFunc.php'); </script>";;
		}
		else{
		if ($id_usr == $id_current)
			echo "<script>alert('Não é possivel a exclusão do usurio logado atualmente.')</script>";
			echo "<script> ajax.loadDiv('divMsg','msgbox.php?st=40'); </script>";
		}
		break;
		
case 4:	//alterando papeis...
		$id_usr = $_GET["Id_Usr"];
		
		$qryStr = "DELETE FROM `USR_has_PAPEIS` WHERE `Id_Usr`=".$id_usr;
		$qry = T_query($qryStr);
		
		$qryPapeisStr = "SELECT `Id_Papel` FROM `PAPEIS` ORDER BY `Id_Papel`";
		$qryPapeis = T_query($qryPapeisStr);
			while ($row = T_fetch_array($qryPapeis)){
				if ($_POST["perm".$row[0]]){
					$qryStr = "INSERT INTO `USR_has_PAPEIS`(`Id_Usr`,`Id_Papel`) VALUES (".$id_usr.",".$row[0].");";
					if (T_query($qryStr)) $work = true;
					else{
						$work = false;
						break;
					}
				}
			}
			
		if ($work){
			echo "<script> ajax.loadDiv('divMsg','msgbox.php?st=31'); ajax.loadDiv('divManip','usrFunc.php');</script>";;
		}
		else{
			echo "<script> ajax.loadDiv('divMsg','msgbox.php?st=30'); ajax.loadDiv('divManip','usrFunc.php?func=3');</script>";
		}	
			
			
		
		break;
				

		

}





?>

<script language="javascript">
ajax.loadDiv('divManip','lista_usr.php');
ajax.showElement('divMsg','inline');
window.setTimeout('ajax.hideElement("divMsg")',2000);
</script>