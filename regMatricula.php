<html>
	<head>
		<?php include("inc/header.php"); ?>
	</head>

	<body>
		<script language="javascript">
			function verifica(){
				if (document.getElementById('matriculaForm').value=='') { 
					alert('Matricula é campo obrigatório.'); 
					return false;
				}
				if ((document.getElementById('matriculaForm').value.search(/\D/)!=-1)&&(document.getElementById('matriculaForm').value.search(/\W/)!=-1)) { 
					alert("A matricula deve conter apenas letras e/ou numeros.");
					return false;
				}
				return true;
			}

			function depois() {
				location.href = "index.php";
			}
		</script>

		<div align="center">
			<table width="752" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF" >
				<tr>
					<td><?php include("inc/topo.php"); ?></td>
				</tr>  
				<tr>
  					<td height="300" align="center" valign=middle style="padding-top:20px;">
  						<div class="divLogin" align="center">
    						<form name="formRegMatricula" id="formRegMatricula" method="post" action="doRegMatricula.php">
    							<table width="200" height="0" border="0" align="center" cellpadding="0" cellspacing="0">
    								<tr>
      									<td width="50" height="0">Cadastre sua matrícula LDAP no sistema.</td>
      								</tr>
    							</table>
      							<table width="200" height="0" border="0" align="center" cellpadding="0" cellspacing="0">
        							<tr>
          								<td width="50" height="60">Matricula</td>
          								<td width="100"><input name="matriculaForm" type="text" maxlength="20" id="matriculaForm"></td>
	        						</tr>
    	  						</table>
        						<table width="200" height="30" border="0" align="center" cellpadding="0" cellspacing="0">
            	    				<tr>
          								<td>
          									<input type="button" id="btnCadastrarMatricula" name="Button" value="CADASTRAR" onClick="if (verifica()) document.getElementById('formRegMatricula').submit();">
          								</td>
          								<td><input type="button" id="btnDepoisMatricula" name="Button" value="DEPOIS" onClick="depois()"></td>
        							</tr>
								</table>
	    					</form>
  						</div>
  					</td>
				</tr>
				<tr>
					<td><?php include("inc/copyright.php"); ?></td>
				</tr>
			</table>
		</div>
	</body>
</html>
