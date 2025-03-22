<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Bandeira - Sistema de Horários</title>
    <link rel="stylesheet" href="../../css/sty.css" />
    <link rel="shortcut icon" href="../../images/bandeira.webp" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body>
    <div class="container">
        <div class="form">
            <div class="logo"><img src="../../images/bandeira.webp" alt=""></div>
            <form id="registrationForm" action="../../controller/cadastrar_escola.php" method="POST">
                <div class="input">
                    <span>Nome da Escola</span>
                    <input type="text" name="escola" id="escola" required placeholder="Nome da Escola" />
                </div>
                <div class="input">
                    <span>CNPJ</span>
                    <input type="text" name="cnpj" id="cnpj" required placeholder="88.888.888/8888-88" />
                </div>
                <div class="input">
                    <span>Endereço</span>
                    <input type="text" name="endereco" id="endereco" required placeholder="Rua Fulano,123" />
                </div>
                <div class="input">
                    <span>Quantidade de Aulas</span>
                    <input type="text" name="qtdAulas" id="qtdAulas" required placeholder="1,2,3" />
                </div>
                <input type="submit" id="input-login" value="Cadastrar Escola" />
            </form>

        </div>
        <br>
        <span>Desenvolvido por Pedro Lucas</span>
    </div>

    <script src="./js/main.js"></script>
</body>

</html>