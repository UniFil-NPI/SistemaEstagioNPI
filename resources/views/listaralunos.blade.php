<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Controle Alunos</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>

    <style>
        body {
            margin-top: 20px;
            background-color: lightgray;
        }

        /* Estilo da navbar */
        .navbar {
            background-color: #F29400;
            padding: 10px 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
            height: 65px;
        }

        /* Logo centralizada */
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
            color: #707173;
        }

        .navbar .btn-back:hover {
            color: #FFF;
        }

        /* Espaçamento entre navbar e conteúdo principal */
        .content-container {
            margin-top: 80px; /* Espaço entre navbar e filtros/tabela */
            padding: 20px;
        }

        /* Estilo para a tabela de alunos com fundo e bordas arredondadas */
        .table-container {
            width: 100%;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }

        /* Botão flutuante no canto superior esquerdo */
        .float-button {
            position: fixed;
            top: 15%;
            left: 5%;
            background-color: #007bff;
            color: #fff;
            border-radius: 50%;
            width: 60px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            box-shadow: 0px 4px 8px rgba(0,0,0,0.3);
            cursor: pointer;
        }

        .float-button:hover {
            background-color: #0056b3;
        }

        /* Estilo do status */
        .status {
            padding: 5px 10px;
            border-radius: 5px;
            color: #fff;
            font-size: 0.875em;
            font-weight: bold;
        }

        .status-normal {
            background-color: #28a745;
        }

        .status-pendente {
            background-color: #dc3545;
        }

        /* Dropdown compacto dentro da tabela */
        .dropdown-menu {
            min-width: auto;
        }

        /* Custom Modal Style */
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

        .dropzone {
            border: 2px dashed #f29400;
            border-radius: 8px;
            padding: 20px;
            text-align: center;
            margin-bottom: 20px;
            cursor: pointer;
        }

        .dropzone:hover {
            background-color: #f7f7f7;
        }

        .dropzone input[type="file"] {
            display: none;
        }

        .mask-custom {
            background: white;
            border-radius: 2em;
            backdrop-filter: blur(25px);
            border: 2px solid rgba(255, 255, 255, 0.05);
            background-clip: padding-box;
            box-shadow: 10px 10px 10px rgba(46, 54, 68, 0.03);
            width: 100%;
        }

        .button-group {
            margin: 20px 0;
            display: flex;
            justify-content: center;
        }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-light fixed-top">
        <a href="/bimestres" class="btn-back">
            <i class="fa fa-arrow-left"></i>
        </a>
        <div class="logo">
            <img src="{{asset('/images/logounifil.png')}}" alt="Logo Unifil">
        </div>
        <div>
            <p style="color:red">ADMIN/COORDENADORES</p>
        </div>
    </nav>

    <!-- Seção principal com filtros e lista de alunos -->
    <section class="content-container container">
        
        <div class="container mb-4">
            <div class="row">
                <div class="col-md-3">
                    <input type="text" id="searchName" class="form-control" placeholder="Pesquisar por Nome">
                </div>
                <div class="col-md-3">
                    <select id="filterEtapa" class="form-select">
                        <option value="">Filtrar por Etapa</option>
                        <option value="Estágio I: Planejamento">Estágio I: Planejamento</option>
                        <option value="Estágio I: Desenvolvimento">Estágio I: Desenvolvimento</option>
                        <option value="Estágio II: Desenvolvimento">Estágio II: Desenvolvimento</option>
                        <option value="Estágio II: Final">Estágio II: Final</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select id="filterStatus" class="form-select">
                        <option value="">Filtrar por Status</option>
                        <option value="0">Normal</option>
                        <option value="1">Pendente</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <input type="text" id="filterOrientador" class="form-control" placeholder="Filtrar por Orientador">
                </div>
            </div>
        </div>

        <!-- Tabela de alunos com fundo branco, borda arredondada e sombra -->
        <div class="table-container">
            <table class="table mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="text-center"><span>Nome</span></th>
                        <th class="text-center"><span>Matrícula</span></th>
                        <th class="text-center"><span>Status</span></th>
                        <th class="text-center"><span>Email</span></th>
                        <th class="text-center"><span>Ações</span></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($alunos as $aluno)
                        <tr>
                            <td>
                                {{ $aluno->nome }}
                            </td>
                            <td>{{ $aluno->matricula }}</td>
                            <td class="text-center">
                                @if ($aluno->pendente)
                                    <span class="badge bg-danger">Pendente</span>
                                @else
                                    <span class="badge bg-success">Normal</span>
                                @endif
                            </td>
                            <td>{{ $aluno->email_aluno }}</td>
                            <td class="text-end">
                                <a href="/atividades/{{$aluno->email_aluno}}/etapa/{{$aluno->etapa}}" class="btn btn-sm btn-primary">Atividades</a>
                                <a href="/rpods/{{$aluno->email_aluno}}/etapa/{{$aluno->etapa}}" class="btn btn-sm btn-secondary">RPODS</a>
                                <a href="/orientacoes/{{$aluno->email_aluno}}/etapa/{{$aluno->etapa}}" class="btn btn-sm btn-success">Orientações</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>

</body>
</html>
