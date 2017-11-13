<?php 
include("sessions.php");
if ($_GET["log"]=="logout") session_unset();
allow();
?>
<html>
<header>
<?php include("inc/header.php"); ?>
</header>
<body>
<div align="center">
<table width="752" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF" >
<tr><td colspan="2"><?php 
$short = true;
include("inc/topo.php"); ?></td></tr>  
<tr>
  <td width="20" height="300" align=left valign=top><?php //include("inc/menu.php"); ?></td>
  <td width=552 height="300" valign=top style="padding-top:20px; padding-left:20px;">
    <p>Olá, <?php echo $_SESSION["USER"]; ?>...<br >
      Seu último acesso foi em <?php echo $_SESSION["LAST_LOGIN"]; ?><br>
     </p>
    <p>&nbsp;</p>
    <p align="center">
      <?php if ($_GET["erro"]=="grant") echo "Voc&ecirc; n&atilde;o tem privil&eacute;gios para acessar essa area!"?>
    </p>
    </td>
</tr>
<tr>
<td colspan="2"><?php include("inc/copyright.php"); ?></td>
</tr>
</table>
</div>
</body>
</html>

