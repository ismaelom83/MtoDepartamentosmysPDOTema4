<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <title>Ejercicio 3PDO</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <link rel="stylesheet" href="../WEBBROOT/css/estilos3.css">
    </head>
    <body>
        <?php
        /**
          @author Ismael Heras Salvador
          @since 6/11/2019
         */
        require '../core/validacionFormularios.php'; //importamos la libreria de validacion  
        require '../config/constantes.php'; //requerimos las constantes para la conexion
        define('OBLIGATORIO', 1); //constante que define que un campo es obligatorio.
        define('NOOBLIGATORIO', 0); //constante que define que un campo NO es obligatorio.
        $entradaOK = true; //Inicializamos una variable que nos ayudara a controlar si todo esta correcto
        //manejo del control de errores.
        $aErrores = ['CodDepartamentos' => null,
            'DescDepartamentos' => null,
            'FechaBaja' => null,
            'VolumenNegocio' => null];
        //manejo de las variables del formulario
        $aFormulario = ['CodDepartamentos' => null,
            'DescDepartamentos' => null,
            'FechaBaja' => null,
            'VolumenNegocio' => null];

        if (isset($_POST['enviar']) && $_POST['enviar'] == 'AñadirRegistro') {
            //La posición del array de errores recibe el mensaje de error si hubiera.
            $aErrores['CodDepartamentos'] = validacionFormularios::comprobarAlfabetico($_POST['CodDepartamentos'], 4, 1, 1);
            $aErrores['DescDepartamentos'] = validacionFormularios::comprobarAlfabetico($_POST['DescDepartamentos'], 300, 1, 1);
            $aErrores['FechaBaja'] = validacionFormularios::validarFecha($_POST['FechaBaja'], 1);
            $aErrores['VolumenNegocio'] = validacionFormularios::comprobarFloat($_POST['VolumenNegocio'], 1000000, 1, 1);
            //foreach para recorrer el array de errores
            foreach ($aErrores as $campo => $error) {
                if (!is_null($error)) {
                    $_REQUEST[$campo] = "";
                    $entradaOK = false;
                }
            }
        } else {
            $entradaOK = false;
        }

        if ($entradaOK) {

            $aFormulario['CodDepartamentos'] = $_POST['CodDepartamentos'];
            $aFormulario['DescDepartamentos'] = $_POST['DescDepartamentos'];
            $aFormulario['FechaBaja'] = $_POST['FechaBaja'];
            $aFormulario['VolumenNegocio'] = $_POST['VolumenNegocio'];

            try {
                //conexion a la base de datos
                $miDB = new PDO(MAQUINA, USUARIO, PASSWD);
                //mensaje por pantalla que todo ha ido bien
                $miDB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                echo "<h2>" . "Conexion PDO OK" . "</h2>";

                echo "<br>";
                //consulta para saber los datos de la tabla.
                $sql = "select * from Departamento";
                $resultadoConsulta = $miDB->query($sql);


                //dentro del while realizamos un FechObject y extraemos toda la informacion del objeto
                while ($campoTabla = $resultadoConsulta->fetchObject()) {
                    '<tr>';
                    "<td>" . '<b>' . $campoTabla->CodDepartamento . "</td>" . "<td>" . '</b>' . '<b>' . $campoTabla->DescDepartamento . "</td>" .
                            "<td>" . '</b>' . '<b>' . $campoTabla->FechaBaja . "</td>" .
                            "<td>" . '</b>' . '<b>' . $campoTabla->VolumenNegocio . "</td>";
                    echo '</tr>';
                }
                echo "<br>";
                //consulta preparada para ingresar valores a la tabla
                $sql = "INSERT INTO Departamento  VALUES(:CodDepartamento, :DescDepartamento, :fecha, :valor)";
                $sentencia = $miDB->prepare($sql);
                //con el bind param introducimos en la sentencia preparada el valor del campo del formulario
                $sentencia->bindParam(":CodDepartamento", $aFormulario["CodDepartamentos"]);
                $sentencia->bindParam(":DescDepartamento", $aFormulario["DescDepartamentos"]);
                $sentencia->bindParam(":fecha", $aFormulario["FechaBaja"]);
                $sentencia->bindParam(":valor", $aFormulario["VolumenNegocio"]);
                $sentencia->execute();
               

                echo '<br>';
                echo "<h1>Insercion realizada con exito</h1>";
                echo '<br>';
                echo '<a href="MtoDepartamentosmysPDOTema4.php">Volver a nuestro CRUD</a>';


                //control de excepciones con la clase PDOException
            } catch (PDOException $miExceptionPDO) {
               
                if ($miExceptionPDO->getCode() == 23000 || $miExceptionPDO->getCode() == 2002) {
                     
                    echo "<h1>Error, Duplicado de clave primaria</h1>";
                    echo '<br>';
                    echo '<a class="a1" href="MtoDepartamentosmysPDOTema4.php">Volver a nuestro CRUD</a>';
                }
            } finally {
                //cierre de conexion
                unset($miDB);
            }
            echo "<br>";
        } else {
            //Mostrar el formulario hasta que se rellene correctamente
            ?>
            <h1 class="h1">formulario para insertar campos en la tabla Departamentos</h1>
            <div class="wrap">
                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                    <fieldset>
                        <div class="obligatorio">
                            CODIGO: 
                            <input type="text" name="CodDepartamentos" placeholder="Alfabetico" class="form-control " value="<?php
                            if ($aErrores['CodDepartamentos'] == NULL && isset($_POST['CodDepartamentos'])) {
                                echo $_POST['CodDepartamentos'];
                            }
                            ?>" <!--//Si el valor es bueno, lo escribe en el campo-->
                                   <?php if ($aErrores['CodDepartamentos'] != NULL) { ?>
                                <div class="error">
                                    <?php echo $aErrores['CodDepartamentos']; //Mensaje de error que tiene el array aErrores       ?>
                                </div>   
                            <?php } ?>                
                        </div>
                        <br>
                        <div class="obligatorio">
                            DESCRIPCION: 
                            <input type="text" name="DescDepartamentos" placeholder="Alfabetico" class="form-control " value="<?php
                            if ($aErrores['DescDepartamentos'] == NULL && isset($_POST['DescDepartamentos'])) {
                                echo $_POST['DescDepartamentos'];
                            }
                            ?>" <!--//Si el valor es bueno, lo escribe en el campo-->
                                   <?php if ($aErrores['DescDepartamentos'] != NULL) { ?>
                                <div class="error">
                                    <?php echo $aErrores['DescDepartamentos']; //Mensaje de error que tiene el array aErrores       ?>
                                </div>   
                            <?php } ?>   
                            <br>
                            <label class="label1" for="FechaBaja" >FechaBaja</label>
                            <input type="date" name="FechaBaja" class="form-control" class="form-control " value="<?php
                            if (isset($_POST['FechaBaja']) && is_null($aErrores['FechaBaja'])) { //comprobamos si ha introducido algo en el campo y que el array de errores este a null
                                echo $_POST['FechaBaja']; //aunque se muestre un campo mal el valor si es correcto se mantiene.
                            }
                            ?>">

                            <?php if ($aErrores['FechaBaja'] != NULL) { ?>
                                <div class="error">
                                    <?php echo "<p class='p1'>" . $aErrores['FechaBaja'] . "</p>"; //mensaje de error que tiene el array aErrores        ?>
                                </div>   
                            <?php } ?> 
                            <br>
                            <br>
                            <label class="label2" for="VolumenNegocio">ValorNegocio</label>
                            <input type="number" name="VolumenNegocio" id="VolumenNegocio" class="form-control" placeholder="ValorNegocio:" value="<?php
                            if (isset($_POST['VolumenNegocio']) && is_null($aErrores['VolumenNegocio'])) { //comprobamos si ha introducido algo en el campo y que el array de errores este a null
                                echo $_POST['VolumenNegocio']; //aunque se muestre un campo mal el valor si es correcto se mantiene.
                            }
                            ?>">
                                   <?php if ($aErrores['VolumenNegocio'] != NULL) { ?>
                                <div class="error">
                                    <?php echo "<p class='p1'>" . $aErrores['VolumenNegocio'] . "</p>"; //mensaje de error que tiene el array aErrores        ?>
                                </div>   
                            <?php } ?> 
                        </div>
                        <br>
                        <div>
                            <input type="submit" name="enviar" value="AñadirRegistro" class="form-control  btn btn-secondary mb-1">
                        </div>
                    </fieldset>
                </form>
            </div>
        <?php } ?>   
        <br/>
        <br/>
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    </body>
</html>

















