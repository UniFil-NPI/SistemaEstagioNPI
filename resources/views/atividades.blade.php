<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Gerenciamento de Notas</title>

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

        .content {
            margin-top: 100px;
            padding: 20px;
        }

        .btn-save {
            background-color: #28a745;
            color: white;
        }

        .btn-save:hover {
            background-color: #218838;
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

    <div class="container content">
        <h1 class="mb-4">Atividades das Classrooms</h1>

        @foreach($classrooms as $classroom)
            <div class="card mb-4">
                <div class="card-header">
                    <h2>{{ $classroom->nome }}</h2>
                </div>
                <div class="card-body">
                    @if($classroom->atividades->isEmpty())
                        <p class="text-center">Nenhuma atividade encontrada para esta sala.</p>
                    @else
                        <form method="POST" action="/atividades/{{ $classroom->id_classroom }}/email/{{ $email_aluno }}/salvarnotas">
                            @csrf
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Nome</th>
                                        <th>Data de Criação</th>
                                        <th>Data de Entrega</th>
                                        <th>Nota</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($classroom->atividades as $atividade)
                                        <tr>
                                            <td>{{ $atividade->titulo }}</td>
                                            <td>{{ $atividade->data_criacao }}</td>
                                            <td>{{ $atividade->data_entrega }}</td>
                                            <td>
                                                <input type="number" class="form-control"
                                                    name="notas[{{ $atividade->id_atividade }}]"
                                                    value="{{ $atividade->nota ?? '' }}" 
                                                    min="0" max="100" step="1"
                                                    placeholder="Nota">
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <button type="submit" class="btn btn-save">Salvar Notas</button>
                        </form>
                    @endif
                </div>
            </div>
        @endforeach
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
