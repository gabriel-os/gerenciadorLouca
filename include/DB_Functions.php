<?php

header('Content-Type: text/html; charset=utf-8');

class DB_Functions {

    private $conn;

    function __construct() {
        require_once 'DB_Connect.php';
        $db = new Db_Connect();
        $this->conn = $db->connect();
    }

    function __destruct() {
        
    }

	public function registraUsuario($nome, $sobrenome, $email, $login, $senha) {

        if (empty($nome) || empty($sobrenome) || empty($email) || empty($login) || empty($senha)) {
            return "Verifique seus dados!!";
        } else {
            $uid = uniqid('', true);
            $hash = $this->hashSSHA($senha);
            $senha_encriptada = $hash["encrypted"];
            $salt = $hash["salt"];
			$falha = 0;
			$trocaSenha = 0;
            $query = "INSERT INTO tbUsuario(unique_index, nome, sobrenome, email, login, senha, salt, criadoEm) VALUES('$uid', '$nome', '$sobrenome','$email', '$login', '$senha_encriptada', '$salt', NOW())";
            $resul = mysqli_query($this->conn, $query);
			
			//Código 1062 == Duplicate Key
			if( mysqli_errno($this->conn) == 1062){
				
				$error = mysqli_error($this->conn);
			
				if(strpos($error, "login")){
						return "login";
				
				}else if(strpos($error, "email")){
					return "email";
					
				}else if(strpos($error, "unique_index")){
					return "uid";
					
				}
			}
            if ($resul) {
                $query = "SELECT * FROM tbUsuario WHERE email = '$email'";
                $resul = mysqli_query($this->conn, $query);
                $row = mysqli_fetch_array($resul);
                return $row;
            }
        }
        mysqli_close($this->conn);
    }
	
    public function login($login, $senha) {

        $query = "SELECT * FROM tbUsuario WHERE login = '$login'";

        if ($resul = mysqli_query($this->conn, $query)) {
            $row = mysqli_fetch_array($resul);
            $hash = $this->checkhashSSHA($row['salt'], $senha);
            if ($row['senha'] == $hash) {
                return $row;
            }
        } else {
            return null;
        }
		mysqli_close($this->conn);
    }
	
	public function entrarGrupo($usuarioUID, $grupoUID){
		
		$queryUsuario = "UPDATE tbUsuario set grupoUID = '$grupoUID' WHERE unique_index = '$usuarioUID'";
		$queryGrupo = "UPDATE tbGrupo set qntdPessoa = qntdPessoa+1 WHERE unique_index = '$grupoUID'"; 
		
		$resulUsuario = mysqli_query($this->conn, $queryUsuario);
		
		if($resulUsuario){
	
			$resulGrupo = mysqli_query($this->conn, $queryGrupo);
			
			if($resulGrupo){

				$query = "SELECT * FROM tbGrupo WHERE unique_index = '$grupoUID'";
				$resul = mysqli_query($this->conn, $query);
				
				return TRUE;
			}
			
		}
		mysqli_close($this->conn);
		
	}
	
	public function criarGrupo($usuarioUID, $nome){

		$uid = rand(100000,999999);
	
		$query = "INSERT INTO tbGrupo (unique_index, nome, qntdPessoa, qntdAdm, criadoEm) VALUES ('$uid', '$nome', '1', '1', NOW())";
		$queryUsuario = "UPDATE tbUsuario set grupoUID = '$uid', admin = '1' WHERE unique_index = '$usuarioUID'";
		
		$resul = mysqli_query($this->conn, $query);
		
		if($resul){
			$query = "SELECT * FROM tbGrupo WHERE unique_index = '$uid'";
			$resul = mysqli_query($this->conn, $query);
			if($resul){
			$row = mysqli_fetch_array($resul);
			return $row;
		}
			$resulUsuario = mysqli_query($this->conn, $queryUsuario);
			
		}
		mysqli_close($this->conn);
	}
	
	public function recuperaGrupo($grupoUID){
		$query = "SELECT * FROM tbGrupo WHERE unique_index = '$grupoUID'";
        $resul = mysqli_query($this->conn, $query);
		
		if($resul){
        $row = mysqli_fetch_array($resul);
        return $row;
		}
	}
	
