<?php

include_once("conexao.php");

$login = addslashes(strtoupper($_GET["login"]));

if (strlen($login)<4){
?>
<div style="display:inline"><font color="#FF0000" style="font-size:8pt; font-weight:bold; text-decoration:underline">Login muito curto </font></div>
<?php	
}
else{
$qryStr = "SELECT `Id_Usr` FROM `USR` WHERE  `Login` = '".$login."'";
$qry = T_query($qryStr);

if (!T_num_rows($qry)){
?>
<font color="#00CC00" style="font-weight:bold; text-decoration:underline">O login est&aacute; disponivel </font>
<?php
} else {

?>
<font color="#FF0000" style="font-weight:bold; text-decoration:underline">O login n&atilde;o est&aacute; dispon&iacute;vel</font>
<?php
}

}
?>
