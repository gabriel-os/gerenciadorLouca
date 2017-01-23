<?php

require_once 'include/DB_Functions.php';
$db = new DB_Functions();

$resposta = array("error" => FALSE);

if (isset($_POST['usuarioUID']) && isset($_POST['grupoUID'])) {


    $usuarioUID = $_POST['usuarioUID'];
    $grupoUID = $_POST['grupoUID'];

    $louca = $db->verLouca($usuarioUID, $grupoUID);

    if ($louca != false) {
        
		$resposta["error"] = FALSE;

		$resposta["resposta"]= $louca;
			
        echo json_encode($resposta);
    } else {
        $resposta["error"] = TRUE;
        $resposta["error_msg"] = "Nenhum registro encontrado";
        echo json_encode($resposta);
    }
} else {

    $resposta["error"] = TRUE;
    $resposta["error_msg"] = "Informação faltando!";
    echo json_encode($resposta);
}
?>