	public function sairGrupo($usuarioUID, $grupoUID){
		
		$queryUsuario = "UPDATE tbUsuario set grupoUID = null WHERE unique_index = '$usuarioUID'";
		$queryGrupo = "UPDATE tbGrupo set qntdPessoa = qntdPessoa-1 WHERE unique_index = '$grupoUID'"; 
		
		$resulUsuario = mysqli_query($this->conn, $queryUsuario);
		
		if($resulUsuario){
	
			$resulGrupo = mysqli_query($this->conn, $queryGrupo);
			
			if($resulGrupo){

				$query = "SELECT * FROM tbGrupo WHERE unique_index = '$grupoUID'";
				$resul = mysqli_query($this->conn, $query);
				
				return TRUE;
			}
			
		}
		mysqli_close($this->conn);
	}
	
	public function tornarAdm($usuarioUID, $grupoUID){
		
		$queryUsuario = "UPDATE tbUsuario set admin = 1 WHERE unique_index = '$usuarioUID'";
		$queryGrupo = "UPDATE tbGrupo set qntdAdm = qntdAdm+1 WHERE unique_index = '$grupoUID'"; 
		
		$resulUsuario = mysqli_query($this->conn, $queryUsuario);
		
		if($resulUsuario){
	
			$resulGrupo = mysqli_query($this->conn, $queryGrupo);
			
			if($resulGrupo){

				$query = "SELECT * FROM tbGrupo WHERE unique_index = '$grupoUID'";
				$resul = mysqli_query($this->conn, $query);
				
				return TRUE;
			}
			
		}
		mysqli_close($this->conn);
	}
	
	public function sairAdm($usuarioUID, $grupoUID){
		
		$queryUsuario = "UPDATE tbUsuario set admin = 0 WHERE unique_index = '$usuarioUID'";
		$queryGrupo = "UPDATE tbGrupo set qntdAdm = qntdAdm-1 WHERE unique_index = '$grupoUID'"; 
		
		$resulUsuario = mysqli_query($this->conn, $queryUsuario);
		
		if($resulUsuario){
	
			$resulGrupo = mysqli_query($this->conn, $queryGrupo);
			
			if($resulGrupo){

				$query = "SELECT * FROM tbGrupo WHERE unique_index = '$grupoUID'";
				$resul = mysqli_query($this->conn, $query);
				
				return TRUE;
			}
			
		}
		mysqli_close($this->conn);
		
	}

	public function verLouca($usuarioUID, $grupoUID){
		
		$query = "SELECT `tblavado`.`usuarioUID`, `tblavado`.`dataLavada`, `tblavado`.`diaCerto`, `tblavado`.`id`, `tblavado`.`itens`, `tblavado`.`observacao`, 
		`tblavado`.`observacaoAdm`, `tbusuario`.`nome` FROM `tblavado` 
		INNER JOIN `tbusuario` ON `tblavado`.`usuarioUID` = `tbusuario`.`unique_index`  
		WHERE`tblavado`.`usuarioUID` = '$usuarioUID' AND `tblavado`.`grupoUID` = '$grupoUID'";
		
		$resul = mysqli_query($this->conn, $query);
		
		$resposta = array();
        $resposta["rec"] = array();
        $resposta["rec"] ["cont"] = 0;
        $resul = mysqli_query($this->conn, $query);

        while ($row = mysqli_fetch_array($resul)) {
		
            $resposta ["rec"] ["cont"] ++;
            $tmp = array();
            $tmp["usuarioUID"] = $row["usuarioUID"];
            $tmp["dataLavada"] = $row["dataLavada"];
            $tmp["diaCerto"] = $row["diaCerto"];
            $tmp["id"] = $row["id"];
            $tmp["itens"] = $row["itens"];
            $tmp["observacao"] = $row["observacao"];
            $tmp["observacaoAdm"] = $row["observacaoAdm"];
			
            array_push($resposta["rec"], $tmp);
			}
			return $resposta;
		mysqli_close($this->conn);
	}
	
