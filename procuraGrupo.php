<?php

require_once 'include/DB_Functions.php';
$db = new DB_Functions();

$resposta = array("error" => FALSE);

if (isset($_POST['grupoUID'])) {


    $grupoUID = $_POST['grupoUID'];

    $grupo = $db->procuraGrupo($grupoUID);

    if ($grupo != false) {
        
		$resposta["error"] = FALSE;
		$resposta["resposta"]= $grupo;
			
        echo json_encode($resposta);
    } else {
        $resposta["error"] = TRUE;
        $resposta["error_msg"] = "Nenhum grupo encontrado";
        echo json_encode($resposta);
    }
} else {

    $resposta["error"] = TRUE;
    $resposta["error_msg"] = "Nenhum número digitado";
    echo json_encode($resposta);
}
?>

