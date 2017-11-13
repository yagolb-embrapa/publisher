<?php

include_once("sessions.php");
allow(16);
include_once("conexao.php");

$assinatura_presidente = T_escape_string($_POST["assinatura_presidente"]);
$assinatura_secretario = T_escape_string($_POST["assinatura_secretario"]);
$email_presidente = T_escape_string($_POST["email_presidente"]);
$email_secretario = T_escape_string($_POST["email_secretario"]);
$email_revisor = T_escape_string($_POST["email_revisor"]);
$revisor = T_escape_string($_POST["revisor"]);
$bibliotecario1 = T_escape_string($_POST["bibliotecario1"]);
$bibliotecario2 = T_escape_string($_POST["bibliotecario2"]);
$email_bib_1 = T_escape_string($_POST["email_bib_1"]);
$email_bib_2 = T_escape_string($_POST["email_bib_2"]);
$editor = T_escape_string($_POST["editor"]);
$email_editor = T_escape_string($_POST["email_editor"]);

//VERIFICACOES PARA SE CERTIFICAR QUE NENHUM CAMPO FICARA VAZIO.

$right = true;

if (str_replace(" ","",$assinatura_presidente) == "") $right = false;
if (str_replace(" ","",$assinatura_secretario) == "") $right = false;
if (str_replace(" ","",$email_presidente) == "") $right = false;
if (str_replace(" ","",$email_secretario) == "") $right = false;
if (str_replace(" ","",$revisor) == "") $right = false;
if (str_replace(" ","",$email_revisor) == "") $right = false;
if (str_replace(" ","",$bibliotecario1) == "") $right = false;
if (str_replace(" ","",$bibliotecario2) == "") $right = false;
if (str_replace(" ","",$email_bib_1) == "") $right = false;
if (str_replace(" ","",$email_bib_2) == "") $right = false;
if (str_replace(" ","",$editor) == "") $right = false;
if (str_replace(" ","",$email_editor) == "") $right = false;

if ($right){
    $sql = "UPDATE EMAIL_CONF SET 
    Assinatura_Presidente = '".$assinatura_presidente."',
    Assinatura_Secretario = '".$assinatura_secretario."',  
    Email_Presidente = '".$email_presidente."', 
	Email_Revisor = '".$email_revisor."', 
    Revisor = '".$revisor."', 
    Email_Secretario = '".$email_secretario."', 
    Bibliotecario1 = '".$bibliotecario1."', 
    Bibliotecario2 = '".$bibliotecario2."', 
    Email_Bib_1 = '".$email_bib_1."', 
    Email_Bib_2 = '".$email_bib_2."', 
    Editor = '".$editor."', 
    Email_Editoracao = '".$email_editor."'";
    if (T_query($sql)) echo "<script> alert('Alterações efetuadas com sucesso'); </script>";
    else echo "<script> alert('Erro ao efetuar alterações'); </script>";
    echo "<script> document.location = 'preferences.php' </script>";
}
else{
    echo "<script> alert('Todos os campos são obrigatorios'); </script>";
    echo "<script> document.location = 'preferences.php?conf=1' </script>";
}

?>