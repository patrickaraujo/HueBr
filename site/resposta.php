<?php
	ini_set('default_charset', 'UTF-8');
	if(isset($_POST['send'])){
		
		$username = $_POST['username'];
		$id = $_POST['id'];
		$senha=$_POST['senha'];
		$confirm=$_POST['ssenha'];

		if(empty($senha)){
			$erro = 'A senha tem q ser preenchida!';
		}
		else if($senha != $confirm){
			$erro = 'As senhas tem q ser iguáis, imbecil!';
		}
		
		else{
			  
			echo $alteracaoCadastro = date('Y-m-d H:i:s');
			include "resposta2.php";
			$sql = "UPDATE cadastro SET senha='$senha', lastUpdate='$alteracaoCadastro' WHERE id='$id'";
			
			$resultado = mysqli_query($conexao, $sql) or die("Não foi possível executar o SQL: ".mysqli_error($conexao));;
			  
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
			
		}
		
		if(isset($erro)):
			echo $erro.'<br />';
		endif;
		
	}
	else if(isset($_POST['delete'])){
		$id = $_POST['id'];
		include "resposta2.php";
		$sql = "DELETE FROM cadastro WHERE id='$id'";
			
		$resultado = mysqli_query($conexao, $sql) or die("Não foi possível executar a SQL: ".mysqli_error($conexao));

		if($resultado){
			echo	'
						<script type="text/javascript">
							alert("Sucesso");
						</script>
					';
		}
		else{
			echo	'
						<script type="text/javascript">
							alert("Fracasso");
						</script>
					';
		}
		mysqli_close($conexao);
	}
	
?>

<!DOCTYPE html>
<html>

    <head>
        <meta charset="utf-8"/>
        <title>
			Cadastro
		</title>
        <link rel="stylesheet" type="text/css" href="style.css" />
    </head>

    <body>
		<?php
			include "read.php";
			$username = $_GET['username'];
			$id = buscaId($username);
		?>
		<form method="post" enctype="multipart/form-data" action="">
            <p>Cadastro</p>

			<p>
				<label for="senha" >
					Senha:
				</label>
				<input type="password" name="senha" id ="senha"/>
			</p>
			<br>
            <p>
				<label for="ssenha" >
					Confirme a senha:
				</label>
				<input type="password" name="ssenha" id ="ssenha"/>
			</p>
			<br>
            <input type="hidden" name="id" value="<?php echo $id;?>"/>
            <input type="hidden" name="username" value="<?php echo $username;?>"/>
            <p>
				<input type="submit" value="Alterar" name="send"/>
			</p>
			<p>
				Excluir cadastro
				<input type="submit" value="Excluir" name="delete"/>
			</p>
        </form>

    </body>
</html>