	public function procuraGrupo($grupoUID){
		
		/*"SELECT `tbgrupo`.`nome` AS 'nomeGrupo', `tbgrupo`.`qntdPessoa`,`tbgrupo`.`qntdAdm`, `tbgrupo`.`criadoEm`,`tbusuario`.`nome`AS 'nomeUsuario,
		`tbusuario`.`admin` FROM `tbgrupo` 
		INNER JOIN `tbusuario` ON `tbgrupo`.`unique_index` = `tbusuario`.`grupoUID`
		WHERE`tbgrupo`.`unique_index` = '$grupoUID'
        ORDER BY `tbusuario`.`admin` DESC, `tbusuario`.`nome` ASC"*/
		
		$query = "SELECT * FROM tbGrupo WHERE unique_index = '$grupoUID'";
		
		$resul = mysqli_query($this->conn, $query);
	
		if($resul){
			
			$resposta = array();
			$grupo = array();
			$resposta["rec"] = array();
			$resposta["rec"] ["cont"] = 0;
			
			$temp = mysqli_fetch_array($resul);
			
			$grupo["nome"] = $temp["nome"];
			$grupo["qntdPessoa"] = $temp["qntdPessoa"];
			$grupo["qntdAdm"] = $temp["qntdAdm"];
			$grupo["criadoEm"] = $temp["criadoEm"];
			
			$resposta["rec"]["grupo"] = $grupo;
			
			$query = "SELECT `tbUsuario`.`nome`, `tbUsuario`.`admin` FROM tbUsuario WHERE grupoUID = '$grupoUID' ORDER BY admin DESC, nome ASC";
			
			$result = mysqli_query($this->conn, $query);

			while ($row = mysqli_fetch_array($result)) {
		
				$resposta ["rec"] ["cont"] ++;
				$tmp = array();
				$tmp["nome"] = $row["nome"];
				$tmp["admin"] = $row["admin"];
			
				array_push($resposta["rec"], $tmp);
			}
		}
		return $resposta;
		mysqli_close($this->conn);
		}
		
	public function verificaEmail($email) {
        $stmt = $this->conn->prepare("SELECT email from tbUsuario WHERE email = ?");

        $stmt->bind_param("s", $email);

        $stmt->execute();

        $stmt->store_result();

        if ($stmt->num_rows > 0) {

            return true;
        } else {

            return false;
        }
        mysqli_close($this->conn);
    }
	
    public function changePassword($uid, $newPassword) {
        
        if (empty($uid) || empty($newPassword)) {
            return "Verifique seus dados!!";
        } else {
            $hash = $this->hashSSHA($newPassword);
            $senha_encriptada = $hash["encrypted"];
            $salt = $hash["salt"];
            $query = "update tbUsuario set senha_encriptada =  \"$senha_encriptada\", salt = \"$salt\" where unique_index = \"$uid\"";
            $resul = mysqli_query($this->conn, $query);
            if (mysqli_affected_rows($this->conn) > 0) {
                $query = "update tbUsuario set trocaSenha = false where unique_index = '$uid'";
                mysqli_query($this->conn, $query);
                return true;
            } else {
                return false;
            }
        }
        mysqli_close($this->conn);
    }

    public function sendEmailPass($email) {
        $query = "SELECT * FROM tbUsuario WHERE email = '$email'";

        $resul = mysqli_query($this->conn, $query);
        $row = mysqli_fetch_array($resul);
		
        $nome = $row['nome'];
		$uid = $row['unique_index'];

        if (!$this->sendMailAuth("",$uid, $email, $nome, "", "","", "", "", "","")) {
            return "Erro no envio, tente novamente mais tarde!";
        } else {
            $query = "update tbUsuario set trocaSenha = true where email = '$email'";
            mysqli_query($this->conn, $query);
            return "Email enviado com sucesso, aguarde na sua caixa de entrada!";
        }
        mysqli_close($this->conn);
    }

    public function checkRequest($email, $uid) {

        if ($email == null) {
            $query = "SELECT trocaSenha from tbUsuario WHERE unique_index = '$uid' and trocaSenha = true";

            $resul = mysqli_query($this->conn, $query);

            if (mysqli_num_rows($resul) != 0) {
                return true;
                mysql_close($this->conn);
            } else {
                return false;
                mysql_close($this->conn);
            }
        } else if ($uid == null) {
            $query = "SELECT trocaSenha from tbUsuario WHERE email = '$email' and trocaSenha = true";

            $resul = mysqli_query($this->conn, $query);

            if (mysqli_num_rows($resul) != 0) {
                return true;
                mysql_close($this->conn);
            } else {
                return false;
                mysql_close($this->conn);
            }
        }
    }
	
