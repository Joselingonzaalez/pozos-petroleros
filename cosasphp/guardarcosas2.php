<?php 
include 'conexion.php';


if (isset($_POST['guardar']) && isset($_POST['guardar']) == 'Guardar') {
    $pozo = $_POST['pozo'];
    $presion = $_POST['presion'];
    $fecha = $_POST['fecha'] . ':00';

    $valores = explode('-', $fecha);
    $permitir_decimal = "(^[0-9]{1,3}$|^[0-9]{1,3}\.[0-9]{1,3}$)";

    $sql_select = "SELECT * FROM datos_pozo WHERE nombre_pozo='$pozo' AND fecha='$fecha';";
    $ejecucuion_sql_select = mysqli_query($conexion, $sql_select) or die('Error: ' . mysqli_error($conexion));
    $contador_sql_select = mysqli_num_rows($ejecucuion_sql_select);
    
    if ($contador_sql_select == 0) {
        
        if (preg_match($permitir_decimal, $presion)) {
                    
            $presion = floatval($presion);

                if (is_float($presion) == true && ($presion > 0)) {

                        $sql_insert = "INSERT INTO datos_pozo (nombre_pozo, presion, fecha) VALUES ('$pozo', '$presion', '$fecha');";
                        $resultado = mysqli_query($conexion, $sql_insert) or die("Error: " . mysqli_error($conexion));
                            unset($_POST['pozo']);
                            unset($_POST['presion']);
                            unset($_POST['fecha']); 
                        header("location:../index.php");
    
                }else {
                    echo 'Medicion manometrica erronea';
                }

        } else {
            echo 'Dato Erroneo en Presion';
        }
        
    } else {
        echo'<script>
        alert("La medicion para el pozo ' . $pozo . ' en la fecha ' . $fecha . ' ya fue registrada");
        window.history.go(-1);
    </script>';
    }
}
?>