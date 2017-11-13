
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php 
include("inc/header.php");
?>
</head>
<script language="javascript" src="windowfiles/dhtmlwindow.js"></script>
<link rel="stylesheet" href="windowfiles/dhtmlwindow.css" type="text/css" />
<script language="javascript" src="TAjax.js"></script>
<script> var ajax = new TAjax(); </script>
<body>
<?php 

$erro_msg = $_GET['e'];

switch($erro_msg){
	case 1:
		echo "<br><div align='center'><b><span style='color:red'>Preencha o campo E-mail</span></b><br></div>";
	break;
	case 2:
		echo "<br><div align='center'><b><span style='color:red'>E-mail n&atilde;o cadastrado no sistema</span></b><br></div>";
	break;
	case 3:
		echo "<br><div align='center'><b><span style='color:green'>Uma mensagem com um link para mudar a senha foi enviada para o seu E-mail</span></b><br></div>";
	break;

}

?>