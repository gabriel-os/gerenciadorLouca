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

		$resposta["error"] = FALSE;
		$resposta["resposta"]= $louca;
			
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

