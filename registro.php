<?php

require_once 'include/DB_Functions.php';
$db = new DB_Functions();


$resposta = array("error" => FALSE);

if (isset($_POST['nome']) && isset($_POST['sobrenome']) && isset($_POST['email']) && isset($_POST['login']) && isset($_POST['senha'])) {

    
    $nome = $_POST['nome'];
    $sobrenome = $_POST['sobrenome'];
    $email = $_POST['email'];
	$login = $_POST['login'];
	$senha = $_POST['senha'];

        $usuario = $db->registraUsuario($nome, $sobrenome, $email, $login, $senha);
		if($usuario == "email"){
			
			$resposta["error"] = TRUE;
            $resposta["error_msg"] = "Já existe um usuário cadastrado com o e-mail " . $email;
			
            echo json_encode($resposta);
			
        }else if ($usuario == "login"){
			
			$resposta["error"] = TRUE;
            $resposta["error_msg"] = "Já existe um usuário cadastrado com o login " . $login;
			
            echo json_encode($resposta);
		}else if ($usuario == "uid"){
			$resposta["error"] = TRUE;
            $resposta["error_msg"] = "Algo deu errado :(\nQue tal tentarmos de novo?";
			
            echo json_encode($resposta);
		}else if ($usuario) {
			
            $resposta["error"] = FALSE;
            $resposta["uid"] = $usuario["unique_index"];
            $resposta["usuario"]["nome"] = $usuario["nome"];
            $resposta["usuario"]["sobrenome"] = $usuario["sobrenome"];
			$resposta["usuario"]["email"] = $usuario["email"];
			$resposta["usuario"]["login"] = $usuario["login"];
			$resposta["usuario"]["criadoEm"] = $usuario["criadoEm"];
			$resposta["usuario"]["atualizadoEm"] = $usuario["atualizadoEm"];
			
            echo json_encode($resposta);
        } else {
            $resposta["error"] = TRUE;
            $resposta["error_msg"] = "Erro desconhecido no cadastro!";
            echo json_encode($resposta);
        }
    
} else {
    $resposta["error"] = TRUE;
    $resposta["error_msg"] = "Verifique os dados preenchidos! (campo(s) vazio(s))";
    echo json_encode($resposta);
}
?>

