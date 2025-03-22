<?php
require 'vendor/autoload.php'; // Certifique-se de ter o SDK do Mercado Pago instalado

use MercadoPago\MercadoPagoConfig;
use MercadoPago\Resources\Preference;
use MercadoPago\Resources\Preference\Item;

MercadoPagoConfig::setAccessToken('TEST-6407040579836872-021313-586867a2ff0b976d29727d592f3e630e-444251439'); // Substitua pelo seu token de acesso

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Coletar o plano escolhido
    $plano = $_POST['plano'];
    $preco = 0;

    // Definir preço baseado no plano
    if ($plano === 'Plano Básico') {
        $preco = 50; // Valor para o Plano Básico
    } elseif ($plano === 'Plano Premium') {
        $preco = 100; // Valor para o Plano Premium
    }

    // Criar uma preferência de pagamento (exemplo)
    $preference = new Preference();

    // Configurar os itens da preferência (o plano escolhido e o valor correspondente)
    $item = new Item();
    $item->title = $plano;
    $item->quantity = 1;
    $item->unit_price = $preco;

    $preference->items = array($item);
    $preference->save();

    // Redireciona para o Mercado Pago para o pagamento
    header("Location: " . $preference->init_point);
    exit();
}
?>



