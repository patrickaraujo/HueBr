<?php
    ini_set('default_charset', 'UTF-8');
    include 'validaDados.php';
    $footer = footer();
    
    if(!(empty($_GET['erro']))){
        $erro = $_GET['erro'];
        session_start();
        if(isset($_SESSION['nome']) && isset($_SESSION['sobrenome']) && $_SESSION['usuario'] && $_SESSION['email'] && $_SESSION['nascimento']){
            $nome = $_SESSION['nome'];
            $sobrenome = $_SESSION['sobrenome'];
            $username = $_SESSION['usuario'];
            $email = $_SESSION['email'];
            $nasc = $_SESSION['nascimento']->format('Y-m-d');
        }
        apagaSessao();
    }
    else if(!(empty($_GET['erro2'])))
        $erro = $_GET['erro2'];
	if(isset($_POST['send'])){
		$nome=$_POST['firstname'];
		$username=$_POST['username'];
		$senha= (md5($_POST['psw']));
		$confirm= (md5($_POST['cPsw']));
		$sobrenome=$_POST['lastname'];
		$email=$_POST['email'];
		$confirmEmail=$_POST['cEmail'];
        $nasc = $_POST['birthday'];
        $nascimento = new DateTime($nasc, new DateTimeZone('America/Araguaina'));
        $cT = new DateTime('now', new DateTimeZone('America/Araguaina'));
		//    1º. Definir os parâmetros de teste
        $diferDatas = (date_diff($nascimento, $cT))->y;
        
        $erro = vInformacoes($nome, $sobrenome, $nascimento, $username, $email, $senha);
        /*
		if(empty($nome)){
			$erro .= 'Campo "Nome" vazio<br>';
		}
		else if(empty($sobrenome)){
			$erro .= 'Campo "Sobrenome" vazio<br>';
		}
		else if(empty($username)){
			$erro .= 'Campo "Usuário" vazio<br>';
		}
		else if(empty($nascimento)){
			$erro .= 'Campo "Nascimento" vazio<br>';
        }
        else if(empty($senha)){
			$erro .= 'Campo "Senha" vazio<br>';
		}
		else if(empty($email)){
			$erro .= 'Campo "e-Mail" vazio<br>';
		}
        else if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
			$erro .= 'Formato de e-Mail não aceito<br>';
		}
        */
        if($diferDatas <= 16){
            $erro .= 'Idade menor de 16 anos<br>';
        }
        else if($diferDatas >= 150){
            $erro .= 'Data de nascimento inválida<br>';
        }
		else if($senha != $confirm){
			$erro .= 'As senhas diferem<br>';
		}
		else if($email != $confirmEmail){
			$erro .= 'Os e-Mails diferem<br>';
		}
		if(empty($erro)){
            session_start();
            $_SESSION['nome'] = $nome;
            $_SESSION['sobrenome'] = $sobrenome;
            $_SESSION['usuario'] = $username;
            $_SESSION['email'] = $email;
            $_SESSION['senha'] = $senha;
            $_SESSION['nascimento'] = $nascimento;
            header('Location: profilePic.php');
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
                <h3>Cadastre-se:</h3>
                <!--<form method="post" enctype="multipart/form-data" action="resposta2.php">-->
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
				<form method="POST" enctype="multipart/form-data" action="">
                    <p>
                        <label for="firstname" >
                            Nome:
                        </label>
                        <input type="text" name="firstname" id="firstname" value=   "<?php
                                                                                        if(!empty($nome))
                                                                                            echo $nome;
                                                                                    ?>"/>
                    </p>
                    <br>
                    <p>
                        <label for="lastname" >
                            Sobrenome:
                        </label>
                        <input type="text" name="lastname" id ="lastname" value="<?php if(!empty($sobrenome))echo $sobrenome; ?>"/>
                    </p>
                    <br>
                    <p>
                        <label for="username" >
                            Usuário:
                        </label>
                        <input type="text" name="username" id ="username" value="<?php if(!empty($username))echo $username; ?>"/>
                    </p>
                    <br>
                    <p>
                        <label for="birthday" >
                            Nascimento:
                        </label>
                        <input type="date" name="birthday" id ="birthday" value="<?php if(!empty($nasc))echo $nasc; ?>"/>
                    </p>
                    <br>
                    <?php if(!empty($erro)){echo '<p style = "background-color: red;
                            text-align: center;
                            font-size: 20px;
                            font-weight: bold;
                            justify-content: center;
                            padding-bottom: 5px;
                            ">Digite o e-Mail e o confirme em seguida</p>';} ?>
                    <p>
                        <label for="email" >
                            e-Mail:
                        </label>
                        <input type="email" name="email" id ="email" value="<?php if(!empty($email))echo $email; ?>"/>
                    </p>
                    <br>
                    <p>
                        <label for="cEmail" >
                            Confirme seu e-Mail:
                        </label>
                        <input type="email" name="cEmail" id ="cEmail"/>
                    </p>
                    <br>
                     <?php if(!empty($erro)){echo '<p style = "background-color: red;
                            text-align: center;
                            font-size: 20px;
                            font-weight: bold;
                            justify-content: center;
                            padding-bottom: 5px;
                            ">Digite a senha e a confirme em seguida</p>';} ?>
                    <p>
                        <label for="psw" >
                            Senha:
                        </label>
                        <input type="password" name="psw" id="senha"/>
                        <br>
                        <input type="checkbox" onclick="myFunction(1)">Mostrar senha
                    </p>
                    <br>
                    <p>
                        <label for="spsw" >	<!--Second password-->
                            Confirme a senha:
                        </label>
                        <input type="password" name="cPsw" id ="cPsw"/>
                        <br>
                        <input type="checkbox" onclick="myFunction(2)">Mostrar confirmação de senha
                    </p>
                    <br>
                    <input type="submit" value="Próximo..." name="send"/>
                    <script type="text/javascript" src="js/script.js"></script> 
                </form>
            </section>
            </main>
            <footer>
                <?php echo $footer; ?>
            </footer>
    </body>
</html>