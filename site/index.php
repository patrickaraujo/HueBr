<?php
    session_start();
	if (isset($_SESSION['usuario'])){
        $usuario = $_SESSION['usuario'];
        header("Location: feed.php?usuario=$usuario");
    }
?>

<!DOCTYPE html>
<!--
	https://php.eduardokraus.com/upload-de-imagens-com-php
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title>index</title>
        <link rel="stylesheet" type="text/css" href="css/style.css" />
    </head>
    <body>
        <header>
            <div>
               <form method="post" action="validaLogin.php">
                    <small>
                        <label for="username" >
                            Usuário:
                        </label>
                   </small>
                   <input type="text" name="username" value="<?php if(!(empty($_GET['user']))) echo $_GET['user']; ?>"/>
                   <small>
                       <label for="senha" >
                           Senha:
                       </label>
                   </small>
                   <input type="password" name="senha"/>
                    <input id="submeter" type="submit" value="Login"/>
                </form>
				<p><?php if(!(empty($_GET['erro']))) echo $_GET['erro']; ?></p>
            </div>
        </header>
        <container>  
            <figure><img alt="logo" src="<?php include 'validaDados.php'; echo logo(); ?>"/></figure>
            <h2>The zuera never ends&trade;!</h2>
            <?php
                    if(!empty($_GET['erro'])){
                        if($_GET['erro'] == "error")
                            echo    '<p
                                        style = "
                                            background-color: red;
                                            text-align: center;
                                            font-size: 20px;
                                            font-weight: bold;
                                            justify-content: center;
                                            padding-bottom: 30px;
                                        ">'.'<br>Algo deu errado<br>Realize seu cadastro novamente'.'</p>';
					}
					else
						echo    '<p
									style = "
										background-color: green;
										text-align: center;
										font-size: 20px;
										font-weight: bold;
										justify-content: center;
										padding-bottom: 30px;
									">'.'<br>Parabéns, agora é só completar a senha acima'.'</p>';
                ?>
            <section>
                <h3>
                    <a href="cadastro.php">
                        CADASTRO
                    </a>
				</h3>
                
            </section>
        </container>
            <footer>
                <?php
					$footer = footer();
					echo $footer;
				?>
            </footer>
    
    </body>

</html>       
