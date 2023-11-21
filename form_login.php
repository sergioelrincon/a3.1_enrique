<?php


?>



<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Incia sesión </title>
    </head>
    <body>
        <form name="InciarSesión" method="POST" action="form.php">
            <fieldset>
                <legend>Inicia Sesion</legend>
                <label> Introduce tu correo: </label> <input  type="email" id="email" name="email" title="Se permiten como maximo 30 carácteres" pattern="[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}" size="30" required /><br>
                <lable> Introduce la contraseña: </label> <input  required type="password" name="Contrasena" id="Constrasena"><br>
                <button type="submit" name="EnviarInfo" value='1' id="EnviarInfo"> Enviar  </button>
            </fieldset>
        </form>
    </body>
</html>