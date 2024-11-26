<?php
include 'db.php';

if (isset($_GET['codigopostal'])) {
    $codigopostal = $_GET['codigopostal'];
    $sql = "SELECT * FROM fe_codigopostal WHERE codigopostal='$codigopostal'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        echo json_encode($row);
    } else {
        echo json_encode([]);
    }
}
?>
