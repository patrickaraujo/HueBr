<?php
	function footer(){
        return '<nav><ul><li><a href="index.php">Casa</a></li><li><a href="humor.php">Humor</a></li><li><a href="about.php">Sobre</a></li><li><a href="contato.php">Contato</a></li><li><a href="cadastro.php">Cadastro</a></li></ul></nav><h4>Copyright &copy;<a target="_blank" href="https://about.me/patrickaraujo">Patrick Araújo '.date('Y').'</h4>';
    }
	
    function logo(){
        return "imagens/logo.png";
    }
    
	function vInformacoes($nome, $sobrenome, $nascimento, $usuario, $email, $senha){    //  function parameters, two variables.
        $erro = "";
        if(empty($nome))
			$erro .= 'Campo "Nome" vazio<br>';
		else if(empty($sobrenome))
			$erro .= 'Campo "Sobrenome" vazio<br>';
		else if(empty($usuario))
			$erro .= 'Campo "Usuário" vazio<br>';
		else if(empty($nascimento))
			$erro .= 'Campo "Nascimento" vazio<br>';
        else if(empty($senha))
			$erro .= 'Campo "Senha" vazio<br>';
		else if(empty($email))
			$erro .= 'Campo "e-Mail" vazio<br>';
        else if(!filter_var($email, FILTER_VALIDATE_EMAIL))
			$erro .= 'Formato de e-Mail não aceito<br>';
        return $erro;
    }
    function buscaUsuario($username, $senha, $uOe){ //uOe: usuário ou email
        //1. Cria conexão
        $retorno = FALSE;
        //2. Cria query
        $con = conectaBD();
        $sql_AU = "SELECT ";

		if($uOe)
            $sql_AU .= "email, senha FROM usuario WHERE email='$username'";	// achar email
        else
            $sql_AU .= "usuario, senha FROM usuario WHERE usuario='$username'";    // achar usuário
        $resultado = mysqli_query($con, $sql_AU) or die("Não foi possível executar a SQL: ".mysqli_error($con));

        //3. Mostra os resultados

        $row = mysqli_fetch_array($resultado);

        if(isset($row)){	//	encontrou usuário ou email
			if(strcmp($senha, $row['senha']) == 0)
				$retorno = 1;	//	senha correta
			else
				$retorno = 0;	//	senha errada
		}
        else	//	não encontrou usuário ou email
            $retorno = -1;

        //4. Fecha a conexão
        mysqli_close($con);
        return $retorno;
    }

    function conectaBD(){
        $conexao = mysqli_connect("localhost", "admin", "commonProject", "huebr");
        mysqli_set_charset($conexao,"utf8");
        if(mysqli_connect_errno())
            echo "Não foi possível conectar. Erro: ".mysqli_connect_error();
        return $conexao;
    }
    function apagaSessao(){
        //  remove all session variables
        session_unset();            
        unset($_SESSION['nome']);   //  destrói uma variável
		unset($_SESSION['sobrenome']);
        unset($_SESSION['senha']);
		unset($_SESSION['usuario']);
		unset($_SESSION['nascimento']);
		unset($_SESSION['email']);
		session_destroy(); // encerra a sessão
    }
?>