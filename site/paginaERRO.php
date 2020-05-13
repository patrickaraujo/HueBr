<!DOCTYPE html>
<html>
	<head>
		<title>Aula 08</title>
		<meta charset="UTF-8"/>
        <link rel="Stylesheet" href="style.css" type="text/css"/>
	</head>
	<body>
        <header>
            <form action="verificaLogin.php" method="post">
                <legend>LOGIN</legend>
                <p>Nome:<br/><input type="text" name="nome" /></p>
                <p>Senha:<br/><input type="password" name="senha" /></p>
                <p><input id="submeter" type="submit" value="Login" /></p>
            </form> 
        </header>
        <container>  
            <figure><img alt="logo" src="img/logo.png"/></figure>
            <h2>The zuera never ends, graças a Deus&trade;!</h2>
            <section>
                <h1>"YOU SHALL NOT PASS!!"</h1>
                <h2>- - - SENHA INCORRETA - - -</h2>
                <h3>Cadastre-se:</h3>
                <form onsubmit="cadastro(this); return false;" method="POST">
                    <div class="formbox">
                        <span class="left">Usuário:</span><input type="text" class="right" name="nome"/>
                        <span class="left">Senha:</span><input type="password" class="right" name="password"/>
                        <span class ="left"><input id="send" type="submit" value="Enviar"/></span><br/>
                    </div>
                </form>
            </section>
            <footer>
                <nav>
                    <ul>
                        <li><a href="index.php">Casa</a>|</li>
                        <li><a href="humor.php">Humor</a>|</li>
                        <li><a href="about.php">Sobre</a>|</li>
                        <li><a href="contato.php">Contato</a>|</li>
                        <li><a href="cadastro.php">Cadastro</a></li>
                    </ul>
                </nav>
                Copyright &copy; Patrick, Bruno e Guilherme 2015
            </footer>
        </container>  
	</body>
</html>