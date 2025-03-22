<div class="filiais">
    <div class="menu_home">
        <a href="#home" class="voltar"><i class="fa-solid fa-arrow-left-long"></i> Voltar ao Início</a>
        <a class="btn-cadastrar" id="cadastroFil">Cadastrar Filiais</a>
    </div>
    <div class="table">
        <table>
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Endereço</th>
                    <th>CNPJ</th>
                    <th>Matriz</th>
                </tr>
            </thead>
            <tbody id="listFiliais">
            </tbody> 
        </table>
    </div>
</div>

<div id="cadastroFilModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>

        <div class="cadastroDisc">
            <form action="" method="post" class="form-default">
                <input type="text" name="nameDisc" id="nameDisc" placeholder="Infome o nome da Filial" required>
                <input type="text" name="endereco" id="endereco" placeholder="Infome o endereço da Filial" required>
                <input type="text" name="cnpj" id="cnpj" placeholder="Infome o CNPJ da Filial" required>
                <input id="submit" type="submit" value="Cadastrar Filial">
            </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $("#cadastroFil").click(function() {
            $("#cadastroFilModal").fadeIn();
        });
        $(".close, .modal").click(function(event) {
            if (event.target === this) {
                $("#cadastroFilModal").fadeOut();
            }
        });
    });

    $(document).ready(function() {
        function carregarFiliais() {
            $.ajax({
                url: "../model/listarFiliais.php",
                type: "GET",
                dataType: "json",
                success: function(data) {
                    let html = "";
                    $.each(data, function(index, filiais) {
                        html += `<tr>
                                    <td>${filiais.filial}</td>
                                    <td>${filiais.endereco}</td>
                                    <td>${filiais.cnpj ? filiais.cnpj : ''}</td>
                                    <td>${filiais.matriz}</td>
                                </tr>`;
                    });
                    $("#listFiliais").html(html);
                },
                error: function(xhr, status, error) {
                    console.error("Erro ao carregar filiais: " + error);
                }
            });
        }

        // Chamar a função ao carregar a página
        carregarFiliais();
    });
</script>