<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $codigopostal = $_POST['codigopostal'];
    $estado = $_POST['estado'];
    $municipio = $_POST['municipio'];
    $localidad = $_POST['localidad'];
    $pais = $_POST['pais'];

    // Validar código postal usando la API de Zippopotam.us
    $url = "https://api.zippopotam.us/{$pais}/{$codigopostal}";
    $response = file_get_contents($url);
    $data = json_decode($response, true);

    if (isset($data['places'])) {
        $sql = "INSERT INTO fe_codigopostal (codigopostal, estado, municipio, localidad) VALUES ('$codigopostal', '$estado', '$municipio', '$localidad')";

        if ($conn->query($sql) === TRUE) {
            header("Location: index.php");
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    } else {
        echo "Código postal no válido.";
    }
    exit();
}
?>
