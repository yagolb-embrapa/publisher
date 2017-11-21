<script src="windowfiles/dhtmlwindow.js"></script>
<link rel="stylesheet" href="windowfiles/dhtmlwindow.css" />
<?php

if (($_GET["ano"] < 2000)||($_GET["ano"] > date("Y"))) echo "<script> alert('Favor inserir uma data válida'); 
trabSelector.close(); </script>";
else{
	
	$destino = $_GET["dest"];

        /* um switch é bem melhor q essa renca de ifs encadeados
	if ($destino==0)
	{
		$url = "rel_acomp";
		$wintitle = "Relatorio de acompanhamento";
	}
	else if ($destino==1)
	{
		$url = "rel_trabalho";
		$wintitle = "Dados do trabalho";
	}
	else if ($destino == 2)
	{
		$url = "rel_comentario";
		$wintitle = "Comentários do trabalho";
	}
        else{

        }*/

        switch($destino){
            case 0:     $url = "rel_acomp";
                        $wintitle = "Relatorio de acompanhamento";
                        break;
            case 1:     $url = "rel_trabalho";
                        $wintitle = "Dados do trabalho";
                        break;
            case 2:     $url = "rel_comentario";
                        $wintitle = "Comentários do trabalho";
                        break;
            case 3:     $url = "lista_sugestoes";
                        $wintitle = "Listagem de sugestões para o trabalho";
                        break;
	    case 4:     $url = "troca_arquivo";
			$wintitle = "Substituição de arquivo";
			break;
	    case 5:	$url = "altera_status_trabalho";
			$wintitle = "Alteração de status de trabalho";
			break;
            case 6:     $url = "altera_titulo_trabalho"; 
                        $wintitle = "Alteração de sttítulo de trabalho";
                        break;
	
            default:    $url = "rel_comentario";
                        $wintitle = "Comentários do trabalho";
        }

	

	include_once("conexao.php");
	
	$ano = substr($_GET["ano"],-2);
	//$sql = "SELECT Id_Trabalho, Titulo FROM TRABALHOS WHERE Id_Trabalho LIKE '%".$ano."' ORDER BY Id_Trabalho DESC";
	$sql = "SELECT t.Id_Trabalho, t.Titulo FROM TRABALHOS AS t WHERE t.Id_Trabalho LIKE '%".$ano."'";
            if (isset($_GET["parec"]))
                $sql .= " AND t.Id_Trabalho IN (SELECT r.Id_Trabalho from REVISOES AS r where r.Id_Trabalho=t.Id_Trabalho and r.revisao>=1 AND arquivo <> '')";
        $sql .= " ORDER BY t.Id_Trabalho DESC";


        $qry = T_query($sql);
	
	$i = 1;
	if (!T_num_rows($qry)) echo "Nenhum trabalho listado nesse ano";
	else
	while($row = T_fetch_array($qry)){
	$i = ($i+1)%2;
?>
<div class="lista_registros<?php echo $i; ?>" style="display:block; cursor:pointer; width:100%;" onClick="ass=dhtmlwindow.open('listDiv', 'ajax', '<?php echo $url; ?>.php?idtrabalho=<?php echo $row["Id_Trabalho"]; ?>', '<?php echo $wintitle ?>', 'width=480px,height=250px,center=1,resize=0,scrolling=1');">
<?php echo $row["Id_Trabalho"]." - ".utf8_encode($row["Titulo"]); ?>
</div>
<?php
	}

}

?>
