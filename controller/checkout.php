<?php
session_start();

require_once "../vendor/autoload.php";

use MercadoPago\MercadoPagoConfig;
use MercadoPago\Client\Preference;
use MercadoPago\Client\Preference\PreferenceClient;

// Verifica se a requisição é POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verifica se o ID do usuário e o plano foram passados
    if (!isset($_POST['user_id']) || !isset($_POST['plano'])) {
        die("Informações inválidas.");
    }

    $userId = $_POST['user_id'];  // Obtém o ID do usuário da sessão
    $plano = $_POST['plano'];     // Obtém o plano escolhido

    // Valores dos planos
    $valores = [
        "essential" => 99.90,
        "professional" => 249.90,
        "premium" => 499.90
    ];

    // Verifica se o plano é válido
    if (!isset($valores[$plano])) {
        die("Plano inválido.");
    }

    $valor = $valores[$plano];
    
    MercadoPagoConfig::setAccessToken('APP_USR-6407040579836872-021313-99a2cb44e48c5e486ed15ab3b3625e3b-444251439');

    // Cria uma preferência de pagamento
    $client = new PreferenceClient();

    // Cria um item para a preferência

    $preference = $client->create([
        "items"=> array(
          array(
            "title" => "Plano $plano",
            "quantity" => 1,
            "unit_price" => $valor
          )
        )
      ]);
    // $item = new MercadoPago\Item();
    // $item->title = "Plano $plano";
    // $item->quantity = 1;
    // $item->unit_price = $valor;

    // // Adiciona o item à preferência
    // $preference->items = array($item);

    // // Configura URLs de retorno após o pagamento
    // $preference->back_urls = array(
    //     "success" => "http://seusite.com.br/sucesso.php",
    //     "failure" => "http://seusite.com.br/falha.php",
    //     "pending" => "http://seusite.com.br/pendente.php"
    // );

    // // Configura o método de pagamento e outras preferências
    // $preference->payment_methods = array(
    //     "excluded_payment_types" => array(
    //         array("id" => "atm")
    //     ),
    //     "installments" => 6
    // );

    // // Define o status da preferência
    // $preference->auto_return = "approved";  // Configura para redirecionar após aprovação do pagamento

    // // Salva a preferência
    // $preference->save();  // A diferença aqui é que o método "save()" realmente é necessário para enviar a preferência

    // Redireciona o usuário para o checkout do Mercado Pago
    header("Location: " . $preference->init_point);  // URL para o checkout
    exit;
}
?>
