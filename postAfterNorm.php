<?php

include_once("sessions.php");
allow(48);
include_once("conexao.php");
include_once("uploadFunc.php");
include_once("emailFunc.php");

$sqlver = "SELECT Id_Categoria_Trabalho FROM TRABALHOS WHERE Id_Trabalho = '".$_POST["idtrabalho"]."'";
$rver = T_fetch_array(T_query($sqlver));

if (($rver[0]!=2)&&($rver[0]!=3)&&($rver[0]!=5)){
    //ta certo que nao ficou bonito, mas é a solucao mais simples
    if ((!validExtension("pdf;doc;docx;odt;zip",$_FILES["f_trabalho"]))||(!validExtension("pdf;doc;docx;odt;zip",$_FILES["f_fichaCat"])))
            echo "<script> alert('Tipo de arquivo não permitido ".$_FILES['f_fichaCat']['name']."'); </script>";
    else{ //se o formato de arquivo for valido...

            $f_fichaCat = $_FILES["f_fichaCat"];
            $f_trabalho = $_FILES["f_trabalho"];
            $ext_ficha = getExtension($_FILES["f_fichaCat"]);
            $versao = $_POST["versao"];
            $idtrabalho = $_POST["idtrabalho"];
            $versao++;

            if (!is_dir("trabalhos/v".$versao)) mkdir("trabalhos/v".$versao,0775);
            if (!is_dir("trabalhos/fichasCat")) mkdir("trabalhos/fichasCat",0775);

            if ((uploadFile($f_trabalho,$idtrabalho,"trabalhos/v".$versao))&&(uploadFile($f_fichaCat,"ficha-".$idtrabalho,"trabalhos/fichasCat"))){

                    //se deu tudo certo com os uploads... vai o trabalho...
                    $sql = "UPDATE TRABALHOS SET Versao = ".$versao.", Ext_ficha = '".$ext_ficha."' WHERE Id_Trabalho = '".$idtrabalho."'";
                    $qry = T_query($sql);

                    unset($sql);
                    $sql = "INSERT INTO ACOMPANHAMENTO (Data_Operacao,Id_Trabalho,Id_Status_Trabalho) VALUES
                    (NOW(),'".$idtrabalho."',7);";
                    $qry = T_query($sql);
                    if ($qry){
                            mailThem($idtrabalho,12);
                    }
            }
            else{
                    echo "<script> alert('Erro ao submeter arquivos'); </script>";
            }

    }
}
else{
    // se nao for com tecnico, circ tecnica ou folder...

    if (!validExtension("pdf;doc;docx;odt;zip",$_FILES["f_trabalho"]))
            echo "<script> alert('Tipo de arquivo não permitido ".$_FILES['f_trabalho']['name']."'); </script>";
    else{ //se o formato de arquivo for valido...
            
            $f_trabalho = $_FILES["f_trabalho"];
            $versao = $_POST["versao"];
            $idtrabalho = $_POST["idtrabalho"];
            $versao++;

            if (!is_dir("trabalhos/v".$versao)) mkdir("trabalhos/v".$versao,0775);
            
            if (uploadFile($f_trabalho,$idtrabalho,"trabalhos/v".$versao)){

                    //se deu tudo certo com os uploads... vai o trabalho...
                    $sql = "UPDATE TRABALHOS SET Versao = ".$versao." WHERE Id_Trabalho = '".$idtrabalho."'";
                    $qry = T_query($sql);

                    unset($sql);
                    $sql = "INSERT INTO ACOMPANHAMENTO (Data_Operacao,Id_Trabalho,Id_Status_Trabalho) VALUES
                    (NOW(),'".$idtrabalho."',7);";
                    $qry = T_query($sql);
                    if ($qry){
                            mailThem($idtrabalho,12);
                    }
            }
            else{
                    echo "<script> alert('Erro ao submeter arquivos'); </script>";
            }

    }
}



echo "<script> document.location = 'acompan.php?rev=true'; </script>";

?>