
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
                    <th></th>
                </tr>
            </thead>
            <tbody id="listDisciplinas">
            </tbody>
        </table>
    </div>
</div>

<div id="cadastroDiscModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>

        <div class="cadastroDisc">
            <form action="../model/cadastrarDisciplina.php" method="post" class="form-default">
                <input type="text" name="nomeDisc" id="nomeDisc" placeholder="Infome o nome da disciplina">
                <input id="submit" type="submit" value="Cadastrar">
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
        function carregarDisciplinas() {
            $.ajax({
                url: "../model/listarDisciplinas.php",
                type: "GET",
                dataType: "json",
                success: function(data) {
                    let html = "";
                    $.each(data, function(index, disciplina) {
                        html += `<tr>
                                    <td>${disciplina.nome}</td>
                                    <td>
                                        <div class='div-options'>
                                            <a class="options"><i class="fa-solid fa-pen"></i></a>
                                            <a class="options"><i class="fa-solid fa-trash-can"></i></a> 
                                        </div>
                                    </td>
                                </tr>`;
                    });
                    $("#listDisciplinas").html(html);
                },
                error: function(xhr, status, error) {
                    console.error("Erro ao carregar disciplinas: " + error);
                }
            });
        }

        // Chamar a função ao carregar a página
        carregarDisciplinas();
    });
</script>