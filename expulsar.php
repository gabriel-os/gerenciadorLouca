<?php

require_once 'include/DB_Functions.php';
$db = new DB_Functions();

$resposta = array("error" => FALSE);

if (isset($_POST['usuarioUID']) && isset($_POST['grupoUID'])) {


    $usuarioUID = $_POST['usuarioUID'];
    $grupoUID = $_POST['grupoUID'];

    $grupo = $db->sairGrupo($usuarioUID, $grupoUID);

    if ($grupo != false) {
			
			$resposta["error"] = FALSE;
			
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

