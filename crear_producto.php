<?php

include "funciones_validacion.php";


//Comprobar que la  ha dado al boton enviar.

if(isset($_POST['Enviar'])){

    //Comprobamos que los campos han sido rellenado.
    if(isset($_POST['nombre_producto']) && isset($_POST['precio_producto']) && isset($_POST['categoria_producto'])){

        $nombre_producto = trim($_POST['nombre_producto']);
        $precio_producto = floatval($_POST['precio_producto']);         
        $fichero_temporal = $_FILES['imagen_producto']['tmp_name'];
        $fichero_subido = $_FILES['imagen_producto']['name'];
        $categoria_producto = intval(($_POST['categoria_producto']));
        $booleano = true;

        
        $resultado = "";

        //¿Se podria optimizar con un bucle?

        if(empty($nombre_producto)){
            $resultado .= "<p class='error'> El nombre del producto esta vacío </p>";
            $booleano = false;
        }
        else if(validar_nombre($nombre_producto)){
            $resultado .= "<p class='correcto' > El nombre del producto se ha validado correctamente </p>";
        }else{
            $resultado .= "<p class='error'> El nombre del producto no es correcto </p>";
            $booleano = false;
        }

        if(empty($precio_producto)){
            $resultado .= "<p class='error'> El precio del producto esta vacío </p>";
            $booleano = false;
        }
        else if(validar_precio($precio_producto)){
            $resultado .= "<p class='correcto' > El precio del producto se ha validado correctamente </p>";
        }else{
            $resultado .= "<p class='error'> El precio del producto no es correcto </p>";
            $booleano = false;
        }

        if (move_uploaded_file($fichero_temporal, 'imagenes/'.$fichero_subido)) {
            if(validar_imagen($_FILES['imagen_producto']['name'])){
                $resultado .= "<p class='correcto'> La imagen se ha validado correctamente </p>";
            }else{
                $resultado .= "<p class='error'>El nombre de la imagen no es correcto</p>";
                $booleano = false;
            }
        } else {
            $resultado .= "<p class='error'> No se ha subido ninguna imagen. </p>";
            $booleano = false;
        }


        

        //si lo datos se han validado correctamente nos comnectamos a la base de datos para insertar los datos.
        if($booleano){

            //mostramos que todo ha ido bien;
            echo $resultado;

            
            $servername = "localhost";
            $username ="mitiendaonline3";
            $passwd = "1234localhost";
            
            //creamos la conexion

            try{
                $conexion = new PDO("mysql:host=".$servername.";dbname=mitiendaonline2", $username, $passwd);
                $conexion -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                echo "Se ha conectado exitosamente a la base de datos<br>";

                
                $sql = "INSERT INTO productos (Nombre, Precio,Imagen, Categoría) 
                VALUES ('".$nombre_producto."','". $precio_producto."','". $fichero_subido."','". $categoria_producto."')";

                $conexion->exec($sql);

                echo "Datos almacenados correctamente";

                //cerramos la conexion
                $conexion = null;                

            }catch (PDOException $e){
                echo "Conexion fallida " . $e ->getMessage();
            }


            //link para volver al menú;
            echo "<br><span>Los datos se han cumplimentado correctamente, vuelve al menu principal: <a href='menu.php'> Volver al menu  </a> </span>";


        //si los datos estan mal validados se muestra cuales están mal y se ofrece un enlace para volver a rellenarlos por tanto
        //no nos conectamos a la base de datos
        }else{


            //mostramos información de los campos erroneos, no se envia la información;
            echo $resultado;
            echo "Vuelve atrás y completa los datos correctamente: <a href='crear_producto.php'> Volver atrás </a>";
            //link para volver a atrás y completar otra vez;


        }
    }

    

}

?>


<!DOCTYPE html>

<html lan="en">
    <head>
        <title> Formulario para crear producto </title>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="crear_producto.css">
    </head>
    <body>
        <h1> Formulario para crear Producto</h1>
        <div>
            <form name="formulario" action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="post" enctype="multipart/form-data">
                <label>Introduce un nombre: </label><input placeholder="nombre" type="text" name="nombre_producto" id="nombre_producto">
                <br>
                <label>Introduce el precio en €: </label><input type="number" placeholder="precio" name="precio_producto" min="0.1" step="any" id="precio_producto">
                <br>
                <!--<input type="hidden" name="MAX_FILE_SIZE" value="30000" /> -->
                <label>Introduce una imagen para el producto: </label> <input type="file" name="imagen_producto" id="imagen_producto">
                <br>
                <label>Selecciona la categoría del producto: </label> 
                <select id="categoria_producto" name="categoria_producto">
                    <option value="1"> Deportivo </option>
                    <option value="2"> Diver </option>
                    <option value="3"> Clasico </option>
                    <option value="4"> Casual </option>
                </select>
                <br>
                <button type="submit" name="Enviar"> Enviar Formulario </button>
            </form>
        </div>
    </body>

</html>