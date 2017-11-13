<?php

include_once("sessions.php");
allow(16);
include_once("conexao.php");

$tabelas = array("AREAS_ATUACAO","CARGOS","CATEGORIAS_TRABALHO");

$qry = T_query_geral($tabelas[$_GET["tab"]]);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1252" />
<title>Untitled Document</title>
</head>

<body>
<div align="center" style="height:350px; overflow:auto; border:#CCCCCC 2px solid;">
  <table width="100%" border="0" cellspacing="0" style="border:1px #CCCCCC solid;">
  <?php 
  
  		while ($row = T_fetch_array($qry)){
  
  ?>
    <tr>
      <td style="border:#CCCCCC 1px solid;"><?php echo $row[0]." - ".utf8_encode($row[1]); ?></td>
    </tr>
<?php
	} //fim do while

?>
  </table>
</div>
<div align="center">
  <form id="form1" name="form1" method="post" action="postTabDom.php">
    <input name="valor" type="text" id="valor" size="25" />
	<input type="hidden" value="<?php echo $_GET["tab"]; ?>" name="tab" />
    <input type="submit" name="Submit" value="Adicionar" />
  </form>
</div>
</body>
</html>
