<?php
	ini_set('default_charset', 'UTF-8');
    include 'validaDados.php';
    $footer = footer();
	
	session_start();
	if (isset($_SESSION['usuario']))
        $usuario = $_SESSION['usuario'];
	else
		header("Location: index.php");
	$con = conectaBD();
	$sql = "SELECT * FROM usuario WHERE usuario='$usuario'";
    $resultado = mysqli_query($con, $sql);
	$vdu = mysqli_fetch_array($resultado);
	$mainID = $vdu['id'];
	
	if(isset($_POST['send'])){
		$legenda = $_POST['legenda'];
		date_default_timezone_set("America/Araguaina");
		$dataPostagem = date('Y-m-d H:i:s');
		
		$sql = "INSERT INTO postagens (autor, legenda, imagem, data) VALUES('$mainID', '$legenda', '', '$dataPostagem')";
		
		
		$resultado = mysqli_query($con, $sql) or die ("Não foi possível executar a SQL: ".mysqli_error($con));
		
		
		include 'imagens.php';
			
		
		
		$fNome = $_FILES['arquivo']['name'];
		$fErro = $_FILES['arquivo']['error'];
		$fTmp_name = $_FILES['arquivo']['tmp_name'];
		$fTamanho = $_FILES[ 'arquivo' ]['size'];
		$fTipo = $_FILES[ 'arquivo' ]['type'];
		
		
		$pSQL = "SELECT id FROM postagens WHERE autor='$mainID' ORDER BY ID DESC LIMIT 1";;
		$rVE = mysqli_fetch_array(mysqli_query($con, $pSQL));    //  resultado da verificação do e-mail no banco de dados
		$postID = $rVE['id'];
		$r = validaImg($fNome, $fErro, $fTmp_name, $fTamanho, $fTipo, $mainID, FALSE, $postID);
		if(strpos($r, 'usuario') !== false){	//	se encontrou usuário em r então é verdadeiro
			$sql = "UPDATE postagens SET imagem='$r' WHERE id='$postID'";
			$resultado = mysqli_query($con, $sql) or die("Não foi possível executar a SQL: ".mysqli_error($conexao));
		}
		
	}
	
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <title>Feed</title>
        <!--
			<link rel="stylesheet" type="text/css" href="css/style2.css" />
		-->		
		<link rel="stylesheet" type="text/css" href="css/style3.css" />
    </head>
    <body>
        <container>
            <header>
				
				<figure>
					<img alt="logo" src="<?php logo()?>"/>
				</figure>
				<!--
					<div class="dropdown">
						<button class="dropbtn"></button>
						<div class="dropdown-content">
							
						</div>
					</div>
				-->
				
				<div class="navbar">
					<!--
						<a href="#home">Home</a>
						<a href="#news">News</a>
					-->
					<div class="dropdown">
						<button class="dropbtn">
							<a href="my.php">
								Feed de <?php echo $vdu['usuario'];?>
							<img src="<?php echo $vdu['imagem']?>" id="perfil" alt="profilePic" class="profilePic"/>
							</a>
							<i class="fa fa-caret-down"></i>
						</button>
						<div class="dropdown-content">
							<a href="alterarDados.php">Configurações</a>
							<a href="sair.php">Sair</a>
						</div>
					</div>
				</div>
			   
                <!-- <img src="<?php echo "user/".$vdu['usuario']."/imgperfil/".$vdu['imagem']?>" id="perfil" alt="profilePic" class="profilePic"/>
                
					<a href='profilePIC.php?id=$id'><img src='img/up.jpeg' alt='delete post' width='15' height='15' class='fRight'/></a>
					<p><?php echo $vdu['nome'];?><p>
					<p><?php echo $vdu['usuario'];?><p>
				 -->
				 
            </header>
			<article>
                <section id="status">	<!--	POSTAGEM	-->
                        <form method="post" enctype="multipart/form-data" action="">
                            <textarea name="legenda" cols ="50" placeholder="Desabafe..." rows="4"></textarea>
                            <input name ="id" type="hidden" value="<?php echo $vdu['id'] ?>"/>
                            <legend>Foto da Hu3ragem </legend>
                            <input type="hidden" name="MAX_FILE_SIZE" value="16777216">
                            <input type="file" name="arquivo"/>
                            <input type="submit" value="ENVIAR" name="send"/>
                        </form>
				</section>
                    <?php
						//$sql2 = "SELECT * FROM postagens WHERE id_usuario = '$c_id_usuario' ORDER BY id DESC";
						$sql2 = "SELECT * FROM postagens ORDER BY id DESC";
						$result = mysqli_query($con,$sql2);
                            if($result){
								while($registro2 = mysqli_fetch_array($result)){
									$post = $registro2['legenda'];
									$id_post = $registro2['id'];
									$img_post = $registro2['imagem'];
									$data_post = $registro2['data'];
									$autor = $registro2['autor'];
									$sql3 = "SELECT usuario FROM usuario WHERE id='$autor'";
									$resultado3 = mysqli_query($con, $sql3);
									$vdu3 = mysqli_fetch_array($resultado3);	
									$user = $vdu3['usuario'];
                                    
                                    echo "Usuário: $user";
                                    echo "Post: $post";
                                    echo $data_post;
                                    //	echo '<form action="post.php"><input type="submit" value="Excluir" name="delete"/></form>';
									if(strcmp($mainID, $autor)==0){
										echo "<a href='post.php?id=$id_post'><img src='img/del.png' alt='delete post' class='fRight'/></a>";
										echo "<a href='upgrade.php?id=$id_post'><img src='img/up.jpeg' alt='delete post' width='15' height='15' class='fRight'/></a>";
									}
									if(!empty($img_post)){
										$imgPost = "<img src='$img_post' alt='profilePic' class='profilePic'/>";
										echo $imgPost;
									}
								}
							}
                        
                     

						//3. Fechar conexão
						mysqli_close($con);

					?>
			</article>
        </container>
		<footer>
                <?php echo $footer; ?>
            </footer>
    </body>
</html>