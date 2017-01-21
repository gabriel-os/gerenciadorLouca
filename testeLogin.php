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
        <FORM NAME="form1" METHOD="post" ACTION="login.php">
LOGIN
Campo 1 (login):

<INPUT TYPE="text" NAME="login">

<BR>
Campo 2 (senha):

<INPUT TYPE="text" NAME="senha">

<BR>



<INPUT TYPE="submit" VALUE="Enviar">

</FORM>

    </body>
</html>
