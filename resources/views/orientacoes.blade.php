<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Orientações</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">

    <style>
        body {
            background-color: lightgray;
            margin-top: 20px;
            font-family: 'Arial', sans-serif;
        }

        .navbar {
            background-color: #F29400;
            padding: 10px 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
            height: 65px;
        }

        .content {
            margin-top: 100px;
            padding: 20px;
        }

        .btn-add {
            background-color: #28a745;
            color: white;
        }

        .btn-add:hover {
            background-color: #218838;
        }

        .btn-delete {
            color: red;
        }

        .btn-delete:hover {
            color: darkred;
        }

        .satisfaction-note {
            font-size: 1.2em;
            margin-bottom: 10px;
        }

        .fa-star {
            color: orange;
            margin-right: 5px;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light fixed-top">
        <a href="/listaralunos/{{$etapa_aluno}}" class="btn-back">
            <i class="fa fa-arrow-left"></i>
        </a>
        <div class="logo">
            <img src="{{asset('/images/logounifil.png')}}" alt="Logo Unifil">
        </div>
    </nav>

    <div class="content container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Orientações do Aluno</h2>
            <!-- Botão para abrir modal de adicionar orientação -->
            <button class="btn btn-add" data-bs-toggle="modal" data-bs-target="#adicionarOrientacaoModal"><i class="fa fa-plus"></i> Registrar Orientação</button>
        </div>

        <!-- Exibe a lista de orientações -->
        <div class="accordion" id="etapasAccordion">
            @foreach ($orientacoes as $orientacao)
                <div class="accordion-item">
                    <h2 class="accordion-header" id="heading{{ $orientacao->id }}">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#orientacao{{ $orientacao->id }}"
                            aria-expanded="true" aria-controls="orientacao{{ $orientacao->id }}">
                            Orientação {{ $orientacao->data_orientacao }}
                        </button>
                    </h2>
                    <div id="orientacao{{ $orientacao->id }}" class="accordion-collapse collapse show" aria-labelledby="heading{{ $orientacao->id }}"
                        data-bs-parent="#etapasAccordion">
                        <div class="accordion-body">
                            <div class="activity-list">
                                <div class="satisfaction-note">
                                    <strong>Nota de satisfação: {{ $orientacao->grau_satisfacao }}</strong>
                                </div>

                                <!-- Exibe as estrelas com base na nota de satisfação -->
                                <div id="stars-{{ $orientacao->id }}">
                                    @for ($i = 0; $i < $orientacao->grau_satisfacao; $i++)
                                        <i class="fa fa-star"></i>
                                    @endfor
                                </div>

                                <!-- Descrição da orientação -->
                                <div class="descricao-orientacao">
                                    <p><strong>Descrição:</strong> {{ $orientacao->descricao }}</p>
                                </div>

                                <!-- Excluir orientação -->
                                <form action="/orientacoes/deletar/{{$orientacao->id_orientacao}}/email/{{ $email_aluno }}/etapa/{{ $etapa_aluno }}" method="POST" style="display:inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-delete"><i class="fa fa-trash"></i></button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Modal para adicionar nova orientação -->
    <div class="modal fade" id="adicionarOrientacaoModal" tabindex="-1" aria-labelledby="adicionarOrientacaoModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="adicionarOrientacaoModalLabel">Adicionar Nova Orientação</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="orientacaoForm" action="/orientacoes/registrar/{{$email_aluno}}/etapa/{{ $etapa_aluno }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="comparecimento" class="form-label">Comparecimento</label>
                            <select id="comparecimento" name="comparecimento" class="form-select" required>
                                <option value="1">Sim</option>
                                <option value="0">Não</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="descricao" class="form-label">Descrição</label>
                            <textarea id="descricao" name="descricao" class="form-control" rows="3" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="grauSatisfacao" class="form-label">Grau de Satisfação</label>
                            <input type="number" id="grauSatisfacao" name="grauSatisfacao" class="form-control" min="1" max="5" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Registrar</button>
                    </form>
                </div>
            </div>
        </div>

    </div>


    <script>
    const comparecimentoSelect = document.getElementById('comparecimento');
    const grauSatisfacaoInput = document.getElementById('grauSatisfacao');

    comparecimentoSelect.addEventListener('change', function() {
        if (this.value === '0') {
            grauSatisfacaoInput.value = 1;
            grauSatisfacaoInput.setAttribute('readonly', true); // Bloqueia a edição
        } else {
            grauSatisfacaoInput.removeAttribute('readonly'); // Permite a edição quando o valor for "Sim"
        }
    });
    </script>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
