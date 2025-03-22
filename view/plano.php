<?php
session_start();

// Verifica se o usuário está na sessão
if (!isset($_SESSION['user_id'])) {
    die("Usuário não autenticado.");
}

$userId = $_SESSION['user_id']; // Obtém o ID do usuário da sessão
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Bandeira - Sistema de Horários</title>
    <link rel="stylesheet" href="../css/sty.css" />
    <link rel="shortcut icon" href="../images/bandeira.webp" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />


</head>

<body>
    <div class="container">
        <div class="form">
            <div class="logo"><img src="../images/bandeira.webp" alt=""></div>
            <div class="planos">
                <div class="card-plano">
                    <h2>Essential</h2>
                    <ul>
                        <li>✅ Geração automática de horários</li>
                        <li>✅ Exportação para PDF</li>
                        <li>✅ Edição manual básica</li>
                        <li>✅ 1 perfil de administrador</li>
                    </ul>
                    <ul>
                        <li>✅ Até 5 usuários</li>
                        <li>✅ 1 escola</li>
                        <li>✅ Geração dos Horários: 2 rodadas/mês</li>
                        <li>✅ Suporte: E-mail</li>
                    </ul>
                    <br><br>
                    <h3>R$ 99,90</h3>
                    <form action="../controller/checkout.php" method="POST">
                        <input type="hidden" name="user_id" value="<?php echo $userId; ?>">
                        <input type="hidden" name="plano" value="essential">
                        <button type="submit" class="btn-assinar">Assinar</button>
                    </form>
                </div>
                <div class="card-plano">
                    <h2>Professional</h2>
                    <ul>
                        <li>✅ Tudo do Essential</li>
                        <li>✅ IA aprimorada para ajustes</li>
                        <li>✅ Drag & Drop</li>
                        <li>✅ Histórico de versões</li>
                        <li>✅ Exportação para Excel</li>
                    </ul>
                    <ul>
                        <li>✅ Até 20 usuários</li>
                        <li>✅ Até 3 escolas</li>
                        <li>✅ Geração dos Horários: 5 rodadas/mês</li>
                        <li>✅ Suporte: E-mail + Whatsapp</li>
                    </ul>
                    <br>
                    <h3>R$ 249,90</h3>
                    <form action="../controller/checkout.php" method="POST">
                        <input type="hidden" name="user_id" value="<?php echo $userId; ?>">
                        <input type="hidden" name="plano" value="professional">
                        <button type="submit" class="btn-assinar">Assinar</button>
                    </form>
                </div>
                <div class="card-plano">
                    <h2>Premium</h2>
                    <ul>
                        <li>✅ Tudo do Professional</li>
                        <li>✅ Gestão de múltiplos turnos</li>
                        <li>✅ Atribuição automática de salas</li>
                        <li>✅ Relatórios detalhados</li>
                    </ul>
                    <ul>
                        <li>✅ Até 50 usuários</li>
                        <li>✅ Até 10 escolas</li>
                        <li>✅ Geração dos Horários: Ilimitado</li>
                        <li>✅ Suporte Prioritário (WhatsApp e Videoconferência)</li>
                    </ul>
                    <h3>R$ 499,90</h3>
                    <form action="../controller/checkout.php" method="POST">
                        <input type="hidden" name="user_id" value="<?php echo $userId; ?>">
                        <input type="hidden" name="plano" value="premium">
                        <button type="submit" class="btn-assinar">Assinar</button>
                    </form>
                </div>
            </div>
        </div>
        <br>
        <span>Desenvolvido por Pedro Lucas</span>
        <br>
    </div>

    <script src="./js/main.js"></script>
</body>

</html>