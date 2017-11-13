<html>
<head>
<?php include("inc/header.php"); 
include_once("conexao.php");?>
</head>
<body>

<script language="javascript" src="windowfiles/dhtmlwindow.js"></script>
<link rel="stylesheet" href="windowfiles/dhtmlwindow.css" type="text/css" />
<script language="javascript" src="TAjax.js"></script>
<script> var ajax = new TAjax(); </script>
<script language="javascript">

function handleEnter (event,nome) {
	var keyCode = event.keyCode ? event.keyCode : event.which ? event.which : event.charCode;
	if (keyCode==13){
		if (typeof nome == "undefined") document.getElementById("btnSub").click();
		else document.getElementById(nome).focus();
	}
}   

function handleEnter2 (event,nome) {
	var keyCode = event.keyCode ? event.keyCode : event.which ? event.which : event.charCode;
	if (keyCode==13){
		if (typeof nome == "undefined") document.getElementById("alterar").click();
		else document.getElementById(nome).focus();
	}
}
 

function verifica(){
if ((document.getElementById('senha').value=='')||(document.getElementById('login').value==''))
{ 
	alert('Login e Senha sao campos obrigatorios.'); 
	return false;
}

if ((document.getElementById('login').value.search(/\D/)!=-1)&&(document.getElementById('login').value.search(/\W/)!=-1)) { 
alert("O Login deve conter apenas letras e/ou numeros.");
return false;
}

return true;
}
  
</script>
<div align="center">
<table width="752" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF" >
<tr><td><?php include("inc/topo.php"); ?></td></tr>  
  
<?php
$submit = $_POST['alterar'];

if(empty($submit)){
	$id = $_GET['id'];//pega o id passado pela URL
	$hash = $_GET['hash'];//pega o hash passado pela URL
}else{
	$id = $_POST['id_hidden'];//pega o id do hidden do form
	$hash = $_POST['hash_hidden'];//peg o hash do hidden do form
}

$hash2 = md5("publ".$id."isher");//para conferir o hash

//verificacao de seguranca
if($hash != $hash2){
	echo "<tr>
		<td align='center'><b><span style='color:red'>Erro: o sistema de seguran&ccedil;a detectou inconsistencia nos dados.</span></b></td>
	</tr>";		
}else{
	if ($submit) {
	
		$nova_senha = $_POST['nova_senha'];
		$nova_senha2 = $_POST['nova_senha2'];		

		if (empty($nova_senha) || empty($nova_senha2)) { $erro_senha = "<b><span style='color:red'>Voce deve digitar sua nova senha e confirm&aacute;-la.</span></b>"; }
		else if ($nova_senha != $nova_senha2) { $erro_senha = "<b><span style='color:red'>As senhas n&atilde;o conferem. Por favor, redigite-as.</span></b>"; }
		else {
			$senha_md5 = md5(sha1($nova_senha));//seguranca da senha
			$query = "UPDATE USR SET Passwd = '{$senha_md5}' WHERE Id_Usr = '{$id}'"; 
			$resultado = T_query($query);
						
			if($resultado)		
				$erro_senha = "<b><span style='color:green'>Senha alterada com sucesso!</span></b>";
			else
				$erro_senha = "<b><span style='color:red'>Ocorreu um erro ao salvar sua senha. Se o problema persistir, contate o administrador.</span></b>";			
		}
	
		if(!empty($erro_senha)){
			echo "<tr><td align='center'>
				{$erro_senha}
			</td></tr>"; 
		}
	}
?>
<tr>
   <td height="100" align="center" valign=middle style="padding-top:20px;">
   <div align="center" class="divLogin">
	<form name="form" id="form" method="post" action="recuperar.senha.php">
      <table width="270" height="72" border="0" align="center" cellpadding="0" cellspacing="0">
			<tr><td colspan='2' align='center'><b>Alterar Senha</b></td></tr>
			<tr><td>&nbsp;</td></tr>        
        <tr>
          <td width="170" height="25">Nova Senha</td>
          <td width="100"><input name="nova_senha" type="password" maxlength="12" id="nova_senha" onKeyPress="handleEnter2 (event,'nova_senha2')"></td>
        </tr>
        <tr>
          <td height="25">Repetir Senha</td>
          <td><input name="nova_senha2" type="password" maxlength="12" id="nova_senha2" onKeyPress="handleEnter2 (event)"></td>
        </tr>
        <tr><td colspan='2'>&nbsp;</td></tr>
        <tr>          
          <td colspan='2' align='center'><input type="submit" id="alterar" name="alterar" value="Alterar Senha"></td>
          <input type='hidden' id='id_hidden' name='id_hidden' value='<?php echo $id; ?>'>
          <input type='hidden' id='hash_hidden' name='hash_hidden' value='<?php echo $hash; ?>'>
        </tr>        
      </table>
</form>
</tr>
<tr>
  <td height="200" align="center" valign=middle style="padding-top:20px;">
  <div class="divLogin" align="center">
    <form name="form1" id="formLogin" method="post" action="doLogin.php">
      <table width="225" height="72" border="0" align="center" cellpadding="0" cellspacing="0">
			<tr><td colspan='2' align='center'><b>Acessar o Sistema</b></td></tr>
			<tr><td>&nbsp;</td></tr>         
        <tr>
          <td width="50" height="25">Login</td>
          <td width="175"><input name="login" type="text" maxlength="20" id="login" onKeyPress="handleEnter (event,'senha')"></td>
        </tr>
        <tr>
          <td height="25">Senha</td>
          <td><input name="senha" type="password" maxlength="12" id="senha" onKeyPress="handleEnter (event)"></td>
        </tr>
        <tr>
          <td height="22">&nbsp;</td>
          <td><input type="button" id="btnSub" name="Button" value="ENTRAR" onClick="if (verifica()) document.getElementById('formLogin').submit();"></td>
        </tr>
      </table>       
      <?php if ($_GET["login"]){ ?>
      	<div align="center" id="errorMsg">
        	<?php 	if ($_GET["login"]=="nouser") echo "Usu&aacute;rio inexistente!";
				else if ($_GET["login"]=="nopass") echo "Senha inv&aacute;lida";
			?>
      	</div>
      <?php } //fim do if de 6 linhas acima 
      ?>
    </form>
  </div></td>
</tr>
<?php 
} //fim do else da verificacao de hash
?>
<tr>
<td>&nbsp;</td>
</tr>
<tr>
<td><?php include("inc/copyright.php"); ?></td>
</tr>
</table>
</div>
<script>
document.getElementById('senha1').focus();
</script>
</body>
</html>