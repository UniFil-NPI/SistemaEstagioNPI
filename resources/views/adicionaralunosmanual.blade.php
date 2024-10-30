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
            height: 65px; /* Mantém o tamanho fixo do navbar */
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

        .task-list {
            padding: 0;
            margin: 0;
            list-style: none;
        }

        .task-list-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px;
            background-color: #fff;
            border: 1px solid #e1e1e1;
            margin-bottom: 10px;
            border-radius: 8px;
        }

        .task-list-item img {
            max-width: 50px;
            margin-right: 15px;
            border-radius: 50%;
        }

        .task-list-item .task-info {
            flex-grow: 1;
            display: flex;
            align-items: center;
        }

        .task-list-item .task-info p {
            margin: 0 15px;
            font-size: 0.875em;
            color: #777;
        }

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

    <div class="container">
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
</div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>