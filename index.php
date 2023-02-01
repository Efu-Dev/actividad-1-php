<?php
    @session_start();
    if(!isset($_SESSION['personas']) || !isset($_SESSION['datos'])){
        $_SESSION['personas'] = [];
        $_SESSION['datos'] = [0,0,0,0,0];
    }

    if(isset($_POST) && isset($_POST['edad']) && isset($_POST['nombre_apellido']) &&
    isset($_POST['sexo']) && isset($_POST['sueldo']) && isset($_POST['estado_civil']))
    {
        $sexo = $_POST['sexo'] == 0 ? "Femenino" : "Masculino";
        $estado = $_POST['estado_civil'] == 0 ? "Soltero" : ($_POST['estado_civil'] == 1 ? "Casado" : "Viudo");
        $sueldo = $_POST['sueldo'] == 0 ? "Menos de 1000 Bs" : ($_POST['sueldo'] == 1 ? "Entre 1000 y 2500 Bs" : "Más de 2500 Bs");

        array_push($_SESSION['personas'], [$_POST['nombre_apellido'], $_POST['edad'], $sexo, $sueldo, $estado]);

        $_SESSION['datos'][0] = $_SESSION['datos'][0] + ($_POST['sexo'] == 0 ? 1 : 0);
        $_SESSION['datos'][1] = $_SESSION['datos'][1] + ($_POST['sexo'] == 1 && $_POST['estado_civil'] == 1 && $_POST['sueldo'] == 2 ? 1 : 0);
        $_SESSION['datos'][2] = $_SESSION['datos'][2] + ($_POST['sexo'] == 0 &&  $_POST['estado_civil'] == 2 &&  $_POST['sueldo'] > 0 ? 1 : 0);

        if($_POST['sexo'] == 1){
            $_SESSION['datos'][3] = $_SESSION['datos'][3] + ($_POST['sexo'] == 1 ? 1 : 0); # Hombres
            $_SESSION['datos'][4] = ($_SESSION['datos'][4]*($_SESSION['datos'][3]-1) + $_POST['edad'])/$_SESSION['datos'][3];
        }

        unset($_POST['sexo']);
        unset($_POST['nombre_apellido']);
        unset($_POST['estado_civil']);
        unset($_POST['sueldo']);
        unset($_POST['edad']);
    }
    
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Actividad 1: Diego Faria</title>
    </head>
    <body>
        <form action="index.php#" method="POST">
            <label for="nombre_apellido">Nombre y Apellido:</label>
            <input type="text" pattern="[A-Za-záéíóúÁÉÍÓÚÑñ]+\s[A-Za-záéíóúÁÉÍÓÚÑñ]+" name="nombre_apellido" id="#nombre_apellido" required>
            <br><br>

            <label for="edad">Edad:</label>
            <input type="number" name="edad" id="#edad" required max="150" min="0" step=".">
            <br><br>

            <label for="estado_civil">Estado Civil:</label>
            <select name="estado_civil" id="#estado_civil" required>
                <option value="0">Soltero</option>
                <option value="1">Casado</option>
                <option value="2">Viudo</option>
            </select>
            <br><br>

            <label for="sexo">Sexo:</label>
            <select name="sexo" id="#sexo" required>
                <option value="0">Femenino</option>
                <option value="1">Masculino</option>
            </select>
            <br><br>

            <label for="sueldo">Sueldo:</label>
            <select name="sueldo" id="#sueldo" required>
                <option value="0">Menos de 1000 Bs</option>
                <option value="1">Entre 1000 y 2500 Bs</option>
                <option value="2">Más de 2500 Bs</option>
            </select>
            <br><br>

            <button type="submit">Enviar</button>
        </form>

        <?php
            if(count($_SESSION['personas']))
            {
                echo "<h2>Personas Registradas</h2>";
                echo "<table style='border: #000 solid 1px;'>";
                    echo "<thead style='border: #000 solid 1px;'>";
                        echo "<th style='border: #000 solid 1px;'>Nombre y Apellido</th>";
                        echo "<th style='border: #000 solid 1px;'>Edad</th>";
                        echo "<th style='border: #000 solid 1px;'>Estado Civil</th>";
                        echo "<th style='border: #000 solid 1px;'>Sexo</th>";
                        echo "<th style='border: #000 solid 1px;'>Sueldo</th>";
                    echo "</thead>";

                    echo "<tbody style='border: #000 solid 1px;'>";
                        foreach($_SESSION['personas'] as $persona){
                            echo "<tr>";
                           echo "<td style='border: #000 solid 1px;'>$persona[0]</td>";
                           echo "<td style='border: #000 solid 1px;'>$persona[1]</td>";                             
                           echo "<td style='border: #000 solid 1px;'>$persona[4]</td>";
                           echo "<td style='border: #000 solid 1px;'>$persona[2]</td>";
                           echo "<td style='border: #000 solid 1px;'>$persona[3]</td>";
                           echo "</tr>";
                        }
                    echo "</tbody>";
                echo "</table>";

                echo "<br> <h2>Detalle de Resultados</h2>";
                echo "<table style='border: #000 solid 1px;'>";
                    echo "<thead style='border: #000 solid 1px;'>";
                        echo "<th style='border: #000 solid 1px;'>Total Empleados Femeninos</th>";
                        echo "<th style='border: #000 solid 1px;'>Total Hombres Casados con sueldo de más de 2500Bs</th>";
                        echo "<th style='border: #000 solid 1px;'>Total de Mujeres Viudas que ganan más de 1000Bs</th>";
                        echo "<th style='border: #000 solid 1px;'>Edad Promedio Hombres</th>";
                    echo "</thead>";

                    echo "<tbody style='border: #000 solid 1px;'>";
                        echo "<tr style='border: #000 solid 1px;'>";
                            echo "<td style='border: #000 solid 1px;'>".$_SESSION['datos'][0]."</td>";
                            echo "<td style='border: #000 solid 1px;'>".$_SESSION['datos'][1]."</td>";                             
                            echo "<td style='border: #000 solid 1px;'>".$_SESSION['datos'][2]."</td>";
                            echo "<td style='border: #000 solid 1px;'>".$_SESSION['datos'][4]."</td>";
                        echo "</tr>";
                    echo "</tbody>";
                echo "</table>";
            } else
            {
                echo "Todavía no hay personas registradas.";
            }
        ?>

    </body>
</html>