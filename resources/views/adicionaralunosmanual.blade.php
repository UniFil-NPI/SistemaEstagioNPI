<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Controle Alunos</title>
    
    <!-- Bootstrap CSS -->
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

        /* Tabela com bordas arredondadas e espaçamento */
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

        /* Estilo dos inputs e selects */
        .table input, .table select {
            border-radius: 8px;
            border: 1px solid #ddd;
            padding: 10px;
            font-size: 1em;
            width: 100%;
        }

        .table input:focus, .table select:focus {
            border-color: #F29400; /* Laranja */
            box-shadow: 0 0 5px rgba(242, 148, 0, 0.5);
        }

        /* Estilo dos botões */
        .btn-primary {
            background-color: #F29400; /* Laranja */
            border-color: #F29400; /* Laranja */
            font-size: 1.2em;
            padding: 10px 20px;
            border-radius: 8px;
        }

        .btn-primary:hover {
            background-color: #e47c00;
        }

        /* Estilo das opções de status */
        .status {
            padding: 5px 10px;
            border-radius: 5px;
            color: #fff;
            font-size: 0.875em;
            font-weight: bold;
        }

        .status-normal {
            background-color: #28a745; /* Verde */
        }

        .status-pendente {
            background-color: #dc3545; /* Vermelho */
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

    <!-- Seção principal com filtros e lista de alunos -->
    <section class="container">

        <h1>Preencha as informações que faltam</h1>

        <form action="{{ url('/admin/alunosadmin/salvar-dados') }}" method="POST"> 
            @csrf
            <input type="hidden" name="id_classroom" value="{{ $idClassroom }}">

            <table class="table">
                <thead>
                    <tr>
                        <th>Matrícula</th>
                        <th>Nome</th>
                        <th>Email</th>
                        <th>Email Professor</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($alunosIncompletos as $aluno)
                    <tr>
                        <td><input type="number" name="matriculas[]" value="{{ $aluno['matricula'] ?? '' }}"></td>
                        <td><input type="text" name="nomes[]" value="{{ $aluno['nome'] ?? '' }}"></td>
                        <td><input type="email" name="emails[]" value="{{ $aluno['email'] ?? '' }}"></td>
                        <td>
                            <select name="emailsProfessores[]">
                                @foreach($users as $user)
                                    <option value="{{ $user->email }}" {{ isset($aluno['emailProfessor']) && $aluno['emailProfessor'] == $user->email ? 'selected' : '' }}>
                                        {{ $user->nome }}
                                    </option>
                                @endforeach
                            </select>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <button type="submit" class="btn btn-primary">Salvar</button>
        </form>

    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
