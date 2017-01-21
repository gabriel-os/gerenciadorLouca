<?php

require_once 'include/DB_Functions.php';
$db = new DB_Functions();

$resposta = array("error" => FALSE);

if (isset($_POST['login']) && isset($_POST['senha'])) {


    $login = $_POST['login'];
    $senha = $_POST['senha'];

    $usuario = $db->login($login, $senha);

    if ($usuario != false) {

        $resposta["error"] = FALSE;
            $resposta["uid"] = $usuario["unique_index"];
            $resposta["usuario"]["nome"] = $usuario["nome"];
            $resposta["usuario"]["sobrenome"] = $usuario["sobrenome"];
			$resposta["usuario"]["email"] = $usuario["email"];
			$resposta["usuario"]["login"] = $usuario["login"];
			$resposta["usuario"]["grupoUID"] = $usuario["grupoUID"];
			$resposta["usuario"]["criadoEm"] = $usuario["criadoEm"];
			$resposta["usuario"]["atualizadoEm"] = $usuario["atualizadoEm"];
			
			if(!Empty($resposta["usuario"]["grupoUID"])){
				
				$grupo = $db->recuperaGrupo($resposta["usuario"]["grupoUID"]);
				
				$resposta["grupo"]["nome"] = $grupo["nome"];
				$resposta["grupo"]["qntdPessoa"] = $grupo["qntdPessoa"];
				$resposta["grupo"]["qntdAdm"] = $grupo["qntdAdm"];
				$resposta["grupo"]["criadoEm"] = $grupo["criadoEm"];
			}
			
        echo json_encode($resposta);
    } else {

        $resposta["error"] = TRUE;
        $resposta["error_msg"] = "Os dados estão incorretos. Por favor verifique!";
        echo json_encode($resposta);
    }
} else {

    $resposta["error"] = TRUE;
    $resposta["error_msg"] = "E-mail ou senha faltando!";
    echo json_encode($resposta);
}
?>

