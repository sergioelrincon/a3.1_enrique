<?php 
/*
Modificar producto
Se implementará en el fichero “edita_producto.php”
Recibirá por GET el id del producto a modificar.
Si no lo recibe, mostrará al usuario un desplegable para que escoja el producto a modificar.
Mostrará los datos del producto en un formulario que el usuario podrá modificar y enviar a sí mismo para realizar la actualización de los datos en la tabla.
*/

include "funciones_validacion.php";

if(isset($_POST['Enviar'])){


    $id_producto = intval($_POST['id_modificar']);

    //¿validar la información permitida?
    if(isset($_POST['nombre_producto']) && isset($_POST['precio_producto']) && isset($_POST['categoria_producto'])){

        $nombre_nuevo = trim($_POST['nombre_producto']);
        $precio_nuevo = floatval($_POST['precio_producto']);         
        $fichero_temporal = $_FILES['imagen_producto']['tmp_name'];
        $fichero_subido = $_FILES['imagen_producto']['name'];
        $categoria_nueva = intval(($_POST['categoria_producto']));
        $booleano = true;

        
        $resultado = "";

        //¿Se podria optimizar con un bucle?

        if(empty($nombre_nuevo)){
            $resultado .= "<p style='color:red;'> El nombre del producto esta vacío </p>";
            $booleano = false;
        }
        else if(validar_nombre($nombre_nuevo)){
            $resultado .= "<p style='color:blue' > El nombre del producto se ha validado correctamente </p>";
        }else{
            $resultado .= "<p  style='color:red;'> El nombre del producto no es correcto </p>";
            $booleano = false;
        }

        if(empty($precio_nuevo)){
            $resultado .= "<p  style='color:red;'> El precio del producto esta vacío </p>";
            $booleano = false;
        }
        else if(validar_precio($precio_nuevo)){
            $resultado .= "<p style='color:blue' > El precio del producto se ha validado correctamente </p>";
        }else{
            $resultado .= "<p style='color:red;'> El precio del producto no es correcto </p>";
            $booleano = false;
        }

        if (move_uploaded_file($fichero_temporal, 'imagenes/'.$fichero_subido)) {
            if(validar_imagen($_FILES['imagen_producto']['name'])){
                $resultado .= "<p style='color:blue'> La imagen se ha validado correctamente </p>";
            }else{
                $resultado .= "<p  style='color:red;'>El nombre de la imagen no es correcto</p>";
                $booleano = false;
            }
        } else {
            $resultado .= "<p style='color:red;'> No se ha subido ninguna imagen. </p>";
            $booleano = false;
        }


        //si lo datos se han validado correctamente nos comnectamos a la base de datos para insertar los datos.
        if($booleano){

            //mostramos que todo ha ido bien;
            echo $resultado;

            //Consulta para modificar el producto a partir de los valores post del formulario de abajo
            $servername = "localhost";
            $username = "mitiendaonline3";
            $password = "1234localhost";
            $dbname = "mitiendaonline2";

            try {
                $conexion = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
                $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $sql1 = "UPDATE productos SET Nombre='".$nombre_nuevo."' WHERE id='". $id_producto."'";
                $sql2 = "UPDATE productos SET Precio='".$precio_nuevo."' WHERE id='". $id_producto."'";
                $sql3 = "UPDATE productos SET Imagen='".$fichero_subido."' WHERE id='". $id_producto."'";
                $sql4 = "UPDATE productos SET Categoría='".$categoria_nueva."' WHERE id='". $id_producto."'";

                // Prepare statement
                $stmt1 = $conexion->prepare($sql1);
                $stmt2 = $conexion->prepare($sql2);
                $stmt3 = $conexion->prepare($sql3);
                $stmt4 = $conexion->prepare($sql4);

                // execute the query
                $stmt1->execute();
                $stmt2->execute();
                $stmt3->execute();
                $stmt4->execute();

                // echo a message to say the UPDATE succeeded
                echo $stmt1->rowCount() . " El nombre se ha modificado correctamente <br>";
                echo $stmt2->rowCount() . " El precio se ha modificado correctamente <br>";
                echo $stmt3->rowCount() . " La imagen se ha modificado correctamente <br>";
                echo $stmt4->rowCount() . " La categoría se ha modificado correctamente <br> <br>";

                $conexion = null;
            } catch(PDOException $e) {
                echo $sql1 . "<br>" . $e->getMessage();
                echo $sql2 . "<br>" . $e->getMessage();
                echo $sql3 . "<br>" . $e->getMessage();
                echo $sql4 . "<br>" . $e->getMessage();
            }

            echo "<br>Los datos se han cumplimentado correctamente, vuelve al menu principal: <a href='menu.php'> Menú</a>";

        }else{

            //mostramos información de los campos erroneos, no se envia la información;
            echo $resultado;
            echo "<br>";
            echo "<span> Vuelve atrás y completa los datos correctamente: <a href='edita_producto.php?EnviarModifica=1&id_modifica=".$id_producto."'> Vuelve atrás </a> </span>";
            echo "<hr>";
            //link para volver a atrás y completar otra vez;
            

        }
    }
    

} else if(isset($_GET['EnviarModifica'])){

    $id = intval($_GET['id_modifica']);

    //Creo el formulario con los datos a rellenar del producto que se recibe (id)


    $String = "<form name='formulario' action= 'edita_producto.php' method='post' enctype='multipart/form-data'>";

    $servername = "localhost";
    $username = "mitiendaonline3";
    $password = "1234localhost";
    $dbname = "mitiendaonline2";
    try{
        $conexion = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt=$conexion->query("SELECT Nombre, Precio, Imagen, Categoría FROM productos WHERE id ='".$id."'");
        $row=$stmt->fetch();
        $n = $row['Nombre'];
        $p = $row['Precio'];
        $img = $row['Imagen'];
        $c = $row['Categoría'];
        $String .= "<label> Introduce el nuevo nombre del producto: </label><input value='".$n."' type='text' placeholder='Nuevo nombre' name='nombre_producto' id='nombre_producto'>";
        $String .="<br>";
        $String .="<label> Introduce el nuevo precio del  producto en Euros: </label><input value='".$p."' type='number' placeholder='precio' name='precio_producto' min='0.1' step='any' id='precio_producto'>";
        $String .="<br>";
        $String .= "<input type='hidden' value='". $id."' name='id_modificar'>";
        $String .= "<label>Introduce una nueva imagen para el producto: </label> <input value='/imagenes/".$img."'type='file' name='imagen_producto' id='imagen_producto_mod'>";
        $String .= "<br>";
        $String .= "<img style='width:50px;' alt='Imagen del producto' src='imagenes/".$img."'>";
        $String .="<br>";
        $String .= "<label>Selecciona la categoría del productos</label><select name='categoria_producto' id='categoria_producto'>";
        if($c == "1"){
            $String .="<option value='1' selected > Deportivo </option>";
        }else{
            $String .="<option value='1'> Deportivo </option>";
        } 
        if($c == "2"){
            $String .="<option value='2' selected > Diver </option>";
        }else{
            $String .="<option value='2' > Diver </option>";

        } 
        if ($c == "3"){
            $String .="<option value='3' selected > Clasico </option>";
        }else {
            $String .="<option value='3'> Clasico </option>";

        }
        if ($c == "4"){
            $String .="<option value='4' selected > Casual </option>"; 
        }else{
            $String .="<option value='4' > Casual </option>";
        }
        $String.="</select>";
        $String .="<br>";
        $String .= "<button type='submit' name='Enviar'> Modificar producto </button>";
        $String .= "</form>";
    
        echo $String;
    

        $conexion = null;

    }catch(PDOException $e){
        echo "Conexion fallida: " . $e->getMessage();
    }
    // Si no se recibe el boton de modificar, mostrar al usuario un desplegable que escoja un producto a modificar
}else{


    $String = "<form name='formulario' action= 'edita_producto.php' method='get' enctype='multipart/form-data'>";
    $String .="<label> Selecciona un producto a ediar </label>";
    $String .="<select name='id_modifica' id='categoría_producto'>";
    //Consulta de los elementos que hay en la base de datos:
    $servername = "localhost";
    $username = "mitiendaonline3";
    $password = "1234localhost";
    $dbname = "mitiendaonline2";
    try{
        $conexion = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt=$conexion->query("SELECT * FROM productos");
        while ($row=$stmt->fetch()){
            $String .= "<option value='".$row['id']."'>" .$row['Nombre'].  "</option>";
        }

        $conexion = null;

    }catch(PDOException $e){
        echo "Conexion fallida: " . $e->getMessage();
    }

    $String .= "</select>";
    $String .= "<button type='submit' name='EnviarModifica'> Modificar Producto </button>";
    $String .= "</form>";

    echo $String;

}

?>

