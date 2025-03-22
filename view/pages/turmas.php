<div class="turmas">
    <div class="menu_home">
        <a href="#home" class="voltar"><i class="fa-solid fa-arrow-left-long"></i> Voltar ao Início</a>
        <a class="btn-cadastrar" id="cadastroTurm">Cadastrar Turmas</a>
    </div>
    <div class="table">
        <table>
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Escola</th>
                    <th></th>
                </tr>
            </thead>
            <tbody id="listTurmas">
                
            </tbody>
        </table>
    </div>
</div>

<div id="cadastroTurmasModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>

        <div class="cadastroTurm">
            <form action="../model/cadastrarTurmas.php" method="post" class="form-default">
                <input type="text" name="nameTurm" id="nameTurm" placeholder="Infome o nome da turma">
                <input id="submit" type="submit" value="Cadastrar">
            </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $("#cadastroTurm").click(function() {
            $("#cadastroTurmasModal").fadeIn();
        });
        $(".close, .modal").click(function(event) {
            if (event.target === this) {
                $("#cadastroTurmasModal").fadeOut();
            }
        });
    });

    $(document).ready(function() {
        function carregarTurmas() {
            $.ajax({
                url: "../model/listarTurmas.php",
                type: "GET",
                dataType: "json",
                success: function(data) {
                    let html = "";
                    $.each(data, function(index, turmas) {
                        html += `<tr>
                                    <td>${turmas.turma}</td>
                                    <td>${turmas.escola}</td>
                                    <td>
                                        <div class='div-options'>
                                            <a class="options"><i class="fa-solid fa-pen"></i></a>
                                            <a class="options"><i class="fa-solid fa-trash-can"></i></a> 
                                        </div>
                                    </td>
                                </tr>`;
                    });
                    $("#listTurmas").html(html);
                },
                error: function(xhr, status, error) {
                    console.error("Erro ao carregar turmas: " + error);
                }
            });
        }

        // Chamar a função ao carregar a página
        carregarTurmas();
    });
</script>