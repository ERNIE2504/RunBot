<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bandita";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Verificar si se reciben los parámetros
if (isset($_GET['temperatura']) && isset($_GET['humedad'])) {
    $temperatura = $_GET['temperatura'];
    $humedad = $_GET['humedad'];

    // Insertar nueva medición para humedad (SensorID = 1)
    $sql_medicion_humedad = "INSERT INTO medicion (SensorID, Tiempo) VALUES (1, CURRENT_TIME())";
    if ($conn->query($sql_medicion_humedad) === TRUE) {
        $medicion_humedad_id = $conn->insert_id;

        // Insertar valor de humedad
        $sql_humedad = "INSERT INTO humedad (Toma_medicion, Valor) VALUES ($medicion_humedad_id, $humedad)";
        if ($conn->query($sql_humedad) === TRUE) {
            echo "Humedad registrada correctamente.\n";
        } else {
            echo "Error al registrar humedad: " . $conn->error . "\n";
        }
    } else {
        echo "Error al registrar medición de humedad: " . $conn->error . "\n";
    }

    // Insertar nueva medición para temperatura (SensorID = 2)
    $sql_medicion_temperatura = "INSERT INTO medicion (SensorID, Tiempo) VALUES (2, CURRENT_TIME())";
    if ($conn->query($sql_medicion_temperatura) === TRUE) {
        $medicion_temperatura_id = $conn->insert_id;

        // Insertar valores de temperatura
        $sql_temperatura = "INSERT INTO temperatura (Toma_medicion, Valor_celsius, Valor_farenheit) 
                            VALUES ($medicion_temperatura_id, $temperatura, $temperatura * 9 / 5 + 32)";
        if ($conn->query($sql_temperatura) === TRUE) {
            echo "Temperatura registrada correctamente.\n";
        } else {
            echo "Error al registrar temperatura: " . $conn->error . "\n";
        }
    } else {
        echo "Error al registrar medición de temperatura: " . $conn->error . "\n";
    }
} else {
    echo "Datos no válidos. Asegúrate de enviar 'temperatura' y 'humedad' como parámetros.\n";
}

// Cerrar conexión
$conn->close();
?>
