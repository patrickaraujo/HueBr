<?php
    ini_set('default_charset', 'UTF-8');
    session_start();
    include 'validaDados.php';

    //  remove all session variables
    //  session_unset();
    //  destroy the session
    //  session_destroy();

    $footer = footer();
    
    $nome = $_SESSION['nome'];
    $sobrenome = $_SESSION['sobrenome'];
    $email = $_SESSION['email'];
    $usuario = $_SESSION['usuario'];
    $nascimento = $_SESSION['nascimento'];
    $senha = $_SESSION['senha'];
    $erro = vInformacoes($nome, $sobrenome, $nascimento, $usuario, $email, $senha);
    $validacao = FALSE;

    if((!(session_status() == PHP_SESSION_NONE)) && (!empty($erro)))
        header("Location: cadastro.php?erro2=$erro");

    /******
		* Upload de imagens
	******/
 
	//	verifica se foi enviado um arquivo
    $con = conectaBD();
    $sql_VE = "SELECT email FROM usuario WHERE email='$email'";
	$rVE = mysqli_fetch_array(mysqli_query($con, $sql_VE));    //  resultado da verificação do e-mail no banco de dados
	$sql_VUser = "SELECT usuario FROM usuario WHERE usuario='$usuario'";
	$rVUser = mysqli_fetch_array(mysqli_query($con, $sql_VUser));  //  resultado da verificação do usuário no banco de dados
    mysqli_close($con);
	
	if($rVE){
		if($rVE['email'] == $email)
			$erro .= "Esse e-mail já está cadastrado, tente um novo. ";
	}
	if($rVUser){
		if($rVUser['usuario'] == $usuario)
			$erro .= "Esse usuário já está cadastrado, tente um novo.";
	}
    if(!empty($erro))
        header("Location: cadastro.php?erro=$erro");
	else{
	
	}
	
    if(isset($_POST['send']) && empty($erro)){
		
        if ( isset( $_FILES[ 'arquivo' ][ 'name' ] ) && $_FILES[ 'arquivo' ][ 'error' ] == 0 ) {
			/*
				echo 'Você enviou o arquivo: <strong>' . $_FILES[ 'arquivo' ][ 'name' ] . '</strong><br />';
				echo 'Este arquivo é do tipo: <strong > ' . $_FILES[ 'arquivo' ][ 'type' ] . ' </strong ><br />';
				echo 'Temporáriamente foi salvo em: <strong>' . $_FILES[ 'arquivo' ][ 'tmp_name' ] . '</strong><br />';
				echo 'Seu tamanho é: <strong>' . $_FILES[ 'arquivo' ][ 'size' ] . '</strong> Bytes<br /><br />';
			*/
			
			$arquivo_tmp = $_FILES[ 'arquivo' ][ 'tmp_name' ];
			
			//	$nome = $_FILES[ 'arquivo' ][ 'name' ];
			//	Pega a extensão
			$extensao = strrchr($_FILES[ 'arquivo' ][ 'name' ], '.');
		 
			//	Converte a extensão para minúsculo
			$extensao = strtolower ( $extensao );
		 
			//	Somente imagens, .jpg;.jpeg;.gif;.png
			//	Aqui eu enfileiro as extensões permitidas e separo por ';'
			//	Isso serve apenas para eu poder pesquisar dentro desta String
			
			$tipos_aceitos = array("image/gif", "image/png", "image/x-png","image/png", "image/bmp", "image/jpeg");
			$tamanho_maximo = 16777216; //  em bits
			    
			if($_FILES[ 'arquivo' ]['size'] == 0 OR $arquivo_tmp == NULL)
				$erro .= "Nenhum arquivo enviado";
			if($_FILES[ 'arquivo' ]['size'] > $tamanho_maximo){
                $tamM = $tamanho_maximo*0.000000125;
				$erro .= 'O arquivo enviado é muito grande.<br/>Tamanho máximo: '.$tamM.' MBs';
			}
			
			if (array_search($_FILES[ 'arquivo' ]['type'], $tipos_aceitos) === FALSE){
                $erro .= 'O arquivo enviado não é do tipo.<br/>Você poderá enviar apenas arquivos "*.jpg;*.jpeg;*.gif;*.png"<br />';
            }
			else{
                //	if ( strstr ( '.jpg;.jpeg;.gif;.png', $extensao ) ) {
				//	Cria um nome único para esta imagem
				//	Evita que duplique as imagens no servidor.
				//	Evita nomes com acentos, espaços e caracteres não alfanuméricos
				
				$novoNome = md5(microtime()). $extensao;
		 
				//	Concatena a pasta com o nome
				$diretorio = 'imagens/usuarios/'.$_SESSION["usuario"].'/profilePic/1';
                
                //  remover
                //  rmdir("nomedodiretorio") or die("erro ao excluir diretório");
                
                //  if(!file_exists('user/'.$usuario.'/imgperfil')):
                    //  mkdir('user/'.$usuario.'/imgperfil', 0777, true);
                //  endif;
				mkdir($diretorio, 0777, true) or die("erro ao criar diretório");
				$destino = $diretorio.'/'.$novoNome;
		 
				//	tenta mover o arquivo para o destino
				if ( @move_uploaded_file ( $arquivo_tmp, $destino ) ) {
                    $con = conectaBD();
					// echo 'Arquivo salvo com sucesso em: <strong>'. $destino .'</strong><br />';
					// echo ' < img src = "' . $destino . '" />';
					//	}
                    $nome = $_SESSION['nome'];
                    $sobrenome = $_SESSION['sobrenome'];
                    $usuario = $_SESSION['usuario'];
                    $email = $_SESSION['email'];
                    $senha = $_SESSION['senha'];
                    $nascimento = $_SESSION['nascimento']->format('Y-m-d H:i:s');
                    
                    date_default_timezone_set("America/Araguaina");
				    $dataCadastro = date('Y-m-d H:i:s');
                    
				
				    $sql = "INSERT INTO usuario (nome, sobrenome, nascimento, usuario, email, senha, imagem, criacao, ultimoAcesso) VALUES ('$nome', '$sobrenome', '$nascimento', '$usuario', '$email', '$senha', '$destino', '$dataCadastro', '$dataCadastro')";
                    
                    $resultado = mysqli_query($con, $sql) or die ("Não foi possível executar a SQL: ".mysqli_error($con));

                    if($resultado)
                        $validacao = FALSE;
                    else
                        $validacao = TRUE;
                    
                    apagaSessao();
				    
                    mysqli_close($con);
                    
				}
				else
					$erro .= 'Erro ao salvar o arquivo.<br/>Aparentemente você não tem permissão de escrita.';
				/*
						}
							else
								echo "Você poderá enviar apenas arquivos \"*.jpg;*.jpeg;*.gif;*.png\"<br />";
					}
					else{
						echo "Você não enviou nenhum arquivo!";
					}
				*/
            }
		}
		
		else if($_FILES[ 'arquivo' ][ 'error' ] != 0 ){
			$erro .= 'Erro no upload do arquivo!<br/>';
			switch($_FILES[ 'arquivo' ]['error']){
				case UPLOAD_ERR_INI_SIZE:
					$erro .= 'O arquivo excede o tamanho máximo permitido.';
                break;
				case UPLOAD_ERR_FORM_SIZE:
					$erro .= 'O arquivo enviado é muito grande.';
                break;
				case UPLOAD_ERR_PARTIAL:
					$erro .= 'O upload não foi completado.';
                break;
				case UPLOAD_ERR_NO_FILE:
					$erro .= 'Nenhum arquivo foi informado para upload.';
                break;
			}
		}
		//    else
		//   $erro .= 'Você não enviou nenhum arquivo!';
        apagaSessao();
        if(empty($erro) && $validacao)
            header("Location: index.php?erro=error");
        else if(empty($erro) && !$validacao)
            header("Location: index.php?erro=$email");
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
				<form method="post" enctype="multipart/form-data" action="<?php include 'imagens.php' $pic = TRUE; validaImagem($pic); ?>">
                    
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
                <?php echo $footer; ?>
            </footer>
    </body>
</html>