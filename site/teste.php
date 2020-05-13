<?php
    ini_set('default_charset', 'UTF-8');
    session_start();

    $nome = $_SESSION['nome'];
    $sobrenome = $_SESSION['sobrenome'];
    $usuario = $_SESSION["usuario"];
    $email = $_SESSION['email'];
    $senha = $_SESSION['senha'];
    $nascimento = $_SESSION['nascimento'];
    
	//  remove all session variables
    //  session_unset();
    //  destroy the session
    //  session_destroy();

    include 'validaDados.php';

    $footer = footer();
    $erro = vInformacoes($nome, $sobrenome, $nascimento, $usuario, $email, $senha);
    if((session_status() == PHP_SESSION_NONE) && (!(empty(erro))))
        header('Location: index.php');

    /******
		* Upload de imagens
	******/
 
	//	verifica se foi enviado um arquivo
	if(isset($_POST['send'])){
        $erro = "";
		
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
			$tamanho_maximo = 1000000; //  em bites
			    
			if($_FILES[ 'arquivo' ]['size'] == 0 OR $arquivo_tmp == NULL){
				$erro .= "Nenhum arquivo enviado";
				exit;
			}
			if($_FILES[ 'arquivo' ]['size'] > $tamanho_maximo){
                $tamM = $tamanho_maximo/1000000;
				$erro .= 'O arquivo enviado é muito grande.<br/>Tamanho máximo: '.$tamM.' MBs';
                exit;
			}
			
			if (array_search($_FILES[ 'arquivo' ]['type'], $tipos_aceitos) === FALSE)
				$erro .= 'O arquivo enviado não é do tipo.<br/>Você poderá enviar apenas arquivos "*.jpg;*.jpeg;*.gif;*.png"<br />';
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
				mkdir($diretorio, 0777, true) or die("erro ao criar diretório");
				$destino = $diretorio.'/'.$novoNome;
		 
				//	tenta mover o arquivo para o destino
				if ( @move_uploaded_file ( $arquivo_tmp, $destino ) ) {
					echo 'Arquivo salvo com sucesso em: <strong>'. $destino .'</strong><br />';
					// echo ' < img src = "' . $destino . '" />';
                    
                    date_default_timezone_set("America/Araguaina");
				    echo $dataCadastro = date('Y-m-d h:i:s');
				
				    $sql = "INSERT INTO usuario (nome, sobrenome, nascimento, usuario,  email, senha, imagem, criacao, ultimoAcesso) VALUES ("$_SESSION['nome']", "$_SESSION['sobrenome']", "$_SESSION['nascimento']", "$_SESSION['usuario']", "$_SESSION['email']", "$_SESSION['senha']", "$destino", "$dataCadastro", "$dataCadastro")";                
                    
                    /*
                    aqui---aqui---aqui---aqui---aqui---aqui---aqui---aqui---aqui---aqui---aqui---
                    */
				}
				else
					$erro .= 'Erro ao salvar o arquivo.<br/>Aparentemente você não tem permissão de escrita.';
            }
		}
		
		else if($_FILES[ 'arquivo' ][ 'error' ] != 0 ){
			$erro .= 'Erro no upload do arquivo!<br/>';
			switch($_FILES[ 'arquivo' ]['error']){
				case UPLOAD_ERR_INI_SIZE:
					$erro .= 'O arquivo excede o tamanho máximo permitido.';
				case UPLOAD_ERR_FORM_SIZE:
					$erro .= 'O arquivo enviado é muito grande.';
				case UPLOAD_ERR_PARTIAL:
					$erro .= 'O upload não foi completado.';
				case UPLOAD_ERR_NO_FILE:
					$erro .= 'Nenhum arquivo foi informado para upload.';
			}
		}
		//    else
			//   $erro .= 'Você não enviou nenhum arquivo!';
		
	}

    function encaminhar() {
        header('Location: index.php');
    }
?>