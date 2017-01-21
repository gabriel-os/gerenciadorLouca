<?php 
require_once 'include/DB_Functions.php';
$db = new DB_Functions();
?>
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        <FORM NAME="form1" METHOD="post" ACTION="criarGrupo.php">
CRIAR GRUPO
Campo 1 (usuarioUID):

<INPUT TYPE="text" NAME="usuarioUID">

<BR>
Campo 2 (nome):

<INPUT TYPE="text" NAME="nome">

<BR>



<INPUT TYPE="submit" VALUE="Enviar">

</FORM>

    </body>
</html>
