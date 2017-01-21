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
        <FORM NAME="form1" METHOD="post" ACTION="registro.php">
REGISTRO
Campo 1 (nome):

<INPUT TYPE="text" NAME="nome">

<BR>
Campo 2 (sobrenome):

<INPUT TYPE="text" NAME="sobrenome">

<BR>

Campo 3 (email):

<INPUT TYPE="text" NAME="email">

<BR>

Campo 4 (login):

<INPUT TYPE="text" NAME="login">

<BR>

Campo 5 (senha):

<INPUT TYPE="text" NAME="senha">

<BR>


<INPUT TYPE="submit" VALUE="Enviar">

</FORM>

    </body>
</html>
