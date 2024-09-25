<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $stripeSecretKey = 'sk_test_51Q2K3C2KRnMXszkB3roV4IqUgcaS9iZkRq8hWlceHIdWYc4HJnJiS18FRbbKFh26bswoumrV25KfaI6g7QPJEWpu00weW9ce5e'; // Coloca aquí tu clave secreta de Stripe

    
    $token = $_POST['stripeToken']; 
    $total = $_POST['total']; 

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, 'https://api.stripe.com/v1/charges');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
        'amount' => $total,
        'currency' => 'pen',
        'source' => $token,
        'description' => 'Pago del pedido en el restaurante',
    ]));

    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer ' . $stripeSecretKey
    ]);

    
    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        echo 'Error:' . curl_error($ch);
    }

    curl_close($ch);

    $response_data = json_decode($response, true);

    if (isset($response_data['id'])) {
        
        echo 'Pago procesado exitosamente. ID de la transacción: ' . $response_data['id'];
    } else {
        
        echo 'Error al procesar el pago: ' . $response_data['error']['message'];
    }
}
?>
