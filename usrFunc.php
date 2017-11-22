<?php 

include("sessions.php");
allow();

/*

	FUNES PADRO PARA A PAGINA DE FUNES DE USURIO:
	0. NENHUMA AO (SOMENTE UM BOTO PARA INSERIR UM NOVO USER)
	1. INSERIR NOVO USURIO
	2. EDITAR DADOS REFERENTES AO USURIO
	3. EDITAR PERMISSES REFERENTES AO USURIO (PAPIS)

*/


	include_once("conexao.php");
	if (!$_GET["func"]){
		if (hasPerm(16)) echo "<input type=\"button\" value=\"Inserir novo usu&aacute;rio\" style=\"padding: 2px 15px 2px 15px; height:24px;\" onclick=\"ajax.loadDiv('divManip','usrFunc.php?func=1')\" >";
	}
	else if ($_GET["func"]==1){
	
?>
<link rel="stylesheet" href="style.css" />
<form id="frmUsr" name="frmUsr" action="javascript:if (verifica()){ajax.ajaxFormPost('divManip','usrDBFunc.php?op=1','frmUsr','Areas_Atuacao');}" method="post">
<div style="border:#000066 1px groove; width:720px;" align="center">
  <h1>Cadastro de Usu&aacute;rio </h1>
  <!--
  // Desabilitado na implementacao do LDAP
  <div style="width:650px; border:#F1F1F1 1px solid; margin: 0 0 10px 0;">
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="18%"><span title="Deve ter no m&iacute;nimo 4 caracteres">Login</span></td>
        <td width="82%"><input name="Login" type="text" id="Login" size="20" maxlength="20"> 
          <a href="javascript://" onclick="if(document.getElementById('Login').value!=''){ajax.loadDiv('divDisp','dispCheck.php?login='+document.getElementById('Login').value); ajax.tempShow('divDisp',5000,'inline');}">Checar Disponibilidade</a>
        <div id="divDisp" style="display:inline;"></div></td>
      </tr>
      <tr>
        <td>Senha</td>
        <td><input name="Passwd" type="password" id="Passwd" size="16" maxlength="12"></td>
      </tr>
      <tr>
        <td>Confirmar Senha </td>
        <td><input name="Passwd2" type="password" id="Passwd2" size="16" maxlength="12"></td>
      </tr>
      
    </table>
  </div>
  -->
  <div style="width:650px; border:#F1F1F1 1px solid; margin: 0 0 10px 0; padding: 0 0 5px 0;">
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="18%"><span title="Deve ter no m&iacute;nimo 4 caracteres">Login/matrícula</span></td>
        <td width="82%"><input name="Login" type="text" id="Login" size="20" maxlength="20"></td>
      </tr>
      <tr>
        <td width="14%">Nome</td>
        <td width="36%"><input name="Nome" type="text" id="Nome2" /></td>
        <td width="14%">Endere&ccedil;o</td>
        <td width="36%"><input name="Endereco" type="text" id="Endereco" /></td>
      </tr>
      <tr>
        <td><p>UF</p>        </td>
        <td><select name="select2" onchange="ajax.loadDiv('divMunic','municLoad.php?coduf='+this.value);">
          <option value="0">--</option>
          <?php
				
				$qry = T_query("SELECT `Cod_UF` FROM `UF` ORDER BY `Cod_UF`");
				while ($row = T_fetch_array($qry)){				
					echo "<option value=\"".$row[0]."\">".$row[0]."</option>";		  	
				}
			?>
        </select></td>
        <td>Munic&iacute;pio</td>
        <td><div style="display:inline;" id="divMunic"></div>
          <script language="JavaScript" type="text/javascript">
		  	ajax.loadDiv('divMunic','municLoad.php');
		  </script></td>
      </tr>
      <tr>
        <td>E-mail</td>
        <td><input name="Email" type="text" id="Email" /></td>
        <td>Telefone</td>
        <td><input name="Telefone" type="text" id="Telefone" onkeydown="mascara(this,maskFone);" maxlength="15" /></td>
      </tr>
      <tr>
        <td>Cargo</td>
        <td><select name="Id_Cargo" id="Id_Cargo">
          <option value="0">--</option>
          <?php
			
			$qryStr = "SELECT * FROM `CARGOS` ORDER BY `Cargo`";
			$qry = T_query($qryStr);
				while ($row = T_fetch_array($qry)){
					echo "<option value=\"".$row[0]."\">".utf8_encode($row[1])."</option>";
				}
			
			?>
        </select></td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
    </table>
  </div>
  <div style="width:650px; border:#F1F1F1 1px solid; margin: 0 0 20px 0;">
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td style="padding: 5px 0 0 0;">&Aacute;reas de Atua&ccedil;&atilde;o <br>
		<div style="width:100%; display:run-in ">
		<?php
			$c = 2;
			$qry = T_query("SELECT * FROM `AREAS_ATUACAO` ORDER BY `Area_Atuacao`");
			$rowct = intval(T_num_rows($qry)/2);
			echo "<table width=90% border=0 cellpadding=0 cellspacing=0 align='center'>";
			for ($i=0; $i<=$rowct;$i++){
				echo "<tr>";
				echo "<td>";
				echo ($row = T_fetch_array($qry)) ? "<input type=\"checkbox\" name=\"Areas_Atuacao\" value=\"".$row["Id_Area_Atuacao"]."\">".utf8_encode($row["Area_Atuacao"]) : "";
				echo "</td>";
				echo "<td>";
				echo ($row = T_fetch_array($qry)) ? "<input type=\"checkbox\" name=\"Areas_Atuacao\" value=\"".$row["Id_Area_Atuacao"]."\">".utf8_encode($row["Area_Atuacao"]) : "";
				echo "</td>";
				echo "</tr>";
			}
			echo "</table>";
	
		?>
		</div>
		</td>
      </tr>
    </table>
	
  </div>
  
  <p>
    <input type="submit" name="Submit" value="Gravar"/>&nbsp;
    <input type="button" name="Submit2" value="Cancelar" onclick="ajax.loadDiv('divManip','lista_usr.php');"/>
  </p>
</div>
</form>
<script language="javascript">
function verifica(){
	// Verificando campos obrigatórios, tamanho minimo...
	if (document.getElementById('Login').value.length==0){
		alert('O campo "Login" é um campo obrigatório.');
		return false;
	}
	if (document.getElementById('Login').value.length<4){
		alert('O Login deve ter no mínimo 4 caracteres.');
		return false;
	}
	if ((document.getElementById('Login').value.search(/\D/)!=-1)&&(document.getElementById('Login').value.search(/\W/)!=-1)) { 
		alert("O Login deve conter apenas letras e/ou numeros.");
		return false;
	}
	if (document.getElementById('Email').value.length==0){
		alert('O campo "E-Mail" é um campo obrigatório.');
		return false;
	}	
	//se passou por todas as verificações acima, o formulário está OK.
	return true;
}
</script>
<?php } //fim das insercoes

	else if($_GET["func"]==2){
	
	$qryStr = "SELECT * FROM `USR` WHERE `Id_Usr` = ".$_GET["Id_Usr"];
	$qry = T_query($qryStr);
	$rowData = T_fetch_array($qry);
	
	unset($qryStr);
	T_free_result($qry);

?>
<form id="frmUsrEdt" name="frmUsrEdt" action="javascript:if (verifica()){ ajax.ajaxFormPost('divManip','usrDBFunc.php?op=2&Id_Usr=<?php echo $rowData["Id_Usr"]; ?>','frmUsrEdt','Areas_Atuacao','password'); }" method="post">
  <div style="border:#000066 1px groove; width:720px;" align="center">
    <h1>Editar Dados de Usu&aacute;rio </h1>
    <!--
    // Desabilitado na implementacao do LDAP
    <div style="width:650px; border:#F1F1F1 1px solid; margin: 0 0 10px 0;">
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="10%"><span title="Deve ter no m&iacute;nimo 4 caracteres">Login</span></td>
          <td width="90%"><input name="Login" type="text" id="Login" value="<?php echo utf8_encode($rowData["Login"]); ?>" size="20" maxlength="20" disabled="disabled"/></td>
        </tr>
        <tr>
          <td>Senha</td>
          <td><input name="Passwd" disabled="disabled" type="text" id="Passwd" value="***************************************" size="16" maxlength="50" /> 
            <?php if ($rowData["Id_Usr"]==$_SESSION["USERID"]) echo "<a style=\"color:#FF0000;\" href=\"senha_change.php\">Alterar senha</a>"; ?></td>
        </tr>
      </table>
    </div>
    -->
    <div style="width:650px; border:#F1F1F1 1px solid; margin: 0 0 10px 0; padding: 0 0 5px 0;">
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
      	<tr>
          <td width="10%"><span title="Deve ter no m&iacute;nimo 4 caracteres">Login/matrícula</span></td>
          <td width="90%"><input name="Login" type="text" id="Login" value="<?php echo utf8_encode($rowData["Login"]); ?>" size="20" maxlength="20" disabled="disabled"/></td>
        </tr>
        <tr>
          <td width="14%">Nome</td>
          <td width="36%"><input name="Nome" value="<?php echo utf8_encode($rowData["Nome"]); ?>" type="text" id="Nome" /></td>
		  <td width="14%"><p>Endere&ccedil;o</p></td>
          <td width="36%"><input name="Endereco" value="<?php echo utf8_encode($rowData["Endereco"]); ?>" type="text" id="Endereco" /></td>
        </tr>
        <tr>
          <td>UF</td>
		  <td>
            <select name="select" onchange="ajax.loadDiv('divMunic','municLoad.php?coduf='+this.value);">
				<option value="0" 
				<?php 	if ($rowData["Id_Municipio"]==NULL){
							echo "selected=\"selected\"";
							$uf = false;
						}
						else{
							$qryUFStr = "SELECT `Cod_UF` FROM `MUNICIPIOS` WHERE `Id_Municipio` = ".$rowData["Id_Municipio"];
							$qryUF = T_query($qryUFStr);
							$uf = T_fetch_array($qryUF);
						}
							
				?>>--</option>
                
				<?php			
				
				$qry = T_query("SELECT `Cod_UF` FROM `UF` ORDER BY `Cod_UF`");
				while ($row = T_fetch_array($qry)){				
					echo "<option value=\"".$row[0]."\"";
					if ($uf[0]==$row[0]) echo "selected=\"selected\"";
					echo " >".$row[0]."</option>";		  	
				}
			?>
            </select>
          </td>
          <td>Munic&iacute;pio</td>
		  <td>
            <div style="display:inline;" id="divMunic"></div>
              <script language="JavaScript" type="text/javascript">
		  			ajax.loadDiv('divMunic','municLoad.php?coduf=<?php echo $uf[0]; ?>&id_munic=<?php echo $rowData["Id_Municipio"]; ?>');
		  		</script>
          </td>
        </tr>
        <tr>
          <td>Telefone</td>
          <td><input name="Telefone" type="text" id="Telefone" value="<?php echo $rowData["Telefone"]; ?>" onkeydown="mascara(this,maskFone);" maxlength="15" /></td>
          <td>E-mail</td>
          <td><input name="Email" type="text" id="Email" value="<?php echo $rowData["Email"]; ?>" /></td>
        </tr>
		<tr>
          <td>Cargo</td>
          <td><select name="Id_Cargo" id="Id_Cargo">
            <option value="0" <?php
				if ($rowData["Id_Cargo"]==NULL){	echo " selected=\"selected\"";	}
			?>>--</option>
            <?php
			
			$qryStr = "SELECT * FROM `CARGOS` ORDER BY `Cargo`";
			$qry = T_query($qryStr);
				while ($row = T_fetch_array($qry)){
					echo "<option value=\"".$row[0]."\"";
					echo ($rowData["Id_Cargo"]==$row[0])?" selected=\"selected\"":"";
					echo">".utf8_encode($row[1])."</option>";
				}
			
			?>
          </select>
          </td>
		  <td></td>
        </tr>
      </table>
    </div>
    <div style="width:650px; border:#F1F1F1 1px solid; margin: 0 0 20px 0;">
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td style="padding: 5px 0 0 0;">&Aacute;reas de Atua&ccedil;&atilde;o <br />
              <div style="width:100%; display:run-in ">
                <?php
			$c = 2;
			$qry = T_query("SELECT * FROM `AREAS_ATUACAO` ORDER BY `Area_Atuacao`");
			$rowct = intval(T_num_rows($qry)/2);
			echo "<table width=90% border=0 cellpadding=0 cellspacing=0 align='center'>";
			for ($i=0; $i<=$rowct;$i++){
				echo "<tr>";
				echo "<td>";
				if ($row = T_fetch_array($qry)){
				echo "<input type=\"checkbox\" name=\"Areas_Atuacao\" value=\"".$row["Id_Area_Atuacao"]."\"";
				$qryArea = T_query("SELECT * FROM `USR_has_AREAS_ATUACAO` WHERE `Id_Usr` = ".$rowData["Id_Usr"]." AND `Id_Area_Atuacao`=".$row["Id_Area_Atuacao"]);
				if (T_num_rows($qryArea)) echo " checked=\"checked\"";
				echo ">".utf8_encode($row["Area_Atuacao"]);
				T_free_result($qryArea);
				}				
				echo "</td>";
				echo "<td>";
				if ($row = T_fetch_array($qry)){
				echo "<input type=\"checkbox\" name=\"Areas_Atuacao\" value=\"".$row["Id_Area_Atuacao"]."\"";
				$qryArea = T_query("SELECT * FROM `USR_has_AREAS_ATUACAO` WHERE `Id_Usr` = ".$rowData["Id_Usr"]." AND `Id_Area_Atuacao`=".$row["Id_Area_Atuacao"]);
				if (T_num_rows($qryArea)) echo " checked=\"checked\"";
				echo ">".utf8_encode($row["Area_Atuacao"]);
				T_free_result($qryArea);
				}
				echo "</td>";
				echo "</tr>";
			}
			echo "</table>";

?>
            </div></td>
        </tr>
      </table>
    </div>
    <p>
      <input type="submit" name="Submit3" value="Gravar" />
      &nbsp;
      <input type="button" name="Submit22" value="Cancelar" onclick="ajax.loadDiv('divManip','lista_usr.php');"/>
    </p>
  </div>
</form>
<script language="javascript">
function verifica(){
	if (document.getElementById('Email').value.length==0){
		alert('O campo "E-mail" é um campo obrigatório.');
		return false;
	}
	return true;
}
</script>
<?php
	}	// fim das edicoes
	else if ($_GET["func"]==3){
	
	$id_usr = $_GET["Id_Usr"];
	$qryStr = "SELECT `Login`,`Nome` FROM `USR` WHERE `Id_Usr` = ".$id_usr;
	$qry = T_query($qryStr);
	$row = T_fetch_array($qry);	
?>
<form id="frmUsrPerm" name="frmUsrPerm" action="javascript:ajax.ajaxFormPost('divManip','usrDBFunc.php?op=4&Id_Usr=<?php echo $id_usr; ?>','frmUsrPerm');" method="post">
<div style="border:#000066 1px groove; width:720px;" align="center">
  <h1>EDITAR PERMISS&Otilde;ES DE USU&Aacute;RIO <br /></h1>
  <span style="font-size:8pt;"><strong>Nome de Usu&aacute;rio:</strong> <?php echo utf8_encode($row[1]);?><br /><strong>Login:</strong> <?php echo $row[0]; ?><br />&nbsp;</span>
  <div style="width:650px; border:#F1F1F1 1px solid; margin: 0 0 10px 0; padding: 0 0 5px 0;">
  <table width="50%" border="0" cellpadding="0" cellspacing="0" style="padding: 0 0 7px 0;">
  <?php 
  	
	unset($qryStr);
	$qryStr = "SELECT `Id_Papel`,`Papel` FROM `PAPEIS` ORDER BY `Id_Papel`";
	$qry = T_query($qryStr);
  
  	while ($row = T_fetch_array($qry)){
		
		echo "<tr>";
		echo "<td width=\"60%\" style=\"padding: 0 0 4px 0;\">".utf8_encode($row[1])."</td>";
		
		$qryStrPapel = "SELECT * FROM `USR_has_PAPEIS` WHERE `Id_Papel`=".$row[0]." AND `Id_Usr`=".$id_usr.";";
		$qryPapel = T_query($qryStrPapel);
		echo "<td><input name=\"perm".$row[0]."\" type=\"radio\" value=\"".$row[0]."\" ";
		echo (T_num_rows($qryPapel))?"checked=\"checked\"/>Sim&nbsp;":"/>Sim&nbsp;";
        echo "<input name=\"perm".$row[0]."\" type=\"radio\" value=\"0\" ";
		if (($_SESSION["USERID"]==$id_usr)&&($row[0]==16)&&($_SESSION["PERMISSAO"] & 16)) echo " disabled=\"disabled\" ";
		echo (!T_num_rows($qryPapel))?"checked=\"checked\"/>N&atilde;o</td>":"/>N&atilde;o</td>";
	    echo "</tr>";
		T_free_result($qryPapel);
		unset($qryStrPapel);
		
	}
  
  ?>
  </table>
  <input type="submit" name="Submit3" value="Gravar" />
      &nbsp;
      <input type="button" name="Submit22" value="Cancelar" onclick="ajax.loadDiv('divManip','lista_usr.php');"/>
  </div>
</div>
</form>
<?php
	}	// fim das edicoes de permissoes de usuario
?>
