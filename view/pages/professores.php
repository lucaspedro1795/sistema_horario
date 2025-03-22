<div class="professores">
    <div class="menu_home">
        <a href="#home" class="voltar"><i class="fa-solid fa-arrow-left-long"></i> Voltar ao Início</a>
        <a class="btn-cadastrar" id="cadastroProfs">Cadastrar Professores</a>
    </div>
    <div class="table">
        <table>
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Email</th>
                    <th>Escola</th>
                    <th></th>
                </tr>
            </thead>
            <tbody id="listProfessores">
            </tbody>
        </table>
    </div>
</div>

<div id="cadastroProfsModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>

        <div class="cadastroProfs">
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
        $("#cadastroProfs").click(function() {
            $("#cadastroProfsModal").fadeIn();
        });
        $(".close, .modal").click(function(event) {
            if (event.target === this) {
                $("#cadastroProfsModal").fadeOut();
            }
        });
    });

    $(document).ready(function() {
        function carregarProfessores() {
            $.ajax({
                url: "../model/listarProfessores.php",
                type: "GET",
                dataType: "json",
                success: function(data) {
                    let html = "";
                    $.each(data, function(index, professores) {
                        html += `<tr>
                                    <td>${professores.nome}</td>
                                    <td>${professores.email}</td>
                                    <td>${professores.nomeescola}</td>
                                    <td>
                                        <div class='div-options'>
                                            <a class="options"><i class="fa-solid fa-pen"></i></a>
                                            <a class="options"><i class="fa-solid fa-trash-can"></i></a>
                                            <a class="options"><i class="fa-solid fa-eye"></i></a>    
                                        </div>
                                    </td>
                                </tr>`;
                    });
                    $("#listProfessores").html(html);
                },
                error: function(xhr, status, error) {
                    console.error("Erro ao carregar professores: " + error);
                }
            });
        }

        // Chamar a função ao carregar a página
        carregarProfessores();
    });


</script>