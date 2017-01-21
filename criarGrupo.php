<?php

require_once 'include/DB_Functions.php';
$db = new DB_Functions();

$resposta = array("error" => FALSE);

if (isset($_POST['usuarioUID']) && isset($_POST['nome'])) {


    $usuarioUID = $_POST['usuarioUID'];
    $nome = $_POST['nome'];

    $grupo = $db->criarGrupo($usuarioUID, $nome);

    if ($grupo != false) {
			
			$resposta["error"] = FALSE;
            $resposta["uid"] = $grupo["unique_index"];
            $resposta["grupo"]["nome"] = $grupo["nome"];
            $resposta["grupo"]["qntdPessoa"] = $grupo["qntdPessoa"];
			$resposta["grupo"]["qntdAdm"] = $grupo["qntdAdm"];
			
			echo json_encode($resposta);
    } else {

        $resposta["error"] = TRUE;
        $resposta["error_msg"] = "Algo deu errado :(\nQue tal tentarmos de novo?";
        echo json_encode($resposta);
    }
} else {

    $resposta["error"] = TRUE;
    $resposta["error_msg"] = "E-mail ou senha faltando!";
    echo json_encode($resposta);
}
?>

