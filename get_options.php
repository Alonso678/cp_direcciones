<?php
include 'db.php';

$response = [];

if (isset($_GET['type'])) {
    $type = $_GET['type'];
    $key = isset($_GET['key']) ? $_GET['key'] : '';

    switch ($type) {
        case 'pais':
            $sql = "SELECT pais_key, descripcion FROM ad_paises";
            break;
        case 'estado':
            $sql = "SELECT estado_key, descripcion FROM ad_estados WHERE pais_key='$key'";
            break;
        case 'municipio':
            $sql = "SELECT municipio_key, descripcion FROM ad_municipios WHERE estado_key='$key'";
            break;
        case 'colonia':
            $sql = "SELECT colonia_key, descripcion FROM ad_colonias WHERE municipio_key='$key'";
            break;
        default:
            $sql = '';
            break;
    }

    if ($sql) {
        $result = $conn->query($sql);
        while ($row = $result->fetch_assoc()) {
            $response[] = $row;
        }
    }
}

echo json_encode($response);
?>
