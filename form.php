
<?php

if($_POST['EnviarInfo']){

    if($_POST['email'] && $_POST['Contrasena']){

        $email = trim($_POST['email']);
        $contrasena = trim($_POST['Contrasena']);



        $host = "localhost";
        $username = "mitiendaonline3";
        $password = "1234localhost";

        try{
            //Nos conectamos a la base de datos
            $conexion = new PDO("mysql:host=".$host.";dbname=mitiendaonline2", $username, $password);
            $conexion -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            echo "Conexion realizada con exito";

            //Obtenemos los registros
            $boolcorreo = false;
            $boolcontra = false;
            $objetstmt = $conexion -> query("SELECT * FROM usuarios2");
            while ($row =$objetstmt -> fetch()){
                if($email == $row['correo_electronico']){
                    $boolcorreo = true;
                }
                if (password_verify($contrasena, $row['contrasena_hash'])){
                    $boolcontra = true;
                }
            }

            if($boolcorreo && $boolcontra){

                echo "<p style='color:blue;' > Los datos proporcionados son correctos </p>";

            }else if($boolcorreo){

                echo "<p  style='color:blue;'> El correo es correcto </p>";
                echo "<p  style='color:red;'> La contraseña es incorrecta </p>";

            }else if($boolcontra){

                echo "<p  style='color:red;'> El correo es incorrecto </p>";
                echo "<p  style='color:blue;'> La contraseña es correcta </p>";

            }else{

                echo "<p  style='color:red;'> Ambos datos introducidos son incorrectos </p>";

            }
        }catch(PDOException $e){
            echo "Conexión fallida " . $e -> getMessage();
        }

    }
    else{
        echo "<p style='color:red;'> Los datos no se han rellenado </p>"; 
    }
}
?>