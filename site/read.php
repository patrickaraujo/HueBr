<?php
	function buscaSenha($username){
		//1. Criar conexão
		include "validaDados.php";

		//2. Criar e executar a query SQL
        $con = conectaBD();
		$sql_AU = "SELECT senha FROM usuario WHERE usuario='$username'"; // achar usuário
		$resultado = mysqli_query($con, $sql_AU) or die("Não foi possível executar a SQL: ".mysqli_error($con));
			
		//3. Mostra os resultados
		
		$senha = "";
        
        if($resultado){
			while ($row = mysqli_fetch_array($resultado)){
				$senha = $row['senha'];
			}
		}
		
		else{
			echo "Nenhum usuário encontrado";
		}
			
		//4. Fechar conexão
		mysqli_close($conexao);
		
		return $senha;
}

function buscaImage($image){
		//1. Criar conexão
		include "conexao.php";

		//2. Criar e executar a query SQL
		$sql = "SELECT imagens FROM cadastro WHERE imagens='$image'";			
		$resultado = mysqli_query($conexao, $sql) or die("Não foi possível executar a SQL: ".mysqli_error($conexao));
			
		//3. Mostra os resultados
		$image = "";
        if($resultado){
			while ($row = mysqli_fetch_array($resultado)){
				$image = $image['imagens'];
			}
		}
		
		else{
			echo "nenhum usuário encontrado";
		}
			
		//4. Fechar conexão
		mysqli_close($conexao);
		
		return $image;
}
/*
function buscaPost($username){
	
        $id = buscaId($username);

        //1. Cria conexão
        include "conexao.php";

        //2. Cria query
        $sql = "UPDATE cadastro SET senha='$psw' WHERE id='$id'";
        $resultado = mysqli_query($conexao, $sql) or die("Não foi possível executar a SQL: ".mysqli_error($conexao));

        //3. Mostra os resultados
        $cont = 0;
        $posts = [];
        if($resultado)
        {
            while($row = mysqli_fetch_array($resultado)){
                $posts[$cont] = $row['texto'];
                $cont++;
            }
        } else {
            echo "nenhum post encontrado";   
        }

        //4. Fecha a conexão
        mysqli_close($conexao);
        
        return $posts;
        
    }
?>
*/