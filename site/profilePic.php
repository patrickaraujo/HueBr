<?php
    ini_set('default_charset', 'UTF-8');
	session_start();
    include 'validaDados.php';
	$validatorMax = TRUE;
	
    //  remove all session variables
    //  session_unset();
    //  destroy the session
    //  session_destroy();

    $footer = footer();
    
	$fID = 0;
	$erro = '';
	if(isset($_SESSION['nome']) && isset($_SESSION['sobrenome']) && isset($_SESSION['email']) && isset($_SESSION['usuario']) && isset($_SESSION['senha'])){	//	TUDO CERTO
		$nome = $_SESSION['nome'];
		$sobrenome = $_SESSION['sobrenome'];
		$email = $_SESSION['email'];
		$usuario = $_SESSION['usuario'];
		$nascimento = $_SESSION['nascimento'];
		$senha = $_SESSION['senha'];
		$erro .= vInformacoes($nome, $sobrenome, $nascimento, $usuario, $email, $senha);
		
		$con = conectaBD();	//	include 'validaDados.php';
		$sql_VE = "SELECT email FROM usuario WHERE email='$email'";
		$rVE = mysqli_fetch_array(mysqli_query($con, $sql_VE));    //  resultado da verificação do e-mail no banco de dados
		$sql_VUser = "SELECT usuario FROM usuario WHERE usuario='$usuario'";
		$rVUser = mysqli_fetch_array(mysqli_query($con, $sql_VUser));  //  resultado da verificação do usuário no banco de dados
		mysqli_close($con);
		
		if(($rVE != NULL) && ($rVE['email'] == $email))
			$erro .= "Esse e-mail já está cadastrado, tente um novo. ";
		
		if(($rVUser != NULL) && ($rVUser['usuario'] == $usuario))
			$erro .= "Esse usuário já está cadastrado, tente um novo.";
		
		if(!empty($erro))
			header("Location: cadastro.php?erro=$erro");
		else{
			$con = conectaBD();
			$dataNascimento = $_SESSION['nascimento']->format('Y-m-d H:i:s');
			
			date_default_timezone_set("America/Araguaina");
			$dataCadastro = date('Y-m-d H:i:s');
			
			$sql = "INSERT INTO usuario (nome, sobrenome, nascimento, usuario, email, senha, imagem, nImagens, criacao, ultimoAcesso) VALUES ('$nome', '$sobrenome', '$dataNascimento', '$usuario', '$email', '$senha', '', 1, '$dataCadastro', '$dataCadastro')";
						
			$resultado = mysqli_query($con, $sql) or die ("Não foi possível executar a SQL: ".mysqli_error($con));
			
			$validacao = FALSE;
			if(!$resultado)
				$validacao = TRUE;
			
			mysqli_close($con);
			
			
			if(empty($erro) && $validacao)
				header("Location: index.php?erro='error'");
			else{
				$con = conectaBD();
				$sql_VE = "SELECT id FROM usuario WHERE usuario='$usuario'";
				$rVE = mysqli_fetch_array(mysqli_query($con, $sql_VE));    //  resultado da verificação do e-mail no banco de dados
				$fID = $rVE['id'];
				
				mysqli_close($con);
			}
			/*
			else if(empty($erro) && !$validacao)
				header("Location: index.php?user=$usuario");
			*/
		}
		apagaSessao();
	}
	
    if(isset($_POST['send']) && empty($erro)){
		$fID = $_POST['id'];
		if($fID != 0){
			$fNome = $_FILES['arquivo']['name'];
			$fErro = $_FILES['arquivo']['error'];
			$fTmp_name = $_FILES['arquivo']['tmp_name'];
			$fTamanho = $_FILES[ 'arquivo' ]['size'];
			$fTipo = $_FILES[ 'arquivo' ]['type'];
			
			include 'imagens.php';
			
			$r = validaImg($fNome, $fErro, $fTmp_name, $fTamanho, $fTipo, $fID, TRUE, 1);
			
			$erro .= $r;
			$con = conectaBD();
			if(strpos($r, 'usuario') !== false){	//	se encontrou usuário em r então é verdadeiro
				 $sql = "UPDATE usuario SET imagem='$r' WHERE id='$fID'";
				 $resultado = mysqli_query($con, $sql) or die("Não foi possível executar a SQL: ".mysqli_error($conexao));
			}
			
			echo $r;
		
        }
	}
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>cadastro</title>
        <link rel="stylesheet" type="text/css" href="css/style2.css"/>
    </head>
    <body>
        <header>            
            <figure>
                <a href="index.php">
                    <img alt="logo" src="<?php echo logo(); ?>"/>
                </a>
            </figure>
        </header>
        <main>
		
            <h2>The zuera never ends, graças a Deus&trade;!</h2>
            <section>
                <h3>Última parte...</h3>
                <?php
                    if(!empty($erro)){
                        echo    '<p
                                    style = "
                                        background-color: red;
                                        text-align: center;
                                        font-size: 20px;
                                        font-weight: bold;
                                        justify-content: center;
                                        padding-bottom: 30px;
                                    ">'.$erro.'</p>';
                    }
                ?>
                <!--<form method="post" enctype="multipart/form-data" action="resposta2.php">-->
				<form method="post" enctype="multipart/form-data" action="">
                    
                    <fieldset>
                        <legend>
                            Upload de foto
                        </legend>
                        <input type="hidden" name="MAX_SIDE_FILE" value="100000">
						<?php echo $fID; ?>
						<input name ="id" type="hidden" value="<?php echo $fID; ?>"/>
                        <input type="file" name="arquivo"/>
                        <br/>
                        <br/>
                    </fieldset>                    
                    <input type="submit" value="ENVIAR" name="send"/>
					
                </form>
            </section>
            </main>
            <footer>
                <?php echo $footer; ?>
            </footer>
    </body>
</html>