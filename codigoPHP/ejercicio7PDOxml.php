<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <title>Ejercicio 7PDOxml</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="../WEBBROOT/css/estilosImportar.css">
    </head>
    <body>
        <?php
//        /**
//          @author Ismael Heras Salvador
//          @since 12/11/2019
//         */
        
        /*
          Importacion XML
         *          */

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

        
        //le ponemos fecha al archivo
            $fecha = new DateTime();
            $nombreArchivo = $fecha->format("Ymd") . "exportarDepartamentos.xml";
        //carga el fichero xml en un objeto de la clase simpleXMLElement.
        $xml = simplexml_load_file("../tmp/$nombreArchivo");

        //realizamos una consulta preparada para insertar en la BD los datos del archivo xml.
        $sql = "INSERT INTO Departamento (CodDepartamento,DescDepartamento) VALUES(:codDepartamento,:descDepartamento)";
        $consultaPreparada = $miDB->prepare($sql);
        //foreach para recorrer el objeto de la clase simpleXMLElement e introducir los valores en la BD.
        foreach ($xml as $value) {
        $miDB->beginTransaction(); //desascivamos el autocomit.
        //blindeamos los parametros los ejecutamos y hacemos un commit.
        $consultaPreparada->bindParam(":codDepartamento", $value->codDepartamento);
        $consultaPreparada->bindParam(":descDepartamento", $value->descDepartamento);
        $consultaPreparada->execute(); //ejecutamos la consulta preparada
        $miDB->commit();//confirmamos los cambios y los consolidamos.
        }
        echo "<h2>" . "Fichero XML cargado exitosamente en nuestra base de datos" . "</h2>";
        echo '<br>';
        //enlace para comprobar los registros cargados en la BD
        echo '<a href="MtoDepartamentosmysPDOTema4.php">Volver a nuestro CRUD</a>';
        //control de excepciones con la clase PDOException
        } catch (PDOException $miExceptionPDO) {
        echo "<h1>Se ha producido un error</h1>";
        $miDB->rollBack();//Reviertimos la transacción actual
        if ($miExceptionPDO->getCode() == 23000 || $miExceptionPDO->getCode() == 2002) {
        echo "<h3>Error, Duplicado de clave primaria</h3>";
         echo '<br>';
        echo '<a href="MtoDepartamentosmysPDOTema4.php">Volver a nuestro CRUD</a>';
        }
        } finally {
        //cierre de conexion
        unset($miDB);
        }
        ?>
    </body>
</html>
