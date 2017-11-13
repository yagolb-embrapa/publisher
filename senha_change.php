<?php 	
		include("sessions.php");
		include_once("conexao.php"); 
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("inc/header.php"); ?>
</head>
<link rel="stylesheet" href="style.css" />
<body>
<div align="center">
<table width="752" height="420" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
<tr><td height="120"><?php include("inc/topo.php"); ?></td></tr>  
<tr>
  <td width=752 height="300" align="center" valign=top style="padding:10px 10px 0 10px;">
  <h1>Alteração de senha</h1>
  <?php
  		$formShow = true;
		if ($_POST["senhaAtual"]){
			$formShow = false;
			$senha = md5(sha1($_POST["senhaAtual"]));
			$sql = "SELECT `Id_Usr` FROM `USR` WHERE `Id_Usr` = ".$_SESSION["USERID"]." AND `Passwd` = '".$senha."';";
			$qry = T_query($sql);
			if (!T_num_rows($qry)){ 
				echo "<script> alert('A senha atual está incorreta!'); </script>";
				$formShow = true;
			}
			else{
				T_free_result($qry);
				unset($sql);
				unset($senha);
				$senha = md5(sha1($_POST["novaSenha"]));
				$sql = "UPDATE `USR` SET `Passwd` = '".$senha."' WHERE `USR`.`Id_Usr` =".$_SESSION["USERID"].";";
				if (T_query($sql)){
					echo "<p><span style=\"font-size:12pt;\">Senha alterada com sucesso</span><br /><a href=\"index.php\" style=\"color:#FF0000\">Retornar a pagina inicial.</a></p>";
				}
				else{
					echo "<p><span style=\"font-size:12pt;\">Erro ao alterar senha. Operação cancelada.</span><br /><a href=\"index.php\" style=\"color:#FF0000\">Retornar a pagina inicial.</a></p>";
				}
				
			}
		}
		if ($formShow){
	?>
  <form id="frmAlterSenha" name="frmAlterSenha" method="post" action="senha_change.php">
    <table width="348" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="148">Senha Atual </td>
        <td width="200"><input name="senhaAtual" type="password" id="senhaAtual" /></td>
      </tr>
      <tr>
        <td>Nova Senha </td>
        <td><input name="novaSenha" type="password" id="novaSenha" /></td>
      </tr>
      <tr>
        <td>Confirmar Nova Senha </td>
        <td><input name="novaSenha2" type="password" id="novaSenha2" /></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td><input type="button" name="Submit" value="Confirmar Alteração" style="margin: 5px 0 0 0 ;" onclick="if (verifica()) document.getElementById('frmAlterSenha').submit();"/></td>
      </tr>
    </table>
    </form>
		<script language="javascript">
		function verifica(){
			if (document.getElementById('senhaAtual').value.length==0){
				alert('A senha atual deve ser informada.');
				return false;
			}
			if (document.getElementById('novaSenha').value!=document.getElementById('novaSenha2').value){
				alert('A nova senha e a confirmação da nova senha são diferentes.');
				return false;
			}
			if (document.getElementById('novaSenha').value.length < 5){
				alert('A nova senha deve ter no mínimo 5 caracteres.');
				return false;
			}
			return true;
			
		}
	</script>
	<?php
	}
	
	?></td>
</tr>
<tr><td style="padding: 5px 0 0 0;">
<?php include("inc/copyright.php"); ?>
</td></tr>
</table>
</div>
</body>
</html>
