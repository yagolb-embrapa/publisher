
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php 
include("inc/header.php"); 
include_once("conexao.php");
?>
<link rel="stylesheet" href="windowfiles/dhtmlwindow.css" />
<script language="javascript" src="TAjax.js"></script>
<script language="javascript">var ajax = new TAjax();</script>
<script language="javascript" src="windowfiles/dhtmlwindow.js"></script>
<script language="javascript">		
	function mensagem(erro){	
		dhtmlwindow.open('rbox', 'ajax', 'msg.senha.php?e='+erro, 'Aviso', 'width=250px,height=50px,center=1,resize=0');		
	}					
</script>
</head>
<body>
<?php 

$submit = (isset($_POST['submit'])) ? $_POST['submit'] : '';

if ($submit) {	
	$email = $_POST['email'];//pega valor do email	
	
	if(empty($email)){
		$erros = "<br><div align='center'><b><span style='color:red'>Preencha o campo E-mail</span></b><br></div>";
		$erro_msg = 1;
	}else{		
		//pegando dados do usuario			
		$query = "SELECT * FROM USR WHERE Email = '{$email}'";
		$resultado = T_query($query);
	
		//exibe mensagem de email nao encontrado ou envia o email para alteração de senha			
		if (!T_num_rows($resultado)){
			$erros = "<br><div align='center'><b><span style='color:red'>E-mail n&atilde;o cadastrado no sistema</span></b><br></div>";
			$erro_msg = 2;
		}else {
			$campo = T_fetch_array($resultado);
					
			$id = $campo['Id_Usr'];
			$hash = md5("publ".$id."isher");//seguranca
						
			//$q = query(sprintf($sql_gera_uniqid,$token,$login));
									
			$mensagem = "<a href=http://serv007.cnptia.embrapa.br/publisher/recuperar.senha.php?id={$id}&hash={$hash} ><b>Clique aqui para alterar a senha</b></a>";
					
			$headers = "From: no-reply@cnptia.embrapa.br\r\n";
			$headers.= "Content-Type: text/html; charset=ISO-8859-1 ";
			$headers .= "MIME-Version: 1.0 "; 
			mail($email, "Recuperação de senha - Sistema Gestor de Publicações", $mensagem, $headers);
			$erros = "<br><div align='center'><b><span style='color:green'>Uma mensagem com um link para mudar a senha foi enviada para o seu E-mail</span></b><br></div>";
			$erro_msg = 3; 
		}
	}		
	if(!empty($erros)){					
		echo "<script language=\"javascript\">
				mensagem({$erro_msg});			
			</script>";
	}
	
}

//imprime mensagem de erros
/*if(!empty($erros)){
	echo $erros;
}*/

?>

<!-- Imprime formulario -->
<br>
<div align='center'>
<form method="post" action="esqueceu.senha.php" bgcolor="#FFFFFF">
<label for="email">E-mail:</label> <input id="email" name="email" maxlength="50" size="35" type="text" value="<?php echo $email; ?>">
<br><br><center><input class="submit" name="submit" value="Enviar" type="submit"></center>
</form>
</div>
</body></html>	

<?php

//$smarty->assign('erros',$erros);
//$smarty->display('esqueceu.tpl');


?>
