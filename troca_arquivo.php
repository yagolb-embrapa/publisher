<html>
<script language="javascript" src="TAjax.js"></script>
<script> var ajax = new TAjax(); </script>
<body>
<div align="center">
<h1>Substituir arquivo do trabalho selecionado</h1>

<?php

	$idtrabalho = $_GET["idtrabalho"];

?>
<form action="substituir_arquivo.php" method="post" enctype="multipart/form-data" name="substTrabalho" id="substTrabalho">
	<table width="100%" style="margin: 0 0 0 0;" border="0" cellspacing="30" cellpadding="0">
		<tr>
        		<td colspan="2">
	
        	        	<table align = "center" style="border:1px; border-color:#FF0000; border-style:solid;" >
                	        	<tr>
                        	        	<td>
							<input name="Arquivo" type="file" id="Arquivo" size="35"/>
						</td>
	                        	</tr>
				</table>
                	</td>
		</tr>
	
		<tr></tr>

		<tr>
			<td align = "center">
				<input type="hidden" value="<?= $idtrabalho ?>" name=idTrabalho id=idTrabalho>
				<input type="button" value="Enviar Trabalho" onClick="if (verifica()) document.getElementById('substTrabalho').submit();"/>
        			<input type="button" value="Cancelar" onClick="window.location = 'index.php'" />
			</td>
		</tr>
	</table>
</form>          
</div>

<script language="javascript">

function verifica(){
        
        if (document.getElementById('Arquivo').value.length==0){
                alert('Nenhum arquivo selecionado para o envio.');
                return false;
        }

/*
        if (!document.getElementById('Arquivo').value.match('.')){
                alert(document.getElementById('Arquivo').value+' não é um arquivo valido.');
                return false;
        } else {
                var arr = document.getElementById('Arquivo').value.split('.');
                var exten = new Array("odt","doc","docx","pdf","zip");
                var val = false;
                for (i in exten){
                        if (exten[i] == arr[arr.length-1]) val = true;
                }
                if (!val){
                        alert('Extensao invalida.');
                        return false;
                }
        }
*/      
        return true;
        
}

</script>
</body>
</html>
