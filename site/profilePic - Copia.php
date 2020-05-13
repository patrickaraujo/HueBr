<?php
    ini_set('default_charset', 'UTF-8');
	if(isset($_POST['send'])){
		$nome=$_POST['firstname'];
		$username=$_POST['username'];
		$senha=$_POST['psw'];
		$confirm=$_POST['spsw'];
		$sobrenome=$_POST['lastname'];
		$email=$_POST['email'];
		$confirmEmail=$_POST['cEmail'];
		$data=$_POST['birthday'];
		//    1º. Definir os parâmetros de teste
        
		$tamanho_maximo = 100000; //  em bites
		$tipos_aceitos = array("image/gif", "image/x-png", "image/bmp", "image/jpeg");
		//    2º. Validar o arquivo enviado
		$arquivo = $_FILES['arquivo'];
        $erro = "vid<br/>";
		if ($arquivo['error'] != 0){
                $erro .= 'Erro no upload do arquivo<br/>';
                switch($arquivo['error']){
                    case UPLOAD_ERR_INI_SIZE:
                        $erro .= 'O arquivo excede o tamanho máximo permitido<br/>';
                    case UPLOAD_ERR_FORM_SIZE:
                        $erro .= 'O arquivo enviado é muito grande<br/>';
                    case UPLOAD_ERR_PARTIAL:
                        $erro .= 'O upload não foi completado<br/>';
                    case UPLOAD_ERR_NO_FILE:
                        $erro .= 'Nenhum arquivo foi informado para upload<br/>';
                }
				if($arquivo['size'] == 0 OR $arquivo['tmp_name'] == NULL){
                    $erro .= "Nenhum arquivo enviado<br/>";
                    exit;
                }
                if($arquivo['size']>$tamanho_maximo){
                    $erro .= "O arquivo enviado é muito grande<br/>";
                    exit;
                }                
                if(array_search($arquivo['type'], $tipos_aceitos) === FALSE){
                    $erro .= "O arquivo enviado não é do tipo";
                    exit;
                }
                exit;
            }
			//   Agora podemos copiar o arquivo enviado
			$destino = 'C:\\xampp\\htdocs\\site\\image\\' .$arquivo['name'];
		if(empty($nome)){
			$erro .= 'Campo "Nome" vazio<br/>';
		}
		else if(empty($sobrenome)){
			$erro .= 'Campo "Sobrenome" vazio<br/>';
		}
		else if(empty($username)){
			$erro .= 'Campo "Usuário" vazio<br/>';
		}
		else if(empty($data)){
			$erro .= 'Campo "Nascimento" vazio<br/>';
		}
		else if(empty($senha)){
			$erro .= 'Campo "Senha" vazio<br/>';
		}
		else if(empty($email)){
			$erro .= 'Campo "e-Mail" vazio<br/>';
		}
		else if($senha != $confirm){
			$erro .= 'As senhas diferem<br/>';
		}
		else if($email != $confirmEmail){
			$erro .= 'Os e-Mails diferem<br/>';
		}
		else if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
			$erro .= 'Formato de e-Mail não aceito<br/>';
		}
		else{	  
			echo $dataCadastro = date('Y-m-d H:i:s');
			include "resposta2.php";
			$sql = "INSERT INTO cadastro (nome , sobrenome, usuario, senha, nascimento, email, dataCadastro, imagens) VALUES ('$nome', '$sobrenome', '$username', '$senha', '$data', '$email', '$dataCadastro', '$destino')";
			
			$resultado = mysqli_query($conexao, $sql) or die("Não foi possível executar a SQL: ".mysqli_error($conexao));;
			  
			if($resultado){
					echo '
						<script type="text/javascript">
							alert("Sucesso");
						</script>
					';
				}
			else{
				echo '
						<script type="text/javascript">
							alert("Fracasso");
						</script>
					';
			}
			mysqli_close($conexao);
			  /*
			  
			  $sql = mysql_query("
					INSERT INTO cadastro  
					value(
						NULL,
						'{$nome}',
						'{$sobrenome}',
						'{$username}',
						'{$email}',
						'{$senha}',
						'{$data}',
						'{$dataCadastro}'
					)
				");
				
				*/
				
				
		}
		if(isset($erro)):
			echo $erro.'<br />';
		endif;
		
		if(!file_exists("image")):
			mkdir("image", 0777, true);
        endif;

		if(move_uploaded_file($arquivo['tmp_name'], $destino)){
		//Tudo Ok, mostramos dos dados

			echo 'O arquivo foi carregado com sucesso para ' .$destino.'!';
			echo "<img src='image/".$arquivo['name']."' alt='imagem'/>";

        }

		else{
			echo 'Ocorreu um erro durante o upload';   
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
                    <img alt="logo" src="img/logo.png"/>
                </a>
            </figure>
        </header>
        <main>
		
            <h2>The zuera never ends, graças a Deus&trade;!</h2>
            <section>
                <h3>Cadastre-se:</h3>
                <!--<form method="post" enctype="multipart/form-data" action="resposta2.php">-->
                <p><?php if(!empty($erro)){echo $erro;} ?></p>
				<form method="post" enctype="multipart/form-data" action="">
                    <p>
                        <label for="firstname" >
                            Nome:
                        </label>
                        <input type="text" name="firstname" id="firstname"/>
                    </p>
                    <br>
                    <p>
                        <label for="lastname" >
                            Sobrenome:
                        </label>
                        <input type="text" name="lastname" id ="lastname"/>
                    </p>
                    <br>
                    <p>
                        <label for="username" >
                            Usuário:
                        </label>
                        <input type="text" name="username" id ="username"/>
                    </p>
                    <br>
                    <p>
                        <label for="psw" >
                            Senha:
                        </label>
                        <input type="password" name="psw" id ="psw"/>
                    </p>
                    <br>
                    <p>
                        <label for="spsw" >
                            Confirme a senha:
                        </label>
                        <input type="password" name="spsw" id ="spsw"/>
                    </p>
                    <br>
                    <p>
                        <label for="birthday" >
                            Nascimento:
                        </label>
                        <input type="date" name="birthday" id ="birthday"/>
                    </p>
                    <br>
                    <p>
                        <label for="email" >
                            e-Mail:
                        </label>
                        <input type="email" name="email" id ="email"/>
                    </p>
                    <br>
                    <p>
                        <label for="cEmail" >
                            Confirme seu e-Mail:
                        </label>
                        <input type="email" name="cEmail" id ="cEmail"/>
                    </p>
                    <br>
                    <fieldset>
                        <legend>
                            Upload de foto
                        </legend>
                        <input type="hidden" name="MAX_SIDE_FILE" value="100000">
                        <input type="file" name="arquivo"/>
                        <br/>
                        <br/>
                    </fieldset>
                        
                    <input type="submit" value="ENVIAR" name="send"/>
					
                </form>
            </section>
            </main>
            <footer>
                <nav>
                    <ul>
                        <li>
                            <a href="index.php">
                                Casa
                            </a>
                        </li>
                        <li>
                            <a href="humor.php">
                                Humor
                            </a>
                        </li>
                        <li>
                            <a href="about.php">
                                Sobre
                            </a>
                        </li>
                        <li>
                            <a href="contato.php">
                                Contato
                            </a>
                        </li>
                        <li>
                            <a href="cadastro.php">
                                Cadastro
                            </a>
                        </li>
                    </ul>
                </nav>
                <h4>Copyright &copy;
                    <a target="_blank" href="https://www.facebook.com/araujo.patrick">
                        Patrick Araújo
                    </a> 2018
                </h4>
            </footer>
    </body>
</html>