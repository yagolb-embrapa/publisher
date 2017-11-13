<?php
		include_once("conexao.php");
		include_once("sessions.php");
		$cor = true;
		
		//if (!$_GET["pag"]) $pagina = 1; else $pagina = $_GET["pag"]; //em q pagina o admin est
		if (!$_GET["pag"]) $pagina = 'a'; else $pagina = $_GET["pag"]; //em q pagina o admin est
		$offset = 10; //resultados por pagina.
		
		
		$qryStr = "SELECT `Id_Usr`,`Nome`,`Login`,`Email` FROM USR";
		if (!hasPerm(16)) { $qryStr .= " WHERE `Id_Usr` = ".$_SESSION["USERID"];
		$qryStr.= " ORDER BY `Nome`";
		}
		else $qryStr.= " where Nome like '".$pagina."%' ORDER BY `Nome`";

		//if (hasPerm(16)) $qryStr.= " LIMIT ".$offset." OFFSET ".(($pagina*$offset)-$offset);
		
		if (hasPerm(16))
		echo "<div align=\"center\" style=\"padding: 0 0 10px 0;\"><input type=\"button\" value=\"Inserir novo usu&aacute;rio\" style=\"padding: 2px 15px 2px 15px; height:24px;\" onclick=\"ajax.loadDiv('divManip','usrFunc.php?func=1')\" ></div>";
?>
<style>
.limiter{
	color:#000077;
}
.limiter:hover{
	color:#0000FF;
}

</style>
<?php
		$rowLim = T_fetch_array(T_query("SELECT COUNT(*) AS ct FROM USR;"));
		
		if (ceil($rowLim[0]/$offset)==$pagina) $numreg = $rowLim[0];
		else $numreg = $pagina*$offset;
		
		//echo "<div style=\"display:block; width:90%;\" align=\"right\">Exibindo de ".(($pagina*$offset)-$offset+1)." a ".$numreg."</div>";
		
		
		$qry = T_query($qryStr);
		while ($row = T_fetch_array($qry)){
		$cor = !$cor;
	?>
	<div class="lista_registros<?php echo ($cor)?"1":"0";	  ?>">
	  <table width="100%" height="36" border="0" cellpadding="0" cellspacing="0" class="lista_registros_content">
        <tr>
          <td height="18%"><?php $nome = (strlen($row["Nome"])>45) ? substr($row["Nome"],0,42)."..." : $row["Nome"];
		  						echo ($row["Nome"]!="")?utf8_encode($nome):"&laquo;Nome n&atilde;o registrado&raquo;"; ?></td>
          <td width="18%" rowspan="2" align="center" valign="middle"><a href="javascript://" onclick="ajax.loadDiv('divManip','usrFunc.php?func=2&Id_Usr=<?php echo $row["Id_Usr"]; ?>')"><img src="img/icon_edit.gif" width="16" height="16" border="0"> Editar Informa&ccedil;&otilde;es</a></td>
          <?php if (hasPerm(16)){ ?><td width="15%" rowspan="2" align="center" valign="middle"><a href="javascript://" onclick="ajax.loadDiv('divManip','usrFunc.php?func=3&Id_Usr=<?php echo $row["Id_Usr"]; ?>')"><img src="img/icon_perm.gif" width="16" height="16" />Editar Papeis</a> </td><?php } ?>
          <?php if ($_SESSION["USERID"]!=$row["Id_Usr"]){ ?><td width="15%" rowspan="2" align="center" valign="middle"><a href="javascript://" onclick="if (confirm('Deseja realmente excluir o usu&aacute;rio?\nEsta op&ccedil;&atilde;o n&atilde;o poder&aacute; ser desfeita.')){ajax.loadDiv('divManip','usrDBFunc.php?op=3&Id_Usr=<?php echo $row["Id_Usr"]; ?>');}"><img src="img/icon_delete.gif" width="16" height="16" />Excluir Usu&aacute;rio</a> </td><?php } ?>
        </tr>
        <tr>
          <td height="18" style="font-size:8pt;"><strong>Login:</strong> <?php echo utf8_encode($row["Login"]); ?> <strong>E-mail:</strong> <?php echo utf8_encode($row["Email"]); ?></td>
        </tr>
      </table>
	</div>
	<?php
	
	}
	
	if (hasPerm(16)){
	
		//FORMA DE EXIBICAO COM BOTÃO ANTERIOR/PROXIMO
		/*echo "<table width=\"50%\" align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">
				<tr><td width=\"50%\" align=\"left\">";
		if ($pagina-1>0) echo "<a href='javascript:\\' onclick=\"ajax.loadDiv('divManip','lista_usr.php?pag=".($pagina-1)."');\"><img src=\"img/anterior.gif\" border=\"0\" align=\"absmiddle\" />&nbsp;Anterior</a>";
				echo "</td><td align=\"right\">";
		if ($rowLim[0]>($pagina*$offset)) echo "<a href='javascript:\\' onclick=\"ajax.loadDiv('divManip','lista_usr.php?pag=".($pagina+1)."');\">Pr&oacute;xima&nbsp;<img src=\"img/proximo.gif\" border=\"0\" align=\"absmiddle\" /></a>";
		echo "</td></tr></table>";
		*/
		//FORMA DE EXIBIÇÃO COM O NUMERO DA PAGINA COMO LINK
		/*$npags = ceil($rowLim[0]/$offset);
		
		for ($i = 1; $i <= $npags; $i++){
			
			if ($pagina!=$i) echo "<a class=\"limiter\" href='javascript:\\' onclick=\"ajax.loadDiv('divManip','lista_usr.php?pag=".$i."');\">";
			echo $i;
			if ($pagina!=$i) echo "</a>";
			echo "&nbsp;";
			
		}*/
echo "<br/><div id='paginacao' align='center'>";
			$letras = array('a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','x','w','y','z');
			foreach ($letras as $letra) {
				echo "<a href=javascript:// onClick=\"ajax.loadDiv('divManip','lista_usr.php?pag=".$letra."')\">" . $letra . "</a> ";
			}
					echo "</div>";
		
	
	
	}
	
	?>
