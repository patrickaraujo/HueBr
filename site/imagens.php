<?php
	function validaImg($nome, $erro, $arquivo_tmp, $tamanho, $tipo, $iD, $vF, $iP){	// verifica se foi enviado um arquivo 
	/******
		* Upload de imagens
	******/
 
	//	verifica se foi enviado um arquivo
	
	$retorno = "";
	
		if(isset($nome) && $erro == 0){
			// Pega a extensao
			$extensao = strrchr($nome, '.');

			// Converte a extensao para mimusculo
			$extensao = strtolower($extensao);

			// Somente imagens, .jpg;.jpeg;.gif;.png
			// Aqui eu enfilero as extesões permitidas e separo por ';'
			// Isso server apenas para eu poder pesquisar dentro desta String
			
			$tipos_aceitos = array("image/gif", "image/png", "image/x-png","image/png", "image/bmp", "image/jpeg");
			$tamanho_maximo = 16777216; //  em bits
			
			if($tamanho == 0 OR $arquivo_tmp == NULL)
				return 'Nenhum arquivo enviado';
			if($tamanho > $tamanho_maximo){
				$tamM = $tamanho_maximo*0.000000125;
				return 'O arquivo enviado é muito grande.<br/>Tamanho máximo: '.$tamM.' MBs';
			}
			
			if (array_search($tipo, $tipos_aceitos) === FALSE){
				return 'O arquivo enviado não é do tipo.<br/>Você poderá enviar apenas arquivos "*.jpg;*.jpeg;*.gif;*.png"<br />';
			}
			else{
				//	if ( strstr ( '.jpg;.jpeg;.gif;.png', $extensao ) ) {
				//	Cria um nome único para esta imagem
				//	Evita que duplique as imagens no servidor.
				//	Evita nomes com acentos, espaços e caracteres não alfanuméricos
				
				$novoNome = md5(microtime()). $extensao;
		 
				//	Concatena a pasta com o nome
				$diretorio = 'imagens/';
				if($vF)
					$diretorio .= 'usuarios/'.$iD.'/profilePic/'.$iP;	//	deseja-se que seja id
				else
					$diretorio .= 'usuarios/'.$iD.'/postagens/'.$iP;	//	deseja-se que seja id
				
				
				//  remover
				//  rmdir("nomedodiretorio") or die("erro ao excluir diretório");
				
				//  if(!file_exists('user/'.$usuario.'/imgperfil')):
					//  mkdir('user/'.$usuario.'/imgperfil', 0777, true);
				//  endif;
				mkdir($diretorio, 0777, true) or die("erro ao criar diretório");
				$destino = $diretorio.'/'.$novoNome;
				
				//	tenta mover o arquivo para o destino
				if ( @move_uploaded_file ( $arquivo_tmp, $destino ) ){
					return $destino;
					// echo 'Arquivo salvo com sucesso em: <strong>'. $destino .'</strong><br />';
					// echo ' < img src = "' . $destino . '" />';
				}
				else{
					return  'Erro ao salvar o arquivo. Aparentemente você não tem permissão de escrita.<br />';
				}
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
		else if($erro != 0 ){
			$retorno .= 'Erro no upload do arquivo!<br/>';
			switch($retorno){
				case UPLOAD_ERR_INI_SIZE:
					$retorno .= 'O arquivo excede o tamanho máximo permitido.';
                break;
				case UPLOAD_ERR_FORM_SIZE:
					$retorno .= 'O arquivo enviado é muito grande.';
                break;
				case UPLOAD_ERR_PARTIAL:
					$retorno .= 'O upload não foi completado.';
                break;
				case UPLOAD_ERR_NO_FILE:
					$retorno .= 'Nenhum arquivo foi informado para upload.';
                break;
			}
		}
		//    else
		//   $retorno .= 'Você não enviou nenhum arquivo!';
	return retorno;
	}
	
?>