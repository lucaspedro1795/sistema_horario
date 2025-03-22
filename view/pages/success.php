<?php
session_start();

require_once "../vendor/autoload.php";

use MercadoPago\MercadoPagoConfig;
use MercadoPago\Client\Payment;
use MercadoPago\Client\Preference\PreferenceClient;

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
    $preference = $client->create([
        "items" => [
            [
                "title" => "Plano $plano",
                "quantity" => 1,
                "unit_price" => $valor
            ]
        ],
        "back_urls" => [
            "success" => "http://seusite.com.br/sucesso.php",
            "failure" => "http://seusite.com.br/falha.php",
            "pending" => "http://seusite.com.br/pendente.php"
        ],
        "auto_return" => "approved"
    ]);

    // Redireciona o usuário para o checkout do Mercado Pago
    header("Location: " . $preference->init_point);  // URL para o checkout
    exit;
}

// Caso o pagamento tenha sido aprovado, trata a resposta
if (isset($_GET['payment_id'])) {
    // Recupera o pagamento usando o Mercado Pago
    MercadoPagoConfig::setAccessToken('TEST-6407040579836872-021313-586867a2ff0b976d29727d592f3e630e-444251439');
    $paymentClient = new Payment();
    $payment = $paymentClient->get($_GET['payment_id']);
    
    if ($payment->status == 'approved') {
        // Atualiza o status de pagamento na tabela de usuários
        require './conexao.php';
        $pdo = Connection::getInstance();
        $stmt = $pdo->prepare("UPDATE users SET pagamento_aprovado = TRUE WHERE id = :user_id");
        $stmt->execute([
            'user_id' => $_SESSION['user_id']  // ID do usuário que fez o pagamento
        ]);

        // Redireciona para a página de sucesso ou para a área restrita
        $_SESSION['pagamento_aprovado'] = true;
        echo "Pagamento aprovado! Bem-vindo!";
        header('Location: /area-restrita.php');
        exit;
    } else {
        echo "Pagamento não aprovado.";
    }
}
?>
