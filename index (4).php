<?php
// Configuración de la conexión a la base de datos
$username = "semiller_estacion1";
$password = "Uw4B9mw6.kUJ";
$servername = "localhost";
$dbname = "semiller_orientacion";
$tabla = "est4c_2348";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Consulta para obtener todos los registros
$sql = "SELECT * FROM $tabla";
$result = $conn->query($sql);

// Consulta para calcular promedios generales
$sql_avg = "SELECT AVG(temperatura) AS avg_temp, AVG(humedad) AS avg_hum FROM $tabla";
$result_avg = $conn->query($sql_avg);
$avg_data = $result_avg->fetch_assoc();

// Consulta para promedios por día
$sql_daily_avg = "SELECT DATE(fecha) AS fecha, AVG(temperatura) AS avg_temp, AVG(humedad) AS avg_hum 
                  FROM $tabla GROUP BY DATE(fecha)";
$result_daily_avg = $conn->query($sql_daily_avg);

// Consulta para promedios por mes
$sql_monthly_avg = "SELECT DATE_FORMAT(fecha, '%Y-%m') AS mes, AVG(temperatura) AS avg_temp, AVG(humedad) AS avg_hum 
                    FROM $tabla GROUP BY mes";
$result_monthly_avg = $conn->query($sql_monthly_avg);

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Datos Estadísticos</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h1>Datos Estadísticos de Temperatura y Humedad</h1>

    <h2>Promedios Generales</h2>
    <p>Promedio de Temperatura: <?php echo round($avg_data['avg_temp'], 2); ?> °C</p>
    <p>Promedio de Humedad: <?php echo round($avg_data['avg_hum'], 2); ?> %</p>

    <h2>Promedios por Día</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Fecha</th>
                <th>Promedio Temperatura (°C)</th>
                <th>Promedio Humedad (%)</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result_daily_avg->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['fecha']; ?></td>
                    <td><?php echo round($row['avg_temp'], 2); ?></td>
                    <td><?php echo round($row['avg_hum'], 2); ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <h2>Promedios por Mes</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Mes</th>
                <th>Promedio Temperatura (°C)</th>
                <th>Promedio Humedad (%)</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result_monthly_avg->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['mes']; ?></td>
                    <td><?php echo round($row['avg_temp'], 2); ?></td>
                    <td><?php echo round($row['avg_hum'], 2); ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <!-- Tabla para mostrar todos los registros -->
    <h2>Todos los Registros</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Temperatura (°C)</th>
                <th>Humedad (%)</th>
                <th>Nombre</th>
                <th>Fecha</th> <!-- Asegúrate de que este campo exista en tu tabla -->
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['id']; ?></td> <!-- Cambia 'id' al nombre de tu campo ID -->
                    <td><?php echo round($row['temperatura'], 2); ?></td>
                    <td><?php echo round($row['humedad'], 2); ?></td>
                    <td><?php echo htmlspecialchars($row['nombre']); ?></td> <!-- Escapar caracteres especiales -->
                    <td><?php echo htmlspecialchars($row['fecha']); ?></td> <!-- Asegúrate de que este campo exista en tu tabla -->
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <?php
    // Cerrar conexión
    $conn->close();
    ?>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>