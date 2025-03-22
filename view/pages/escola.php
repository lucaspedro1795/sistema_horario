<div class="disciplinas">
    <div class="menu_home">
        <a href="#home" class="voltar"><i class="fa-solid fa-arrow-left-long"></i> Voltar ao Início</a>
        <a class="btn-cadastrar" id="cadastroDisc">Cadastrar Disciplina</a>
    </div>
    <div class="table">
        <table>
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>CNPJ</th>
                    <th>Qtd. Aulas</th>
                    <th>Endereço</th>
                    <th></th>
                </tr>
            </thead>
            <tbody id="listEscola">
            </tbody>
        </table>
    </div>
</div>

<div id="cadastroDiscModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>

        <div class="cadastroDisc">
            <form action="" method="post" class="form-cadastro">
                <input type="text" name="nameDisc" id="nameDisc" placeholder="Infome o nome da disciplina">
                <select name="" id="">
                    <option value="">Linguagens e Códigos</option>
                    <option value="">Ciências Exatas e Códigos</option>
                    <option value="">Ciências Humanas e Códigos</option>
                    <option value="">Ciências Natureza e Códigos</option>
                </select>
            </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $("#cadastroDisc").click(function() {
            $("#cadastroDiscModal").fadeIn();
        });
        $(".close, .modal").click(function(event) {
            if (event.target === this) {
                $("#cadastroDiscModal").fadeOut();
            }
        });
    });

    $(document).ready(function() {
        function carregarEscola() {
            $.ajax({
                url: "../model/listarEscola.php",
                type: "GET",
                dataType: "json",
                success: function(data) {
                    let html = "";
                    $.each(data, function(index, escola) {
                        html += `<tr>
                                    <td>${escola.nome}</td>
                                    <td>${escola.cnpj ? escola.cnpj : ''}</td>
                                    <td>${escola.qtd_aulas}</td>
                                    <td>${escola.endereco}</td>
                                    <td>
                                        <div class='div-options'>
                                            <a class="options"><i class="fa-solid fa-pen"></i></a>
                                            <a class="options"><i class="fa-solid fa-trash-can"></i></a> 
                                        </div>
                                    </td>
                                </tr>`;
                    });
                    $("#listEscola").html(html);
                },
                error: function(xhr, status, error) {
                    console.error("Erro ao carregar escolas: " + error);
                }
            });
        }

        // Chamar a função ao carregar a página
        carregarEscola();
    });
</script>