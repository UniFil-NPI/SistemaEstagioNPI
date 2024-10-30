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

        .navbar .logo {
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .navbar .logo img {
            height: 70%;
            width: 70%;
        }

        .navbar .btn-back {
            font-size: 1.5em;
            color: #707173; /* Cinza */
        }

        .navbar .btn-back:hover {
            color: #FFF; /* Branco */
        }

        .navbar .btn-outline-primary {
            color: #707173; /* Cinza */
            border-color: #707173; /* Cinza */
            transition: background-color 0.3s ease;
        }

        .navbar .btn-outline-primary:hover {
            background-color: #707173; /* Cinza */
            color: #FFF;
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
        <a href="/listaralunos/{{ $etapa_aluno }}" class="btn-back">
            <i class="fa fa-arrow-left"></i>
        </a>
        <div class="logo">
            <img src="{{asset('/images/logounifil.png')}}" alt="Logo Unifil">
        </div>
    </nav>

    <div class="content container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Relatórios Periódicos de Orientação Direta (RPODS)</h2>
            <!-- Botão para abrir modal de adicionar relatório -->
            <button class="btn btn-add" data-bs-toggle="modal" data-bs-target="#adicionarRpodModal"><i class="fa fa-plus"></i> Adicionar Relatório</button>
        </div>

        <div class="row row-cols-1 row-cols-md-2 g-4">
            @foreach ($rpods as $rpod)
                <div class="col">
                    <div class="card">
                        <div class="card-body">
                            <div>
                                <h5 class="card-title">RPODS {{ $rpod->data_entrega }}</h5>
                            </div>
                            <div>
                                <!-- Botão para visualizar o relatório -->
                                <button class="btn btn-view" data-bs-toggle="modal" data-bs-target="#modalPdf"
                                    onclick="carregarPdf('data:application/pdf;base64,{{ $rpod->base_64 }}')">Visualizar</button>

                                <!-- Botão para deletar -->
                                <form action="/rpods/excluir/{{ $rpod->email_aluno }}/id/{{ $rpod->id_rpod }}/etapa/{{ $etapa_aluno }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('POST')
                                    <button type="submit" class="btn btn-delete"><i class="fa fa-trash"></i></button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Modal para visualizar PDF -->
    <div class="modal fade" id="modalPdf" tabindex="-1" aria-labelledby="modalPdfLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalPdfLabel">Relatório</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- PDF embutido -->
                    <embed id="pdfViewer" src="" type="application/pdf" width="100%" height="600px" />
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para adicionar relatório -->
    <div class="modal fade" id="adicionarRpodModal" tabindex="-1" aria-labelledby="adicionarRpodModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="adicionarRpodModalLabel">Adicionar Relatório</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="rpodForm" action="/rpods/registrar/data/email/{{ $email_aluno }}/etapa/{{$etapa_aluno}}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="rpod" class="form-label">Selecione o arquivo RPOD (PDF)</label>
                            <input type="file" class="form-control" id="rpod" name="rpod" required>
                        </div>
                        <!-- Campo para selecionar a data -->
                        <div class="mb-3">
                            <label for="data_entrega" class="form-label">Data de Entrega</label>
                            <input type="date" class="form-control" id="data_entrega" name="data_entrega" value="{{ now()->format('Y-m-d') }}" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Adicionar Relatório</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function carregarPdf(pdfBase64) {
            const pdfViewer = document.getElementById('pdfViewer');
            pdfViewer.setAttribute('src', pdfBase64);
        }

        document.getElementById('rpodForm').addEventListener('submit', function(event) {
            event.preventDefault();
            const dataEntrega = document.getElementById('data_entrega').value;
            const formAction = `/rpods/registrar/${dataEntrega}/email/{{ $email_aluno }}/etapa/{{$etapa_aluno}}`;
            this.action = formAction;
            this.submit();
        });

    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>


























