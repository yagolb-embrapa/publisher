<?php 

/*
---------------------------------------------------------------------------
Obs: 
- O menu foi trocado em 04/03/2009.
- A versao antiga se encontra comentada no fim desta pagina e era colocada em inc/topo.php.
- O esquema de permissoes do novo menu foi totalmente baseado no menu antigo
---------------------------------------------------------------------------
*/

// exibe menu principal do sistema
function show_menu ( $id,  $permissoes, $funcao )
{	  	
	echo "<div id=\"staticMenu\">
	<table border='0'  width='600px' align='center' class='menu'>
	<tr>";
	
	//Inicial
	/*if($_SESSION["PERMISSAO"] & 31)
		echo"<td align='left' width='11%'><span onclick=\"top.location.href='index.php';\" >&nbsp;Inicial</a></td>";
	*/
		
	//Trabalhos
	if($_SESSION["PERMISSAO"] & 1 || $_SESSION["PERMISSAO"] & 2 ){
		echo "
		<td align='left' width='18%' >
			<ul id='nav'> 
				<li ><span>&nbsp;&nbsp;Trabalhos</span>		
					<ul>";
					if($_SESSION["PERMISSAO"] & 1)				
						echo "<li><span onclick=\"top.location.href='myRevision.php';\">
									Meus Trabalhos</span></li>";					
					if($_SESSION["PERMISSAO"] & 2)									
						echo "<li><span onclick=\"top.location.href='revisao.php';\">
									Revisão</span></li>";
					if($_SESSION["PERMISSAO"] & 1)		
						echo "<li><span onclick=\"top.location.href='submissao.php';\">
									Submissão</span></li>";
					
					echo "
					</ul>
				</li> 
			</ul>
		</td>";
	}	
	
	//Acompanhamento		
	if($_SESSION["PERMISSAO"] & 104){
		echo "
		<td align='left' width='25%' >
			<ul id='nav2'><li> 		
			<span onclick=\"top.location.href='acompan.php';\">&nbsp;&nbsp;Acompanhamento</span>
			</li></ul>			
		</td>";
	}
	
	//Administração		
	if($_SESSION["PERMISSAO"] & 4 || $_SESSION["PERMISSAO"] & 63 || $_SESSION["PERMISSAO"] & 16){
		echo "
		<td align='left' width='23%' >		
			<ul id='nav2'> 
				<li ><span>&nbsp;&nbsp;Administração</span>		
						<ul>";
						if($_SESSION["PERMISSAO"] & 4) {
						
							echo "<li><span onclick=\"top.location.href='relatorios.php';\">
									Relatórios</span></li>";
						}
						//if($_SESSION["PERMISSAO"] & )									
							echo "<li><span onclick=\"top.location.href='versoes.php';\">
									Versões</span></li>";
						if($_SESSION["PERMISSAO"] & 63)	{				
							echo "<li><span onclick=\"top.location.href='ctrlUsr.php';\">
									Usuários</span></li>";
						}
						if($_SESSION["PERMISSAO"] & 16) {					
							echo "<li><span onclick=\"top.location.href='preferences.php';\">
									Configuração</span></li>";
						}
						if($_SESSION["PERMISSAO"] & 4) {
                                                        echo "<li><span onclick=\"top.location.href='selecionar_arquivo.php';\">
                                                                        Substituir arquivo</span></li>";

                                                }
						if($_SESSION["PERMISSAO"] & 4) {
                                                        echo "<li><span onclick=\"top.location.href='selecionar_trabalho.php';\">
                                                                        Alterar status de trabalho</span></li>";

                                                }

							
					echo"
					</ul>
				</li> 
			</ul>
		</td>";
	}
		
	//Ajuda
	if($_SESSION["PERMISSAO"] & 63){
		echo "
		<td align='left' width='18%' >		
			<ul id='nav3'> 
				<li ><span></span>&nbsp;&nbsp;Ajuda</span>		
						<ul>
							<li><span onclick=\"window.open('tutorial/Tutorial_SGP_V2.0.pdf');\">
									Tutorial</span></li>
							<li><span onclick=\"window.open('http://intranet.cnptia.embrapa.br/content/serie-embrapa');\">
									Série Embrapa</span></li>
							<li><span onclick=\"window.open('http://www.rexlab.ufsc.br:8080/more/index.jsp');\">
									Referências Online</span></li>																																						
						</ul>
				</li> 
			</ul>
		</td>";
	}
	
	//Sair	
	if($_SESSION["PERMISSAO"] & 63)
		echo "<td align='center' width='11%'><ul><li>
			<span onclick=\"top.location.href='logout.php';\">Sair</span></td>
		</li></ul>
		</tr>
		</table></div><br>";
	

}

/*
//------------------------ ESSE É O MENU ANTIGO! ---------------------------------

if (!$short){
	unset($perm);
	unset($textos);
	$perm = array(31,1,1,2,8,4,63,16,63,63,63);
	$textos = array("Home","Meus Trabalhos", "Submiss&atilde;o","Revis&atilde;o","Acompanhamento","Relat&oacute;rios","Gerenciar Usu&aacute;rios","Prefer&ecirc;ncias","Ajuda","Logout");
	$links = array("index.php","myRevision.php", "submissao.php","revisao.php","acompan.php","relatorios.php","ctrlUsr.php","preferences.php","http://intranet.cnptia.embrapa.br/content/serie-embrapa","logout.php");
	$a = false;
		for ($i=0; $i<sizeof($perm); $i++){
			if ($_SESSION["PERMISSAO"] & $perm[$i]){
				if ($a) echo "|&nbsp;";
				$a = true;
				if (($textos[$i]=="Gerenciar Usu&aacute;rios")&&(!($_SESSION["PERMISSAO"] & 16))) $textos[$i] = "Gerenciar Conta";
				echo "<a style=\"color:#7d6d5e\" href=\"".$links[$i]."\"";
				if ($textos[$i]=="Ajuda") echo " target=\"_blank\"";
				echo ">".utf8_encode($textos[$i])."</a>&nbsp;";
			}
		}
	
}
*/
 
?>
