<?php
	$conexao = mysqli_connect("localhost", "root", "joaopessoa", "huebr");
	mysqli_set_charset($conexao,"utf8");
	if(mysqli_connect_errno())
	{
        echo "Não foi possível conectar. Erro: ".mysql_connect_error();   
    } 
?>