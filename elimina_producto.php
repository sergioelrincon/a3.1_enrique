
<?php
    if(isset($_POST['Eliminar'])){
        //Si se le da al boton, eliminar producto.

        $id = $_POST['id_eliminar'];
        $servername = "localhost";
        $username = "mitiendaonline3";
        $password = "1234localhost";
        $dbname = "mitiendaonline2";
        $String ="<form action='elimina_producto.php' method='post' name='formulario'>";
        try{
            $conexion = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
            $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
            $sql = "DELETE FROM productos  WHERE id=".$id;

            // use exec() because no results are returned
            $conexion->exec($sql);
            
            echo "<p style='colo:blue'>Se ha borrado correctamente el producto</p>";

            echo "<p><a href='menu.php'>Vuelve al menú principal </a></p>";

    
            $conexion = null;
    
        }catch(PDOException $e){
            echo "Conexion fallida: " . $e->getMessage();
        }



        

    }else if(isset($_GET["EnviarElimina"])){
        // obtener los datos de la base de datos y mostrarlos por pantalla para que el usuario confirme que quiere eliminar el producto.

        $id = intval($_GET['id_elimina']);

        $servername = "localhost";
        $username = "mitiendaonline3";
        $password = "1234localhost";
        $dbname = "mitiendaonline2";
        $String ="<form action='elimina_producto.php' method='post' name='formulario'>";
        try{
            $conexion = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
            $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            

            //Lo ideal es hacer un INNER JOIN que haga una consulta a las dos tablas y revise los datos que coinden.

            $stmt=$conexion->query("SELECT Nombre, Precio, Imagen, Categoría FROM productos WHERE id ='".$id."'");
            while ($row=$stmt->fetch()){
                $String .="<label>Nombre: </label>".$row['Nombre']."<br><label>Precio: </label>".$row['Precio']."<br>
                <label>Imagen: </label><img style='width:50px;' alt='Imagen del producto' src='imagenes/".$row['Imagen']."'>";
                if($row['Categoría'] == 1){
                    $String .= "<br> <label>Categoría: </label> Deportivo";
                }else if($row['Categoría' ==  2]){
                    $String .= "<br> <label>Categoría: </label> Diver";
                }else if($row['Categoría' ==  3]){
                    $String .= "<br> <label>Categoría: </label> Clásico";
                }else if($row['Categoría' ==  4]){
                    $String .= "<br> <label>Categoría: </label> Casual";
                }   
                $String .= "<input type='hidden' value='". $id."' name='id_eliminar'><br>";
            }

            $String .= "<label>Si desea eliminar el producto clicke en el siguiente boton: </label> <button type='submit' name='Eliminar'> Eliminar Producto</button><br>";
            $String .= "<label>Si no desea eliminar el producto clicke en el siguiente enlace y vuelva atrás: <a href='elimina_producto.php'> Vuelve atrás </a></label><br>";
            $String .= "</form>";
            echo $String;

    
            $conexion = null;
    
        }catch(PDOException $e){
            echo "Conexion fallida: " . $e->getMessage();
        }

        
    }else{


        $String = "<form name='formulario' action= 'elimina_producto.php' method='get' enctype='multipart/form-data'>";
        $String .="<label> Selecciona un producto a ediar </label>";
        $String .="<select name='id_elimina' id='categoría_producto'>";
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
        $String .= "<button type='submit' name='EnviarElimina'> Mostrar producto para eliminar </button>";
        $String .= "</form>";
    
        echo $String;
    
    }

?>


