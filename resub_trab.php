<?php 	
		include("sessions.php");
		allow(3);
?>
<?php include_once("conexao.php"); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include("inc/header.php"); ?>
</head>
<link rel="stylesheet" href="windowfiles/dhtmlwindow.css" />
<script language="javascript" src="windowfiles/dhtmlwindow.js">

/***********************************************
* DHTML Window Widget- © Dynamic Drive (www.dynamicdrive.com)
* This notice must stay intact for legal use.
* Visit http://www.dynamicdrive.com/ for full source code
***********************************************/

</script>
<script language="javascript" src="TAjax.js"></script>
<script> var ajax = new TAjax(); </script>
<body>
<div align="center">
<table width="752" height="420" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
<tr><td height="120"><?php include("inc/topo.php"); ?></td></tr>  
<tr>
  <td width=752 height="300" align="center" valign=top style="padding:10px 10px 0 10px;">
	  <div align="center">
<h1>Submeter correção de trabalho</h1>
    <p>&nbsp;      </p>
    <div align="center">
	<?php include("mylist_trabalhos.php"); ?>
	</div>

    </p>
	  </div>
	  <br />
	</td>
</tr>

<tr><td style="padding: 5px 0 0 0;">
<?php include("inc/copyright.php"); ?>
</td></tr>
</table>
</div>
</body>
</html>
