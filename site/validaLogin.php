<?php
	$username = ($_POST['username']);
	$senha = ($_POST['senha']);
	$senha = md5($senha);
	
	include "validaDados.php";
    $uOe = FALSE; // usuário ou e-mail
	
    if(filter_var($username, FILTER_VALIDATE_EMAIL))
        $uOe = TRUE;

    if (buscaUsuario($username, $senha, $uOe) == -1){	//	usuario ou senha não estão contidos no banco de dados
		$_erro = ":(, tente novamente";
		if($uOe)
			header("Location: index.php?erro=Endereço de e-Mail não encontrado");
		else
			header("Location: index.php?erro=Nome de usuário não encontrado");
    }
	
	else if(buscaUsuario($username, $senha, $uOe) == 1){	//	tudo certo
		// setcookie('username', $username, time()+(24*60*60));
		session_start();
		$_SESSION['usuario'] = $username;
		header("Location: index.php");
	}
	
	else if(buscaUsuario($username, $senha, $uOe) == 0){	//	senha incorreta
		header("Location: index.php?erro=Senha incorreta&user=$username");
	}
?>