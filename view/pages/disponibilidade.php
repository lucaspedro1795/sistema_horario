<div class="disponibilidade">
    <div>
        <table class="tabelaCadas">
            <thead>
                <tr>
                    <th class="unico">Aulas</th>
                    <th class="unico">Segunda</th>
                    <th class="unico">Terça</th>
                    <th class="unico">Quarta</th>
                    <th class="unico">Quinta</th>
                    <th class="unico">Sexta</th>
                </tr>
            </thead>
            <tbody id="tabela-aulas">
                <!-- As linhas serão geradas aqui pelo JavaScript -->
            </tbody>
        </table>
        <br>
        <button id="salvarDisponibilidade">Salvar Disponibilidade</button>
    </div>
    <div>
        <h2>Minha disponibilidade Atual</h2>
        <br>
        <table class="tabelaCadas">
            <thead>
                <tr>
                    <th>Aulas</th>
                    <th>Segunda</th>
                    <th>Terça</th>
                    <th>Quarta</th>
                    <th>Quinta</th>
                    <th>Sexta</th>
                </tr>
            </thead>
            <tbody id="listDisponibilidade">
                <!-- As linhas serão geradas aqui pelo JavaScript -->

            </tbody>
        </table>
    </div>

</div>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        function carregarEscola() {
            $.ajax({
                url: "../model/consultaEscola.php",
                type: "GET",
                dataType: "json",
                success: function(data) {
                    if (data.length > 0) {
                        let qtdAulas = data[0].qtd_aulas; // Número de aulas
                        gerarTabelaAulas(qtdAulas);
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Erro ao carregar dados da escola: " + error);
                }
            });
        }

        function gerarTabelaAulas(qtdAulas) {
            const tabelaAulas = document.getElementById('tabela-aulas');
            tabelaAulas.innerHTML = ''; // Limpa a tabela antes de recriar

            for (let i = 1; i <= qtdAulas; i++) {
                const tr = document.createElement('tr');

                // Nome da aula (Ex: "1ª Aula")
                const tdAula = document.createElement('td');
                tdAula.textContent = `${i}ª Aula`;
                tr.appendChild(tdAula);

                // Criando células com checkboxes para cada dia
                for (let j = 0; j < 5; j++) { // 5 dias úteis
                    const td = document.createElement('td');
                    const checkbox = document.createElement('input');
                    checkbox.type = 'checkbox';
                    checkbox.id = `aula${i}-dia${j}`;
                    checkbox.dataset.aula = i; // Armazena o número da aula
                    checkbox.dataset.dia = j; // Armazena o dia da semana
                    td.appendChild(checkbox);
                    tr.appendChild(td);
                }

                tabelaAulas.appendChild(tr);
            }
        }

        carregarEscola();

        

        // Capturar e enviar os dados da tabela para o PHP
        $("#salvarDisponibilidade").click(function() {
            let disponibilidades = [];
            let idProfessor = 1; // Você pode pegar esse ID de outro local, como session ou dropdown

            $("#tabela-aulas input[type='checkbox']").each(function() {
                let aula = $(this).data("aula");
                let dia = $(this).data("dia");
                let checked = $(this).prop("checked") ? 1 : 0;

                disponibilidades.push({
                    aula,
                    dia,
                    checked
                });
            });

            $.ajax({
                url: "../model/cadastrarDisponibilidade.php",
                type: "POST",
                data: JSON.stringify({
                    id_professor: idProfessor,
                    disponibilidades: disponibilidades
                }),
                contentType: "application/json",
                success: function(response) {
                    console.log(response);
                    location.reload();
                },
                error: function(xhr, status, error) {
                    console.error("Erro ao salvar disponibilidade: " + error);
                }
            });
        });
    });

    $(document).ready(function() {
        function mapearNumeroAula(numeroAula) {
            const aulas = [
                "1ª Aula", "2ª Aula", "3ª Aula", "4ª Aula", "5ª Aula", "6ª Aula", "7ª Aula"
            ];
            return aulas[numeroAula - 1] || ''; // Se o numeroAula for maior que 7, retorna vazio
        }

        function carregarDisponibilidade() {
            $.ajax({
                url: "../model/listarDisponibilidade.php",
                type: "GET",
                dataType: "json",
                success: function(data) {
                    let html = "";
                    $.each(data, function(index, disponibilidade) {
                        html += `<tr>
                                <td>${mapearNumeroAula(disponibilidade.numero_aula)}</td>
                                <td class="diff">${Number(disponibilidade.segunda) === 1 ? '<i class="fa-solid fa-circle-check"></i>' : '<i class="fa-solid fa-circle-xmark"></i>'}</td>
                                <td class="diff">${Number(disponibilidade.terca) === 1 ? '<i class="fa-solid fa-circle-check"></i>' : '<i class="fa-solid fa-circle-xmark"></i>'}</td>
                                <td class="diff">${Number(disponibilidade.quarta) === 1 ? '<i class="fa-solid fa-circle-check"></i>' : '<i class="fa-solid fa-circle-xmark"></i>'}</td>
                                <td class="diff">${Number(disponibilidade.quinta) === 1 ? '<i class="fa-solid fa-circle-check"></i>' : '<i class="fa-solid fa-circle-xmark"></i>'}</td>
                                <td class="diff">${Number(disponibilidade.sexta) === 1 ? '<i class="fa-solid fa-circle-check"></i>' : '<i class="fa-solid fa-circle-xmark"></i>'}</td>
                            </tr>
                            ;`;
                    });
                    $("#listDisponibilidade").html(html);
                },
                error: function(xhr, status, error) {
                    console.error("Erro ao carregar disponibilidade: " + error);
                }
            });
        }

        // Chamar a função ao carregar a página
        carregarDisponibilidade();
    });
</script>