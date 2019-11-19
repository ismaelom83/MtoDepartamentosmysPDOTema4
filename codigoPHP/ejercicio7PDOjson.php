<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <title>Ejercicio 7PDOjson</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="../WEBBROOT/css/estilos8.css">
    </head>
    <body>
        <?php
        /**
          @author Ismael Heras Salvador
          @since 12/11/2019
         */
        /*
          inportar archivo JSON
         *            */
        require '../config/constantes.php'; //requerimos las constantes para la conexion
        try {
            //conexion a la base de datos
            $miDB = new PDO(MAQUINA, USUARIO, PASSWD);
            //para poder utilizar las excepciones.
            $miDB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            //try cacth por si falla la conexion.
        } catch (PDOException $excepcionPDO) {
            die("Error al conectarse a la base de datos");
        }
        try {
            echo "<h2>" . "Conexion PDO OK" . "</h2>";
            echo '<br>';
            
            //instanciamos uuna fecha para ponerlo en el nombre del archivo.
             $fecha = new DateTime();
            //definimos el nombre del archivo con la fecha
            $archivoJSON1 = $fecha->format("Ymd") . "exportarDepartamentos.json";
            //transmite un fichero completo a una cadena
            $archivoJSON2 = file_get_contents("../tmp/$archivoJSON1");
            //Decodifica un string de JSON
            $archivoJSONDeco = json_decode($archivoJSON2, true);

            //realizamos la consulta preparada
            $sql = "INSERT INTO Departamento VALUES(:cod,:desc, :fecha, :valor)";
            $consultaPreparada = $miDB->prepare($sql);
            //foreach para rellenar la base de datos con los datos del archivo json
            foreach ($archivoJSONDeco as $registros) {
                $miDB->beginTransaction(); //desascivamos el autocomit.     
                $consultaPreparada->bindParam(":cod", $registros['CodDepartamento']);
                $consultaPreparada->bindParam(":desc", $registros['DescDepartamento']);
                $consultaPreparada->bindParam(":fecha", $registros['FechaBaja']);
                $consultaPreparada->bindParam(":valor", $registros['ValorNegocio']);
                $consultaPreparada->execute(); //ejecutamos la consulta preparada.
                $miDB->commit(); //confirmamos los cambios y los consolidamos
            }

            echo "<h2>" . "Fichero JSON cargado exitosamente en nuestra base de datos" . "</h2>";
            echo '<br>';
            //enlace para ver los registros que hemos insertado.
            echo "<a href='../tmp/$archivoJSON1'>Pulse aqui para comprobar los registros cargados en nuestra base de datos</a>";
            echo '<br>';
            echo '<br>';
             echo '<a href="MtoDepartamentosmysPDOTema4.php">Volver a nuestro CRUD</a>';
            //control de excepciones con la clase PDOException
        } catch (PDOException $miExceptionPDO) {
            $miDB->rollBack();//Reviertimos la transacción actual
            echo "<h1>Se ha producido un error</h1>";
            if ($miExceptionPDO->getCode() == 23000 || $miExceptionPDO->getCode() == 2002) {
                echo "<h3>Error, Duplicado de clave primaria</h3>";
            }
        } finally {
            //cierre de conexion
            unset($miDB);
        }
        ?>
    </body>
</html>
