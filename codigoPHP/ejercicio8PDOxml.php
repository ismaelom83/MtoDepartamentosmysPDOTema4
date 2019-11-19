<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <title>Ejercicio 8PDOxml</title>
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
          Exportacion XML
         *          */

        require '../config/constantes.php'; //requerimos las constantes para la conexion
        try {
            //conexion a la base de datos
            $miDB = new PDO(MAQUINA, USUARIO, PASSWD);
            //mensaje por pantalla que todo ha ido bien
            $miDB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            echo "<h2>" . "Conexion PDO OK" . "</h2>";
            echo '<br>';
        } catch (PDOException $excepcionPDO) {
            die("Error al conectarse a la base de datos");
        }
        try {
            //alamacenamos en una variable la consulta a mysql
            $Departamentos = $miDB->query("SELECT * FROM Departamento");
            //crear el fichero
            $archivoXML = new DOMDocument("1.0", "utf-8");
            //hace que salga espaciado y tabulado
            $archivoXML->formatOutput = true;
            $ecreacionElementoXML = $archivoXML->createElement("Departamentos");
            $elementoHijo = $archivoXML->appendChild($ecreacionElementoXML);
            while ($registroDepartamentos = $Departamentos->fetchObject()) { //Mientras haya registros, que los meta a la estructura xml            
                $departamento = $elementoHijo->appendChild($archivoXML->createElement("departamento"));
                $codDepartamento = $departamento->appendChild($archivoXML->createElement("codDepartamento", $registroDepartamentos->CodDepartamento));
                $descDepart = $departamento->appendChild($archivoXML->createElement("descDepartamento", $registroDepartamentos->DescDepartamento));
                
            }
            //copia el arbol interno a un string.
            $archivoXML->saveXML();
            //objeto de tiempo para concatenar la fecha y hota actual con el nombre del archivo.
            $fecha = new DateTime();
            $nombreArchivo = $fecha->format("Ymd") . "exportarDepartamentos.xml";
            $archivoXML->save("../tmp/$nombreArchivo");
            echo "<h2>" . "Exportacion de los archivos xml correcta" . "</h2>";
            echo '<br>';
            echo "<a href='../tmp/$nombreArchivo'>Pulse aqui para comprobar el archivo xml descargado</a>";
            echo '<br>';
            echo "<a href='MtoDepartamentosmysPDOTema4.php'>Volver al CRUD</a>";
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
