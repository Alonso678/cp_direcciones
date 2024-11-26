<?php
include 'db.php';

$sql = "SELECT cp.codigopostal, ae.descripcion AS estado, am.descripcion AS municipio, ac.descripcion AS localidad, ap.descripcion AS pais
        FROM fe_codigopostal cp
        JOIN ad_estados ae ON cp.estado = ae.estado_key
        JOIN ad_municipios am ON cp.municipio = am.municipio_key
        JOIN ad_colonias ac ON cp.localidad = ac.colonia_key
        JOIN ad_paises ap ON ae.pais_key = ap.pais_key";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo '<div class="card mb-3">';
        echo '<div class="card-body">';
        echo '<input type="checkbox" name="seleccionar[]" value="' . $row["codigopostal"] . '"> ';
        echo '<div class="record-attribute"><strong>C.P.:</strong> ' . $row["codigopostal"] . '</div>';
        echo '<div class="record-attribute"><strong>Estado:</strong> ' . $row["estado"] . '</div>';
        echo '<div class="record-attribute"><strong>Municipio:</strong> ' . $row["municipio"] . '</div>';
        echo '<div class="record-attribute"><strong>Localidad:</strong> ' . $row["localidad"] . '</div>';
        echo '<div class="record-attribute"><strong>País:</strong> ' . $row["pais"] . '</div>';
        echo '</div>';
        echo '</div>';
    }
} else {
    echo "<div class='alert alert-warning'>No hay resultados.</div>";
}
?>
