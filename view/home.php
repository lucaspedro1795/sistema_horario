<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bandeira - Sistema de Horários</title>
    <link rel="stylesheet" href="../css/sty.css" />
    <link rel="shortcut icon" href="../images/bandeira.webp" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body>
    <?php
    require '../model/conexao.php';
    session_start();

    // Verifica se a sessão do usuário está definida
    if (!isset($_SESSION['user'])) {
        header("Location: ../index.php"); // Redireciona para login se não estiver autenticado
        exit();
    }

    $user = $_SESSION['user'];

    $pdo = Connection::getInstance();

    $stmt = $pdo->prepare("SELECT foto FROM users WHERE id = :foto");
    $stmt->bindParam(':foto', $user['id'], PDO::PARAM_INT);
    $stmt->execute();
    $foto = $stmt->fetch(PDO::FETCH_ASSOC);

    // Se houver foto, converter para base64
    if ($foto && !empty($foto['foto'])) {
        $imagemPerfil = "data:image/jpeg;base64," . base64_encode($foto['foto']);
    } else {
        $imagemPerfil = "../images/user-default.png"; // Caminho da imagem padrão
    }

    // Ajuste para acessar o tipo de usuário corretamente
    if (isset($user['tipoUser']) && ($user['tipoUser'] == 'Administrador' || $user['tipoUser'] == 'Operador')) {
    ?>
        <nav class="menu">
            <div class="logo">
                <img src="../images/bandeira.webp" alt="">
                <div class="text-logo">
                    <h1>Bandeira</h1>
                    <h5>Horários Escolares</h5>
                </div>
            </div>
            <ul>
                <li class="links"><a href="#home" data-route="true"><i class="fa-solid fa-house"></i> Home</a></li>
                <li class="links" id="dropdown">
                    <a href="#"><i class="fa-solid fa-folder-plus"></i> Cadastros <i class="fa-solid fa-caret-down"></i></a>
                    <ul id="dropdown-menu">
                        <li><a href="#disciplinas" data-route="true"><i class="fa-solid fa-book-open"></i> Disciplinas</a></li>
                        <li><a href="#turmas" data-route="true"><i class="fa-solid fa-users"></i> Turmas</a></li>
                        <li><a href="#professores" data-route="true"><i class="fa-solid fa-person-chalkboard"></i> Professores</a></li>
                        <li><a href="#filiais" data-route="true"><i class="fa-solid fa-building-columns"></i> Filiais</a></li>
                        <hr>
                        <li><a href="#escola" data-route="true"><i class="fa-solid fa-school"></i> Escola</a></li>
                        <li><a href="#usuarios" data-route="true"><i class="fa-solid fa-user-tie"></i> Usuários</a></li>
                    </ul>
                </li>
                <li class="links"><a href="#gerarhorario" data-route="true"><i class="fa-regular fa-calendar-days"></i> Gerar Horário</a></li>
                <li class="links"><a href="#relatorios" data-route="true"><i class="fa-solid fa-chart-line"></i> Relatórios</a></li>
            </ul>
            <div class="user">
                <a href="" class="" id="dropdown-us"><img class="phUser" src="<?php echo $imagemPerfil; ?>" alt=""></a>
                <ul id="dropdown-user">
                    <li id="dataUser">
                        <img class="phUser" src="<?php echo $imagemPerfil; ?>" alt="">
                        <p><?php echo $user['nome'] ?></p>
                    </li>
                    <li><a href=""><i class="fa-solid fa-unlock"></i> Alterar senha</a></li>
                    <li><a href=""><i class="fa-solid fa-credit-card"></i> Assinatura</a></li>
                    <li><a href="../model/userLogoff.php" class="off"><i class="fa-regular fa-circle-xmark"></i> Sair</a></li>
                </ul>
            </div>
        </nav>
    <?php } else { ?>
        <nav class="menu">
            <div class="logo">
                <img src="../images/bandeira.webp" alt="">
                <div class="text-logo">
                    <h1>Bandeira</h1>
                    <h5>Horários Escolares</h5>
                </div>
            </div>
            <ul>
                <li class="links"><a href="#home" data-route="true"><i class="fa-solid fa-house"></i> Home</a></li>
                <li class="links"><a href="#disponibilidade" data-route="true"><i class="fa-solid fa-clock"></i> Cadastrar Disponibilidade</a></li>
                <li class="links"><a href="#meuhorario" data-route="true"><i class="fa-solid fa-address-book"></i> Meu horário</a></li>
            </ul>
            <div class="user">
                <a href="" class="" id="dropdown-us"><img class="phUser" src="<?php echo $imagemPerfil; ?>" alt=""></a>
                <ul id="dropdown-user">
                    <li id="dataUser">
                        <img class="phUser" src="<?php echo $imagemPerfil; ?>" alt="">
                        <p><?php echo $user['nome'] ?></p>
                    </li>
                    <li><a href=""><i class="fa-solid fa-unlock"></i> Alterar senha</a></li>
                    <!-- <li><a href=""><i class="fa-solid fa-credit-card"></i> Assinatura</a></li> -->
                    <li><a href="../model/userLogoff.php" class="off"><i class="fa-regular fa-circle-xmark"></i> Sair</a></li>
                </ul>
            </div>
        </nav>
    <?php } ?>


    <div class="main">
        <div id="content"></div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            function carregarPagina() {
                let pagina = location.hash.substring(1) || "home"; // Define "home" como padrão
                $("#content").load(`pages/${pagina}.php`, function(response, status) {
                    if (status === "error") {
                        $("#content").html("<h2>Página não encontrada!</h2>");
                    }
                });
            }

            // Carregar página ao mudar o hash
            $(window).on("hashchange", carregarPagina);

            // Carregar a página inicial ao entrar no site
            carregarPagina();

            // Interceptar apenas os links com data-route="true"
            $("a[data-route='true']").on("click", function(event) {
                event.preventDefault();
                let link = $(this).attr("href").replace(".php", ""); // Remove a extensão .php
                location.hash = link; // Atualiza o hash na URL
            });
        });
    </script>


    <script>
        // document.getElementById("btn-logoff").addEventListener("click", function() {
        //     fetch("../model/userLogoff.php")
        //         .then(response => response.json())
        //         .then(data => {
        //             if (data.success) {
        //                 window.location.href = "../index.php"; // Redireciona para a tela de login
        //             } else {
        //                 alert("Erro ao encerrar a sessão!");
        //             }
        //         })
        //         .catch(error => {
        //             console.error("Erro ao conectar ao servidor:", error);
        //         });
        // });

        document.addEventListener("DOMContentLoaded", function() {
            const dropdown = document.getElementById("dropdown");
            const dropdownMenu = document.getElementById("dropdown-menu");

            dropdown.addEventListener("click", function(event) {
                event.preventDefault(); // Evita que o link redirecione
                dropdownMenu.style.display = dropdownMenu.style.display === "block" ? "none" : "block";
            });

            // Fechar o dropdown ao clicar fora dele
            document.addEventListener("click", function(event) {
                if (!dropdown.contains(event.target) && !dropdownMenu.contains(event.target)) {
                    dropdownMenu.style.display = "none";
                }
            });
        });

        document.addEventListener("DOMContentLoaded", function() {
            const dropdown = document.getElementById("dropdown");
            const dropdownMenu = document.getElementById("dropdown-menu");
            const caretIcon = dropdown.querySelector("i.fa-caret-down, i.fa-caret-up");

            dropdown.addEventListener("click", function(event) {
                event.preventDefault(); // Evita o redirecionamento ao clicar no link

                // Alternar visibilidade do menu com a classe 'show'
                const isOpen = dropdownMenu.classList.contains("show");
                dropdownMenu.classList.toggle("show", !isOpen);

                // Alternar ícone com transição
                if (caretIcon) {
                    caretIcon.classList.toggle("fa-caret-down", isOpen);
                    caretIcon.classList.toggle("fa-caret-up", !isOpen);
                }
            });

            // Fechar o dropdown ao clicar fora
            document.addEventListener("click", function(event) {
                if (!dropdown.contains(event.target) && !dropdownMenu.contains(event.target)) {
                    dropdownMenu.classList.remove("show");

                    // Garantir que o ícone volte para "fa-caret-down" ao fechar
                    if (caretIcon) {
                        caretIcon.classList.add("fa-caret-down");
                        caretIcon.classList.remove("fa-caret-up");
                    }
                }
            });
        });


        document.addEventListener("DOMContentLoaded", function() {
            const dropdownUs = document.getElementById("dropdown-us");
            const dropdownUser = document.getElementById("dropdown-user");

            dropdownUs.addEventListener("click", function(event) {
                event.preventDefault(); // Evita que o link redirecione
                dropdownUser.style.display = dropdownUser.style.display === "block" ? "none" : "block";
            });

            // Fechar o dropdown ao clicar fora dele
            document.addEventListener("click", function(event) {
                if (!dropdownUs.contains(event.target) && !dropdownUser.contains(event.target)) {
                    dropdownUser.style.display = "none";
                }
            });
        });
    </script>
    
</body>

</html>