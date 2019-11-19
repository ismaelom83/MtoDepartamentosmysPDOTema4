<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <title>Ejercicio 2PDO</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="../WEBBROOT/css/estilos2.css">
    </head>
    <body>
        <?php
        /**
          @author Ismael Heras Salvador
          @since 4/11/2019
         */
        require '../config/constantes.php'; //requerimos las constantes para la conexion
        try {
            //conexion a la base de datos
            $miDB = new PDO(MAQUINA, USUARIO, PASSWD);
            //mensaje por pantalla que todo ha ido bien
            $miDB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            echo "<h2>" . "Conexion PDO OK" . "</h2>";
            echo '<br>';

            //selecccion del query
            //almacenamos en una variable la consulta sql
            $sql = ('select * from Departamento');
            $resultadoConsulta = $miDB->query($sql);

            echo "<h3>" . "La consulta de el departamento es la siguiente" . "</h3>";
            echo '<br>';
           
         //tabla para formatear la salida en formato tabla.
        echo '<table border="1">';
        echo '<caption>Tabla Departamentos</caption>';
        echo '<tr>';
        echo '<th>Código</th>';
        echo '<th>Descripción</th>';
        echo '</tr>';


            //dentro del while realizamos un FechObject y extraemos toda la informacion del objeto
            while ($campoTabla = $resultadoConsulta->fetchObject()) {
                echo '<tr>';
                echo "<td>" . '<b>' . $campoTabla->CodDepartamento ."</td>". "<td>".'</b>' .'<b>' . $campoTabla->DescDepartamento ."</td>";
                echo '</tr>';
            }
            echo "<br>";

            

            echo '<h3>' . "El numero de registros de la consulta es: " . $resultadoConsulta->rowCount() . '</h3>';
            echo "<br>";

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
