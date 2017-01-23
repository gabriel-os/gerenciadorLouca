<?php

require_once 'include/DB_Functions.php';
$db = new DB_Functions();


$resposta = array("error" => FALSE);

if (isset($_POST['usuarioUID']) && isset($_POST['grupoUID']) && isset($_POST['dataLavada']) && isset($_POST['diaCerto']) && isset($_POST['itens']) 
	&& isset($_POST['observacao']) && isset($_POST['observacaoAdm'])) {

    
    $usuarioUID = $_POST['usuarioUID'];
    $dataLavada = $_POST['dataLavada'];
    $diaCerto = $_POST['diaCerto'];
	$itens = $_POST['itens'];
	$observacao = $_POST['observacao'];
	$observacaoAdm = $_POST['observacaoAdm'];

        $usuario = $db->insereLouca($usuarioUID, $grupoUID, $dataLavada, $diaCerto, $itens, $observacao, $observacaoAdm);
	
	if ($usuario) {
			
            $resposta["error"] = FALSE;
			
            echo json_encode($resposta);
        } else {
            $resposta["error"] = TRUE;
            $resposta["error_msg"] = "Erro desconhecido no cadastro da louça!";
            echo json_encode($resposta);
        }
    
} else {
    $resposta["error"] = TRUE;
    $resposta["error_msg"] = "Verifique os dados preenchidos! (campo(s) vazio(s))";
    echo json_encode($resposta);
}
?>

