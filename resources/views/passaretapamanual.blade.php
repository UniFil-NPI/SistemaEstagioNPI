<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Avaliar Alunos por Etapa</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">

    <style>
        body {
            margin-top: 20px;
            background-color: lightgray;
        }

        .navbar {
            background-color: #F29400; /* Laranja */
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

        .container {
            margin-top: 80px; /* Espaço entre navbar e conteúdo principal */
            padding: 20px;
        }

        h1 {
            font-size: 1.8em;
            color: #333;
            margin-bottom: 20px;
        }

        /* Tabela de Alunos */
        .table {
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.1);
        }

        .table th, .table td {
            vertical-align: middle;
            padding: 15px;
        }

        .table th {
            background-color: #f2f2f2;
            color: #F29400; /* Laranja */
        }

        .table input[type="checkbox"] {
            width: 20px;
            height: 20px;
        }

        /* Estilo dos botões */
        .btn-primary, .btn-success, .btn-danger {
            font-size: 1.1em;
            padding: 10px 20px;
            border-radius: 8px;
        }

        .btn-primary {
            background-color: #F29400; /* Laranja */
            border-color: #F29400;
        }

        .btn-primary:hover {
            background-color: #e47c00;
        }

        .btn-success {
            background-color: #28a745; /* Verde */
            border-color: #28a745;
        }

        .btn-success:hover {
            background-color: #218838;
        }

        .btn-danger {
            background-color: #dc3545; /* Vermelho */
            border-color: #dc3545;
        }

        .btn-danger:hover {
            background-color: #c82333;
        }

        /* Modal Customizado */
        .modal-content {
            border-radius: 8px;
            border: 1px solid #f29400;
        }

        .modal-header {
            background-color: #f29400;
            color: #fff;
        }

        .modal-footer .btn-primary {
            background-color: #f29400;
            border-color: #f29400;
        }

        .modal-footer .btn-secondary {
            background-color: #707173;
            border-color: #707173;
        }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-light fixed-top">
        <a href="/admin" class="btn-back">
            <i class="fa fa-arrow-left"></i>
        </a>
        <div class="logo">
            <img src="{{asset('/images/logounifil.png')}}" alt="Logo Unifil">
        </div>
        <div>
            <p style="color:red">ADMIN/COORDENADORES</p>
        </div>
    </nav>

    <div class="container">
        <h1>Avaliar Alunos da Etapa {{ $etapa }}</h1>

        <!-- Tabela de Alunos -->
        <div id="alunosContainer" class="table-responsive">
        <form method="POST" action="/admin/alunosadmin/passaretapamanual/finalizar">
    @csrf
    <input type="hidden" name="classroom_id" value="{{ $novoClassroomID }}">
    <input type="hidden" name="classroom_atual" value="{{ $classroomAtual }}">
    <input type="hidden" name="etapa" value="{{ $etapa }}">

    <table class="table table-striped">
        <thead>
            <tr>
                <th>Nome</th>
                <th>Email</th>
                <th>Média Final</th>
                <th>Carga Horária</th>
                <th>Status</th>
                <th>Está no Novo Classroom?</th>
                <th>Aprovar</th> 
            </tr>
        </thead>
        <tbody>
            @foreach ($alunosAvaliados as $aluno)
                <tr>
                    <td>{{ $aluno['nome'] }}</td>
                    <td>{{ $aluno['email'] }}</td>
                    <td>{{ $aluno['media'] }}</td>
                    <td>{{ $aluno['cargaHoraria'] }} orientações</td>
                    <td>{{ $aluno['status'] }}</td>
                    <td>{{ $aluno['novoClassroom'] }}</td>
                    <td>
                        <!-- Certifique-se de que o 'aprovados[]' está com o valor do e-mail do aluno -->
                        <input type="checkbox" name="aprovados[]" value="{{ $aluno['email'] }}" 
                            {{ $aluno['status'] == 'Aprovado' ? 'checked' : '' }} 
                            {{ $aluno['novoClassroom'] == 1 ? '' : 'disabled' }}>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <button type="submit" class="btn btn-success mt-3">Finalizar Etapa</button>
    <a href="{{ url()->previous() }}" class="btn btn-danger mt-3">Cancelar</a>
</form>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
