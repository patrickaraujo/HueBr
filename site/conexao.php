<?php
	$conexao = mysqli_connect("localhost", "admin", "commonProject", "huebr");
	mysqli_set_charset($conexao,"utf8");
	if(mysqli_connect_errno()){
        echo "Não foi possível conectar. Erro: ".mysqli_connect_error();   
    }
?>