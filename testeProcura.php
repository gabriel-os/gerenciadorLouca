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
        <FORM NAME="form1" METHOD="post" ACTION="procuraGrupo.php">
LOGIN
Campo 1 (grupoUID):

<INPUT TYPE="text" NAME="grupoUID">

<BR>

<INPUT TYPE="submit" VALUE="Enviar">

</FORM>

    </body>
</html>
