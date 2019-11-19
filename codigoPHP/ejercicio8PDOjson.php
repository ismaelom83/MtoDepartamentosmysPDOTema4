<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <title>Ejercicio 8PDOjson</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="../WEBBROOT/css/estilos8.css">
    </head>
    <body>    
        <?php
        /**
          @author Ismael Heras Salvador
          @since 19/11/2019
         */
         /*
          Exportacion JSON
         *          */       
        require '../config/constantes.php'; //requerimos las constantes para la conexion
        try {
            //conexion a la base de datos
            $miDB = new PDO(MAQUINA, USUARIO, PASSWD);
            //mensaje por pantalla que todo ha ido bien
            $miDB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $excepcionPDO) {
            die("Error al conectarse a la base de datos");
        }
        try {

            //preparamos una consulta preparada selecionando todos los registros de la tabla departamentos.
            $sql = "SELECT * FROM Departamento";
            $consultaPreparada = $miDB->prepare($sql);
            $consultaPreparada->execute();

            //declaramos una variable array para introducir los registros de la tabla.
            $arrayJson = array();

            //while para darle formato al arcivo json con los registros de la tabla departamentos.
            while ($columnasBD = $consultaPreparada->fetch(PDO::FETCH_ASSOC)) {
                $arrayJson[] = $columnasBD;
            } 
            
             //retorna los datos en estructura json.
            //le ponemos la fecha al archivo
            $jEncodec = json_encode($arrayJson, JSON_PRETTY_PRINT);
            $fecha = new DateTime();
            //definimos el nombre del archivo con la fecha
            $archivoJSON1 = $fecha->format("Ymd") . "exportarDepartamentos.json";
            //decimos la ruta 
            $archivoJSON2 = "../tmp/$archivoJSON1"; 
            echo $jEncodec;
//            $archivoJSON = '../tmp/exportar.json';
            //escribe los datos en el fichero
            file_put_contents($archivoJSON2, $jEncodec);

            echo '<br>';
            echo "<h2>" . "Exportacion de los archivos JSON correcta" . "</h2>";
            echo '<br>';
            echo "<a href='../tmp/$archivoJSON2'>Pulse aqui para comprobar el  archivo json descargado</a>";
             echo '<br>';
             echo '<a href="MtoDepartamentosmysPDOTema4.php">Volver a nuestro CRUD</a>';
            //control de excepciones con la clase PDOException
        } catch (PDOException $miExceptionPDO) {
            //mostrar mensaje de errores
            echo'Error: ' . $miExceptionPDO->getMessage();
            echo'Código de error: ' . $miExceptionPDO->getCode();
        } finally {
            //cierre de la conexion
            unset($miDB);
        }
        ?>
    </body>
</html>