	public function sendMailAuth($codUnico ,$uid, $email, $nome, $nomeAnimal, $servico, $adicional, $local, $dataHora, $preco, $nomePetshop){
				require_once('phpmailer/class.phpmailer.php');
				
		if(empty($nomeAnimal) || empty($servico) || empty($local) || empty($dataHora) || empty($preco) ){
			
			$mensagem = '<html>
<head>
    <title> Recuperar senha </title>
    <meta charset="UTF-8">
</head>
<body style="background-color: #1F9561;">

    <img src="http://i.imgur.com/wKEqfg0.png" height="30%" width="15%" style="margin-left:42%; margin-bottom: 2%;">
           
    <div style="text-align: center; font-size: 25px;  border-radius: 20px; width: 50%; height: auto; margin: 0 auto; font-family:Century Gothic; background-color: #eee;"
<p>
<br> <b> Caro (a) '.$nome.',</b><br /> Parece que esqueceu sua senha! <br/>Altere sua senha <a href="http://android.nexpetapp.com.br/recoveryPassword.php?uid='.$uid.'" style="text-decoration: none;">clicando aqui!</a><br /></p>
<p>
<br>
Atencionsamente,
<br /><b> Equipe NexPet</b></p>
</p>
<br>
    </div>
</body>
</html>';
			
			$subject = 'Recuperação de senha';
		}else {
			
			$mensagem = '<html>
<head>
    <title> Comprovante de serviço </title>
    <meta charset="UTF-8" media="print">
</head>
<body style="background-color: #1F9561;">
           
    <div style="margin-bottom: 100px; text-align: center; font-size: 22px;  border-radius: 20px; width: 50%; height: auto; margin: 0 auto; font-family:Century Gothic; background-color: #eee;"
<p>
    <img src="http://nexpetapp.com.br/img/logo.png" height="70" width="70" style= "margin-top: 2% ;  margin-bottom: 2%">
    <table style="text-align: center;margin: 0 auto; font-size: 25px; font-family:Century Gothic;">
      <tr>
     <td>
     <td><b> Olá '.$nome.',</b> <br> Você agendou o seguinte serviço: <td>
    <tr>
    </table>
    <br>
     <div style="text-align:justify;background-color: #D3D3D3; width: 90%; height: auto;margin: 0 auto"> 
<b> Nome do animal:</b> '.$nomeAnimal.' <br>
<b> Tipo do serviço:</b> '.$servico.' <br> 
<b> Serviço adicional:</b> '.$adicional.' <br>
<b> Local:</b> '.'Happy pet'.' <br>
<b> Data e Hora:</b> '.$dataHora.'<br>
<b> Preço total:</b> R$'.$preco.'0</br>
</div>
<br>CONFIRME as informações do serviço e APRESENTE este comprovante no local agendado!</br>
<br>
<br>
<div style="background-color: #D3D3D3; width: 50%; height: auto; text-align: center;  margin: 0 auto">
'.$codUnico.' <br>
    </div>
    <br>
Atencionsamente,
<br /><b> Equipe NexPet</b></p>
</p>
<br>
    </div>
</body>
</html>';
						
						$subject = 'Comprovante de serviço';
		}
	require_once('phpmailer/PHPMailerAutoload.php');
$mail = new PHPMailer();

//$body             = file_get_contents('contents.html');
//$body             = eregi_replace("[\]",'',$body);

$mail->IsSMTP();
$mail->SMTPDebug  = 0;                     
                                           
                                           
$mail->SMTPAuth   = true;                  
$mail->SMTPSecure = "ssl";

$mail->Host       = "mx1.hostinger.com.br"; 
$mail->Port       = 465;                    
$mail->Username   = "suporte@nexpetapp.com.br"; 
$mail->Password   = "V&!H9y1bWYhp0487`n";

$mail->SetFrom('suporte@nexpetapp.com.br', 'NexPet');

$mail->CharSet = "UTF-8";
//$mail->AddReplyTo("teste@casacerta.in","First Last");

$mail->Subject    = $subject;

$mail->AltBody    = "Utilize o navegador para vizualizar melhor este e-mail"; // optional, comment out and test

$mail->MsgHTML($mensagem);

$mail->AddAddress($email, $nome);

//$mail->AddAttachment("images/phpmailer.gif");      // attachment
//$mail->AddAttachment("images/phpmailer_mini.gif"); // attachment

if(!$mail->Send()) {
	echo "Mailer Error: " . $mail->ErrorInfo;
	return false;
} else {
	return true;
}
		
	
	}
	
    public function hashSSHA($password) {
        $salt = sha1(rand());
        $salt = substr($salt, 0, 10);
        $encrypted = base64_encode(sha1($password . $salt, true) . $salt);
        $hash = array("salt" => $salt, "encrypted" => $encrypted);
        return $hash;
    }

    public function checkhashSSHA($salt, $password) {

        $hash = base64_encode(sha1($password . $salt, true) . $salt);

        return $hash;
    }

}
?>