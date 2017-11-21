<html>
<script language="javascript" src="TAjax.js"></script>
<script> var ajax = new TAjax(); </script>
<body>
<div align="center">
<h1>Alterar título do trabalho selecionado</h1>

<?php
	include_once("sessions.php");
	include_once("conexao.php");
	$idtrabalho = $_GET["idtrabalho"];
//echo "idtrabalho = ".$idtrabalho;exit;
?>

        <form action="post_novo_titulo.php" method="post" enctype="multipart/form-data"> <!--name="alteraTituloTrabalho" id="alteraTituloTrabalho"-->
        <table width="100%" style="margin: 0 0 0 0;" border="0" cellspacing="20" cellpadding="0">
		<tr>
            		<td align="center" valign="top">Novo título:</td>

                        <tr>
                            <td width="73%"><textarea name="Titulo" cols="52" rows="2"></textarea></td>
                       </tr>


  	  	</tr>

		<tr>

			<table align = "center">
				<td align = "center">
					<input type="hidden" value="<? echo $idtrabalho; ?>" name="idtrabalho" id="idtrabalho">
					<input type="submit" value="Enviar"><!-- onClick="if (verifica()) document.getElementById('alteraTituloTrabalho').submit();"/-->
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
                alert('A Categoria do trabalho deve ser informada.');
                return false;
        }         

        return true;
}

</script>
</body>
</html>
