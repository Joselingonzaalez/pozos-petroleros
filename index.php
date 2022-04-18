<?php
include '<manometro_petroleo>cosasphp/conexion.php';
date_default_timezone_set('America/Caracas');
$no_palante = date('Y-m-d\TH:i');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>El Manometro Digital</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta2/css/all.min.css" integrity="sha512-YWzhKL2whUzgiheMoBFwW8CKV4qpHQAEuvilg9FAn5VJUDwKZZxkJNuGM4XkWuk94WCrrwslk8yWNGmY1EduTA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="style.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.6.0/chart.js" integrity="sha512-CWVDkca3f3uAWgDNVzW+W4XJbiC3CH84P2aWZXj+DqI6PNbTzXbl1dIzEHeNJpYSn4B6U8miSZb/hCws7FnUZA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
</head>
<body>
    <div class="text-center align-items-center pt-3 text-light">
        <h1 class="fs-1">El Manometro Digital</h1>
    </div>

    <form action="<manometro_petroleo>cosasphp/guardarcosas2.php" method="post" class="text-light">
    <div class="conte_manome p-3 mx-2"  style="border: 2px solid #a0ecb9;">
        <div class="mb-3">
            <label for="formGroupExampleInput" class="form-label">nombre del pozo</label>
            <input type="text" class="form-control form-control-lg"
            style="border: 2px rgb(0, 0, 0) solid; background-color: rgb(135, 241, 132); color: black;" 
            name="pozo" id="formGroupExampleInput" placeholder="Ejemplo: Pozo No.1">
        </div>

        <div class="mb-3">
            <label for="formGroupExampleInput" class="form-label">Presion Manometrica</label>
            <input type="number" class="form-control form-control-lg" 
            style="border: 2px rgb(0, 0, 0) solid; background-color: rgb(135, 241, 132); color: black;"
            name="presion" id="formGroupExampleInput" placeholder="Ejemplo: 00.0">
        </div>

        <div>
            <!-- Limite de fecha minimo hasta el 27 de agosto de 1859 ya que en esa epoca se hizo el primer pozo petrolero-->
            <!-- limite maximo es el dia actual -->
            <label for="formGroupExampleInput" class="form-label">Ingrese la Fecha De La Medicion</label>
            <input type="datetime-local" class="form-control form-control-lg fecha"
            style="border: 2px rgb(0, 0, 0) solid; background-color: rgb(135, 241, 132); color: black;"
            id="formGroupExampleInput" name="fecha" min="1859-08-27T00:00" max="<?php echo $no_palante ?>">
        </div>
        <div class="marco_btn pt-4">
            <input type="submit" value="Guardar" name="guardar" class="btn btn-success">
        </div>
    </div>
    </form>

    <div class="text-center align-items-center pt-3 text-light">
    <h2 class="fs-2">Grafique Un Pozo</h2>
    </div>
    <form action="<?php $_SERVER['PHP_SELF'] ?>" method="post">
        <div class="conte_manome p-3 mx-2"  style="border: 2px solid #a0ecb9;">
            <select class="form-select form-control form-control-lg" 
            style="border: 2px rgb(0, 0, 0) solid; background-color: rgb(135, 241, 132); color: black;"
            aria-label="Default select example" name="ver_pozo">
                <option selected>--Seleccione una Opción--</option>
                <?php
                
                $consulta = "SELECT nombre_pozo, count(fecha) as fecha FROM datos_pozo GROUP BY nombre_pozo ASC";
                //$consulta = "SELECT nombre_pozo, fecha FROM datos_pozo ORDER BY id_pozo ASC";-->
                

                if ($resultado = $conexion->query($consulta)) {

                    while ($fila = $resultado->fetch_assoc()) {
                        ?>
                        <option value="<?php echo $fila["nombre_pozo"]; ?>"> 
                            <?php echo $fila["nombre_pozo"] . " | mediciones realizadas - " . $fila["fecha"]; ?>
                        </option>
                        <?php
                    }

                    $resultado->free();
                }
                ?>
            </select>
            <div class="marco_btn pt-4">
                <input type="submit" value="graficar" name="Graficar" class="btn btn-success">
            </div>
        </div>
    </form>

    <?php

    if(isset($_POST['Graficar']) && $_POST['Graficar'] == "graficar"){
        $ver_pozo = $_POST["ver_pozo"];
        ?>
        <div class="grafica pt-5">
        <canvas id="myChart" width="100%" height="50px" style="background-color: rgb(70, 71, 69);">></canvas>
    <script>
        Chart.defaults.color = "rgb(255, 255, 255)";
    const ctx = document.getElementById('myChart').getContext('2d');
    const myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: [
                <?php
                    $sql_select_grafica_fecha = "SELECT fecha FROM datos_pozo WHERE nombre_pozo = '$ver_pozo' ORDER BY fecha ASC;";

                    if ($resul = $conexion->query($sql_select_grafica_fecha)) {
                    
                        while ($datos = $resul->fetch_assoc()) {
                            $timestamp = strtotime($datos["fecha"]);
                            $nueva_fecha = date('d/m/Y H:i:s', $timestamp);
                    ?>
                            '<?php echo 'medido el ' . $nueva_fecha ?>',
                    <?php
                        }
                    
                        $resul->free();
                    }
                ?>
        ],
            datasets: [{
                label: 'Presion Manometrica',
                data: [<?php
                    $sql_select_grafica_fecha = "SELECT presion FROM datos_pozo WHERE nombre_pozo = '$ver_pozo' ORDER BY fecha ASC;";

                    if ($resul = $conexion->query($sql_select_grafica_fecha)) {
                    
                        while ($datos = $resul->fetch_assoc()) {
                    ?>
                            '<?php echo $datos["presion"] ?>',
                    <?php
                        }
                    
                        $resul->free();
                    }
                ?>],
                backgroundColor: [
                    'rgba(255, 119, 162, 0.6)',
                    'rgba(88, 152, 145, 0.6)',
                    'rgba(255, 206, 86, 0.6)',
                    'rgba(45, 168, 15, 0.6)',
                    'rgba(163, 14, 156, 0.6)',
                    'rgba(255, 159, 64, 0.6)'
                ],
                borderColor: [
                    'rgba(255, 119, 162, 1)',
                    'rgba(88, 152, 145, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(45, 168, 15, 1)',
                    'rgba(163, 14, 156, 1)',
                    'rgba(255, 159, 64, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
    </script>
    </div>
        <?php
    }
    ?>








    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js" integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous"></script>
    
    <?php
    mysqli_close($conexion);
    ?>
</body>
</html>