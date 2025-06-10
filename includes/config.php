<?php
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "AgendaFest";
    
    $conexao = new mysqli($servername, $username, $password, $dbname);
    
    if ($conexao->connect_error){
        die("Conexao falhou" . $conexao->connect_error);
    }
?>