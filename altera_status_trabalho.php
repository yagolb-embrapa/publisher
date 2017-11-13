<html>
<script language="javascript" src="TAjax.js"></script>
<script> var ajax = new TAjax(); </script>
<body>
<div align="center">
<h1>Alterar status do trabalho selecionado</h1>

<?php
	include_once("sessions.php");
	include_once("conexao.php");
	$idtrabalho = $_GET["idtrabalho"];
?>

        <form action="post_novo_status.php" method="post" enctype="multipart/form-data" name="alteraStatusTrabalho" id="alteraStatusTrabalho">
        <table width="100%" style="margin: 0 0 0 0;" border="0" cellspacing="20" cellpadding="0">
		<tr>
            		<td align="center" valign="top">Selecionar novo status:</td>

            			<td><select name="Id_Status_Trabalho" id="Id_Status_Trabalho">
                                	<option selected="selected" value="0">----</option>
                                	<?php
                                        	$qryStr = "SELECT `Id_Status_Trabalho`, `Status_Trabalho` FROM `STATUS_TRABALHO`";
               	                         $qry = T_query($qryStr);

                	                        while ($row = T_fetch_array($qry)){
                        	                        echo "<option value=\"".$row[0]."\">".utf8_encode($row[1])."</option>";
                                	        }

                    	           	 ?>
            			</select></td>

			</td>
  	  	</tr>

		<tr>

			<table align = "center">
				<td align = "center">
					<input type="hidden" value="<?= $idtrabalho ?>" name=idTrabalho id=idTrabalho>
					<input type="button" value="Enviar" onClick="if (verifica()) document.getElementById('alteraStatusTrabalho').submit();"/>
        				<input type="button" value="Cancelar" onClick="window.location = 'index.php'" />
				</td>
			</table>
		</tr>
	</table>
	</form>          
</div>

<script language="javascript">

function verifica() {
	
	if (document.getElementById('Id_Status_Trabalho').value==0){
                alert('A Categoria do Trabalho do trabalho deve ser informada.');
                return false;
        }         

        return true;
}

</script>
</body>
</html>
