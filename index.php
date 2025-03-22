<!DOCTYPE html>
<html lang="pt-BR">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Bandeira - Sistema de Horários</title>
    <link rel="stylesheet" href="./css/sty.css" />
    <link rel="shortcut icon" href="./images/bandeira.webp" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  </head>
  <body>
    <div class="container">
      <div class="form">
        <div class="logo"><img src="./images/bandeira.webp" alt=""></div>
        <form id="loginForm">
            <div class="input">
                <span>E-mail</span>
                <input type="email" name="email" id="email" required placeholder="pedro@pedro.com.br" />
            </div>
            <div class="input">
                <span>Senha</span>
                <input type="password" name="senha" id="senha" required placeholder="*********"/>
            </div>
            <p id="message"></p>
          <input type="submit" id="input-login" value="Entrar" />
          <a href="./view/pages/cadastroEscola.php" style="text-align: center; margin: 10px;">Não possue acesso? Adquira já o seu sistema</a>
          <br>
        </form>
      </div>
      <br>
      <span>Desenvolvido por Pedro Lucas</span>
    </div>
    
    <script src="./js/main.js"></script>
  </body>
</html>
