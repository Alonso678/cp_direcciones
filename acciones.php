<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['eliminar'])) {
    if (!empty($_POST['seleccionar'])) {
        foreach ($_POST['seleccionar'] as $codigopostal) {
            $sql = "DELETE FROM fe_codigopostal WHERE codigopostal='$codigopostal'";
            $conn->query($sql);
        }
    }
    header("Location: index.php");
    exit();
}
?>
