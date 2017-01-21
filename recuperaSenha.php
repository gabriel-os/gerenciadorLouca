<?php
require_once 'include/DB_Functions.php';
$db = new DB_Functions();

$response = array("error" => FALSE);

if (isset($_POST['email'])) {

    $email = $_POST['email'];
    
		if ($db->isUserExisted($email)) {
			if (!$db->checkRequest($email, $uid)){
		$user = $db->sendEmailPass($email);
			
            $response["error"] = FALSE;
            $response["msg"] = $user;
            echo json_encode($response);
			}else{
			$response["error"] = TRUE;
            $response["error_msg"] = "Já há uma solicitação em andamento, verifique seu e-mail";
            echo json_encode($response);
				
			}
			}else{
				$response["error"] = TRUE;
				$response["error_msg"] = "Não há nenhum cadastro com esse e-mail :(";
				echo json_encode($response);
			}
			}else {
    $response["error"] = TRUE;
    $response["error_msg"] = "Erro no envio, tente novamente!";
    echo json_encode($response);
}
?>
