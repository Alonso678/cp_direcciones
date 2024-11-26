<?php
function validarCodigoPostal($pais, $codigoPostal) {
    $url = "https://api.zippopotam.us/{$pais}/{$codigoPostal}";
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $response = curl_exec($ch);
    curl_close($ch);

    if ($response) {
        $data = json_decode($response, true);
        if (isset($data['places']) && count($data['places']) > 0) {
            return [
                'valid' => true,
                'data' => $data
            ];
        }
    }
    return [
        'valid' => false,
        'message' => 'Código postal no válido o no encontrado.'
    ];
}
?>
